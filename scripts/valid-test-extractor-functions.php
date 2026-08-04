<?php
/**
 * Copyright (c) anno Domini nostri Jesu Christi MMXXIV John Boehr & contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

use Brick\VarExporter\VarExporter;
use Illuminate\Validation\ValidationRuleParser;
use Illuminate\Validation\Validator;

function is_anonymous_class(object $instance): bool
{
    return (new ReflectionClass($instance))->isAnonymous();
}

function is_exportable(mixed $expr): bool
{
    return match (gettype($expr)) {
        'boolean', 'integer', 'double', 'string', 'NULL' => true,
        'array' => count(array_filter($expr, function ($value, $key) {
            return !is_exportable($key) || !is_exportable($value);
        }, ARRAY_FILTER_USE_BOTH)) === 0,
        'object' => match (true) {
            $expr instanceof PHPUnit\Framework\MockObject\MockObject => false,
            $expr instanceof DateTimeInterface,
            $expr instanceof Symfony\Component\HttpFoundation\File\File => !is_anonymous_class($expr),
            default => false,
        },
        default => false,
    };
}

/**
 * Determine whether rules were added to or otherwise changed on a validator
 * after its source rules were installed by Validator::setRules().
 *
 * The source rule array cannot faithfully represent calls to addRules() or
 * sometimes(). Exporting such a validator would therefore test an incomplete
 * rule set. Re-expand the source rules with Laravel's own parser and compare
 * them with the rules that actually ran.
 */
function validator_rules_were_mutated(Validator $validator): bool
{
    $reflection = new ReflectionClass($validator);
    $initialRulesProperty = $reflection->getProperty('initialRules');
    $initialRulesProperty->setAccessible(true);

    /** @var array<mixed, mixed> $initialRules */
    $initialRules = $initialRulesProperty->getValue($validator);
    $data = $validator->getData();

    $expectedRules = (new ValidationRuleParser($data))->explode(
        ValidationRuleParser::filterConditionalRules($initialRules, $data)
    )->rules;

    return $expectedRules !== $validator->getRules();
}

/**
 * Get Laravel's internal placeholders for literal dots and asterisks in
 * attribute names.
 *
 * @return array{dot: string|null, asterisk: string|null}
 */
function get_validator_placeholders(Validator $validator): array
{
    $reflection = new ReflectionClass($validator);

    if ($reflection->hasProperty('dotPlaceholder')) {
        $property = $reflection->getProperty('dotPlaceholder');
        $property->setAccessible(true);
        $dotPlaceholder = $property->getValue($validator);

        return [
            'dot' => is_string($dotPlaceholder) ? $dotPlaceholder : null,
            'asterisk' => '__asterisk__',
        ];
    }

    if ($reflection->hasProperty('placeholderHash')) {
        $property = $reflection->getProperty('placeholderHash');
        $property->setAccessible(true);
        $placeholderHash = $property->getValue($validator);

        if (is_string($placeholderHash)) {
            return [
                'dot' => '__dot__' . $placeholderHash,
                'asterisk' => '__asterisk__' . $placeholderHash,
            ];
        }
    }

    return ['dot' => null, 'asterisk' => null];
}

/**
 * Restore attribute keys that Laravel encoded before validation.
 *
 * Literal dots remain escaped so they cannot be mistaken for Laravel's dot
 * path separator when a fixture is consumed later. Literal asterisks are
 * restored to the attribute character that Laravel originally encoded.
 *
 * @param array{dot: string|null, asterisk: string|null} $placeholders
 */
function revert_validator_placeholders(mixed $data, array $placeholders): mixed
{
    if (!is_array($data)) {
        return $data;
    }

    $newData = [];
    foreach ($data as $key => $value) {
        if (is_string($key)) {
            if ($placeholders['dot'] !== null) {
                $key = str_replace($placeholders['dot'], '\\.', $key);
            }
            if ($placeholders['asterisk'] !== null) {
                $key = str_replace($placeholders['asterisk'], '*', $key);
            }
        }
        $newData[$key] = revert_validator_placeholders($value, $placeholders);
    }

    return $newData;
}

/**
 * Build a stable identity for an exported validation result.
 *
 * @param array<mixed, mixed> $data
 * @param array<mixed, mixed> $validated
 * @param array<mixed, mixed> $rules
 * @param array<mixed, mixed> $expandedRules
 */
function validation_fixture_hash(
    string $location,
    array $data,
    array $validated,
    array $rules,
    array $expandedRules
): string {
    $contents = implode('', [
        $location,
        VarExporter::export(normalize_validation_fixture_hash_value($rules), VarExporter::ADD_RETURN),
        VarExporter::export(normalize_validation_fixture_hash_value($expandedRules), VarExporter::ADD_RETURN),
        VarExporter::export(normalize_validation_fixture_hash_value($data), VarExporter::ADD_RETURN),
        VarExporter::export(normalize_validation_fixture_hash_value($validated), VarExporter::ADD_RETURN),
    ]);

    return sodium_bin2base64(md5($contents, true), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
}

/**
 * Remove runtime-only state from values before hashing them.
 */
function normalize_validation_fixture_hash_value(mixed $value): mixed
{
    if ($value instanceof DateTimeInterface) {
        return [
            '__validation_fixture_datetime__' => [
                'class' => get_class($value),
                'date' => $value->format('Y-m-d H:i:s.u'),
                'timezone' => $value->getTimezone()->getName(),
            ],
        ];
    }

    if (is_array($value)) {
        $normalized = [];
        foreach ($value as $key => $item) {
            $normalized[$key] = normalize_validation_fixture_hash_value($item);
        }
        return $normalized;
    }

    return $value;
}

/**
 * Find the PHPUnit test and source line responsible for a validation call.
 *
 * @param list<array<mixed, mixed>> $backtrace
 */
function validation_test_location(array $backtrace): string
{
    $previousTrace = null;

    foreach ($backtrace as $trace) {
        $class = $trace['class'] ?? null;
        $function = $trace['function'] ?? null;

        if (
            is_string($class)
            && is_string($function)
            && str_starts_with($function, 'test')
            && is_a($class, PHPUnit\Framework\TestCase::class, true)
        ) {
            $line = $previousTrace['line'] ?? $trace['line'] ?? null;
            $suffix = is_int($line) ? ':' . $line : '';

            return $class . '::' . $function . $suffix;
        }

        $previousTrace = $trace;
    }

    return 'unknown';
}

/**
 * Render a complete validation fixture with reproducible source metadata.
 *
 * @param array<string, array<mixed, mixed>> $tests
 */
function validation_fixture_contents(array $tests, string $laravelVersion, string $laravelCommit): string
{
    if (preg_match('/^\d+\.\d+\.\d+(?:[-+][0-9A-Za-z.-]+)?$/D', $laravelVersion) !== 1) {
        throw new InvalidArgumentException('Invalid Laravel version: ' . $laravelVersion);
    }
    if (preg_match('/^[0-9a-f]{40}$/D', $laravelCommit) !== 1) {
        throw new InvalidArgumentException('Invalid Laravel commit: ' . $laravelCommit);
    }

    return '<?php /* laravel ' . $laravelVersion . ' commit ' . $laravelCommit . ' */ return '
        . VarExporter::export($tests)
        . ';';
}
