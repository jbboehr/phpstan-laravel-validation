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

namespace jbboehr\PhpstanLaravelValidation\Test\Support;

use Composer\InstalledVersions;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\Type;
use PHPStan\Type\VerbosityLevel;

final class InferenceAudit
{
    /**
     * @param array<string, array{
     *     rules: array<array-key, mixed>|\Closure(array<mixed, mixed>): array<array-key, mixed>,
     *     data: array<mixed, mixed>|\Closure(): array<mixed, mixed>,
     *     concern: string,
     *     precision?: bool
     * }> $cases
     * @return array{
     *     laravel: string,
     *     laravelReference: string|null,
     *     cases: array<string, array{
     *         concern: string,
     *         rules: array<array-key, mixed>,
     *         input: array<mixed>|bool|float|int|string|null,
     *         runtime: array{
     *             outcome: string,
     *             output: array<mixed>|bool|float|int|string|null,
     *             warnings: list<string>,
     *             exception: string|null
     *         },
     *         inference: array{
     *             type: string|null,
     *             outputType: string|null,
     *             containsOutput: string|null,
     *             exception: string|null,
     *             classification: string
     *         },
     *         precision: array{
     *             candidateOutputType: string|null,
     *             containedByInference: string|null,
     *             preservedOnSuccess: bool|null,
     *             classification: string
     *         }
     *     }>
     * }
     */
    public static function run(array $cases): array
    {
        $factory = new Factory(new Translator(new ArrayLoader(), 'en'));
        $laravelVersion = self::frameworkVersion();
        $laravelVersionContext = new LaravelVersionContext('', $laravelVersion);
        $typeResolver = new TypeResolver($laravelVersionContext);
        $results = [];

        foreach ($cases as $id => $case) {
            $data = $case['data'] instanceof \Closure ? $case['data']() : $case['data'];
            $rules = $case['rules'] instanceof \Closure ? $case['rules']($data) : $case['rules'];
            $warnings = [];
            $passes = null;
            $validated = null;
            $runtimeException = null;

            set_error_handler(
                static function (int $severity, string $message) use (&$warnings): bool {
                    $warning = self::normalizeWarning($severity, $message);
                    if ($warning !== null) {
                        $warnings[] = $warning;
                    }
                    return true;
                }
            );

            try {
                $validator = $factory->make($data, $rules);
                $passes = $validator->passes();
                if ($passes) {
                    $validated = $validator->validated();
                }
            } catch (\Throwable $throwable) {
                $runtimeException = $throwable::class;
            } finally {
                restore_error_handler();
            }

            $inferredType = null;
            $outputType = null;
            $acceptance = null;
            $inferenceException = null;
            $candidateOutputType = null;
            $candidateAcceptance = null;
            $precisionProbe = $case['precision'] ?? false;

            try {
                $inferred = $typeResolver->evaluate(RuleParser::parse($rules, $laravelVersionContext));
                $inferredType = $inferred->describe(VerbosityLevel::precise());

                if ($precisionProbe) {
                    $candidate = LaravelValueType::fromValue($data);
                    $candidateOutputType = $candidate->describe(VerbosityLevel::precise());
                    $candidateResult = $inferred->isSuperTypeOf($candidate);
                    $candidateAcceptance = self::describeRelation($candidateResult);
                }

                if ($validated !== null) {
                    $actual = LaravelValueType::fromValue($validated);
                    $outputType = $actual->describe(VerbosityLevel::precise());
                    $result = $inferred->isSuperTypeOf($actual);
                    $acceptance = self::describeRelation($result);
                }
            } catch (\Throwable $throwable) {
                $inferenceException = $throwable::class;
            }

            $results[$id] = [
                'concern' => $case['concern'],
                'rules' => $rules,
                'input' => self::normalizeValue($data),
                'runtime' => [
                    'outcome' => $runtimeException !== null ? 'exception' : ($passes ? 'passed' : 'failed'),
                    'output' => $validated === null ? null : self::normalizeValue($validated),
                    'warnings' => array_values(array_unique($warnings)),
                    'exception' => $runtimeException,
                ],
                'inference' => [
                    'type' => $inferredType,
                    'outputType' => $outputType,
                    'containsOutput' => $acceptance,
                    'exception' => $inferenceException,
                    'classification' => self::classify(
                        $runtimeException,
                        $passes,
                        $inferenceException,
                        $acceptance
                    ),
                ],
                'precision' => [
                    'candidateOutputType' => $candidateOutputType,
                    'containedByInference' => $candidateAcceptance,
                    'preservedOnSuccess' => $precisionProbe && $passes === true
                        ? $validated === $data
                        : null,
                    'classification' => self::classifyPrecision(
                        $precisionProbe,
                        $runtimeException,
                        $passes,
                        $validated,
                        $data,
                        $inferenceException,
                        $candidateAcceptance
                    ),
                ],
            ];

            self::closeResources($data);
        }

        return [
            'laravel' => $laravelVersion,
            'laravelReference' => self::frameworkReference(),
            'cases' => $results,
        ];
    }

