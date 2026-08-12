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
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\TypeCombinator;

final class FacadeValidateExtension implements DynamicStaticMethodReturnTypeExtension
{
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

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'validate';
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
}
