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
use Illuminate\Support\Arr;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsLaravelValidation;
use jbboehr\Rensei\Parse;
use jbboehr\Rensei\Rules\DateTimeRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

/**
 * Runtime conformance for `Parse::dateTime()`.
 */
#[Group('laravel')]
final class ParseDateTimeLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
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
     * @return iterable<string, array{mixed}>
     */
    public static function defaultDateCases(): iterable
    {
        yield 'date' => ['2024-02-29'];
        yield 'date and time' => ['2024-02-29 23:59:58'];
        yield 'non-padded date' => ['2024-2-29'];
        yield 'compact numeric date string' => ['20240229'];
        yield 'compact numeric date integer' => [20240229];
        yield 'relative operation on a complete date' => ['2024-02-29 +1 day'];
        yield 'explicit offset' => ['2024-02-29T12:34:56+05:30'];
        yield 'Unix timestamp syntax' => ['@1709251198'];
        yield 'invalid calendar date' => ['2024-02-30'];
        yield 'relative text without a calendar date' => ['tomorrow'];
        yield 'plain Unix timestamp' => ['1709251198'];
        yield 'blank string' => [''];
        yield 'array' => [[]];
        yield 'object' => [new \stdClass()];
    }

    #[DataProvider('defaultDateCases')]
    public function testDefaultModeFollowsLaravelsDateAcceptance(mixed $value): void
    {
        $factory = self::factory();
        $laravel = $factory->make(
            ['starts_at' => $value],
            ['starts_at' => ['required', 'date']]
        );
        $rules = ['starts_at' => ['required', Parse::dateTime()]];
        $parser = $factory->make(['starts_at' => $value], $rules);
        $laravelPasses = $laravel->passes();
        $parserPasses = $parser->passes();

        self::assertSame(
            $laravelPasses,
            $parserPasses,
            sprintf('Default parser diverged for %s.', get_debug_type($value))
        );

        if (!$parserPasses) {
            return;
        }

        $validated = self::validated($parser);
        $this->assertInferredTypeContainsLaravelOutput(
            'default date/time ' . get_debug_type($value),
            $rules,
            $validated
        );
        self::dateTimeValue($validated['starts_at'] ?? null);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function nullByteDateCases(): iterable
    {
        yield 'trailing null byte' => ["2024-02-29\0"];
        yield 'embedded null byte' => ["2024-02\0-29"];
    }

    #[DataProvider('nullByteDateCases')]
    public function testDefaultModeRejectsNullBytesEvenWhenLaravelAcceptsThem(
        string $value
    ): void {
        $validator = self::factory()->make(
            ['starts_at' => $value],
            ['starts_at' => ['required', Parse::dateTime()]]
        );

        self::assertFalse($validator->passes());
    }

    /**
     * @return iterable<string, array{mixed, bool}>
     */
    public static function stringCases(): iterable
    {
        yield 'leap day' => ['2024-02-29 23:59:58', true];
        yield 'ordinary date' => ['2025-01-01 00:00:00', true];
        yield 'normalized day' => ['2024-02-30 23:59:58', false];
        yield 'missing leading zero' => ['2024-2-29 23:59:58', false];
        yield 'relative text' => ['tomorrow', false];
        yield 'trailing data' => ['2024-02-29 23:59:58 UTC', false];
        yield 'null byte' => ["2024-02-29 23:59:58\0", false];
        yield 'blank' => ['', false];
        yield 'integer timestamp' => [1709251198, false];
        yield 'array' => [[], false];
        yield 'object' => [new \stdClass(), false];
    }

    #[DataProvider('stringCases')]
    public function testProducesOnlyExactImmutableDateTimes(mixed $value, bool $expectedPasses): void
    {
        $rules = ['starts_at' => ['required', Parse::dateTime('Y-m-d H:i:s')]];
        $validator = self::factory()->make(
            ['starts_at' => $value],
            $rules
        );

        self::assertSame($expectedPasses, $validator->passes());
        if (!$expectedPasses) {
            return;
        }

        $validated = self::validated($validator);
        $this->assertInferredTypeContainsLaravelOutput(
            'exact date/time ' . get_debug_type($value),
            $rules,
            $validated
        );
        $parsed = self::dateTimeValue($validated['starts_at'] ?? null);
        self::assertSame($value, $parsed->format('Y-m-d H:i:s'));
        self::assertSame('UTC', $parsed->getTimezone()->getName());
    }

    public function testConfiguredTimezoneAndInputOffsetHaveExplicitPrecedence(): void
    {
        $local = self::factory()->make(
            ['starts_at' => '2024-02-29 12:34:56'],
            ['starts_at' => ['required', Parse::dateTime(
                'Y-m-d H:i:s',
                'America/New_York'
            )]]
        );
        self::assertTrue($local->passes());
        $localValue = self::dateTimeValue(self::validated($local)['starts_at'] ?? null);
        self::assertSame('America/New_York', $localValue->getTimezone()->getName());
        self::assertSame('-05:00', $localValue->format('P'));

        $offset = self::factory()->make(
            ['starts_at' => '2024-02-29 12:34:56+05:30'],
            ['starts_at' => ['required', Parse::dateTime(
                'Y-m-d H:i:sP',
                'America/New_York'
            )]]
        );
        self::assertTrue($offset->passes());
        $offsetValue = self::dateTimeValue(self::validated($offset)['starts_at'] ?? null);
        self::assertSame('+05:30', $offsetValue->format('P'));
    }

    public function testDefaultModeUsesTheConfiguredTimezone(): void
    {
        $rules = [
            'starts_at' => ['required', Parse::dateTime(
                timezone: 'America/New_York'
            )],
        ];

        $local = self::factory()->make(
            ['starts_at' => '2024-02-29 12:34:56'],
            $rules
        );
        self::assertTrue($local->passes());
        $localValue = self::dateTimeValue(self::validated($local)['starts_at'] ?? null);
        self::assertSame('America/New_York', $localValue->getTimezone()->getName());
        self::assertSame('2024-02-29T12:34:56-05:00', $localValue->format('c'));

        $offset = self::factory()->make(
            ['starts_at' => '2024-02-29T12:34:56+05:30'],
            $rules
        );
        self::assertTrue($offset->passes());
        $offsetValue = self::dateTimeValue(self::validated($offset)['starts_at'] ?? null);
        self::assertSame('+05:30', $offsetValue->getTimezone()->getName());
        self::assertSame('2024-02-29T12:34:56+05:30', $offsetValue->format('c'));

        $timestamp = self::factory()->make(['starts_at' => '@0'], $rules);
        self::assertTrue($timestamp->passes());
        $timestampValue = self::dateTimeValue(
            self::validated($timestamp)['starts_at'] ?? null
        );
        self::assertSame('America/New_York', $timestampValue->getTimezone()->getName());
        self::assertSame('1969-12-31T19:00:00-05:00', $timestampValue->format('c'));
    }

    public function testMultipleExactFormatsAreTriedInDeclarationOrder(): void
    {
        $rules = [
            'starts_at' => ['required', Parse::dateTime(['m/d/Y', 'Y-m-d'])],
        ];

        foreach (['02/29/2024', '2024-02-29'] as $input) {
            $validator = self::factory()->make(['starts_at' => $input], $rules);

            self::assertTrue($validator->passes());
            $validated = self::validated($validator);
            $this->assertInferredTypeContainsLaravelOutput(
                'multiple exact date/time formats',
                $rules,
                $validated
            );
            self::dateTimeValue($validated['starts_at'] ?? null);
        }
    }

    public function testUnixTimestampUsesTheConfiguredOutputTimezone(): void
    {
        $rules = ['created_at' => ['required', Parse::dateTime(
            'U.u',
            'America/New_York'
        )]];
        $validator = self::factory()->make(['created_at' => '0.500000'], $rules);

        self::assertTrue($validator->passes());
        $validated = self::validated($validator);
        $this->assertInferredTypeContainsLaravelOutput(
            'Unix timestamp output timezone',
            $rules,
            $validated
        );
        $parsed = self::dateTimeValue($validated['created_at'] ?? null);
        self::assertSame('0.500000', $parsed->format('U.u'));
        self::assertSame('America/New_York', $parsed->getTimezone()->getName());
        self::assertSame('1969-12-31T19:00:00.500000-05:00', $parsed->format('Y-m-d\TH:i:s.uP'));
    }

    public function testExistingDateTimesProduceImmutableValues(): void
    {
        $immutable = new DateTimeImmutable(
            '2024-02-29 12:34:56',
            new DateTimeZone('Asia/Tokyo')
        );
        $immutableValidator = self::factory()->make(
            ['starts_at' => $immutable],
            ['starts_at' => ['required', Parse::dateTime('Y-m-d')]]
        );
        self::assertTrue($immutableValidator->passes());
        self::assertSame($immutable, self::validated($immutableValidator)['starts_at'] ?? null);

        $mutable = new DateTime('2024-02-29 12:34:56', new DateTimeZone('Asia/Tokyo'));
        $mutableValidator = self::factory()->make(
            ['starts_at' => $mutable],
            ['starts_at' => ['required', Parse::dateTime('Y-m-d')]]
        );
        self::assertTrue($mutableValidator->passes());
        $parsed = self::dateTimeValue(
            self::validated($mutableValidator)['starts_at'] ?? null
        );
        self::assertSame('2024-02-29T12:34:56+09:00', $parsed->format('c'));
    }

    public function testOptionalNullableNestedAndWildcardValues(): void
    {
        $optional = self::factory()->make([], [
            'starts_at' => [Parse::dateTime('Y-m-d')],
        ]);
        self::assertTrue($optional->passes());
        self::assertSame([], self::validated($optional));

        $nullable = self::factory()->make(['starts_at' => null], [
            'starts_at' => ['nullable', Parse::dateTime('Y-m-d')],
        ]);
        self::assertTrue($nullable->passes());
        self::assertSame(['starts_at' => null], self::validated($nullable));

        $nested = self::factory()->make(
            ['event' => ['starts_at' => '2024-02-29']],
            ['event.starts_at' => ['required', Parse::dateTime('Y-m-d')]]
        );
        self::assertTrue($nested->passes());
        self::dateTimeValue(
            Arr::get(self::validated($nested), 'event.starts_at')
        );

        $wildcard = self::factory()->make(
            ['events' => [['starts_at' => '2024-02-29'], ['starts_at' => '2025-01-01']]],
            ['events.*.starts_at' => ['required', Parse::dateTime('Y-m-d')]]
        );
        self::assertTrue($wildcard->passes());
        $validated = self::validated($wildcard);
        self::dateTimeValue(Arr::get($validated, 'events.0.starts_at'));
        self::dateTimeValue(Arr::get($validated, 'events.1.starts_at'));
    }

    public function testExclusionStillRemovesTheParsedAttribute(): void
    {
        $validator = self::factory()->make(
            ['starts_at' => '2024-02-29'],
            ['starts_at' => ['exclude', Parse::dateTime('Y-m-d')]]
        );

        self::assertTrue($validator->passes());
        self::assertSame([], self::validated($validator));
    }

    public function testOrdinaryRulesObserveTheOriginalValueBeforeWriteBack(): void
    {
        $validator = self::factory()->make(
            ['starts_at' => '2024-02-29', 'confirmation' => '2024-02-29'],
            [
                'starts_at' => ['required', Parse::dateTime('Y-m-d')],
                'confirmation' => ['required', 'same:starts_at'],
            ]
        );

        self::assertTrue($validator->passes());
        $validated = self::validated($validator);
        self::dateTimeValue($validated['starts_at'] ?? null);
        self::assertSame('2024-02-29', $validated['confirmation'] ?? null);
    }

    public function testAValidatorCannotBeRunAgainAfterDateTimeWriteBack(): void
    {
        $validator = self::factory()->make(
            ['starts_at' => '2024-02-29'],
            ['starts_at' => ['required', Parse::dateTime('Y-m-d')]]
        );

        self::assertTrue($validator->passes());
        self::assertTrue($validator->fails());
        self::assertSame(
            ['A validator containing parsing rules cannot be reused.'],
            $validator->errors()->get('starts_at')
        );
    }

    public function testConstructorRejectsAnEmptyFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('non-empty format');

        new DateTimeRule('');
    }

    public function testConstructorRejectsAnEmptyFormatList(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('non-empty format or format list');

        new DateTimeRule([]);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function invalidTimezoneCases(): iterable
    {
        yield 'unknown identifier' => ['Not/A_Timezone'];
        yield 'null byte' => ["UTC\0"];
    }

    #[DataProvider('invalidTimezoneCases')]
    public function testConstructorWrapsInvalidTimezoneConfiguration(string $timezone): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date/time timezone');

        new DateTimeRule('Y-m-d', $timezone);
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    /**
     * Keep runtime assertions independent from this extension's return-type
     * inference for the literal rules used to construct the validator.
     *
     * @return array<mixed, mixed>
     */
    private static function validated(Validator $validator): array
    {
        return $validator->validated();
    }

    private static function dateTimeValue(mixed $value): DateTimeImmutable
    {
        self::assertInstanceOf(DateTimeImmutable::class, $value);

        return $value;
    }
}
