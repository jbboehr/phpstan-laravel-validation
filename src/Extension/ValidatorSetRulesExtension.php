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
use jbboehr\PhpstanLaravelValidation\Type\ValidatorType;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\TypeCombinator;

final class ValidatorSetRulesExtension implements DynamicMethodReturnTypeExtension
{
    public function __construct(
        private RuleSetResolver $ruleSetResolver
    ) {
    }

    public function getClass(): string
    {
        return \Illuminate\Validation\Validator::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'setRules';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): ?\PHPStan\Type\Type {
        try {
            if (count($methodCall->getArgs()) < 1) {
                return null;
            }

            $ruleTrees = $this->ruleSetResolver->resolve($methodCall->getArgs()[0]->value, $scope);
            if ($ruleTrees === []) {
                return null;
            }

            return TypeCombinator::union(...array_map(
                static fn ($ruleTree) => new ValidatorType($ruleTree),
                $ruleTrees
            ));
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }
}
