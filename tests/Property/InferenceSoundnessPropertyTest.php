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

namespace jbboehr\PhpstanLaravelValidation\Test\Property;

use Eris\Attributes\ErisRepeat;
use Eris\Generators;
use Eris\TestTrait;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Test\Support\InferenceAudit;
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\VerbosityLevel;
use PHPUnit\Framework\Attributes\Group;

#[Group('property')]
final class InferenceSoundnessPropertyTest extends PHPStanTestCase
{
    use TestTrait;

    private const ITERATIONS = 250;
    private const MINIMUM_SUCCESSFUL_OUTPUT_RATIO = 0.3;

    private const SCALAR_RULES = [
        'string',
        'integer',
        'numeric',
        'boolean',
        'array',
        'in:0,1',
    ];

    /** @var list<list<string>> */
    private const SCALAR_MODIFIERS = [
        [],
        ['required'],
        ['nullable'],
        ['sometimes'],
        ['filled'],
        ['required', 'nullable'],
        ['sometimes', 'nullable'],
    ];

    /** @var list<array{present: bool, value?: mixed}> */
    private const SCALAR_INPUTS = [
        ['present' => false],
        ['present' => true, 'value' => null],
        ['present' => true, 'value' => ''],
        ['present' => true, 'value' => ' '],
        ['present' => true, 'value' => '0'],
        ['present' => true, 'value' => 0],
        ['present' => true, 'value' => '1'],
        ['present' => true, 'value' => 1],
        ['present' => true, 'value' => 1.0],
        ['present' => true, 'value' => 1.5],
        ['present' => true, 'value' => true],
        ['present' => true, 'value' => false],
        ['present' => true, 'value' => 'plain'],
        ['present' => true, 'value' => []],
        ['present' => true, 'value' => ['item']],
    ];

    private Factory $factory;
    private LaravelVersionContext $laravelVersionContext;
    private TypeResolver $typeResolver;
    private string $laravelVersion;

    protected function setUp(): void
    {
        parent::setUp();
        self::getContainer();

        $this->factory = new Factory(new Translator(new ArrayLoader(), 'en'));
        $this->laravelVersion = InferenceAudit::frameworkVersion();
        $this->laravelVersionContext = new LaravelVersionContext('', $this->laravelVersion);
        $this->typeResolver = new TypeResolver($this->laravelVersionContext);
    }

    #[ErisRepeat(self::ITERATIONS)]
    public function testScalarPresenceAndNativeRepresentationsRemainSound(): void
    {
        $successfulOutputs = 0;

        $this->forAll(
            Generators::choose(0, count(self::SCALAR_RULES) - 1),
            Generators::choose(0, count(self::SCALAR_MODIFIERS) - 1),
            Generators::choose(0, count(self::SCALAR_INPUTS) - 1),
            Generators::bool(),
        )->then(function (int $ruleIndex, int $modifierIndex, int $inputIndex, bool $modifierFirst) use (
            &$successfulOutputs
        ): void {
            $parts = self::SCALAR_MODIFIERS[$modifierIndex];
            if ($modifierFirst) {
                $parts[] = self::SCALAR_RULES[$ruleIndex];
            } else {
                array_unshift($parts, self::SCALAR_RULES[$ruleIndex]);
            }

            $input = self::SCALAR_INPUTS[$inputIndex];
            $data = $input['present'] ? ['value' => $input['value']] : [];
            $successfulOutputs += (int) $this->assertSuccessfulOutputIsContained(
                $data,
                ['value' => implode('|', $parts)],
            );
        });

        $this->assertEnoughSuccessfulOutputs($successfulOutputs);
    }

    #[ErisRepeat(self::ITERATIONS)]
    public function testNestedProjectionAndWildcardsRemainSound(): void
    {
        $successfulOutputs = 0;

        $this->forAll(
            Generators::choose(0, 5),
            Generators::choose(0, 7),
        )->then(function (int $template, int $input) use (&$successfulOutputs): void {
            $case = self::structuralCase($template, $input);
            $successfulOutputs += (int) $this->assertSuccessfulOutputIsContained(
                $case['data'],
                $case['rules'],
            );
        });

        $this->assertEnoughSuccessfulOutputs($successfulOutputs);
    }

    #[ErisRepeat(self::ITERATIONS)]
    public function testCrossFieldPresenceAndExclusionRemainSound(): void
    {
        $successfulOutputs = 0;

        $this->forAll(
            Generators::choose(0, 6),
            Generators::choose(0, 3),
            Generators::bool(),
            Generators::choose(0, 4),
        )->then(function (
            int $ruleIndex,
            int $modeIndex,
            bool $triggerPresent,
            int $valueIndex,
        ) use (&$successfulOutputs): void {
            $case = self::conditionalCase($ruleIndex, $modeIndex, $triggerPresent, $valueIndex);
            $successfulOutputs += (int) $this->assertSuccessfulOutputIsContained(
                $case['data'],
                $case['rules'],
            );
        });

        $this->assertEnoughSuccessfulOutputs($successfulOutputs);
    }

