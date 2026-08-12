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

use Illuminate\Foundation\Validation\ValidatesRequests;
use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Validation\InvalidCustomRuleContractException;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\TypeCombinator;

final class ControllerValidateExtension implements DynamicMethodReturnTypeExtension
{
    private bool $assumeHttpInputNormalization;

    public function __construct(
        private RuleSetResolver $ruleSetResolver,
        private TypeResolver $typeResolver,
        private CallArgumentResolver $callArgumentResolver,
        bool $assumeHttpInputNormalization
    ) {
        $this->assumeHttpInputNormalization = $assumeHttpInputNormalization;
    }

    public function getClass(): string
    {
        return \Illuminate\Routing\Controller::class;
        //        return \App\Http\Controllers\Controller::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'validate';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): ?\PHPStan\Type\Type {
        try {
            if (!$methodReflection->getDeclaringClass()->hasTraitUse(ValidatesRequests::class)) {
                return null;
            }

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
                fn ($ruleTree) => $this->typeResolver->evaluate(
                    $ruleTree,
                    $this->assumeHttpInputNormalization
                ),
                $ruleTrees
            ));
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }
}
