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

use jbboehr\PhpstanLaravelValidation\Rule\FormRequestResultCacheDependencyRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\BasicRequest;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestTypeRegistry;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\CollectedDataEmitter;
use PHPStan\Analyser\NodeCallbackInvoker;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\ResultCacheDependencyCollector;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPUnit\Framework\Attributes\DataProvider;

final class FormRequestResultCacheDependencyRuleTest extends \PHPStan\Testing\PHPStanTestCase
{
    #[DataProvider('supportedMethodProvider')]
    public function testEmitsTheConcreteRequestDependency(string $method): void
    {
        $registry = self::getContainer()->getByType(FormRequestTypeRegistry::class);
        $reflectionProvider = self::getContainer()->getByType(ReflectionProvider::class);
        $receiver = new Variable('request');
        $call = new MethodCall($receiver, new Identifier($method));
        $scope = $this->createMockForIntersectionOfInterfaces([
            Scope::class,
            CollectedDataEmitter::class,
            NodeCallbackInvoker::class,
        ]);
        $scope->expects(self::once())
            ->method('getType')
            ->with($receiver)
            ->willReturn(new ObjectType(
                BasicRequest::class,
                null,
                $reflectionProvider->getClass(BasicRequest::class)
            ));
        $scope->expects(self::once())
            ->method('emitCollectedData')
            ->with(
                ResultCacheDependencyCollector::class,
                [
                    'extensionKey' => 'phpstan-laravel-validation.form-requests',
                    'dependencyKey' => BasicRequest::class,
                ]
            );

        $rule = new FormRequestResultCacheDependencyRule($registry, true);

        self::assertSame([], $rule->processNode($call, $scope));
    }

    public function testEmitsTheDependencyForAConstantDynamicMethodName(): void
    {
        $registry = self::getContainer()->getByType(FormRequestTypeRegistry::class);
        $reflectionProvider = self::getContainer()->getByType(ReflectionProvider::class);
        $receiver = new Variable('request');
        $method = new Variable('method');
        $call = new MethodCall($receiver, $method);
        $scope = $this->createMockForIntersectionOfInterfaces([
            Scope::class,
            CollectedDataEmitter::class,
            NodeCallbackInvoker::class,
        ]);
        $scope->expects(self::exactly(2))
            ->method('getType')
            ->willReturnCallback(static function (Expr $expression) use ($reflectionProvider): Type {
                if ($expression instanceof Variable && $expression->name === 'method') {
                    return new ConstantStringType('validated');
                }
                if ($expression instanceof Variable && $expression->name === 'request') {
                    return new ObjectType(
                        BasicRequest::class,
                        null,
                        $reflectionProvider->getClass(BasicRequest::class)
                    );
                }

                self::fail('Unexpected expression passed to Scope::getType().');
            });
        $scope->expects(self::once())
            ->method('emitCollectedData')
            ->with(
                ResultCacheDependencyCollector::class,
                [
                    'extensionKey' => 'phpstan-laravel-validation.form-requests',
                    'dependencyKey' => BasicRequest::class,
                ]
            );

        $rule = new FormRequestResultCacheDependencyRule($registry, true);

        self::assertSame([], $rule->processNode($call, $scope));
    }

    public function testEmitsTheDependencyForAFiniteDynamicMethodNameContainingSafe(): void
    {
        $registry = self::getContainer()->getByType(FormRequestTypeRegistry::class);
        $reflectionProvider = self::getContainer()->getByType(ReflectionProvider::class);
        $receiver = new Variable('request');
        $method = new Variable('method');
        $call = new MethodCall($receiver, $method);
        $scope = $this->createMockForIntersectionOfInterfaces([
            Scope::class,
            CollectedDataEmitter::class,
            NodeCallbackInvoker::class,
        ]);
        $scope->expects(self::exactly(2))
            ->method('getType')
            ->willReturnCallback(static function (Expr $expression) use ($reflectionProvider): Type {
                if ($expression instanceof Variable && $expression->name === 'method') {
                    return TypeCombinator::union(
                        new ConstantStringType('unrelated'),
                        new ConstantStringType('SAFE')
                    );
                }
                if ($expression instanceof Variable && $expression->name === 'request') {
                    return new ObjectType(
                        BasicRequest::class,
                        null,
                        $reflectionProvider->getClass(BasicRequest::class)
                    );
                }

                self::fail('Unexpected expression passed to Scope::getType().');
            });
        $scope->expects(self::once())
            ->method('emitCollectedData')
            ->with(
                ResultCacheDependencyCollector::class,
                [
                    'extensionKey' => 'phpstan-laravel-validation.form-requests',
                    'dependencyKey' => BasicRequest::class,
                ]
            );

        $rule = new FormRequestResultCacheDependencyRule($registry, true);

        self::assertSame([], $rule->processNode($call, $scope));
    }

    public function testDoesNotInspectReceiverForAnUnboundedDynamicMethodName(): void
    {
        $registry = self::getContainer()->getByType(FormRequestTypeRegistry::class);
        $receiver = new Variable('request');
        $method = new Variable('method');
        $call = new MethodCall($receiver, $method);
        $scope = $this->createMockForIntersectionOfInterfaces([
            Scope::class,
            CollectedDataEmitter::class,
            NodeCallbackInvoker::class,
        ]);
        $scope->expects(self::once())
            ->method('getType')
            ->with($method)
            ->willReturn(new StringType());
        $scope->expects(self::never())->method('emitCollectedData');

        $rule = new FormRequestResultCacheDependencyRule($registry, true);

        self::assertSame([], $rule->processNode($call, $scope));
    }

    /** @return iterable<string, array{string}> */
    public static function supportedMethodProvider(): iterable
    {
        yield 'validated' => ['validated'];
        yield 'safe' => ['safe'];
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../extension.neon',
            __DIR__ . '/form-request/phpstan.neon',
        ];
    }
}
