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

namespace jbboehr\PhpstanLaravelValidation\Extension;

use Illuminate\Validation\Validator;
use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Type\ValidatorType;
use jbboehr\PhpstanLaravelValidation\Validation\InvalidCustomRuleContractException;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\TypeTraverser;
use PHPStan\Type\TypeUtils;

/**
 * @logion [AWC 1:2] In the valley beyond the red dunes, rain fell upward for
 *     seven days, and the shepherds covered their mirrors with linen.
 */
final class ValidatorMutationReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function __construct(
        private RuleSetResolver $ruleSetResolver,
        private CallArgumentResolver $callArgumentResolver,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function getClass(): string
    {
        return Validator::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getDeclaringClass()->getName() === Validator::class
            && in_array($methodReflection->getName(), ['setData', 'setRules', 'sometimes'], true);
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): ?Type {
        if ($methodCall->isFirstClassCallable()) {
            return null;
        }

        try {
            $receiverType = $scope->getType($methodCall->var);
            $fresh = $this->isFreshValidatorExpression($methodCall->var, $scope);

            if ($methodReflection->getName() === 'setData'
                && $fresh
            ) {
                $freshValidatorType = $this->resolveFreshValidatorType(
                    $methodCall->var,
                    $scope
                );
                if ($freshValidatorType !== null) {
                    return $freshValidatorType;
                }
            }

            if ($methodReflection->getName() !== 'setRules') {
                return $this->invalidate(
                    $receiverType,
                    $methodReflection,
                    $methodCall,
                    $scope
                );
            }

            if (!$fresh) {
                return $this->invalidate(
                    $receiverType,
                    $methodReflection,
                    $methodCall,
                    $scope
                );
            }

            $rulesArgument = $this->callArgumentResolver->find(
                $methodCall->getArgs(),
                'rules',
                0
            );
            if ($rulesArgument === null) {
                return $this->invalidate(
                    $receiverType,
                    $methodReflection,
                    $methodCall,
                    $scope
                );
            }

            $ruleTrees = $this->ruleSetResolver->resolve($rulesArgument->value, $scope);
            if ($ruleTrees === []) {
                return $this->invalidate(
                    $receiverType,
                    $methodReflection,
                    $methodCall,
                    $scope
                );
            }

            return TypeCombinator::union(...array_map(
                static fn ($ruleTree): ValidatorType => new ValidatorType($ruleTree),
                $ruleTrees
            ));
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }

    private function invalidate(
        Type $type,
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): ?Type {
        $types = [];
        TypeTraverser::map(
            $type,
            static function (Type $innerType, callable $traverse) use (&$types): Type {
                if ($innerType instanceof ValidatorType) {
                    $types[] = new ObjectType(
                        Validator::class,
                        $innerType->getSubtractedType()
                    );

                    return $innerType;
                }

                return $traverse($innerType);
            }
        );

        foreach (TypeUtils::flattenTypes($type) as $innerType) {
            if ($this->containsValidatorType($innerType)) {
                continue;
            }

            if (!$innerType->hasMethod($methodReflection->getName())->yes()) {
                continue;
            }

            $unrelatedMethod = $innerType->getMethod(
                $methodReflection->getName(),
                $scope
            );
            $types[] = ParametersAcceptorSelector::selectFromArgs(
                $scope,
                $methodCall->getArgs(),
                $unrelatedMethod->getVariants()
            )->getReturnType();
        }

        return $types === [] ? null : TypeCombinator::union(...$types);
    }

    private function containsValidatorType(Type $type): bool
    {
        $found = false;
        TypeTraverser::map(
            $type,
            static function (Type $innerType, callable $traverse) use (&$found): Type {
                if ($innerType instanceof ValidatorType) {
                    $found = true;

                    return $innerType;
                }

                return $traverse($innerType);
            }
        );

        return $found;
    }

    private function isFreshValidatorExpression(
        Expr $expression,
        Scope $scope
    ): bool {
        if ($expression instanceof MethodCall) {
            return $expression->name instanceof Node\Identifier
                && $expression->name->toLowerString() === 'make'
                && !$expression->isFirstClassCallable()
                && (new ObjectType(\Illuminate\Validation\Factory::class))
                    ->isSuperTypeOf($scope->getType($expression->var))
                    ->yes();
        }

        if ($expression instanceof StaticCall) {
            return $expression->name instanceof Node\Identifier
                && $expression->name->toLowerString() === 'make'
                && !$expression->isFirstClassCallable()
                && $expression->class instanceof Node\Name
                && $scope->resolveName($expression->class)
                    === \Illuminate\Support\Facades\Validator::class;
        }

        if (!$expression instanceof FuncCall
            || $expression->isFirstClassCallable()
            || !$expression->name instanceof Node\Name
            || !$this->reflectionProvider->hasFunction($expression->name, $scope)
        ) {
            return false;
        }

        return strtolower($this->reflectionProvider
            ->getFunction($expression->name, $scope)
            ->getName()) === 'validator';
    }

    private function resolveFreshValidatorType(
        Expr $expression,
        Scope $scope
    ): ?Type {
        $rulesArgument = null;
        if ($expression instanceof MethodCall) {
            $rulesArgument = $this->callArgumentResolver->find(
                $expression->getArgs(),
                'rules',
                1
            );
        } elseif ($expression instanceof StaticCall) {
            $rulesArgument = $this->callArgumentResolver->find(
                $expression->getArgs(),
                'rules',
                1
            );
        } elseif ($expression instanceof FuncCall) {
            $rulesArgument = $this->callArgumentResolver->find(
                $expression->getArgs(),
                'rules',
                1
            );
        }

        if ($rulesArgument === null) {
            return null;
        }

        $ruleTrees = $this->ruleSetResolver->resolve(
            $rulesArgument->value,
            $scope
        );
        if ($ruleTrees === []) {
            return null;
        }

        return TypeCombinator::union(...array_map(
            static fn ($ruleTree): ValidatorType => new ValidatorType($ruleTree),
            $ruleTrees
        ));
    }
}
