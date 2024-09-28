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
            return $this->getValueFromType($scope->getType($expr), $scope);
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
    private function getValueFromType(Type\Type $type, Scope $scope): mixed
    {
        if ($type->isConstantScalarValue()->yes()) {
            if (count($values = $type->getConstantScalarValues()) === 1) {
                return $values[0];
            }
        } elseif (count($constantArrayTypes = $type->getConstantArrays()) === 1) {
            $constantArrayType = $constantArrayTypes[0];
            $arr = [];
            foreach ($constantArrayType->getKeyTypes() as $keyType) {
                $valueType = $constantArrayType->getOffsetValueType($keyType);
                $valueFromType = $this->getValueFromType($valueType, $scope);
                $arr[$this->getValueFromType($keyType, $scope)] = $valueFromType;
            }
            return $arr;
        }
        if ($type instanceof \PHPStan\Type\ObjectType) {
            if (is_subclass_of($type->getClassName(), \Illuminate\Contracts\Validation\ValidationRule::class)) {
                
                $reflection = $type->getClassReflection();
                $method = $reflection->getMethod('validate', $scope);
                $typeAssert = $method->getAsserts()->getAsserts();

                $validAssert = null;
                foreach ($typeAssert as $typeAssert2) {
                    if ($typeAssert2->getParameter()->getParameterName() !== '$value') {
                        continue;
                    }
                    if ($validAssert === null) {
                        $validAssert = $typeAssert2;
                    } else {
                        $validAssert = $typeAssert2->withType($validAssert->getType());
                    }
                }
                if ($validAssert !== null) {
                    return 'PHPStanType:' . serialize($validAssert->getType());
                }
            }
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
