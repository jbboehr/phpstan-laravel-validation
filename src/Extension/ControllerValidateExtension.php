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
use jbboehr\PhpstanLaravelValidation\Evaluator\UnsafeConstExprEvaluator;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PhpParser\ConstExprEvaluationException;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;

final class ControllerValidateExtension implements DynamicMethodReturnTypeExtension
{
    private UnsafeConstExprEvaluator $constExprEvaluator;

    public function __construct(
        UnsafeConstExprEvaluator $constExprEvaluator
    ) {
        $this->constExprEvaluator = $constExprEvaluator;
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

            if (count($methodCall->getArgs()) < 2) {
                return null;
            }

            $rulesArg = $methodCall->getArgs()[1];
            $rulesValue = $this->constExprEvaluator->evaluate($rulesArg->value, $scope);

            $validatorRules = RuleParser::parse($rulesValue);
            $evaluator = new TypeResolver();
            return $evaluator->evaluate($validatorRules);
        } catch (ConstExprEvaluationException $e) {
            // @todo log or error?
            return null;
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }
}
