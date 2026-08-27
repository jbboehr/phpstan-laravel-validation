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

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use jbboehr\Rensei\Internal\DateTimeGrammar;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

final class DateTimeGrammarTest extends TestCase
{
    /**
     * @return iterable<string, array{mixed, string}>
     */
    public static function laravelCompatibleCases(): iterable
    {
        yield 'date' => ['2024-02-29', '2024-02-29T00:00:00+00:00'];
        yield 'date and time' => [
            '2024-02-29 23:59:58',
            '2024-02-29T23:59:58+00:00',
        ];
        yield 'non-padded date' => ['2024-2-29', '2024-02-29T00:00:00+00:00'];
        yield 'compact numeric date' => [20240229, '2024-02-29T00:00:00+00:00'];
        yield 'relative operation on a complete date' => [
            '2024-02-29 +1 day',
            '2024-03-01T00:00:00+00:00',
        ];
        yield 'explicit offset' => [
            '2024-02-29T12:34:56+05:30',
            '2024-02-29T12:34:56+05:30',
        ];
        yield 'Unix timestamp syntax' => [
            '@1709251198',
            '2024-02-29T23:59:58+00:00',
        ];
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function rejectedLaravelCompatibleCases(): iterable
    {
        yield 'trailing null byte' => ["2024-02-29\0"];
        yield 'embedded null byte' => ["2024-02\0-29"];
        yield 'invalid calendar date' => ['2024-02-30'];
        yield 'partial calendar date' => ['February 29'];
        yield 'relative text without a calendar date' => ['tomorrow'];
        yield 'plain Unix timestamp' => ['1709251198'];
        yield 'blank string' => [''];
        yield 'boolean' => [true];
        yield 'null' => [null];
        yield 'array' => [[]];
        yield 'object' => [new stdClass()];
    }

    /**
     * @return iterable<string, array{string, string, string, string}>
     */
    public static function acceptedStringCases(): iterable
    {
        yield 'date' => ['Y-m-d', '2024-02-29', 'UTC', '2024-02-29T00:00:00.000000+00:00'];
        yield 'date and time' => [
            'Y-m-d H:i:s',
            '2024-02-29 23:59:58',
            'America/New_York',
            '2024-02-29T23:59:58.000000-05:00',
        ];
        yield 'microseconds' => [
            'Y-m-d H:i:s.u',
            '2024-02-29 23:59:58.123456',
            'UTC',
            '2024-02-29T23:59:58.123456+00:00',
        ];
        yield 'input offset takes precedence' => [
            'Y-m-d H:i:sP',
            '2024-02-29 23:59:58+05:30',
            'America/New_York',
            '2024-02-29T23:59:58.000000+05:30',
        ];
        yield 'Unix timestamp uses configured output timezone' => [
            'U',
            '0',
            'America/New_York',
            '1969-12-31T19:00:00.000000-05:00',
        ];
        yield 'fractional Unix timestamp uses configured output timezone' => [
            'U.u',
            '0.500000',
            'America/New_York',
            '1969-12-31T19:00:00.500000-05:00',
        ];
        yield 'timezone without a date' => [
            'P',
            '+05:30',
            'UTC',
            '1970-01-01T00:00:00.000000+05:30',
        ];
        yield 'escaped parse control is a literal' => [
            'Y-m-d\\|',
            '2024-02-29|',
            'UTC',
            '2024-02-29T00:00:00.000000+00:00',
        ];
        yield 'time-only input is reset to the epoch date' => [
            'H:i',
            '12:34',
            'UTC',
            '1970-01-01T12:34:00.000000+00:00',
        ];
    }

    /**
     * @return iterable<string, array{string, mixed, string}>
     */
    public static function rejectedCases(): iterable
    {
        yield 'normalized day' => ['Y-m-d', '2024-02-30', 'UTC'];
        yield 'normalized month' => ['Y-m-d', '2024-13-01', 'UTC'];
        yield 'nonexistent DST wall time' => [
            'Y-m-d H:i',
            '2024-03-10 02:30',
            'America/New_York',
        ];
        yield 'relative date' => ['Y-m-d', 'tomorrow', 'UTC'];
        yield 'wrong separator' => ['Y-m-d', '2024/02/29', 'UTC'];
        yield 'missing leading zero' => ['Y-m-d', '2024-2-29', 'UTC'];
        yield 'trailing data' => ['Y-m-d', '2024-02-29 later', 'UTC'];
        yield 'leading whitespace' => ['Y-m-d', ' 2024-02-29', 'UTC'];
        yield 'trailing newline' => ['Y-m-d', "2024-02-29\n", 'UTC'];
        yield 'null byte' => ['Y-m-d', "2024-02-29\0", 'UTC'];
        yield 'blank string' => ['Y-m-d', '', 'UTC'];
        yield 'integer timestamp' => ['U', 1709164800, 'UTC'];
        yield 'float timestamp' => ['U.u', 1709164800.5, 'UTC'];
        yield 'true' => ['Y-m-d', true, 'UTC'];
        yield 'false' => ['Y-m-d', false, 'UTC'];
        yield 'null' => ['Y-m-d', null, 'UTC'];
        yield 'array' => ['Y-m-d', ['2024-02-29'], 'UTC'];
        yield 'object' => ['Y-m-d', new stdClass(), 'UTC'];
    }

    #[DataProvider('laravelCompatibleCases')]
    public function testParsesLaravelCompatibleValues(mixed $value, string $expected): void
    {
        $parsed = (new DateTimeGrammar(null, new DateTimeZone('UTC')))->parse($value);

        self::assertNotNull($parsed);
        self::assertSame($expected, $parsed->format('c'));
    }

    #[DataProvider('rejectedLaravelCompatibleCases')]
    public function testRejectsValuesOutsideLaravelsDateContract(mixed $value): void
    {
        self::assertNull(
            (new DateTimeGrammar(null, new DateTimeZone('UTC')))->parse($value)
        );
    }

    #[DataProvider('acceptedStringCases')]
    public function testParsesExactStrings(
        string $format,
        string $value,
        string $timezone,
        string $expected
    ): void {
        $parsed = (new DateTimeGrammar($format, new DateTimeZone($timezone)))->parse($value);

        self::assertNotNull($parsed);
        self::assertSame($expected, $parsed->format('Y-m-d\TH:i:s.uP'));
    }

    #[DataProvider('rejectedCases')]
    public function testRejectsNonExactInput(string $format, mixed $value, string $timezone): void
    {
        self::assertNull(
            (new DateTimeGrammar($format, new DateTimeZone($timezone)))->parse($value)
        );
    }

    public function testImmutableInputPassesThroughUnchanged(): void
    {
        $value = new DateTimeImmutable('2024-02-29 12:34:56', new DateTimeZone('Asia/Tokyo'));
        $grammar = new DateTimeGrammar('Y-m-d', new DateTimeZone('UTC'));

        self::assertSame($value, $grammar->parse($value));
    }

    public function testMutableInputIsCopiedWithItsInstantAndTimezone(): void
    {
        $value = new DateTime('2024-02-29 12:34:56', new DateTimeZone('Asia/Tokyo'));
        $parsed = (new DateTimeGrammar('Y-m-d', new DateTimeZone('UTC')))->parse($value);

        self::assertInstanceOf(DateTimeImmutable::class, $parsed);
        self::assertSame('2024-02-29T12:34:56.000000+09:00', $parsed->format('Y-m-d\TH:i:s.uP'));
    }

    public function testParsingDoesNotUseTheProcessDefaultTimezone(): void
    {
        $previous = date_default_timezone_get();

        try {
            date_default_timezone_set('Pacific/Honolulu');
            $exact = (new DateTimeGrammar(
                'Y-m-d H:i:s',
                new DateTimeZone('UTC')
            ))->parse('2024-02-29 12:34:56');
            $laravelCompatible = (new DateTimeGrammar(
                null,
                new DateTimeZone('UTC')
            ))->parse('2024-02-29 +1 day');
        } finally {
            date_default_timezone_set($previous);
        }

        self::assertNotNull($exact);
        self::assertSame('UTC', $exact->getTimezone()->getName());
        self::assertSame('2024-02-29T12:34:56+00:00', $exact->format('c'));

        self::assertNotNull($laravelCompatible);
        self::assertSame('UTC', $laravelCompatible->getTimezone()->getName());
        self::assertSame('2024-03-01T00:00:00+00:00', $laravelCompatible->format('c'));
    }

    public function testParsingIsAFixedPoint(): void
    {
        $grammar = new DateTimeGrammar('Y-m-d', new DateTimeZone('UTC'));
        $once = $grammar->parse('2024-02-29');

        self::assertNotNull($once);
        self::assertSame($once, $grammar->parse($once));
    }

    public function testMultipleExactFormatsAreTriedInDeclarationOrder(): void
    {
        $grammar = new DateTimeGrammar(
            ['m/d/Y', 'd/m/Y', 'Y-m-d'],
            new DateTimeZone('UTC')
        );

        $ambiguous = $grammar->parse('01/02/2024');
        self::assertNotNull($ambiguous);
        self::assertSame('2024-01-02', $ambiguous->format('Y-m-d'));

        $fallback = $grammar->parse('2024-02-29');
        self::assertNotNull($fallback);
        self::assertSame('2024-02-29', $fallback->format('Y-m-d'));
    }

    public function testLaravelCompatibleParsingUsesTheConfiguredTimezone(): void
    {
        $grammar = new DateTimeGrammar(
            null,
            new DateTimeZone('America/New_York')
        );

        $local = $grammar->parse('2024-02-29 12:34:56');
        self::assertNotNull($local);
        self::assertSame('America/New_York', $local->getTimezone()->getName());
        self::assertSame('2024-02-29T12:34:56-05:00', $local->format('c'));

        $offset = $grammar->parse('2024-02-29T12:34:56+05:30');
        self::assertNotNull($offset);
        self::assertSame('+05:30', $offset->getTimezone()->getName());
        self::assertSame('2024-02-29T12:34:56+05:30', $offset->format('c'));

        $timestamp = $grammar->parse('@0');
        self::assertNotNull($timestamp);
        self::assertSame('America/New_York', $timestamp->getTimezone()->getName());
        self::assertSame('1969-12-31T19:00:00-05:00', $timestamp->format('c'));

        $paddedTimestamp = $grammar->parse(' @0');
        self::assertNotNull($paddedTimestamp);
        self::assertSame('America/New_York', $paddedTimestamp->getTimezone()->getName());
        self::assertSame('1969-12-31T19:00:00-05:00', $paddedTimestamp->format('c'));
    }

    public function testAcceptsAnAmbiguousWallTimeWithoutPromisingItsOffset(): void
    {
        $parsed = (new DateTimeGrammar(
            'Y-m-d H:i',
            new DateTimeZone('America/New_York')
        ))->parse('2024-11-03 01:30');

        self::assertNotNull($parsed);
        self::assertSame('2024-11-03 01:30', $parsed->format('Y-m-d H:i'));
        self::assertSame('America/New_York', $parsed->getTimezone()->getName());
        self::assertContains($parsed->format('P'), ['-04:00', '-05:00']);
    }

    public function testRejectsAnEmptyFormatAtConstruction(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('requires a non-empty format');

        new DateTimeGrammar('', new DateTimeZone('UTC'));
    }

    public function testRejectsAnEmptyFormatListAtConstruction(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('non-empty format or format list');

        new DateTimeGrammar([], new DateTimeZone('UTC'));
    }

    public function testRejectsANonListFormatArrayAtConstruction(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('non-empty format or format list');

        new DateTimeGrammar(['date' => 'Y-m-d'], new DateTimeZone('UTC'));
    }

    /**
     * @return iterable<string, array{array<mixed>}>
     */
    public static function invalidFormatListMemberCases(): iterable
    {
        yield 'empty member' => [['Y-m-d', '']];
        yield 'non-string member' => [['Y-m-d', 1]];
    }

    /** @param array<mixed> $formats */
    #[DataProvider('invalidFormatListMemberCases')]
    public function testRejectsInvalidFormatListMembers(array $formats): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must be a non-empty string');

        new DateTimeGrammar($formats, new DateTimeZone('UTC'));
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function invalidFormatCases(): iterable
    {
        yield 'reset at start' => ['!Y-m-d', '!'];
        yield 'reset at end' => ['Y-m-d|', '|'];
        yield 'allow trailing data' => ['Y-m-d+', '+'];
        yield 'any byte' => ['Y-m-d*', '*'];
        yield 'any byte without separator' => ['Y-m-d?', '?'];
        yield 'separator class' => ['Y-m-d#', '#'];
    }

    #[DataProvider('invalidFormatCases')]
    public function testRejectsUnescapedParseOnlyFormatControls(
        string $format,
        string $control
    ): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('control character "%s"', $control));

        new DateTimeGrammar($format, new DateTimeZone('UTC'));
    }

    public function testRejectsADanglingFormatEscape(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cannot end with an escape character');

        new DateTimeGrammar('Y-m-d\\', new DateTimeZone('UTC'));
    }

    public function testAnEscapedControlDoesNotHideALaterUnescapedControl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('control character "+"');

        new DateTimeGrammar('Y\\!+', new DateTimeZone('UTC'));
    }

    public function testRejectsAUnixTimestampCombinedWithAnInputTimezone(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cannot combine a Unix timestamp with an input timezone');

        new DateTimeGrammar('U P', new DateTimeZone('UTC'));
    }
}
