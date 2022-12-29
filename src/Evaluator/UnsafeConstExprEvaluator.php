<?php
declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Evaluator;

use PhpParser\ConstExprEvaluationException;
use PhpParser\ConstExprEvaluator;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type;

/**
 * @see https://github.com/Roave/BetterReflection/blob/6.6.x/src/NodeCompiler/CompileNodeToValue.php
 */
class UnsafeConstExprEvaluator
{
    private ConstExprEvaluator $internalEvaluator;

    public function __construct()
    {
        $this->internalEvaluator = new ConstExprEvaluator();
    }

    /**
     * @return mixed
     * @throws ConstExprEvaluationException
     */
    public function evaluate(Expr $expr, Scope $scope): mixed
    {
        // Only works on very simple constant expressions
        try {
            return $this->getValueFromType($scope->getType($expr));
        } catch (ConstExprEvaluationException) {
        }

        // Resolve class constants
        if ($expr instanceof Expr\ClassConstFetch) {
            return $this->getClassConstantValue($expr, $scope);
        }

        return $this->internalEvaluator->evaluateSilently($expr);
    }

    /**
     * @param Type\Type $type
     * @return mixed
     * @throws ConstExprEvaluationException
     */
    private function getValueFromType(Type\Type $type): mixed
    {
        if ($type instanceof Type\ConstantScalarType) {
            return $type->getValue();
        } elseif ($type instanceof Type\Constant\ConstantArrayType) {
            $arr = [];
            foreach ($type->getKeyTypes() as $keyType) {
                $valueType = $type->getOffsetValueType($keyType);
                $arr[$this->getValueFromType($keyType)] = $this->getValueFromType($valueType);
            }
            return $arr;
        }

        throw new ConstExprEvaluationException();
    }

    /**
     * @throws ConstExprEvaluationException
     * @psalm-suppress UndefinedDocblockClass
     */
    private function getClassConstantValue(Expr\ClassConstFetch $node, Scope $scope): mixed
    {
        $classConstantName = $this->resolveClassConstantName($node, $scope);

        [$className, $constantName] = explode('::', $classConstantName);
        if ($constantName === '') {
            throw new ConstExprEvaluationException();
        }

        if ($constantName === 'class') {
            return $className;
        }

        $classReflection = $scope->getClassReflection();
        if (null === $classReflection) {
            // This happens when the constant is referenced outside the class - any reason it can't work?
            throw new ConstExprEvaluationException();
        }

        $reflectionConstant = $classReflection->getNativeReflection()
            ->getReflectionConstant($constantName);

        if (false !== $reflectionConstant) {
            return $this->evaluate($reflectionConstant->getValueExpression(), $scope);
        }

        throw new ConstExprEvaluationException();
    }

    /**
     * @throws ConstExprEvaluationException
     */
    private function resolveClassConstantName(Expr\ClassConstFetch $node, Scope $scope): string
    {
        if (!($node->name instanceof Identifier) || !($node->class instanceof Name)) {
            throw new ConstExprEvaluationException();
        }

        $constantName = $node->name->name;
        $className = $node->class->toString();

        return sprintf('%s::%s', $this->resolveClassName($className, $scope), $constantName);
    }

    /**
     * @throws ConstExprEvaluationException
     */
    private function resolveClassName(string $className, Scope $scope): string
    {
        if ($className !== 'self' && $className !== 'static' && $className !== 'parent') {
            return $className;
        }

        $classReflection = $scope->getClassReflection();
        if (null === $classReflection) {
            throw new ConstExprEvaluationException();
        }

        if ($className !== 'parent') {
            return $classReflection->getName();
        }

        $parentClass = $classReflection->getParentClass();
        if (!($parentClass instanceof ClassReflection)) {
            throw new ConstExprEvaluationException();
        };

        return $parentClass->getName();
    }
}
