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
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\ExcludeIf;
use Illuminate\Validation\Rules\ProhibitedIf;
use Illuminate\Validation\Rules\RequiredIf;
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class ConditionalRuleBuilderLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    public function testLiteralConditionsSerializeLikeTheirUnconditionalRules(): void
    {
        foreach ($this->conditionalRules(false) as $name => $rule) {
            self::assertSame('', (string) $rule, $name);
        }

        foreach ($this->conditionalRules(true) as $name => $rule) {
            $expected = match (true) {
                str_starts_with($name, 'required') => 'required',
                str_starts_with($name, 'exclude') => 'exclude',
                default => 'prohibited',
            };
            self::assertSame($expected, (string) $rule, $name);
        }
    }

    public function testFalseConditionsAddNoConstraintAndPreservePresentInput(): void
    {
        foreach ($this->conditionalRules(false) as $name => $rule) {
            $validator = self::factory()->make(['value' => 'text'], ['value' => $rule]);

            self::assertTrue($validator->passes(), $name);
            self::assertSame(['value' => 'text'], $validator->validated(), $name);

            $withString = self::factory()->make(
                ['value' => 'text'],
                ['value' => [$rule, 'string']]
            );
            self::assertTrue($withString->passes(), $name);
            self::assertSame(['value' => 'text'], $withString->validated(), $name);
            self::assertFalse(self::factory()->make(
                ['value' => 123],
                ['value' => [$rule, 'string']]
            )->passes(), $name);
        }
    }

    public function testFalseConditionsStillMarkParentsForCompleteProjection(): void
    {
        $data = ['payload' => ['name' => 'Ada', 'extra' => 'preserved']];

        foreach ($this->conditionalRules(false) as $name => $rule) {
            $validator = self::factory()->make($data, [
                'payload' => $rule,
                'payload.name' => 'required|string',
            ]);

            self::assertSame([''], $validator->getRules()['payload'], $name);
            self::assertTrue($validator->passes(), $name);
            self::assertSame($data, $validator->validated(), $name);
        }
    }

    public function testTrueRequiredConditionsRequireAndPreserveInput(): void
    {
        foreach ([Rule::requiredIf(true), new RequiredIf(true)] as $rule) {
            self::assertFalse(self::factory()->make([], ['value' => $rule])->passes());

            $validator = self::factory()->make(['value' => 'text'], ['value' => $rule]);
            self::assertTrue($validator->passes());
            self::assertSame(['value' => 'text'], $validator->validated());
        }
    }

    public function testTrueExcludeConditionsRemoveInputFromValidatedOutput(): void
    {
        foreach ([Rule::excludeIf(true), new ExcludeIf(true)] as $rule) {
            $validator = self::factory()->make(['value' => 'text'], ['value' => $rule]);

            self::assertTrue($validator->passes());
            self::assertSame([], $validator->validated());
        }
    }

    public function testTrueProhibitedConditionsRejectNonEmptyInput(): void
    {
        foreach ([Rule::prohibitedIf(true), new ProhibitedIf(true)] as $rule) {
            self::assertTrue(self::factory()->make([], ['value' => $rule])->passes());
            self::assertFalse(self::factory()->make(
                ['value' => 'text'],
                ['value' => $rule]
            )->passes());
        }
    }

    /** @return array<string, \Stringable> */
    private function conditionalRules(bool $condition): array
    {
        return [
            'required factory' => Rule::requiredIf($condition),
            'required direct' => new RequiredIf($condition),
            'exclude factory' => Rule::excludeIf($condition),
            'exclude direct' => new ExcludeIf($condition),
            'prohibited factory' => Rule::prohibitedIf($condition),
            'prohibited direct' => new ProhibitedIf($condition),
        ];
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }
}
