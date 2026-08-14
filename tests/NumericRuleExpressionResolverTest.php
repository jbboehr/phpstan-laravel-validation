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
use jbboehr\PhpstanLaravelValidation\Validation\NumericRuleExpressionResolver;
use jbboehr\PhpstanLaravelValidation\Validation\Rule;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\VariadicPlaceholder;
use PHPStan\Analyser\Scope;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;

final class NumericRuleExpressionResolverTest extends PHPStanTestCase
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
        self::assertSame(Rule::RULE_NUMERIC, $rule->getRuleName());
    }

    /** @return iterable<string, array{Expr, string, bool}> */
    public static function availabilityProvider(): iterable
    {
        yield 'factory before introduction' => [self::factory(), '11.41.0', false];
        yield 'factory at introduction' => [self::factory(), '11.42.0', true];
        yield 'constructor before introduction' => [self::constructor(), '11.41.0', false];
        yield 'constructor at introduction' => [self::constructor(), '11.42.0', true];
    }

    /**
     * @dataProvider predicateMethodProvider
     */
    public function testPredicateMethodsRetainTheNumericAcceptedFamily(string $method): void
    {
        $rule = $this->resolve(
            new Expr\MethodCall(self::factory(), new Identifier($method)),
            '12.55.0'
        );

        self::assertNotNull($rule);
        self::assertSame(Rule::RULE_NUMERIC, $rule->getRuleName());
    }

    /** @return iterable<string, array{string}> */
    public static function predicateMethodProvider(): iterable
    {
        foreach ([
            'between',
            'decimal',
            'different',
            'digits',
            'digitsBetween',
            'exactly',
            'greaterThan',
            'greaterThanOrEqualTo',
            'integer',
            'lessThan',
            'lessThanOrEqualTo',
            'max',
            'maxDigits',
            'min',
            'minDigits',
            'multipleOf',
            'same',
        ] as $method) {
            yield $method => [$method];
        }
    }

    /**
     * @dataProvider strictIntegerProvider
     * @param list<string> $expectedParameters
     */
    public function testStrictIntegerFollowsItsBuilderBoundary(
        string $version,
        Expr $strict,
        bool $named,
        string $expectedRule,
        array $expectedParameters
    ): void {
        $argument = new Arg(
            $strict,
            false,
            false,
            [],
            $named ? new Identifier('strict') : null
        );
        $expression = new Expr\MethodCall(
            self::factory(),
            new Identifier('integer'),
            [$argument]
        );

        $rule = $this->resolve($expression, $version);

        self::assertNotNull($rule);
        self::assertSame($expectedRule, $rule->getRuleName());
        self::assertSame($expectedParameters, $rule->getParameters());
    }

    /** @return iterable<string, array{string, Expr, bool, string, list<string>}> */
    public static function strictIntegerProvider(): iterable
    {
        yield 'true before support' => [
            '12.54.0',
            new Expr\ConstFetch(new Name('true')),
            false,
            Rule::RULE_NUMERIC,
            [],
        ];
        yield 'positional true at support' => [
            '12.55.0',
            new Expr\ConstFetch(new Name('true')),
            false,
            'Integer',
            ['strict'],
        ];
        yield 'named true at support' => [
            '12.55.0',
            new Expr\ConstFetch(new Name('true')),
            true,
            'Integer',
            ['strict'],
        ];
        yield 'false at support' => [
            '12.55.0',
            new Expr\ConstFetch(new Name('false')),
            false,
            Rule::RULE_NUMERIC,
            [],
        ];
        yield 'dynamic bool at support' => [
            '12.55.0',
            new Expr\Variable('strict'),
            false,
            Rule::RULE_NUMERIC,
            [],
        ];
    }

    public function testStrictIntegerSurvivesLaterPredicateCalls(): void
    {
        $strict = new Expr\MethodCall(
            self::factory(),
            new Identifier('integer'),
            [new Arg(new Expr\ConstFetch(new Name('true')))]
        );
        $expression = new Expr\MethodCall($strict, new Identifier('max'));

        $rule = $this->resolve($expression, '12.55.0');

        self::assertNotNull($rule);
        self::assertSame('Integer', $rule->getRuleName());
        self::assertSame(['strict'], $rule->getParameters());
    }

    public function testUnpackedStrictArgumentRemainsConservative(): void
    {
        $expression = new Expr\MethodCall(
            self::factory(),
            new Identifier('integer'),
            [new Arg(new Expr\Variable('strictArguments'), false, true)]
        );

        $rule = $this->resolve($expression, '12.55.0');

        self::assertNotNull($rule);
        self::assertSame(Rule::RULE_NUMERIC, $rule->getRuleName());
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
            new Expr\New_(new FullyQualified('Application\\Rules\\Numeric')),
        ];
        yield 'parent forwarding call' => [
            new Expr\StaticCall(new Name('parent'), new Identifier('numeric')),
        ];
        yield 'factory first-class callable' => [
            new Expr\StaticCall(
                new FullyQualified(\Illuminate\Validation\Rule::class),
                new Identifier('numeric'),
                [new VariadicPlaceholder()]
            ),
        ];
        yield 'method first-class callable' => [
            new Expr\MethodCall(
                self::factory(),
                new Identifier('integer'),
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
        $scope->method('getType')->willReturnCallback(
            static fn (Expr $node): Type => match (true) {
                $node instanceof Expr\ConstFetch && $node->name->toLowerString() === 'true'
                    => new ConstantBooleanType(true),
                $node instanceof Expr\ConstFetch && $node->name->toLowerString() === 'false'
                    => new ConstantBooleanType(false),
                $node instanceof Expr\Variable && $node->name === 'strict'
                    => new BooleanType(),
                default => new MixedType(),
            }
        );

        return (new NumericRuleExpressionResolver(
            new LaravelVersionContext('', $version)
        ))->resolve($expression, $scope);
    }

    private static function factory(): Expr\StaticCall
    {
        return new Expr\StaticCall(
            new FullyQualified(\Illuminate\Validation\Rule::class),
            new Identifier('numeric')
        );
    }

    private static function constructor(): Expr\New_
    {
        return new Expr\New_(
            new FullyQualified('Illuminate\\Validation\\Rules\\Numeric')
        );
    }
}
