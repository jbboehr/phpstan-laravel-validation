<?php
declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Extension;

use Illuminate\Foundation\Validation\ValidatesRequests;
use jbboehr\PhpstanLaravelValidation\Evaluator\UnsafeConstExprEvaluator;
use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Type\ValidatorType;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PhpParser\ConstExprEvaluationException;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;

final class ControllerValidateWithExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return \Illuminate\Routing\Controller::class;
//        return \App\Http\Controllers\Controller::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'validateWith';
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

            $validatorType = $scope->getType($methodCall->getArgs()[0]->value);
            if (!($validatorType instanceof ValidatorType)) {
                return null;
            }

            $validatorRules = $validatorType->getValidatorRules();
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
