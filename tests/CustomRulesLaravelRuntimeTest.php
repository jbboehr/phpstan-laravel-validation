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
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\AttributeRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\DataAwareIntegerRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\ImplicitStringRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\IntegerRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\InvokableIntegerRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\LegacyImplicitIntegerRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\LegacyIntegerRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\PhpDocRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/CustomRules/Rules.php';

final class CustomRulesLaravelRuntimeTest extends TestCase
{
    /**
     * @return iterable<string, array{mixed, mixed, object}>
     */
    public static function integerRules(): iterable
    {
        yield 'modern validation rule' => [42, '42', new IntegerRule()];
        yield 'attributed modern rule' => ['value', '', new AttributeRule()];
        yield 'PHPDoc modern rule' => [1, 0, new PhpDocRule()];
        yield 'legacy validation rule' => [42, '42', new LegacyIntegerRule()];
        yield 'legacy invokable rule' => [42, '42', new InvokableIntegerRule()];
    }

    #[DataProvider('integerRules')]
    public function testObjectRulesPreserveSuccessfulValuesAndRejectOthers(
        mixed $passingValue,
        mixed $failingValue,
        object $rule
    ): void {
        $passing = $this->factory()->make(['value' => $passingValue], ['value' => ['required', $rule]]);
        $failing = $this->factory()->make(['value' => $failingValue], ['value' => ['required', $rule]]);

        self::assertTrue($passing->passes());
        self::assertSame(['value' => $passingValue], $passing->validated());
        self::assertFalse($failing->passes());
    }

    public function testOptionalBlankStringBypassesNonImplicitObjectRule(): void
    {
        $validator = $this->factory()->make(['value' => ''], ['value' => [new IntegerRule()]]);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => ''], $validator->validated());
    }

    public function testMissingValueDoesNotRunNonImplicitObjectRule(): void
    {
        $validator = $this->factory()->make([], ['value' => [new IntegerRule()]]);

        self::assertTrue($validator->passes());
        self::assertSame([], $validator->validated());
    }

    public function testImplicitObjectRuleRunsForBlankString(): void
    {
        $validator = $this->factory()->make(
            ['value' => ''],
            ['value' => [new LegacyImplicitIntegerRule()]]
        );

        self::assertFalse($validator->passes());
    }

    public function testModernImplicitObjectRuleRunsForBlankString(): void
    {
        $validator = $this->factory()->make(
            ['value' => ''],
            ['value' => [new ImplicitStringRule()]]
        );

        self::assertFalse($validator->passes());
    }

    public function testDataAwareRuleCanDependOnOtherInputWhilePreservingValue(): void
    {
        $rules = [
            'enabled' => 'required|boolean',
            'value' => ['required', new DataAwareIntegerRule()],
        ];
        $passing = $this->factory()->make(['enabled' => true, 'value' => 42], $rules);
        $failing = $this->factory()->make(['enabled' => false, 'value' => 42], $rules);

        self::assertTrue($passing->passes());
        self::assertSame(['enabled' => true, 'value' => 42], $passing->validated());
        self::assertFalse($failing->passes());
    }

    public function testClosureRulePreservesSuccessfulOriginalValue(): void
    {
        $rule = static function (string $attribute, mixed $value, \Closure $fail): void {
            if (!is_int($value)) {
                $fail('The value must be an integer.');
            }
        };
        $passing = $this->factory()->make(['value' => 42], ['value' => ['required', $rule]]);
        $failing = $this->factory()->make(['value' => '42'], ['value' => ['required', $rule]]);

        self::assertTrue($passing->passes());
        self::assertSame(['value' => 42], $passing->validated());
        self::assertFalse($failing->passes());
    }

    public function testRegisteredStringRulePreservesSuccessfulOriginalValue(): void
    {
        $factory = $this->factory();
        $factory->extend(
            'custom_integer',
            static fn (string $attribute, mixed $value): bool => is_int($value)
        );
        $passing = $factory->make(['value' => 42], ['value' => 'required|custom_integer']);
        $failing = $factory->make(['value' => '42'], ['value' => 'required|custom_integer']);

        self::assertTrue($passing->passes());
        self::assertSame(['value' => 42], $passing->validated());
        self::assertFalse($failing->passes());
    }

    private function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }
}
