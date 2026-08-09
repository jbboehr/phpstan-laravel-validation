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
use jbboehr\PhpstanLaravelValidation\Test\Support\InferencePropertyCases;
use jbboehr\PhpstanLaravelValidation\Test\Support\LaravelValueType;
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

    private Factory $factory;
    private LaravelVersionContext $laravelVersionContext;
    private TypeResolver $typeResolver;
    private string $laravelVersion;

    /** @var array<string, array{data: array<mixed, mixed>, rules: array<string, string>}> */
    private array $scalarCases;

    /** @var array<string, array{data: array<mixed, mixed>, rules: array<string, string>}> */
    private array $structuralCases;

    /** @var array<string, array{data: array<mixed, mixed>, rules: array<string, string>}> */
    private array $conditionalCases;

    protected function setUp(): void
    {
        parent::setUp();
        self::getContainer();

        $this->factory = new Factory(new Translator(new ArrayLoader(), 'en'));
        $this->laravelVersion = InferenceAudit::frameworkVersion();
        $this->laravelVersionContext = new LaravelVersionContext('', $this->laravelVersion);
        $this->typeResolver = new TypeResolver($this->laravelVersionContext);
        $this->scalarCases = InferencePropertyCases::scalar();
        $this->structuralCases = InferencePropertyCases::structural();
        $this->conditionalCases = InferencePropertyCases::conditional();
    }

    #[ErisRepeat(self::ITERATIONS)]
    public function testScalarPresenceAndNativeRepresentationsRemainSound(): void
    {
        $successfulOutputs = 0;

        $this->forAll(
            Generators::choose(0, count($this->scalarCases) - 1),
        )->then(function (int $caseIndex) use (&$successfulOutputs): void {
            [$caseId, $case] = self::caseAt($this->scalarCases, $caseIndex);
            $successfulOutputs += (int) $this->assertSuccessfulOutputIsContained(
                $caseId,
                $case['data'],
                $case['rules'],
            );
        });

        $this->assertEnoughSuccessfulOutputs($successfulOutputs);
    }

    #[ErisRepeat(self::ITERATIONS)]
    public function testNestedProjectionAndWildcardsRemainSound(): void
    {
        $successfulOutputs = 0;

        $this->forAll(
            Generators::choose(0, count($this->structuralCases) - 1),
        )->then(function (int $caseIndex) use (&$successfulOutputs): void {
            [$caseId, $case] = self::caseAt($this->structuralCases, $caseIndex);
            $successfulOutputs += (int) $this->assertSuccessfulOutputIsContained(
                $caseId,
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
            Generators::choose(0, count($this->conditionalCases) - 1),
        )->then(function (int $caseIndex) use (&$successfulOutputs): void {
            [$caseId, $case] = self::caseAt($this->conditionalCases, $caseIndex);
            $successfulOutputs += (int) $this->assertSuccessfulOutputIsContained(
                $caseId,
                $case['data'],
                $case['rules'],
            );
        });

        $this->assertEnoughSuccessfulOutputs($successfulOutputs);
    }

    /**
     * @param array<mixed, mixed> $data
     * @param array<array-key, mixed> $rules
     */
    private function assertSuccessfulOutputIsContained(string $caseId, array $data, array $rules): bool
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
            $actualType = LaravelValueType::fromValue($validated);
            $relation = $inferredType->isSuperTypeOf($actualType);
        } catch (\Throwable $throwable) {
            throw new \RuntimeException(sprintf(
                "Laravel %s property probe %s threw unexpectedly.\nRules: %s\nInput: %s",
                $this->laravelVersion,
                $caseId,
                var_export($rules, true),
                var_export($data, true),
            ), 0, $throwable);
        }

        self::assertTrue($relation->yes(), sprintf(
            "Laravel %s property probe %s produced a value outside inference."
            . "\nRules: %s\nInput: %s\nValidated: %s\nInferred: %s\nActual: %s",
            $this->laravelVersion,
            $caseId,
            var_export($rules, true),
            var_export($data, true),
            var_export($validated, true),
            $inferredType->describe(VerbosityLevel::precise()),
            $actualType->describe(VerbosityLevel::precise()),
        ));

        return true;
    }

    /**
     * @param array<string, array{data: array<mixed, mixed>, rules: array<string, string>}> $cases
     * @return array{string, array{data: array<mixed, mixed>, rules: array<string, string>}}
     */
    private static function caseAt(array $cases, int $index): array
    {
        $caseId = array_keys($cases)[$index];

        return [$caseId, $cases[$caseId]];
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
