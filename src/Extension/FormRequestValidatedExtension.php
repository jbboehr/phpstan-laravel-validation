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

use Illuminate\Foundation\Http\FormRequest;
use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestTypeRegistry;
use jbboehr\PhpstanLaravelValidation\Validation\InvalidCustomRuleContractException;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

final class FormRequestValidatedExtension implements DynamicMethodReturnTypeExtension
{
    public function __construct(
        private FormRequestTypeRegistry $typeRegistry
    ) {
    }

    public function getClass(): string
    {
        return FormRequest::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'validated';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): ?Type {
        try {
            $args = $methodCall->getArgs();
            if ($args !== [] && !$scope->getType($args[0]->value)->isNull()->yes()) {
                return null;
            }

            $types = [];
            foreach ($scope->getType($methodCall->var)->getObjectClassReflections() as $classReflection) {
                if (!$classReflection->isSubclassOf(FormRequest::class)) {
                    continue;
                }

                $type = $this->typeRegistry->getType($classReflection);
                if ($type === null) {
                    return null;
                }

                $types[] = $type;
            }

            if ($types === []) {
                return null;
            }

            return TypeCombinator::union(...$types);
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }
}
