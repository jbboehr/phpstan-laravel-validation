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

use BackedEnum;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum as LaravelEnumRule;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FractionValidationValue;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\IntegerValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\ValidationStringable;
use jbboehr\Rensei\Parse;
use jbboehr\Rensei\Rules\EnumRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use ReflectionClass;

/**
 * Runtime conformance for `Parse::enum()`.
 */
#[Group('laravel')]
final class ParseEnumLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
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
     * @return iterable<string, array{class-string<BackedEnum>, mixed, BackedEnum|null}>
     */
    public static function valueCases(): iterable
    {
        yield 'string case' => [
            StringValidationStatus::class,
            StringValidationStatus::Draft,
            StringValidationStatus::Draft,
        ];
        yield 'string backing value' => [
            StringValidationStatus::class,
            'draft',
            StringValidationStatus::Draft,
        ];
        yield 'string numeric backing value' => [
            StringValidationStatus::class,
            '1',
            StringValidationStatus::One,
        ];
        yield 'string unknown value' => [StringValidationStatus::class, 'missing', null];
        yield 'string enum rejects blank' => [StringValidationStatus::class, '', null];
        yield 'string enum rejects int' => [StringValidationStatus::class, 1, null];
        yield 'string enum rejects float' => [StringValidationStatus::class, 1.0, null];
        yield 'string enum rejects bool' => [StringValidationStatus::class, true, null];
        yield 'string enum rejects Stringable' => [
            StringValidationStatus::class,
            new ValidationStringable('draft'),
            null,
        ];

        yield 'int case' => [
            IntegerValidationStatus::class,
            IntegerValidationStatus::One,
            IntegerValidationStatus::One,
        ];
        yield 'int backing value' => [
            IntegerValidationStatus::class,
            1,
            IntegerValidationStatus::One,
        ];
        yield 'int unknown value' => [IntegerValidationStatus::class, 9, null];
        yield 'int enum rejects string' => [IntegerValidationStatus::class, '1', null];
        yield 'int enum rejects leading-zero string' => [IntegerValidationStatus::class, '01', null];
        yield 'int enum rejects float' => [IntegerValidationStatus::class, 1.0, null];
        yield 'int enum rejects bool' => [IntegerValidationStatus::class, true, null];
        yield 'int enum rejects other enum' => [
            IntegerValidationStatus::class,
            StringValidationStatus::One,
            null,
        ];
        yield 'enum rejects null without nullable' => [StringValidationStatus::class, null, null];
        yield 'enum rejects array' => [StringValidationStatus::class, [], null];
    }

    /**
     * @param class-string<BackedEnum> $enum
     */
    #[DataProvider('valueCases')]
    public function testParsesOnlyExactBackingTypes(
        string $enum,
        mixed $value,
        ?BackedEnum $expected
    ): void {
        $validator = self::factory()->make(
            ['value' => $value],
            ['value' => ['required', Parse::enum($enum)]]
        );

        self::assertSame($expected !== null, $validator->passes());
        if ($expected !== null) {
            self::assertSame(['value' => $expected], $validator->validated());
        }
    }

    public function testRejectsCoercionsThatLaravelsEnumRuleAcceptsAndPreserves(): void
    {
        $cases = [
            [StringValidationStatus::class, 1],
            [IntegerValidationStatus::class, '1'],
        ];

        foreach ($cases as [$enum, $value]) {
            $laravel = self::factory()->make(
                ['value' => $value],
                ['value' => ['required', new LaravelEnumRule($enum)]]
            );
            self::assertTrue($laravel->passes());
            self::assertSame(['value' => $value], $laravel->validated());

            $parser = self::factory()->make(
                ['value' => $value],
                ['value' => ['required', Parse::enum($enum)]]
            );
            self::assertFalse($parser->passes());
        }
    }

    /** @return iterable<string, array{class-string<\UnitEnum>, mixed}> */
    public static function nativeEnumSiblingValues(): iterable
    {
        yield 'fraction string' => [FractionValidationValue::class, '0.5'];
        yield 'Stringable' => [FractionValidationValue::class, new ValidationStringable('0.5')];
        yield 'backed case' => [FractionValidationValue::class, FractionValidationValue::Half];
        yield 'pure case' => [PureValidationStatus::class, PureValidationStatus::Draft];
        yield 'numeric string for integer enum' => [IntegerValidationStatus::class, '01'];
        yield 'boolean for integer enum' => [IntegerValidationStatus::class, true];
    }

    #[DataProvider('nativeEnumSiblingValues')]
    public function testNativeEnumPreservesItsValueBesideAParser(string $enum, mixed $value): void
    {
        $validator = self::factory()->make(['age' => '42', 'value' => $value], [
            'age' => ['required', Parse::integer()],
            'value' => ['required', Rule::enum($enum)],
        ]);

        self::assertTrue($validator->passes());
        self::assertSame(['age' => 42, 'value' => $value], $validator->validated());
    }

    /** @return iterable<string, array{bool}> */
    public static function nativeEnumParserOrders(): iterable
    {
        yield 'parser first' => [true];
        yield 'predicate first' => [false];
    }

    #[DataProvider('nativeEnumParserOrders')]
    public function testNativeEnumChecksTheOriginalValueBeforeParserWriteBack(bool $parserFirst): void
    {
        $fractionRules = [Parse::float(), new LaravelEnumRule(FractionValidationValue::class)];
        $statusRules = [Parse::enum(StringValidationStatus::class), Rule::enum(StringValidationStatus::class)];
        if (!$parserFirst) {
            $fractionRules = array_reverse($fractionRules);
            $statusRules = array_reverse($statusRules);
        }
        $validator = self::factory()->make(['fraction' => '0.5', 'status' => 'draft'], [
            'fraction' => ['required', ...$fractionRules],
            'status' => ['required', ...$statusRules],
        ]);

        self::assertTrue($validator->passes());
        self::assertSame([
            'fraction' => 0.5,
            'status' => StringValidationStatus::Draft,
        ], $validator->validated());
    }

    #[DataProvider('nativeEnumParserOrders')]
    public function testNativeEnumFilterChecksTheOriginalValueBeforeParserWriteBack(bool $parserFirst): void
    {
        if (!(new ReflectionClass(LaravelEnumRule::class))->hasMethod('only')) {
            self::markTestSkipped('Enum filters require Laravel 10.46 or newer.');
        }

        $rules = [
            Parse::float(),
            Rule::enum(FractionValidationValue::class)->only(FractionValidationValue::Half),
        ];
        if (!$parserFirst) {
            $rules = array_reverse($rules);
        }

        $validator = self::factory()->make(['fraction' => '0.5'], [
            'fraction' => ['required', ...$rules],
        ]);

        self::assertTrue($validator->passes());
        self::assertSame(['fraction' => 0.5], $validator->validated());
    }

    public function testNativeEnumFiltersBesideAParser(): void
    {
        if (!(new ReflectionClass(LaravelEnumRule::class))->hasMethod('only')) {
            self::markTestSkipped('Enum filters require Laravel 10.46 or newer.');
        }

        $validator = self::factory()->make([
            'age' => '42',
            'only' => PureValidationStatus::Draft,
            'except' => PureValidationStatus::Draft,
        ], [
            'age' => ['required', Parse::integer()],
            'only' => ['required', Rule::enum(PureValidationStatus::class)->only(PureValidationStatus::Draft)],
            'except' => ['required', (new LaravelEnumRule(PureValidationStatus::class))
                ->except(PureValidationStatus::Published)],
        ]);

        self::assertTrue($validator->passes());
        self::assertSame([
            'age' => 42,
            'only' => PureValidationStatus::Draft,
            'except' => PureValidationStatus::Draft,
        ], $validator->validated());
    }

    public function testNativeEnumOptionalAndNestedValuesBesideParsers(): void
    {
        foreach ([[], ['fraction' => ''], ['fraction' => null]] as $optional) {
            $validator = self::factory()->make(['age' => '42', ...$optional], [
                'age' => ['required', Parse::integer()],
                'fraction' => ['nullable', Rule::enum(FractionValidationValue::class)],
            ]);
            self::assertTrue($validator->passes());
            self::assertSame(['age' => 42, ...$optional], $validator->validated());
        }

        $validator = self::factory()->make(['rows' => [['age' => '7', 'fraction' => '0.5']]], [
            'rows.*.age' => ['required', Parse::integer()],
            'rows.*.fraction' => ['required', Rule::enum(FractionValidationValue::class)],
        ]);
        self::assertTrue($validator->passes());
        self::assertSame(['rows' => [['age' => 7, 'fraction' => '0.5']]], $validator->validated());
    }

    public function testOptionalNullableAndWildcardValues(): void
    {
        $optional = self::factory()->make([], [
            'status' => [Parse::enum(StringValidationStatus::class)],
        ]);
        self::assertTrue($optional->passes());
        self::assertSame([], $optional->validated());

        $nullable = self::factory()->make(['status' => null], [
            'status' => ['nullable', Parse::enum(StringValidationStatus::class)],
        ]);
        self::assertTrue($nullable->passes());
        self::assertSame(['status' => null], $nullable->validated());

        $wildcard = self::factory()->make(
            ['users' => [['status' => 'draft'], ['status' => 'published']]],
            ['users.*.status' => ['required', Parse::enum(StringValidationStatus::class)]]
        );
        self::assertTrue($wildcard->passes());
        self::assertSame([
            'users' => [
                ['status' => StringValidationStatus::Draft],
                ['status' => StringValidationStatus::Published],
            ],
        ], $wildcard->validated());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function invalidEnumClasses(): iterable
    {
        yield 'pure enum' => [PureValidationStatus::class];
        yield 'ordinary class' => [\stdClass::class];
        yield 'missing class' => ['MissingEnumClass'];
    }

    #[DataProvider('invalidEnumClasses')]
    public function testNonBackedEnumClassesAreRejectedAtConstruction(string $enum): void
    {
        $reflection = new ReflectionClass(EnumRule::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($enum . ' is not a backed enum.');
        $reflection->newInstance($enum);
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }
}
