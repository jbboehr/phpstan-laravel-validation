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
use jbboehr\PhpstanLaravelValidation\Validation\DateRuleExpressionResolver;
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class DateRuleBuilderLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    private const INTRODUCED = '11.40.0';
    private const NOW_METHODS_INTRODUCED = '12.44.0';
    private const FORMAT_SERIALIZATION_CHANGED = '12.3.0';

    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    public function testDateBuilderAvailabilityFollowsTheRuntimeBoundaries(): void
    {
        $dateExpected = version_compare(self::frameworkVersion(), self::INTRODUCED, '>=');
        $nowExpected = version_compare(
            self::frameworkVersion(),
            self::NOW_METHODS_INTRODUCED,
            '>='
        );

        self::assertSame($dateExpected, method_exists(Rule::class, 'date'));
        self::assertSame($dateExpected, class_exists('Illuminate\\Validation\\Rules\\Date'));
        self::assertSame($nowExpected, method_exists(Rule::class, 'dateTime'));
    }

    public function testFactoryAndConstructorProduceTheNativeDateRule(): void
    {
        $factoryRule = $this->newDateRuleFromFactory();
        $directRule = $this->newDateRuleDirectly();
        if ($factoryRule === null || $directRule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        self::assertSame('date', self::ruleString($factoryRule));
        self::assertSame('date', self::ruleString($directRule));
    }

    public function testDateBuilderAcceptsAndPreservesItsNativeFamily(): void
    {
        $rule = $this->newDateRuleFromFactory();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $date = new \DateTimeImmutable('2024-01-01');
        foreach ([$date, 20240101, 20240101.0, '2024-01-01'] as $value) {
            $validator = $this->factory->make(
                ['value' => $value],
                ['value' => ['required', $rule]]
            );

            self::assertTrue($validator->passes(), get_debug_type($value));
            self::assertSame(['value' => $value], $validator->validated(), get_debug_type($value));
        }

        $stringable = new class () implements \Stringable {
            public function __toString(): string
            {
                return '2024-01-01';
            }
        };
        foreach ([$stringable, true, ['2024-01-01']] as $value) {
            $validator = $this->factory->make(
                ['value' => $value],
                ['value' => ['required', $rule]]
            );

            self::assertFalse($validator->passes(), get_debug_type($value));
        }
    }

    public function testFormatChangesSerializationButAlwaysUsesTheDateFormatFamily(): void
    {
        $rule = $this->newDateRuleFromFactory();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $rule = $this->invokeBuilderMethod($rule, 'format', 'Ymd');
        self::assertSame(
            version_compare(self::frameworkVersion(), self::FORMAT_SERIALIZATION_CHANGED, '>=')
                ? 'date_format:Ymd'
                : 'date|date_format:Ymd',
            self::ruleString($rule)
        );

        foreach ([20240101, 20240101.0, '20240101'] as $value) {
            $validator = $this->factory->make(
                ['value' => $value],
                ['value' => ['required', $rule]]
            );

            self::assertTrue($validator->passes(), get_debug_type($value));
            self::assertSame(['value' => $value], $validator->validated(), get_debug_type($value));
        }

        $validator = $this->factory->make(
            ['value' => new \DateTimeImmutable('2024-01-01')],
            ['value' => ['required', $rule]]
        );
        self::assertFalse($validator->passes());
    }

    public function testComparisonChainPreservesAFormattedNumericValue(): void
    {
        $rule = $this->newDateRuleFromFactory();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $rule = $this->invokeBuilderMethod($rule, 'format', 'Ymd');
        $rule = $this->invokeBuilderMethod($rule, 'after', '20230101');
        $rule = $this->invokeBuilderMethod($rule, 'before', '20250101');
        $validator = $this->factory->make(
            ['value' => 20240101],
            ['value' => ['required', $rule]]
        );

        self::assertTrue($validator->passes());
        self::assertSame(['value' => 20240101], $validator->validated());
    }

    public function testDateBuilderRetainsLaravelBlankBypass(): void
    {
        $rule = $this->newDateRuleFromFactory();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $validator = $this->factory->make(['value' => ''], ['value' => [$rule]]);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => ''], $validator->validated());
    }

    public function testNowRelativeMethodsAndDateTimeFactoryFollowTheirBoundary(): void
    {
        $dateRule = $this->newDateRuleFromFactory();
        if ($dateRule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $available = version_compare(
            self::frameworkVersion(),
            self::NOW_METHODS_INTRODUCED,
            '>='
        );
        foreach ([
            'past' => 'date|before:now',
            'future' => 'date|after:now',
            'nowOrPast' => 'date|before_or_equal:now',
            'nowOrFuture' => 'date|after_or_equal:now',
        ] as $method => $expected) {
            self::assertSame($available, method_exists($dateRule, $method));
            if ($available) {
                $freshRule = $this->newDateRuleFromFactory();
                if ($freshRule === null) {
                    throw new \LogicException('Date rule builder became unavailable during the test.');
                }
                self::assertSame(
                    $expected,
                    self::ruleString($this->invokeBuilderMethod($freshRule, $method))
                );
            }
        }

        $dateTimeRule = $this->newDateTimeRuleFromFactory();
        self::assertSame($available, $dateTimeRule !== null);
        if ($dateTimeRule !== null) {
            self::assertSame('date_format:Y-m-d H:i:s', self::ruleString($dateTimeRule));

            $value = '2024-01-01 12:00:00';
            $validator = $this->factory->make(
                ['value' => $value],
                ['value' => ['required', $dateTimeRule]]
            );
            self::assertTrue($validator->passes());
            self::assertSame(['value' => $value], $validator->validated());
        }
    }

    public function testResolverPredicateInventoryMatchesTheRuntimeBuilder(): void
    {
        $rule = $this->newDateRuleFromFactory();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $reflection = new \ReflectionClass($rule);
        $runtimeMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if (
                $method->getFileName() === $reflection->getFileName()
                && $method->getName() !== '__toString'
            ) {
                $runtimeMethods[] = strtolower($method->getName());
            }
        }

        $supportedMethods = self::resolverPredicateMethods();
        sort($runtimeMethods);
        sort($supportedMethods);

        self::assertSame($runtimeMethods, $supportedMethods);
    }

    private function newDateRuleFromFactory(): ?object
    {
        if (!method_exists(Rule::class, 'date')) {
            return null;
        }

        $rule = (new \ReflectionMethod(Rule::class, 'date'))->invoke(null);
        return is_object($rule)
            ? $rule
            : throw new \LogicException('Rule::date() did not return an object.');
    }

    private function newDateRuleDirectly(): ?object
    {
        $factoryRule = $this->newDateRuleFromFactory();
        if ($factoryRule === null) {
            return null;
        }

        return (new \ReflectionClass($factoryRule))->newInstance();
    }

    private function newDateTimeRuleFromFactory(): ?object
    {
        if (!method_exists(Rule::class, 'dateTime')) {
            return null;
        }

        $rule = (new \ReflectionMethod(Rule::class, 'dateTime'))->invoke(null);
        return is_object($rule)
            ? $rule
            : throw new \LogicException('Rule::dateTime() did not return an object.');
    }

    private function invokeBuilderMethod(object $rule, string $method, mixed ...$arguments): object
    {
        $result = (new \ReflectionMethod($rule, $method))->invoke($rule, ...$arguments);
        return is_object($result)
            ? $result
            : throw new \LogicException("Date::$method() did not return an object.");
    }

    private static function ruleString(object $rule): string
    {
        if (!$rule instanceof \Stringable) {
            throw new \LogicException('Date rule builder is not Stringable.');
        }

        return (string) $rule;
    }

    /** @return list<string> */
    private static function resolverPredicateMethods(): array
    {
        $methods = self::stringListConstant('PREDICATE_METHODS');
        if (version_compare(self::frameworkVersion(), self::NOW_METHODS_INTRODUCED, '>=')) {
            $methods = array_merge(
                $methods,
                self::stringListConstant('NOW_PREDICATE_METHODS')
            );
        }

        return $methods;
    }

    /** @return list<string> */
    private static function stringListConstant(string $name): array
    {
        $class = new \ReflectionClass(DateRuleExpressionResolver::class);
        $constant = $class->getReflectionConstant($name);
        if (!$constant instanceof \ReflectionClassConstant) {
            throw new \LogicException("Date rule predicate inventory $name is unavailable.");
        }

        $value = $constant->getValue();
        if (!is_array($value)) {
            throw new \LogicException("Date rule predicate inventory $name is not an array.");
        }

        $methods = [];
        foreach ($value as $method) {
            if (!is_string($method)) {
                throw new \LogicException("Date rule predicate inventory $name contains a non-string.");
            }
            $methods[] = $method;
        }

        return $methods;
    }

    private static function frameworkVersion(): string
    {
        $version = InstalledVersions::getPrettyVersion('laravel/framework');

        return $version === null
            ? \Illuminate\Foundation\Application::VERSION
            : ltrim($version, 'v');
    }
}
