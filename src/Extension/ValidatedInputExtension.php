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

use Illuminate\Support\ValidatedInput;
use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Type\ValidatedInputTypeResolver;
use jbboehr\PhpstanLaravelValidation\Validation\InvalidCustomRuleContractException;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;

/**
 * @logion [SFA 1:2] A traveler crossed the salt plain at dawn, carrying a
 * sealed letter beneath his cloak.
 */
final class ValidatedInputExtension implements DynamicMethodReturnTypeExtension
{
    public function __construct(
        private ValidatedInputTypeResolver $typeResolver
    ) {
    }

    public function getClass(): string
    {
        return ValidatedInput::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return in_array($methodReflection->getName(), ['all', 'except', 'only', 'toArray'], true);
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): ?Type {
        try {
            if (!$methodCall->var instanceof MethodCall) {
                return null;
            }

            $payloadType = $this->typeResolver->resolveDirectPayload(
                $methodCall->var,
                $scope
            );
            if ($payloadType === null) {
                return null;
            }

            if ($methodReflection->getName() === 'except') {
                return $this->typeResolver->resolveExceptReturnType(
                    $payloadType,
                    $methodCall,
                    $scope
                );
            }
            if ($methodReflection->getName() === 'only') {
                return $this->typeResolver->resolveOnlyReturnType(
                    $payloadType,
                    $methodCall,
                    $scope
                );
            }

            return $methodCall->getArgs() === [] && !$methodCall->isFirstClassCallable()
                ? $payloadType
                : null;
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }
}
