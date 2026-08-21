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

use Illuminate\Validation\Validator;
use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsLaravelValidation;
use jbboehr\Rensei\Parse;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

/**
 * Runtime and inference conformance for `Parse::float()`.
 */
#[Group('laravel')]
final class ParseFloatLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
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
     * @return iterable<string, array{mixed, float|null}>
     */
    public static function valueCases(): iterable
    {
        yield 'native int' => [42, 42.0];
        yield 'native negative int' => [-42, -42.0];
        yield 'native float' => [1.5, 1.5];
        yield 'integer string' => ['42', 42.0];
        yield 'negative integer string' => ['-42', -42.0];
        yield 'decimal string' => ['1.5', 1.5];
        yield 'negative decimal string' => ['-1.5', -1.5];
        yield 'zero string' => ['0', 0.0];

        yield 'leading zero' => ['01.5', null];
        yield 'leading plus' => ['+1.5', null];
        yield 'leading decimal point' => ['.5', null];
        yield 'trailing decimal point' => ['1.', null];
        yield 'scientific notation' => ['1e3', null];
        yield 'leading whitespace' => [' 1.5', null];
        yield 'trailing newline' => ["1.5\n", null];
        yield 'overflowing decimal string' => [str_repeat('9', 400), null];
        yield 'infinity' => [INF, null];
        yield 'negative infinity' => [-INF, null];
        yield 'true' => [true, null];
        yield 'false' => [false, null];
        yield 'blank' => ['', null];
        yield 'null' => [null, null];
        yield 'array' => [[], null];
        yield 'object' => [new \stdClass(), null];
    }

    #[DataProvider('valueCases')]
    public function testProducesOnlyFiniteFloats(mixed $value, ?float $expected): void
    {
        $this->assertLaravelValidationCase(
            'float ' . get_debug_type($value),
            ['value' => $value],
            ['value' => ['required', Parse::float()]],
            $expected !== null,
            $expected === null ? null : ['value' => $expected]
        );
    }

    public function testOptionalNullableNestedAndWildcardValues(): void
    {
        $this->assertLaravelValidationCase(
            'absent optional float',
            [],
            ['value' => [Parse::float()]],
            true,
            []
        );

        $this->assertLaravelValidationCase(
            'nullable float',
            ['value' => null],
            ['value' => ['nullable', Parse::float()]],
            true,
            ['value' => null]
        );

        $this->assertLaravelValidationCase(
            'nested float',
            ['measurement' => ['ratio' => '1.5']],
            ['measurement.ratio' => ['required', Parse::float()]],
            true,
            ['measurement' => ['ratio' => 1.5]]
        );

        $this->assertLaravelValidationCase(
            'wildcard floats',
            ['measurements' => [['ratio' => '1.5'], ['ratio' => 2]]],
            ['measurements.*.ratio' => ['required', Parse::float()]],
            true,
            ['measurements' => [['ratio' => 1.5], ['ratio' => 2.0]]]
        );
    }

    public function testSizeRulesObserveTheOriginalRepresentation(): void
    {
        $this->assertLaravelValidationCase(
            'unmarked float min measures string length',
            ['value' => '0.5'],
            ['value' => [Parse::float(), 'min:1']],
            true,
            ['value' => 0.5]
        );

        $this->assertLaravelValidationCase(
            'numeric float min measures numeric value',
            ['value' => '0.5'],
            ['value' => ['numeric', Parse::float(), 'min:1']],
            false,
            null
        );
    }
}
