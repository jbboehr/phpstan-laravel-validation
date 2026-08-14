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
use jbboehr\PhpstanLaravelValidation\Validation\StringRuleExpressionResolver;
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class StringRuleBuilderLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    private const INTRODUCED = '12.55.0';

    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    public function testStringBuilderAvailabilityFollowsTheRuntimeBoundary(): void
    {
        $expected = version_compare(self::frameworkVersion(), self::INTRODUCED, '>=');

        self::assertSame($expected, method_exists(Rule::class, 'string'));
        self::assertSame($expected, class_exists('Illuminate\\Validation\\Rules\\StringRule'));
    }

    public function testFactoryAndConstructorProduceTheNativeStringRule(): void
    {
        $factoryRule = $this->newStringRuleFromFactory();
        $directRule = $this->newStringRuleDirectly();
        if ($factoryRule === null || $directRule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        self::assertSame('string', self::ruleString($factoryRule));
        self::assertSame('string', self::ruleString($directRule));
    }

    public function testResolverPredicateInventoryMatchesTheRuntimeBuilder(): void
    {
        $rule = $this->newStringRuleFromFactory();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $reflection = new \ReflectionClass($rule);
        $runtimeMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if (
                $method->getDeclaringClass()->getName() === $reflection->getName()
                && !in_array($method->getName(), ['__toString', 'when', 'unless'], true)
            ) {
                $runtimeMethods[] = strtolower($method->getName());
            }
        }

        $supportedMethods = self::resolverPredicateMethods();
        sort($runtimeMethods);
        sort($supportedMethods);

        self::assertSame($runtimeMethods, $supportedMethods);
    }

    public function testStringBuilderAcceptsAndPreservesOnlyNativeStrings(): void
    {
        $rule = $this->newStringRuleFromFactory();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $stringable = new class () implements \Stringable {
            public function __toString(): string
            {
                return 'hello';
            }
        };

        foreach ([['hello', true], [1, false], [1.5, false], [$stringable, false]] as [$value, $expected]) {
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

    public function testStringBuilderRetainsLaravelBlankBypass(): void
    {
        $rule = $this->newStringRuleFromFactory();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $validator = $this->factory->make(['value' => ''], ['value' => [$rule]]);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => ''], $validator->validated());
    }

    public function testFluentPredicatesRetainTheNativeStringContract(): void
    {
        $rule = $this->newStringRuleFromFactory();
        if ($rule === null) {
            self::assertLessThan(0, version_compare(self::frameworkVersion(), self::INTRODUCED));
            return;
        }

        $rule = $this->invokeBuilderMethod($rule, 'min', 3);
        $rule = $this->invokeBuilderMethod($rule, 'max', 10);
        $rule = $this->invokeBuilderMethod($rule, 'uppercase');
        self::assertSame('string|min:3|max:10|uppercase', self::ruleString($rule));

        foreach ([['HELLO', true], ['HI', false], ['hello', false], [12345, false]] as [$value, $expected]) {
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

    private function newStringRuleFromFactory(): ?object
    {
        if (!method_exists(Rule::class, 'string')) {
            return null;
        }

        $rule = (new \ReflectionMethod(Rule::class, 'string'))->invoke(null);
        return is_object($rule)
            ? $rule
            : throw new \LogicException('Rule::string() did not return an object.');
    }

    private function newStringRuleDirectly(): ?object
    {
        $factoryRule = $this->newStringRuleFromFactory();
        if ($factoryRule === null) {
            return null;
        }

        return (new \ReflectionClass($factoryRule))->newInstance();
    }

    private function invokeBuilderMethod(object $rule, string $method, mixed ...$arguments): object
    {
        $result = (new \ReflectionMethod($rule, $method))->invoke($rule, ...$arguments);
        return is_object($result)
            ? $result
            : throw new \LogicException("StringRule::$method() did not return an object.");
    }

    private static function ruleString(object $rule): string
    {
        if (!$rule instanceof \Stringable) {
            throw new \LogicException('String rule builder is not Stringable.');
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

    /** @return list<string> */
    private static function resolverPredicateMethods(): array
    {
        $reflection = new \ReflectionClass(StringRuleExpressionResolver::class);
        $constant = $reflection->getReflectionConstant('PREDICATE_METHODS');
        if (!$constant instanceof \ReflectionClassConstant) {
            throw new \LogicException('StringRule predicate inventory is unavailable.');
        }

        $value = $constant->getValue();
        if (!is_array($value)) {
            throw new \LogicException('StringRule predicate inventory is not an array.');
        }

        $methods = [];
        foreach ($value as $method) {
            if (!is_string($method)) {
                throw new \LogicException('StringRule predicate inventory contains a non-string.');
            }
            $methods[] = $method;
        }

        return $methods;
    }
}