    /**
     * @return array{data: array<mixed, mixed>, rules: array<string, string>}
     */
    private static function structuralCase(int $template, int $input): array
    {
        if ($template < 4) {
            $data = match ($input) {
                0 => [],
                1 => ['payload' => []],
                2 => ['payload' => ['name' => 'Alice']],
                3 => ['payload' => ['name' => 'Alice', 'extra' => 1]],
                4 => ['payload' => ['email' => 'alice@example.test']],
                5 => ['payload' => ['name' => '']],
                6 => ['payload' => 'scalar'],
                default => ['payload' => null],
            };

            $rules = match ($template) {
                0 => ['payload' => 'array'],
                1 => ['payload' => 'array:name,email'],
                2 => ['payload' => 'array', 'payload.name' => 'required|string'],
                default => ['payload' => 'array', 'payload.name' => 'sometimes|nullable|string'],
            };

            return ['data' => $data, 'rules' => $rules];
        }

        $data = match ($input) {
            0 => [],
            1 => ['items' => []],
            2 => ['items' => [['value' => 'first']]],
            3 => ['items' => ['named' => ['value' => 'named']]],
            4 => ['items' => [0 => ['value' => 'first'], 'named' => ['value' => 'named']]],
            5 => ['items' => [['other' => 'unvalidated']]],
            6 => ['items' => [['value' => '']]],
            default => ['items' => 'scalar'],
        };

        $rules = ['items.*.value' => 'required|string'];
        if ($template === 5) {
            $rules = ['items' => 'array'] + $rules;
        }

        return ['data' => $data, 'rules' => $rules];
    }

    /**
     * @return array{data: array<string, mixed>, rules: array<string, string>}
     */
    private static function conditionalCase(
        int $ruleIndex,
        int $modeIndex,
        bool $triggerPresent,
        int $valueIndex,
    ): array {
        $rules = [
            'mode' => 'sometimes|string',
            'trigger' => 'sometimes|string',
            'value' => [
                'required_if:mode,member|string',
                'required_unless:mode,guest|string',
                'required_with:trigger|string',
                'exclude_if:mode,guest|string',
                'exclude_unless:mode,member|string',
                'exclude_with:trigger|string',
                'exclude_without:trigger|string',
            ][$ruleIndex],
        ];

        $data = [];
        if ($modeIndex > 0) {
            $data['mode'] = ['guest', 'member', 'other'][$modeIndex - 1];
        }
        if ($triggerPresent) {
            $data['trigger'] = 'present';
        }
        if ($valueIndex > 0) {
            $data['value'] = [null, '', 'value', 0][$valueIndex - 1];
        }

        return ['data' => $data, 'rules' => $rules];
    }

    /**
     * @param array<mixed, mixed> $data
     * @param array<array-key, mixed> $rules
     */
    private function assertSuccessfulOutputIsContained(array $data, array $rules): bool
    {
        try {
            $validator = $this->factory->make($data, $rules);
            if (!$validator->passes()) {
                return false;
            }

            $validated = $validator->validated();
            $inferredType = $this->typeResolver->evaluate(
                RuleParser::parse($rules, $this->laravelVersionContext),
            );
            $actualType = InferenceAudit::toType($validated);
            $relation = $inferredType->isSuperTypeOf($actualType);
        } catch (\Throwable $throwable) {
            throw new \RuntimeException(sprintf(
                "Laravel %s property probe threw unexpectedly.\nRules: %s\nInput: %s",
                $this->laravelVersion,
                var_export($rules, true),
                var_export($data, true),
            ), 0, $throwable);
        }

        self::assertTrue($relation->yes(), sprintf(
            "Laravel %s produced a value outside inference.\nRules: %s\nInput: %s\nValidated: %s\nInferred: %s\nActual: %s",
            $this->laravelVersion,
            var_export($rules, true),
            var_export($data, true),
            var_export($validated, true),
            $inferredType->describe(VerbosityLevel::precise()),
            $actualType->describe(VerbosityLevel::precise()),
        ));

        return true;
    }

    private function assertEnoughSuccessfulOutputs(int $successfulOutputs): void
    {
        $minimum = (int) ceil(self::ITERATIONS * self::MINIMUM_SUCCESSFUL_OUTPUT_RATIO);

        self::assertGreaterThanOrEqual(
            $minimum,
            $successfulOutputs,
            sprintf(
                'Only %d of %d generated trials produced successful Laravel output; expected at least %d.',
                $successfulOutputs,
                self::ITERATIONS,
                $minimum,
            ),
        );
    }
}
