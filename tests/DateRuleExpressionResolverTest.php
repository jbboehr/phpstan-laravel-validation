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

use jbboehr\PhpstanLaravelValidation\Validation\DateRuleExpressionResolver;
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Validation\Rule;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\VariadicPlaceholder;
use PHPStan\Analyser\Scope;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\MixedType;

final class DateRuleExpressionResolverTest extends PHPStanTestCase
{
    /**
     * @dataProvider availabilityProvider
     */
    public function testFactoryAndConstructorFollowTheIntroductionBoundaries(
        Expr $expression,
        string $version,
        ?string $expectedRule
    ): void {
        $rule = $this->resolve($expression, $version);

        if ($expectedRule === null) {
            self::assertNull($rule);
            return;
        }

        self::assertNotNull($rule);
        self::assertSame($expectedRule, $rule->getRuleName());
    }

    /** @return iterable<string, array{Expr, string, string|null}> */
    public static function availabilityProvider(): iterable
    {
        yield 'factory before introduction' => [self::factory(), '11.39.0', null];
        yield 'factory at introduction' => [self::factory(), '11.40.0', 'Date'];
        yield 'constructor before introduction' => [self::constructor(), '11.39.0', null];
        yield 'constructor at introduction' => [self::constructor(), '11.40.0', 'Date'];
        yield 'datetime before introduction' => [self::factory('dateTime'), '12.43.0', null];
        yield 'datetime at introduction' => [self::factory('dateTime'), '12.44.0', 'DateFormat'];
    }

    /**
     * @dataProvider originalPredicateMethodProvider
     */
    public function testOriginalPredicateMethodsRetainTheDateContract(string $method): void
    {
        $rule = $this->resolve(
            new Expr\MethodCall(self::factory(), new Identifier($method)),
            '11.43.2'
        );

        self::assertNotNull($rule);
        self::assertSame(
            $method === 'format' ? 'DateFormat' : 'Date',
            $rule->getRuleName()
        );
    }

    /** @return iterable<string, array{string}> */
    public static function originalPredicateMethodProvider(): iterable
    {
        foreach ([
            'after',
            'afterOrEqual',
            'afterToday',
            'before',
            'beforeOrEqual',
            'beforeToday',
            'between',
            'betweenOrEqual',
            'format',
            'todayOrAfter',
            'todayOrBefore',
        ] as $method) {
            yield $method => [$method];
        }
    }

    /**
     * @dataProvider nowPredicateMethodProvider
     */
    public function testNowPredicateMethodsFollowTheirIntroductionBoundary(string $method): void
    {
        $expression = new Expr\MethodCall(self::factory(), new Identifier($method));

        self::assertNull($this->resolve($expression, '12.43.0'));

        $rule = $this->resolve($expression, '12.44.0');
        self::assertNotNull($rule);
        self::assertSame('Date', $rule->getRuleName());
    }

    /** @return iterable<string, array{string}> */
    public static function nowPredicateMethodProvider(): iterable
    {
        foreach (['future', 'nowOrFuture', 'nowOrPast', 'past'] as $method) {
            yield $method => [$method];
        }
    }

    public function testFormatContractSurvivesLaterPredicates(): void
    {
        $format = new Expr\MethodCall(self::factory(), new Identifier('format'));
        $expression = new Expr\MethodCall($format, new Identifier('after'));

        $rule = $this->resolve($expression, '11.43.2');

        self::assertNotNull($rule);
        self::assertSame('DateFormat', $rule->getRuleName());
    }

    public function testFormatCanReplaceAnEarlierDatePredicate(): void
    {
        $before = new Expr\MethodCall(self::factory(), new Identifier('before'));
        $expression = new Expr\MethodCall($before, new Identifier('format'));

        $rule = $this->resolve($expression, '11.43.2');

        self::assertNotNull($rule);
        self::assertSame('DateFormat', $rule->getRuleName());
    }

