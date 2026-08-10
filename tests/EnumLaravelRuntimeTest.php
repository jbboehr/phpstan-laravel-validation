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
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rules\Enum;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\IntegerValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\ValidationStringable;

final class EnumLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    /**
     * @dataProvider pureEnumProvider
     */
    public function testPureEnumRuntimeContract(mixed $value, bool $passes): void
    {
        $rule = new Enum(PureValidationStatus::class);

        if (!$passes) {
            self::assertFalse($rule->passes('value', $value));
            return;
        }

        $this->assertAcceptedAndPreserved($rule, $value);
    }

    /** @return iterable<string, array{mixed, bool}> */
    public static function pureEnumProvider(): iterable
    {
        yield 'matching case' => [PureValidationStatus::Draft, true];
        yield 'case name' => ['Draft', false];
        yield 'other enum' => [StringValidationStatus::Draft, false];
        yield 'integer' => [1, false];
    }

    /**
     * @dataProvider stringBackedEnumProvider
     */
    public function testStringBackedEnumPreservesCoerciveInputs(mixed $value, bool $passes): void
    {
        $rule = new Enum(StringValidationStatus::class);

        if (!$passes) {
            self::assertFalse($rule->passes('value', $value));
            return;
        }

        $this->assertAcceptedAndPreserved($rule, $value);
    }

    /** @return iterable<string, array{mixed, bool}> */
    public static function stringBackedEnumProvider(): iterable
    {
        yield 'enum case' => [StringValidationStatus::Draft, true];
        yield 'native backing string' => ['draft', true];
        yield 'compatible Stringable' => [new ValidationStringable('draft'), true];
        yield 'integer zero' => [0, true];
        yield 'integer one' => [1, true];
        yield 'integral float' => [1.0, true];
        yield 'fractional float truncated by PHP' => [1.5, true];
        yield 'false coerces through zero' => [false, true];
        yield 'true coerces through one' => [true, true];
        yield 'different numeric string representation' => ['1.0', false];
        yield 'array' => [[], false];
        yield 'non-Stringable object' => [new \stdClass(), false];
    }

    /**
     * @dataProvider integerBackedEnumProvider
     */
    public function testIntegerBackedEnumPreservesCoerciveInputs(mixed $value, bool $passes): void
    {
        $rule = new Enum(IntegerValidationStatus::class);

        if (!$passes) {
            self::assertFalse($rule->passes('value', $value));
            return;
        }

        $this->assertAcceptedAndPreserved($rule, $value);
    }

    /** @return iterable<string, array{mixed, bool}> */
    public static function integerBackedEnumProvider(): iterable
    {
        yield 'enum case' => [IntegerValidationStatus::One, true];
        yield 'native backing integer' => [1, true];
        yield 'canonical numeric string' => ['1', true];
        yield 'leading-zero numeric string' => ['01', true];
        yield 'decimal numeric string' => ['1.0', true];
        yield 'exponent numeric string' => ['1e0', true];
        yield 'integral float' => [1.0, true];
        yield 'fractional float truncated by PHP' => [1.5, true];
        yield 'false coerces to zero' => [false, true];
        yield 'true coerces to one' => [true, true];
        yield 'non-numeric string' => ['draft', false];
        yield 'Stringable is not coerced to int' => [new ValidationStringable('1'), false];
        yield 'array' => [[], false];
    }

    public function testOptionalBlankAndNullableValuesFollowValidatorSemantics(): void
    {
        $factory = self::factory();
        $rule = new Enum(StringValidationStatus::class);

        $missing = $factory->make([], ['value' => [$rule]]);
        self::assertTrue($missing->passes());
        self::assertSame([], $missing->validated());

        $blank = $factory->make(['value' => ''], ['value' => [$rule]]);
        self::assertTrue($blank->passes());
        self::assertSame(['value' => ''], $blank->validated());

        self::assertFalse($rule->passes('value', null));

        $nullable = $factory->make(['value' => null], ['value' => ['nullable', $rule]]);
        self::assertTrue($nullable->passes());
        self::assertSame(['value' => null], $nullable->validated());
    }

    public function testOnlyAndExceptFollowTheirRuntimeVersionBoundaryAndPrecedence(): void
    {
        $supportsFilters = version_compare(self::frameworkVersion(), '10.46.0', '>=');
        self::assertSame($supportsFilters, self::enumMethodExists('only'));
        self::assertSame($supportsFilters, self::enumMethodExists('except'));

        if (!$supportsFilters) {
            return;
        }

        $onlyDraft = (new Enum(PureValidationStatus::class))->only(PureValidationStatus::Draft);
        $this->assertAcceptedAndPreserved($onlyDraft, PureValidationStatus::Draft);
        self::assertFalse($onlyDraft->passes('value', PureValidationStatus::Published));

        $exceptDraft = (new Enum(PureValidationStatus::class))->except(PureValidationStatus::Draft);
        self::assertFalse($exceptDraft->passes('value', PureValidationStatus::Draft));
        $this->assertAcceptedAndPreserved($exceptDraft, PureValidationStatus::Published);

        $onlyWins = (new Enum(PureValidationStatus::class))
            ->only(PureValidationStatus::Draft)
            ->except(PureValidationStatus::Draft);
        $this->assertAcceptedAndPreserved($onlyWins, PureValidationStatus::Draft);

        $coerciveOnly = (new Enum(StringValidationStatus::class))->only(StringValidationStatus::One);
        $this->assertAcceptedAndPreserved($coerciveOnly, 1.5);

        $coerciveExcept = (new Enum(StringValidationStatus::class))->except(StringValidationStatus::One);
        self::assertFalse($this->withoutDeprecations(
            static fn (): bool => $coerciveExcept->passes('value', 1.5)
        ));

        $leadingZeroOnly = (new Enum(StringValidationStatus::class))
            ->only(StringValidationStatus::LeadingZero);
        $this->assertAcceptedAndPreserved($leadingZeroOnly, '01');
        $this->assertAcceptedAndPreserved($leadingZeroOnly, new ValidationStringable('01'));
        self::assertFalse($leadingZeroOnly->passes('value', 1));
        self::assertFalse($this->withoutDeprecations(
            static fn (): bool => $leadingZeroOnly->passes('value', 1.5)
        ));

        $nonNumericOnly = (new Enum(StringValidationStatus::class))
            ->only(StringValidationStatus::Draft);
        self::assertFalse($nonNumericOnly->passes('value', 1));

        $integerTwoOnly = (new Enum(IntegerValidationStatus::class))
            ->only(IntegerValidationStatus::Two);
        $this->assertAcceptedAndPreserved($integerTwoOnly, 2.5);
        $this->assertAcceptedAndPreserved($integerTwoOnly, '2.5');
        self::assertFalse($integerTwoOnly->passes('value', false));
        self::assertFalse($integerTwoOnly->passes('value', true));
    }

    private function assertAcceptedAndPreserved(Enum $rule, mixed $value): void
    {
        $validator = self::factory()->make(['value' => $value], ['value' => ['required', $rule]]);

        self::assertTrue($this->withoutDeprecations($validator->passes(...)));
        self::assertSame($value, $validator->validated()['value']);
    }

    private function withoutDeprecations(\Closure $callback): mixed
    {
        set_error_handler(static fn (int $severity): bool => $severity === E_DEPRECATED);
        try {
            return $callback();
        } finally {
            restore_error_handler();
        }
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    private static function frameworkVersion(): string
    {
        return ltrim((string) InstalledVersions::getPrettyVersion('laravel/framework'), 'v');
    }

    private static function enumMethodExists(string $method): bool
    {
        return method_exists(Enum::class, $method);
    }
}
