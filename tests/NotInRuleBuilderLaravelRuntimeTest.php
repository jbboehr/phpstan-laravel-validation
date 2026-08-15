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
use Illuminate\Validation\Rules\NotIn;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\ValidationStringable;
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class NotInRuleBuilderLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    public function testDirectArrayConstructorMatchesFactoryAcrossSupportedVersions(): void
    {
        $factoryRule = Rule::notIn(['admin', 'owner']);
        $directRule = new NotIn(['admin', 'owner']);

        self::assertSame((string) $factoryRule, (string) $directRule);
        $this->assertAcceptedAndPreserved($directRule, 'member');
        $this->assertRejected($directRule, 'owner');
    }

    public function testDirectScalarConstructorFollowsItsLaravelBoundary(): void
    {
        if (version_compare(self::frameworkVersion(), '10.36.0', '<')) {
            $this->expectException(\TypeError::class);
            new NotIn('admin');

            return;
        }

        $factoryRule = Rule::notIn('admin', 'owner');
        $directRule = new NotIn('admin', 'owner');

        self::assertSame((string) $factoryRule, (string) $directRule);
        $this->assertAcceptedAndPreserved($directRule, 'member');
        $this->assertRejected($directRule, 'owner');
    }

    public function testStringBuilderRejectsForbiddenValuesAndPreservesAllowedValues(): void
    {
        $rule = Rule::notIn(['admin']);

        $this->assertAcceptedAndPreserved($rule, 'member');
        $this->assertAcceptedAndPreserved($rule, new ValidationStringable('member'));
        $this->assertRejected($rule, 'admin');
        $this->assertRejected($rule, new ValidationStringable('admin'));
    }

    public function testNumericBuilderUsesLaravelLooseStringComparison(): void
    {
        $rule = Rule::notIn([1]);

        foreach ([1, 1.0, true, '01', '1.0', '1e0'] as $value) {
            $this->assertRejected($rule, $value);
        }

        $this->assertAcceptedAndPreserved($rule, 1.5);
        $this->assertAcceptedAndPreserved($rule, false);
    }

    public function testOptionalBlankValueBypassesTheNonImplicitRule(): void
    {
        $validator = self::factory()->make(
            ['value' => ''],
            ['value' => [Rule::notIn([''])]]
        );

        self::assertTrue($validator->passes());
        self::assertSame(['value' => ''], $validator->validated());
    }

    public function testEnumArgumentsFollowTheirLaravelIntroductionBoundary(): void
    {
        $supportsEnumValues = version_compare(self::frameworkVersion(), '10.21.1', '>=');

        if (!$supportsEnumValues) {
            $this->expectException(\TypeError::class);
            self::factory()->make(
                ['value' => 'Published'],
                ['value' => ['required', Rule::notIn([PureValidationStatus::Draft])]]
            )->passes();

            return;
        }

        $rule = Rule::notIn([PureValidationStatus::Draft]);
        $this->assertRejected($rule, 'Draft');
        $this->assertAcceptedAndPreserved($rule, 'Published');

        $directRule = new NotIn([PureValidationStatus::Draft]);
        $this->assertRejected($directRule, 'Draft');
        $this->assertAcceptedAndPreserved($directRule, 'Published');
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
