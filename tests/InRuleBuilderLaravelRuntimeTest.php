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
use Illuminate\Validation\Rule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\ValidationStringable;
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class InRuleBuilderLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    public function testLiteralStringBuilderPreservesAcceptedNativeValues(): void
    {
        $rule = Rule::in(['one', 'a,b', 'a"b']);

        $this->assertAcceptedAndPreserved($rule, 'one');
        $this->assertAcceptedAndPreserved($rule, 'a,b');
        $this->assertAcceptedAndPreserved($rule, new ValidationStringable('a"b'));
        $this->assertRejected($rule, 'other');
    }

    public function testNumericBuilderUsesLaravelLooseStringComparison(): void
    {
        $rule = Rule::in([1]);

        foreach ([1, 1.0, true, '01', '1.0', '1e0'] as $value) {
            $this->assertAcceptedAndPreserved($rule, $value);
        }

        $this->assertRejected($rule, 1.5);
        $this->assertRejected($rule, false);
    }

    public function testNumericBuilderPreservesIntegerAndFormattingEquivalentFloatValues(): void
    {
        $rule = Rule::in([1, 2.5, -3.0]);

        foreach ([1, -3, 1.00000000000001, 2.5] as $value) {
            $this->assertAcceptedAndPreserved($rule, $value);
        }

        $this->assertRejected($rule, 2);
        $this->assertRejected($rule, -2);
    }

    public function testFloatBuilderCanAcceptAnIntegerAfterRuntimePrecisionChanges(): void
    {
        $originalPrecision = ini_get('precision');

        try {
            self::assertNotFalse(ini_set('precision', '1'));
            $rule = Rule::in([2.5]);

            self::assertSame('in:"2"', (string) $rule);
            $this->assertAcceptedAndPreserved($rule, 2);
        } finally {
            ini_set('precision', $originalPrecision);
        }
    }

    public function testFalseAndOptionalBlankValuesRetainTheirOriginalRepresentation(): void
    {
        $falseRule = Rule::in([false]);
        $this->assertAcceptedAndPreserved($falseRule, false);

        $null = self::factory()->make(['value' => null], ['value' => [$falseRule]]);
        self::assertTrue($null->passes());
        self::assertSame(['value' => null], $null->validated());

        $factory = self::factory();
        $missing = $factory->make([], ['value' => [Rule::in(['one'])]]);
        self::assertTrue($missing->passes());
        self::assertSame([], $missing->validated());

        $blank = $factory->make(['value' => ''], ['value' => [Rule::in(['one'])]]);
        self::assertTrue($blank->passes());
        self::assertSame(['value' => ''], $blank->validated());
    }

    public function testEnumArgumentsFollowTheirLaravelIntroductionBoundary(): void
    {
        $supportsEnumValues = version_compare(self::frameworkVersion(), '10.21.1', '>=');

        if (!$supportsEnumValues) {
            $this->expectException(\TypeError::class);
            self::factory()->make(
                ['value' => 'Draft'],
                ['value' => ['required', Rule::in([PureValidationStatus::Draft])]]
            )->passes();

            return;
        }

        $this->assertAcceptedAndPreserved(Rule::in([PureValidationStatus::Draft]), 'Draft');
        $this->assertRejected(Rule::in([PureValidationStatus::Draft]), 'Published');
        $this->assertAcceptedAndPreserved(Rule::in([StringValidationStatus::One]), 1.0);
    }

    private function assertAcceptedAndPreserved(\Stringable $rule, mixed $value): void
    {
        $validator = self::factory()->make(['value' => $value], ['value' => ['required', $rule]]);

        self::assertTrue($validator->passes());
        $validated = $validator->validated();
        self::assertArrayHasKey('value', $validated);
        self::assertSame($value, $validated['value']);
    }

    private function assertRejected(\Stringable $rule, mixed $value): void
    {
        self::assertFalse(self::factory()->make(
            ['value' => $value],
            ['value' => ['required', $rule]]
        )->passes());
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    private static function frameworkVersion(): string
    {
        return ltrim((string) InstalledVersions::getPrettyVersion('laravel/framework'), 'v');
    }
}