    public static function frameworkVersion(): string
    {
        $version = InstalledVersions::getPrettyVersion('laravel/framework');

        return $version === null
            ? \Illuminate\Foundation\Application::VERSION
            : ltrim($version, 'v');
    }

    public static function frameworkReference(): ?string
    {
        return InstalledVersions::getReference('laravel/framework');
    }

    /**
     * @return array<mixed, mixed>|bool|float|int|string|null
     */
    public static function normalizeValue(mixed $value): array|bool|float|int|string|null
    {
        if (is_float($value) && !is_finite($value)) {
            return ['type' => 'float', 'value' => match (true) {
                is_nan($value) => 'NAN',
                $value > 0 => 'INF',
                default => '-INF',
            }];
        }

        if (is_resource($value)) {
            return ['type' => 'resource'];
        }

        if ($value instanceof \DateTimeInterface) {
            return [
                'type' => 'object',
                'class' => $value::class,
                'value' => $value->format('Y-m-d H:i:s.uP'),
            ];
        }

        if (is_object($value)) {
            $normalized = ['type' => 'object', 'class' => $value::class];
            if ($value instanceof \Stringable) {
                $normalized['value'] = (string) $value;
            }
            return $normalized;
        }

        if (is_array($value)) {
            $normalized = [];
            foreach ($value as $key => $item) {
                $normalized[$key] = self::normalizeValue($item);
            }
            return $normalized;
        }

        return match (true) {
            is_bool($value), is_float($value), is_int($value), is_string($value), $value === null => $value,
            default => throw new \LogicException('Unsupported audit value type'),
        };
    }

    private static function normalizeWarning(int $severity, string $message): ?string
    {
        if ($severity === E_DEPRECATED || $severity === E_USER_DEPRECATED) {
            return null;
        }

        if (str_contains($message, 'Array to string conversion')) {
            return 'array-to-string';
        }

        return 'php-' . $severity;
    }

    private static function closeResources(mixed $value): void
    {
        if (is_resource($value)) {
            fclose($value);
            return;
        }

        if (!is_array($value)) {
            return;
        }

        foreach ($value as $item) {
            self::closeResources($item);
        }
    }

    private static function classify(
        ?string $runtimeException,
        ?bool $passes,
        ?string $inferenceException,
        ?string $acceptance
    ): string {
        if ($inferenceException !== null) {
            return 'inference-error';
        }
        if ($runtimeException !== null) {
            return 'runtime-exception';
        }
        if ($passes !== true) {
            return 'no-successful-output';
        }
        if ($acceptance === 'yes') {
            return 'observed-sound';
        }

        return 'observed-unsound';
    }

    /**
     * @param array<mixed, mixed> $data
     */
    private static function classifyPrecision(
        bool $precisionProbe,
        ?string $runtimeException,
        ?bool $passes,
        mixed $validated,
        array $data,
        ?string $inferenceException,
        ?string $candidateAcceptance
    ): string {
        if (!$precisionProbe) {
            return 'not-probed';
        }
        if ($inferenceException !== null) {
            return 'inference-error';
        }
        if ($runtimeException !== null) {
            return 'runtime-exception';
        }
        if ($candidateAcceptance !== 'yes') {
            return $candidateAcceptance === 'no'
                ? 'candidate-outside-inference'
                : 'candidate-indeterminate';
        }
        if ($passes !== true) {
            return 'observed-imprecision';
        }
        if ($validated !== $data) {
            return 'preservation-mismatch';
        }

        return 'observed-realizable';
    }

    private static function describeRelation(Type\IsSuperTypeOfResult $result): string
    {
        return $result->yes() ? 'yes' : ($result->no() ? 'no' : 'maybe');
    }

}
