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

use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Validation\InvalidCustomRuleContractException;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\StaticMethodTypeSpecifyingExtension;
use PHPStan\Type\TypeCombinator;

final class FacadeValidateExtension implements
    DynamicStaticMethodReturnTypeExtension,
    StaticMethodTypeSpecifyingExtension,
    TypeSpecifierAwareExtension
{
    private TypeSpecifier $typeSpecifier;

    public function __construct(
        private RuleSetResolver $ruleSetResolver,
        private TypeResolver $typeResolver,
        private CallArgumentResolver $callArgumentResolver
    ) {
    }

    public function getClass(): string
    {
        return \Illuminate\Support\Facades\Validator::class;
    }

    public function isStaticMethodSupported(
        MethodReflection $methodReflection,
        ?StaticCall $node = null,
        ?TypeSpecifierContext $context = null
    ): bool {
        if ($methodReflection->getName() !== 'validate') {
            return false;
        }

        // DynamicStaticMethodReturnTypeExtension calls this method with only
        // the reflection. The type-specifying interface supplies the call and
        // context; refine only after an ordinary executed call.
        return $node === null
            || (!$node->isFirstClassCallable() && $context?->null() === true);
    }

    public function getTypeFromStaticMethodCall(
        MethodReflection $methodReflection,
        StaticCall $methodCall,
        Scope $scope
    ): ?\PHPStan\Type\Type {
        try {
            $rulesArg = $this->callArgumentResolver->find(
                $methodCall->getArgs(),
                'rules',
                1
            );
            if ($rulesArg === null) {
                return null;
            }

            $ruleTrees = $this->ruleSetResolver->resolve($rulesArg->value, $scope);
            if ($ruleTrees === []) {
                return null;
            }

            return TypeCombinator::union(...array_map(
                fn ($ruleTree) => $this->typeResolver->evaluate($ruleTree),
                $ruleTrees
            ));
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }

    public function specifyTypes(
        MethodReflection $staticMethodReflection,
        StaticCall $node,
        Scope $scope,
        TypeSpecifierContext $context
    ): SpecifiedTypes {
        try {
            $dataArg = $this->callArgumentResolver->find($node->getArgs(), 'data', 0);
            $rulesArg = $this->callArgumentResolver->find($node->getArgs(), 'rules', 1);
            if (
                $dataArg === null
                || $rulesArg === null
                || !$dataArg->value instanceof Expr\Variable
                || !is_string($dataArg->value->name)
                || $this->otherArgumentMayChangeEvaluationState($node, $dataArg)
            ) {
                return new SpecifiedTypes([], []);
            }

            $ruleTrees = $this->ruleSetResolver->resolve($rulesArg->value, $scope);
            if ($ruleTrees === []) {
                return new SpecifiedTypes([], []);
            }

            $currentInputType = $scope->getType($dataArg->value);
            $inputType = TypeCombinator::union(...array_map(
                fn ($ruleTree) => $this->typeResolver->refineSuccessfulDirectInput(
                    $ruleTree,
                    $currentInputType
                ),
                $ruleTrees
            ));

            return $this->typeSpecifier->create(
                $dataArg->value,
                $inputType,
                TypeSpecifierContext::createTruthy(),
                $scope
            );
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }

    public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
    {
        $this->typeSpecifier = $typeSpecifier;
    }

    private function otherArgumentMayChangeEvaluationState(
        StaticCall $node,
        Arg $dataArg
    ): bool {
        foreach ($node->getArgs() as $argument) {
            if ($argument === $dataArg) {
                continue;
            }

            $unsafeNode = (new NodeFinder())->findFirst(
                [$argument->value],
                static fn (Node $candidate): bool =>
                    $candidate instanceof Expr\Assign
                    || $candidate instanceof Expr\AssignOp
                    || $candidate instanceof Expr\AssignRef
                    || $candidate instanceof Expr\CallLike
                    || $candidate instanceof Expr\Closure
                    || $candidate instanceof Expr\ArrowFunction
                    || $candidate instanceof Expr\PreInc
                    || $candidate instanceof Expr\PreDec
                    || $candidate instanceof Expr\PostInc
                    || $candidate instanceof Expr\PostDec
                    || $candidate instanceof Expr\ArrayDimFetch
                    || $candidate instanceof Expr\PropertyFetch
                    || $candidate instanceof Expr\NullsafePropertyFetch
                    || $candidate instanceof Expr\StaticPropertyFetch
                    || $candidate instanceof Expr\Include_
                    || $candidate instanceof Expr\Eval_
                    || ($candidate instanceof Expr\Variable && !is_string($candidate->name))
            );
            if ($unsafeNode !== null) {
                return true;
            }
        }

        return false;
    }
}
