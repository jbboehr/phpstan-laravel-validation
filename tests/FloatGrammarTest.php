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

use jbboehr\Rensei\Internal\FloatGrammar;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

final class FloatGrammarTest extends TestCase
{
    /**
     * @return iterable<string, array{mixed, float}>
     */
    public static function acceptedCases(): iterable
    {
        yield 'int zero' => [0, 0.0];
        yield 'int positive' => [42, 42.0];
        yield 'int negative' => [-42, -42.0];
        yield 'int max' => [PHP_INT_MAX, (float) PHP_INT_MAX];
        yield 'native zero' => [0.0, 0.0];
        yield 'native fraction' => [1.5, 1.5];
        yield 'native negative' => [-1.5, -1.5];
        yield 'native max' => [PHP_FLOAT_MAX, PHP_FLOAT_MAX];
        yield 'string zero' => ['0', 0.0];
        yield 'string positive integer' => ['42', 42.0];
        yield 'string negative integer' => ['-42', -42.0];
        yield 'string fraction' => ['1.5', 1.5];
        yield 'string negative fraction' => ['-1.5', -1.5];
        yield 'string less than one' => ['0.125', 0.125];
        yield 'string trailing fractional zeroes' => ['1.500', 1.5];
        yield 'string negative zero' => ['-0', -0.0];
        yield 'string negative decimal zero' => ['-0.0', -0.0];
        yield 'underflowing decimal string' => [
            '0.' . str_repeat('0', 400) . '1',
            0.0,
        ];
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function rejectedCases(): iterable
    {
        yield 'leading zero' => ['042'];
        yield 'decimal with leading zero' => ['01.5'];
        yield 'negative decimal with leading zero' => ['-01.5'];
        yield 'leading plus' => ['+42'];
        yield 'leading space' => [' 42'];
        yield 'trailing space' => ['42 '];
        yield 'trailing newline' => ["42\n"];
        yield 'leading decimal point' => ['.5'];
        yield 'trailing decimal point' => ['1.'];
        yield 'scientific notation' => ['1e3'];
        yield 'uppercase scientific notation' => ['1E3'];
        yield 'locale decimal separator' => ['1,5'];
        yield 'thousands separator' => ['1,000'];
        yield 'digit separator' => ['1_000'];
        yield 'hexadecimal' => ['0x1A'];
        yield 'infinity string' => ['INF'];
        yield 'negative infinity string' => ['-INF'];
        yield 'nan string' => ['NAN'];
        yield 'trailing garbage' => ['1.5foo'];
        yield 'blank string' => [''];
        yield 'whitespace string' => ['   '];
        yield 'non numeric string' => ['abc'];
        yield 'overflowing decimal string' => [str_repeat('9', 400)];
        yield 'infinity' => [INF];
        yield 'negative infinity' => [-INF];
        yield 'nan' => [NAN];
        yield 'true' => [true];
        yield 'false' => [false];
        yield 'null' => [null];
        yield 'empty array' => [[]];
        yield 'array' => [['1.5']];
        yield 'object' => [new stdClass()];
    }

    #[DataProvider('acceptedCases')]
    public function testAcceptsCanonicalFiniteFloatSyntax(mixed $value, float $expected): void
    {
        self::assertSame($expected, FloatGrammar::parse($value));
    }

    #[DataProvider('rejectedCases')]
    public function testRejectsEverythingElse(mixed $value): void
    {
        self::assertNull(FloatGrammar::parse($value));
    }

    /**
     * Parser output must remain valid parser input. The lifecycle can observe
     * its own previous output while detecting an unsupported validator reuse.
     */
    #[DataProvider('acceptedCases')]
    public function testParsingIsAFixedPoint(mixed $value, float $expected): void
    {
        $once = FloatGrammar::parse($value);

        self::assertSame($once, FloatGrammar::parse($once));
        self::assertSame($expected, $once);
    }

    /**
     * @return iterable<string, array{float|string}>
     */
    public static function negativeZeroCases(): iterable
    {
        yield 'native negative zero' => [-0.0];
        yield 'negative integer string' => ['-0'];
        yield 'negative decimal string' => ['-0.0'];
    }

    #[DataProvider('negativeZeroCases')]
    public function testPreservesNegativeZero(float|string $value): void
    {
        $parsed = FloatGrammar::parse($value);

        self::assertNotNull($parsed);
        self::assertSame(-INF, fdiv(1.0, $parsed));
    }
}
