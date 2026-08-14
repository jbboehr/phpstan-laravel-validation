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
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class NumericRuleBuilderLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    private const INTRODUCED = '11.42.0';
    private const STRICT_INTEGER_INTRODUCED = '12.55.0';

    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    public function testNumericBuilderAvailabilityFollowsTheRuntimeBoundary(): void
    {
        $expected = version_compare(self::frameworkVersion(), self::INTRODUCED, '>=');

        self::assertSame($expected, method_exists(Rule::class, 'numeric'));
        self::assertSame($expected, class_exists('Illuminate\\Validation\\Rules\\Numeric'));
    }

    public function testNumericBuilderPreservesSuccessfulNativeRepresentations(): void
    {
        $rule = $this->newNumericRule();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        foreach ([1, 1.5, '1e2'] as $value) {
            $validator = $this->factory->make(
                ['value' => $value],
                ['value' => ['required', $rule]]
            );

            self::assertTrue($validator->passes(), get_debug_type($value));
            self::assertSame(['value' => $value], $validator->validated(), get_debug_type($value));
        }
    }

    public function testNumericBuilderRetainsLaravelBlankBypass(): void
    {
        $rule = $this->newNumericRule();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $validator = $this->factory->make(['value' => ''], ['value' => [$rule]]);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => ''], $validator->validated());
    }

    public function testNonStrictIntegerBuilderPreservesCoerciveNumericValues(): void
    {
        $rule = $this->newNumericRule();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $rule = $this->invokeBuilderMethod($rule, 'integer');
        self::assertSame('numeric|integer', self::ruleString($rule));

        foreach ([10, 10.0, '10'] as $value) {
            $validator = $this->factory->make(
                ['value' => $value],
                ['value' => ['required', $rule]]
            );

            self::assertTrue($validator->passes(), get_debug_type($value));
            self::assertSame(['value' => $value], $validator->validated(), get_debug_type($value));
        }
    }

    public function testStrictIntegerBuilderFollowsItsRuntimeBoundary(): void
    {
        $rule = $this->newNumericRule();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $strict = version_compare(self::frameworkVersion(), self::STRICT_INTEGER_INTRODUCED, '>=');
        $rule = $this->invokeBuilderMethod($rule, 'integer', true);
        self::assertSame(
            $strict ? 'numeric|integer:strict' : 'numeric|integer',
            self::ruleString($rule)
        );

        foreach ([[10, true], ['10', !$strict], [10.0, !$strict]] as [$value, $expected]) {
            $validator = $this->factory->make(
                ['value' => $value],
                ['value' => ['required', $rule]]
            );

            self::assertSame($expected, $validator->passes(), get_debug_type($value));
            if ($expected) {
                self::assertSame(['value' => $value], $validator->validated(), get_debug_type($value));
            }
        }
    }

    private function newNumericRule(): ?object
    {
        if (!method_exists(Rule::class, 'numeric')) {
            return null;
        }

        $rule = (new \ReflectionMethod(Rule::class, 'numeric'))->invoke(null);
        return is_object($rule)
            ? $rule
            : throw new \LogicException('Rule::numeric() did not return an object.');
    }

    private function invokeBuilderMethod(object $rule, string $method, mixed ...$arguments): object
    {
        $result = (new \ReflectionMethod($rule, $method))->invoke($rule, ...$arguments);
        return is_object($result)
            ? $result
            : throw new \LogicException("Numeric::$method() did not return an object.");
    }

    private static function ruleString(object $rule): string
    {
        if (!$rule instanceof \Stringable) {
            throw new \LogicException('Numeric rule builder is not Stringable.');
        }

        return (string) $rule;
    }

    private static function frameworkVersion(): string
    {
        $version = InstalledVersions::getPrettyVersion('laravel/framework');

        return $version === null
            ? \Illuminate\Foundation\Application::VERSION
            : ltrim($version, 'v');
    }
}
