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

use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Validation\Rule;
use jbboehr\PhpstanLaravelValidation\Validation\StringRuleExpressionResolver;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\VariadicPlaceholder;
use PHPStan\Analyser\Scope;
use PHPStan\Testing\PHPStanTestCase;

final class StringRuleExpressionResolverTest extends PHPStanTestCase
{
    /**
     * @dataProvider availabilityProvider
     */
    public function testFactoryAndConstructorFollowTheIntroductionBoundary(
        Expr $expression,
        string $version,
        bool $recognized
    ): void {
        $rule = $this->resolve($expression, $version);

        if (!$recognized) {
            self::assertNull($rule);
            return;
        }

        self::assertNotNull($rule);
        self::assertSame('String', $rule->getRuleName());
    }

    /** @return iterable<string, array{Expr, string, bool}> */
    public static function availabilityProvider(): iterable
    {
        yield 'factory before introduction' => [self::factory(), '12.54.0', false];
        yield 'factory at introduction' => [self::factory(), '12.55.0', true];
        yield 'constructor before introduction' => [self::constructor(), '12.54.0', false];
        yield 'constructor at introduction' => [self::constructor(), '12.55.0', true];
    }

    /**
     * @dataProvider predicateMethodProvider
     */
    public function testPredicateMethodsRetainTheNativeStringContract(string $method): void
    {
        $rule = $this->resolve(
            new Expr\MethodCall(self::factory(), new Identifier($method)),
            '12.55.0'
        );

        self::assertNotNull($rule);
        self::assertSame('String', $rule->getRuleName());
    }

    /** @return iterable<string, array{string}> */
    public static function predicateMethodProvider(): iterable
    {
        foreach ([
            'alpha',
            'alphaDash',
            'alphaNumeric',
            'ascii',
            'between',
            'doesntEndWith',
            'doesntStartWith',
            'endsWith',
            'exactly',
            'lowercase',
            'max',
            'min',
            'startsWith',
            'uppercase',
        ] as $method) {
            yield $method => [$method];
        }
    }

    public function testDeclaredPredicateChainsRemainNativeStrings(): void
    {
        $expression = new Expr\MethodCall(
            new Expr\MethodCall(self::factory(), new Identifier('min')),
            new Identifier('uppercase')
        );

        $rule = $this->resolve($expression, '12.55.0');

        self::assertNotNull($rule);
        self::assertSame('String', $rule->getRuleName());
    }

    /**
     * @dataProvider opaqueExpressionProvider
     */
    public function testRuntimeDependentOrLateBoundExpressionsRemainOpaque(Expr $expression): void
    {
        self::assertNull($this->resolve($expression, '12.55.0'));
    }

    /** @return iterable<string, array{Expr}> */
    public static function opaqueExpressionProvider(): iterable
    {
        yield 'conditionable callback' => [
            new Expr\MethodCall(self::factory(), new Identifier('when')),
        ];
        yield 'assigned builder' => [new Expr\Variable('builder')];
        yield 'subclass constructor' => [
            new Expr\New_(new FullyQualified('Application\\Rules\\StringRule')),
        ];
        yield 'parent forwarding call' => [
            new Expr\StaticCall(new Name('parent'), new Identifier('string')),
        ];
        yield 'unknown method' => [
            new Expr\MethodCall(self::factory(), new Identifier('custom')),
        ];
        yield 'factory first-class callable' => [
            new Expr\StaticCall(
                new FullyQualified(\Illuminate\Validation\Rule::class),
                new Identifier('string'),
                [new VariadicPlaceholder()]
            ),
        ];
        yield 'method first-class callable' => [
            new Expr\MethodCall(
                self::factory(),
                new Identifier('uppercase'),
                [new VariadicPlaceholder()]
            ),
        ];
    }

    private function resolve(Expr $expression, string $version): ?Rule
    {
        $scope = $this->createMock(Scope::class);
        $scope->method('resolveName')->willReturnCallback(
            static fn (Name $name): string => $name->toString()
        );

        return (new StringRuleExpressionResolver(
            new LaravelVersionContext('', $version)
        ))->resolve($expression, $scope);
    }

    private static function factory(): Expr\StaticCall
    {
        return new Expr\StaticCall(
            new FullyQualified(\Illuminate\Validation\Rule::class),
            new Identifier('string')
        );
    }

    private static function constructor(): Expr\New_
    {
        return new Expr\New_(
            new FullyQualified('Illuminate\\Validation\\Rules\\StringRule')
        );
    }
}
