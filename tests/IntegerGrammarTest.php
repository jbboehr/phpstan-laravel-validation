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

use jbboehr\Rensei\Internal\IntegerGrammar;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

final class IntegerGrammarTest extends TestCase
{
    /**
     * @return iterable<string, array{mixed, int}>
     */
    public static function acceptedCases(): iterable
    {
        yield 'int zero' => [0, 0];
        yield 'int positive' => [42, 42];
        yield 'int negative' => [-42, -42];
        yield 'int max' => [PHP_INT_MAX, PHP_INT_MAX];
        yield 'int min' => [PHP_INT_MIN, PHP_INT_MIN];
        yield 'string zero' => ['0', 0];
        yield 'string positive' => ['42', 42];
        yield 'string negative' => ['-42', -42];
        yield 'string negative zero' => ['-0', 0];
        yield 'string max' => [(string) PHP_INT_MAX, PHP_INT_MAX];
        yield 'string min' => [(string) PHP_INT_MIN, PHP_INT_MIN];
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function rejectedCases(): iterable
    {
        yield 'leading zero' => ['042'];
        yield 'repeated zero' => ['00'];
        yield 'negative leading zero' => ['-042'];
        yield 'leading plus' => ['+42'];
        yield 'leading space' => [' 42'];
        yield 'trailing space' => ['42 '];
        yield 'trailing newline' => ["42\n"];
        yield 'inner space' => ['4 2'];
        yield 'integral decimal' => ['42.0'];
        yield 'fractional decimal' => ['1.5'];
        yield 'scientific notation' => ['1e3'];
        yield 'hexadecimal' => ['0x1A'];
        yield 'digit separator' => ['4_2'];
        yield 'thousands separator' => ['1,000'];
        yield 'trailing garbage' => ['1foo'];
        yield 'blank string' => [''];
        yield 'whitespace string' => ['   '];
        yield 'non numeric string' => ['abc'];
        yield 'integral float' => [42.0];
        yield 'fractional float' => [42.9];
        yield 'infinity' => [INF];
        yield 'true' => [true];
        yield 'false' => [false];
        yield 'null' => [null];
        yield 'empty array' => [[]];
        yield 'array' => [['42']];
        yield 'object' => [new stdClass()];
    }

    #[DataProvider('acceptedCases')]
    public function testAcceptsCanonicalIntegerSyntax(mixed $value, int $expected): void
    {
        self::assertSame($expected, IntegerGrammar::parse($value));
    }

    #[DataProvider('rejectedCases')]
    public function testRejectsEverythingElse(mixed $value): void
    {
        self::assertNull(IntegerGrammar::parse($value));
    }

    /**
     * A reused validator hands the parser its own previous output, because
     * the write-back outlives the run. A parser that rejected it would fail
     * on a value it had already accepted.
     */
    #[DataProvider('acceptedCases')]
    public function testParsingIsAFixedPoint(mixed $value, int $expected): void
    {
        $once = IntegerGrammar::parse($value);

        self::assertSame($once, IntegerGrammar::parse($once));
        self::assertSame($expected, $once);
    }

    /**
     * Overflow is rejected rather than saturated. Derive the boundary from
     * PHP_INT_MAX so the case is honest on 32-bit builds too.
     */
    public function testRejectsValuesBeyondThePlatformIntegerWidth(): void
    {
        // The negative boundary is |PHP_INT_MIN| + 1, not PHP_INT_MAX + 1:
        // -(PHP_INT_MAX + 1) is exactly PHP_INT_MIN and remains representable.
        $beyondMax = self::increment((string) PHP_INT_MAX);
        $beyondMin = '-' . self::increment(substr((string) PHP_INT_MIN, 1));

        self::assertNull(IntegerGrammar::parse($beyondMax));
        self::assertNull(IntegerGrammar::parse($beyondMin));

        self::assertSame(PHP_INT_MAX, IntegerGrammar::parse((string) PHP_INT_MAX));
        self::assertSame(PHP_INT_MIN, IntegerGrammar::parse((string) PHP_INT_MIN));
    }

    public function testRejectsOverflowRatherThanSaturating(): void
    {
        $beyondMax = self::increment((string) PHP_INT_MAX);

        // (int) would silently clamp to PHP_INT_MAX here.
        self::assertSame(PHP_INT_MAX, (int) $beyondMax);
        self::assertNull(IntegerGrammar::parse($beyondMax));
    }

    /**
     * Decimal string increment, so the overflow boundary needs no arbitrary
     * precision extension and holds on any integer width.
     */
    private static function increment(string $digits): string
    {
        $carry = 1;
        $result = '';

        for ($index = strlen($digits) - 1; $index >= 0; $index--) {
            $sum = (int) $digits[$index] + $carry;
            $result = ((string) ($sum % 10)) . $result;
            $carry = intdiv($sum, 10);
        }

        return $carry > 0 ? $carry . $result : $result;
    }
}
