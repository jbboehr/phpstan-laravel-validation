<?php
declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Extension;

use jbboehr\PhpstanLaravelValidation\Evaluator\UnsafeConstExprEvaluator;
use jbboehr\PhpstanLaravelValidation\Rule\RuleParser;
use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Type\ValidatorType;
use PhpParser\ConstExprEvaluationException;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;

final class FacadeMakeExtension implements DynamicStaticMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return \Illuminate\Support\Facades\Validator::class;
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'make';
    }

    public function getTypeFromStaticMethodCall(
        MethodReflection $methodReflection,
        StaticCall $methodCall,
        Scope $scope
    ): ?\PHPStan\Type\Type {
        try {
            if (count($methodCall->getArgs()) < 2) {
                return null;
            }

            $rulesArg = $methodCall->getArgs()[1];
            $evaluator = new UnsafeConstExprEvaluator();

            $rulesValue = $evaluator->evaluate($rulesArg->value);

            return new ValidatorType(RuleParser::parse($rulesValue));
        } catch (ConstExprEvaluationException $e) {
            // @todo log or error?
            return null;
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }
}
