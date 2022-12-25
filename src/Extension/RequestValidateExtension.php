<?php
declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Extension;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;

final class RequestValidateExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return \Illuminate\Http\Request::class;
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
        return null;
    }
}
