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

use jbboehr\PhpstanLaravelValidation\Evaluator\UnsafeConstExprEvaluator;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Type\ValidatorType;
use PhpParser\ConstExprEvaluationException;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Type\ObjectType;

final class ValidatorFunctionExtension implements DynamicFunctionReturnTypeExtension
{
    private UnsafeConstExprEvaluator $constExprEvaluator;

    public function __construct(
        UnsafeConstExprEvaluator $constExprEvaluator
    ) {
        $this->constExprEvaluator = $constExprEvaluator;
    }

    public function isFunctionSupported(FunctionReflection $functionReflection): bool
    {
        return $functionReflection->getName() === 'validator';
    }

    public function getTypeFromFunctionCall(
        FunctionReflection $functionReflection,
        FuncCall $functionCall,
        Scope $scope
    ): ?\PHPStan\Type\Type {
        try {
            if (count($functionCall->getArgs()) <= 0) {
                return new ObjectType(\Illuminate\Contracts\Validation\Factory::class);
            }

            if (count($functionCall->getArgs()) < 2) {
                return null;
            }

            $rulesArg = $functionCall->getArgs()[1];
            $rulesValue = $this->constExprEvaluator->evaluate($rulesArg->value, $scope);

            return new ValidatorType(RuleParser::parse($rulesValue));
        } catch (ConstExprEvaluationException $e) {
            // @todo log or error?
            return null;
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }
}
