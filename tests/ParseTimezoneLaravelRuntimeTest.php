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

namespace jbboehr\PhpstanLaravelValidation\Test;

use Composer\InstalledVersions;
use DateTimeZone;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsLaravelValidation;
use jbboehr\Rensei\Parse;
use jbboehr\Rensei\Rules\TimezoneRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

/**
 * Runtime and inference conformance for `Parse::timezone()`.
 */
#[Group('laravel')]
final class ParseTimezoneLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    use AssertsLaravelValidation;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::validatorSupportsSetValue(Validator::class)) {
            self::markTestSkipped('Parsing rules require Validator::setValue().');
        }
    }

    /**
     * @return iterable<string, array{mixed, bool}>
     */
    public static function valueCases(): iterable
    {
        yield 'UTC' => ['UTC', true];
        yield 'region identifier' => ['America/Los_Angeles', true];
        yield 'Etc UTC alias' => ['Etc/UTC', false];
        yield 'GMT region' => ['Etc/GMT+5', false];
        yield 'backward-compatible alias' => ['US/Eastern', false];
        yield 'offset' => ['+05:30', false];
        yield 'abbreviation' => ['EST', false];
        yield 'wrong case' => ['utc', false];
        yield 'unknown identifier' => ['Not/A_Timezone', false];
        yield 'null byte' => ["UTC\0", false];
        yield 'blank' => ['', false];
        yield 'integer' => [0, false];
        yield 'array' => [[], false];
        yield 'object' => [new \stdClass(), false];
    }

    #[DataProvider('valueCases')]
    public function testRequiredStringInputMatchesLaravelsDefaultTimezoneRuleAndProducesAnObject(
        mixed $value,
        bool $expectedPasses
    ): void {
        $laravel = self::factory()->make(
            ['timezone' => $value],
            ['timezone' => ['required', 'timezone']]
        );
        $rules = ['timezone' => ['required', Parse::timezone()]];
        $validator = self::factory()->make(['timezone' => $value], $rules);

        self::assertSame($expectedPasses, $laravel->passes());
        self::assertSame($expectedPasses, $validator->passes());
        if (!$expectedPasses) {
            return;
        }

        self::assertIsString($value);

        $validated = self::validated($validator);
        $this->assertInferredTypeContainsLaravelOutput(
            'default timezone identifier ' . $value,
            $rules,
            $validated
        );

        $timezone = $validated['timezone'] ?? null;
        self::assertInstanceOf(DateTimeZone::class, $timezone);
        self::assertSame($value, $timezone->getName());
    }

    public function testExistingTimezonePassesThroughUnchanged(): void
    {
        $timezone = new DateTimeZone('US/Eastern');
        $rules = ['timezone' => ['required', new TimezoneRule()]];
        $validator = self::factory()->make(['timezone' => $timezone], $rules);

        self::assertTrue($validator->passes());
        $validated = self::validated($validator);
        $this->assertInferredTypeContainsLaravelOutput(
            'existing timezone object',
            $rules,
            $validated
        );
        self::assertSame($timezone, $validated['timezone'] ?? null);
    }

    public function testAnAdjacentLaravelRuleCanRestrictTheIdentifierSet(): void
    {
        $rules = [
            'timezone' => [
                'required',
                'timezone:per_country,US',
                Parse::timezone(),
            ],
        ];

        $accepted = self::factory()->make(
            ['timezone' => 'America/Los_Angeles'],
            $rules
        );
        self::assertTrue($accepted->passes());
        $validated = self::validated($accepted);
        $this->assertInferredTypeContainsLaravelOutput(
            'US timezone identifier subset',
            $rules,
            $validated
        );
        $timezone = $validated['timezone'] ?? null;
        self::assertInstanceOf(DateTimeZone::class, $timezone);
        self::assertSame('America/Los_Angeles', $timezone->getName());

        $rejected = self::factory()->make(
            ['timezone' => 'Europe/Paris'],
            $rules
        );
        if (version_compare(self::frameworkVersion(), '10.12.0', '>=')) {
            self::assertFalse($rejected->passes());

            return;
        }

        self::assertTrue($rejected->passes());
        $validated = self::validated($rejected);
        $this->assertInferredTypeContainsLaravelOutput(
            'parameters ignored before Laravel 10.12',
            $rules,
            $validated
        );
        $timezone = $validated['timezone'] ?? null;
        self::assertInstanceOf(DateTimeZone::class, $timezone);
        self::assertSame('Europe/Paris', $timezone->getName());
    }

    /** @return iterable<string, array{string}> */
    public static function optionalBlankCases(): iterable
    {
        yield 'empty string' => [''];
        yield 'whitespace-only string' => [' '];
    }

    #[DataProvider('optionalBlankCases')]
    public function testImplicitParserRejectsBlankValuesSkippedByOptionalTimezoneRule(string $value): void
    {
        $laravel = self::factory()->make(
            ['timezone' => $value],
            ['timezone' => ['timezone']]
        );
        $parser = self::factory()->make(
            ['timezone' => $value],
            ['timezone' => [Parse::timezone()]]
        );

        self::assertTrue($laravel->passes());
        self::assertSame(['timezone' => $value], self::validated($laravel));
        self::assertFalse($parser->passes());
    }

    public function testPresenceNullabilityAndWildcardProjectionRemainLaravelConcerns(): void
    {
        $this->assertLaravelValidationCase(
            'absent optional timezone',
            [],
            ['timezone' => [Parse::timezone()]],
            true,
            []
        );
        $this->assertLaravelValidationCase(
            'nullable timezone',
            ['timezone' => null],
            ['timezone' => ['nullable', Parse::timezone()]],
            true,
            ['timezone' => null]
        );

        $rules = [
            'offices.*.timezone' => ['required', Parse::timezone()],
        ];
        $validator = self::factory()->make([
            'offices' => [
                ['timezone' => 'UTC'],
                ['timezone' => 'America/Los_Angeles'],
            ],
        ], $rules);

        self::assertTrue($validator->passes());
        $validated = self::validated($validator);
        $this->assertInferredTypeContainsLaravelOutput(
            'wildcard timezone identifiers',
            $rules,
            $validated
        );

        $utc = Arr::get($validated, 'offices.0.timezone');
        self::assertInstanceOf(DateTimeZone::class, $utc);
        self::assertSame('UTC', $utc->getName());

        $losAngeles = Arr::get($validated, 'offices.1.timezone');
        self::assertInstanceOf(DateTimeZone::class, $losAngeles);
        self::assertSame('America/Los_Angeles', $losAngeles->getName());
    }

    public function testExclusionStillRemovesTheParsedAttribute(): void
    {
        $validator = self::factory()->make(
            ['timezone' => 'UTC'],
            ['timezone' => ['exclude', Parse::timezone()]]
        );

        self::assertTrue($validator->passes());
        self::assertSame([], self::validated($validator));
    }

    public function testOrdinaryRulesObserveTheOriginalValueBeforeWriteBack(): void
    {
        $validator = self::factory()->make(
            ['timezone' => 'UTC', 'confirmation' => 'UTC'],
            [
                'timezone' => ['required', Parse::timezone()],
                'confirmation' => ['required', 'same:timezone'],
            ]
        );

        self::assertTrue($validator->passes());
        $validated = self::validated($validator);
        self::assertInstanceOf(DateTimeZone::class, $validated['timezone'] ?? null);
        self::assertSame('UTC', $validated['confirmation'] ?? null);
    }

    public function testAValidatorCannotBeRunAgainAfterTimezoneWriteBack(): void
    {
        $validator = self::factory()->make(
            ['timezone' => 'UTC'],
            ['timezone' => ['required', Parse::timezone()]]
        );

        self::assertTrue($validator->passes());
        self::assertTrue($validator->fails());
        self::assertSame(
            ['A validator containing parsing rules cannot be reused.'],
            $validator->errors()->get('timezone')
        );
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    /** @return array<mixed, mixed> */
    private static function validated(Validator $validator): array
    {
        return $validator->validated();
    }

    /** @param class-string $validatorClass */
    private static function validatorSupportsSetValue(string $validatorClass): bool
    {
        return method_exists($validatorClass, 'setValue');
    }

    private static function frameworkVersion(): string
    {
        $version = InstalledVersions::getPrettyVersion('laravel/framework');

        return ltrim($version !== null ? $version : Application::VERSION, 'v');
    }
}