    public function testFluentChainsFollowTheRuleParserPlacementBoundaries(): void
    {
        $expression = new Expr\MethodCall(self::factory(), new Identifier('beforeToday'));

        self::assertNull($this->resolve($expression, '11.40.0'));
        self::assertNull($this->resolve($expression, '11.40.0', true));

        self::assertNull($this->resolve($expression, '11.41.0'));
        self::assertSame('Date', $this->resolve($expression, '11.41.0', true)?->getRuleName());

        self::assertNull($this->resolve($expression, '11.43.1'));
        self::assertSame('Date', $this->resolve($expression, '11.43.1', true)?->getRuleName());

        self::assertSame('Date', $this->resolve($expression, '11.43.2')?->getRuleName());
        self::assertSame('Date', $this->resolve($expression, '11.43.2', true)?->getRuleName());
    }

    public function testDateTimeAndFormatPreserveConstantFormatParameters(): void
    {
        $dateTime = $this->resolve(self::factory('dateTime'), '12.44.0');
        self::assertNotNull($dateTime);
        self::assertSame(['Y-m-d H:i:s'], $dateTime->getParameters());

        $format = new Expr\MethodCall(
            self::factory(),
            new Identifier('format'),
            [new Arg(new String_('Ymd'))]
        );
        $formatted = $this->resolve($format, '11.43.2');
        self::assertNotNull($formatted);
        self::assertSame(['Ymd'], $formatted->getParameters());

        $dynamicFormat = new Expr\MethodCall(
            self::factory(),
            new Identifier('format'),
            [new Arg(new Expr\Variable('format'))]
        );
        $dynamic = $this->resolve($dynamicFormat, '11.43.2');
        self::assertNotNull($dynamic);
        self::assertSame([], $dynamic->getParameters());
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
        yield 'conditionable unless callback' => [
            new Expr\MethodCall(self::factory(), new Identifier('unless')),
        ];
        yield 'macro' => [
            new Expr\MethodCall(self::factory(), new Identifier('fiscalYear')),
        ];
        yield 'assigned builder' => [new Expr\Variable('builder')];
        yield 'subclass constructor' => [
            new Expr\New_(new FullyQualified('Application\\Rules\\Date')),
        ];
        yield 'parent forwarding call' => [
            new Expr\StaticCall(new Name('parent'), new Identifier('date')),
        ];
        yield 'factory first-class callable' => [
            new Expr\StaticCall(
                new FullyQualified(\Illuminate\Validation\Rule::class),
                new Identifier('date'),
                [new VariadicPlaceholder()]
            ),
        ];
        yield 'method first-class callable' => [
            new Expr\MethodCall(
                self::factory(),
                new Identifier('beforeToday'),
                [new VariadicPlaceholder()]
            ),
        ];
    }

    /**
     * @dataProvider otherBuilderProvider
     */
    public function testSharedMethodNamesDoNotCaptureOtherBuilders(Expr $expression): void
    {
        self::assertNull($this->resolve($expression, '12.55.0'));
    }

    /** @return iterable<string, array{Expr}> */
    public static function otherBuilderProvider(): iterable
    {
        yield 'numeric between' => [
            new Expr\MethodCall(self::factory('numeric'), new Identifier('between')),
        ];
        yield 'string between' => [
            new Expr\MethodCall(self::factory('string'), new Identifier('between')),
        ];
        yield 'file between' => [
            new Expr\MethodCall(self::factory('file'), new Identifier('between')),
        ];
    }

    private function resolve(Expr $expression, string $version, bool $insideRuleList = false): ?Rule
    {
        $scope = $this->createMock(Scope::class);
        $scope->method('resolveName')->willReturnCallback(
            static fn (Name $name): string => $name->toString()
        );
        $scope->method('getType')->willReturnCallback(
            static fn (Expr $node): ConstantStringType|MixedType => $node instanceof String_
                ? new ConstantStringType($node->value)
                : new MixedType()
        );

        return (new DateRuleExpressionResolver(
            new LaravelVersionContext('', $version)
        ))->resolve($expression, $scope, $insideRuleList);
    }

    private static function factory(string $method = 'date'): Expr\StaticCall
    {
        return new Expr\StaticCall(
            new FullyQualified(\Illuminate\Validation\Rule::class),
            new Identifier($method)
        );
    }

    private static function constructor(): Expr\New_
    {
        return new Expr\New_(
            new FullyQualified('Illuminate\\Validation\\Rules\\Date')
        );
    }
}
