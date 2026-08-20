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

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsLaravelValidation;
use jbboehr\Rensei\Parse;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

/**
 * Runtime and inference conformance for `Parse::boolean()`.
 */
#[Group('laravel')]
final class ParseBooleanLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
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
     * @param class-string $validatorClass
     */
    private static function validatorSupportsSetValue(string $validatorClass): bool
    {
        return method_exists($validatorClass, 'setValue');
    }

    /**
     * @return iterable<string, array{mixed, bool, bool|null}>
     */
    public static function valueCases(): iterable
    {
        yield 'true' => [true, true, true];
        yield 'one' => [1, true, true];
        yield 'string one' => ['1', true, true];
        yield 'false' => [false, true, false];
        yield 'zero' => [0, true, false];
        yield 'string zero' => ['0', true, false];

        yield 'integer two' => [2, false, null];
        yield 'negative integer' => [-1, false, null];
        yield 'float one' => [1.0, false, null];
        yield 'float zero' => [0.0, false, null];
        yield 'string true' => ['true', false, null];
        yield 'string false' => ['false', false, null];
        yield 'on' => ['on', false, null];
        yield 'off' => ['off', false, null];
        yield 'yes' => ['yes', false, null];
        yield 'no' => ['no', false, null];
        yield 'blank' => ['', false, null];
        yield 'whitespace' => [' ', false, null];
        yield 'null' => [null, false, null];
        yield 'array' => [[], false, null];
        yield 'object' => [new \stdClass(), false, null];
    }

    #[DataProvider('valueCases')]
    public function testMatchesLaravelBooleanAcceptanceAndProducesABool(
        mixed $value,
        bool $expectedPasses,
        ?bool $expectedValue
    ): void {
        $factory = new Factory(new Translator(new ArrayLoader(), 'en'));
        $laravel = $factory->make(['enabled' => $value], [
            'enabled' => ['required', 'boolean'],
        ]);
        self::assertSame($expectedPasses, $laravel->passes());

        $this->assertLaravelValidationCase(
            'boolean ' . get_debug_type($value),
            ['enabled' => $value],
            ['enabled' => ['required', Parse::boolean()]],
            $expectedPasses,
            $expectedPasses ? ['enabled' => $expectedValue] : null
        );
    }

    public function testNullableNullRemainsNull(): void
    {
        $this->assertLaravelValidationCase(
            'nullable null',
            ['enabled' => null],
            ['enabled' => ['nullable', Parse::boolean()]],
            true,
            ['enabled' => null]
        );
    }

    public function testAbsentOptionalValueRemainsAbsent(): void
    {
        $this->assertLaravelValidationCase(
            'absent optional boolean',
            [],
            ['enabled' => [Parse::boolean()]],
            true,
            []
        );
    }

    public function testNestedAndWildcardValuesAreParsed(): void
    {
        $this->assertLaravelValidationCase(
            'wildcard booleans',
            ['users' => [['enabled' => '1'], ['enabled' => '0']]],
            ['users.*.enabled' => ['required', Parse::boolean()]],
            true,
            ['users' => [['enabled' => true], ['enabled' => false]]]
        );
    }
}
