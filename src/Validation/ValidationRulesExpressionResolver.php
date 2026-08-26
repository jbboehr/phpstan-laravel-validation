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

namespace jbboehr\PhpstanLaravelValidation\Validation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Extension\CallArgumentResolver;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\NeverType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\TypeUtils;

/**
 * Locates validation rule expressions at Laravel's supported entry points.
 *
 * @logion [SFA 1:4] A child found a silver seed beneath the dry well; she
 *     buried it beneath her doorway, and for seven nights the house dreamed
 *     of rain.
 */
final class ValidationRulesExpressionResolver
{
    public function __construct(
        private CallArgumentResolver $callArgumentResolver,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function resolve(Node $node, Scope $scope): ?Expr
    {
        if ($node instanceof Return_) {
            return $this->isFormRequestRulesReturn($scope) ? $node->expr : null;
        }

        if (!$node instanceof CallLike || $node->isFirstClassCallable()) {
            return null;
        }

        if ($node instanceof FuncCall) {
            if (!$this->isLaravelValidatorFunction($node, $scope)) {
                return null;
            }

            return $this->findArgument($node, 'rules', 1);
        }

        if ($node instanceof StaticCall) {
            if (!$node->name instanceof Node\Identifier
                || !in_array($node->name->toLowerString(), ['make', 'validate'], true)
                || !$node->class instanceof Node\Name
                || $node->class->isSpecialClassName()
                || !$this->isSubtypeOf(
                    new ObjectType($scope->resolveName($node->class)),
                    ValidatorFacade::class
                )
            ) {
                return null;
            }

            return $this->findArgument($node, 'rules', 1);
        }

        if (!$node instanceof MethodCall && !$node instanceof NullsafeMethodCall) {
            return null;
        }
        if (!$node->name instanceof Node\Identifier) {
            return null;
        }

        $method = $node->name->toLowerString();
        if ($method === 'setrules' && $this->isFreshValidatorExpression($node->var, $scope)) {
            return $this->findArgument($node, 'rules', 0);
        }

        $receiverType = $scope->getType($node->var);
        if (in_array($method, ['make', 'validate'], true)
            && $this->isSubtypeOf($receiverType, Factory::class)
        ) {
            return $this->findArgument($node, 'rules', 1);
        }
        if ($method === 'validate' && $this->isSubtypeOf($receiverType, Request::class)) {
            return $this->findArgument($node, 'rules', 0);
        }
        if (!$this->usesValidatesRequests($receiverType)) {
            return null;
        }

        return match ($method) {
            'validate' => $this->findArgument($node, 'rules', 1),
            'validatewith' => $this->findArgument($node, 'validator', 0),
            'validatewithbag' => $this->findArgument($node, 'rules', 2),
            default => null,
        };
    }

    private function isFreshValidatorExpression(Expr $expression, Scope $scope): bool
    {
        if ($expression instanceof FuncCall) {
            return !$expression->isFirstClassCallable()
                && $this->isLaravelValidatorFunction($expression, $scope);
        }

        if ($expression instanceof StaticCall) {
            return $expression->name instanceof Node\Identifier
                && $expression->name->toLowerString() === 'make'
                && !$expression->isFirstClassCallable()
                && $expression->class instanceof Node\Name
                && !$expression->class->isSpecialClassName()
                && $this->isSubtypeOf(
                    new ObjectType($scope->resolveName($expression->class)),
                    ValidatorFacade::class
                );
        }

        return $expression instanceof MethodCall
            && $expression->name instanceof Node\Identifier
            && $expression->name->toLowerString() === 'make'
            && !$expression->isFirstClassCallable()
            && $this->isSubtypeOf($scope->getType($expression->var), Factory::class);
    }

    public function findPathLine(Expr $expression, string $path, Scope $scope): int
    {
        if (!$expression instanceof Expr\Array_) {
            return $expression->getStartLine();
        }

        foreach ($expression->items as $item) {
            if ($item->unpack || $item->key === null) {
                continue;
            }

            $keys = $scope->getType($item->key)->getConstantScalarValues();
            if (count($keys) !== 1 || (!is_int($keys[0]) && !is_string($keys[0]))) {
                continue;
            }

            if (str_replace('\\.', '.', (string) $keys[0]) === $path) {
                return $item->getStartLine();
            }
        }

        return $expression->getStartLine();
    }

    private function findArgument(CallLike $call, string $name, int $position): ?Expr
    {
        return $this->callArgumentResolver->find($call->getArgs(), $name, $position)?->value;
    }

    private function isLaravelValidatorFunction(FuncCall $call, Scope $scope): bool
    {
        if (!$call->name instanceof Node\Name) {
            return false;
        }

        // NameResolver records the namespaced candidate even though PHP falls
        // back to a global function when that candidate does not exist. Check
        // a real namespaced declaration before accepting the global helper.
        $namespacedName = $call->name->getAttribute('namespacedName');
        if (!$namespacedName instanceof Node\Name
            && !$call->name->isFullyQualified()
            && $scope->getNamespace() !== null
        ) {
            $namespacedName = new Node\Name\FullyQualified(
                $scope->getNamespace() . '\\' . $call->name->toString()
            );
        }
        if ($namespacedName instanceof Node\Name
            && $this->reflectionProvider->hasFunction($namespacedName, $scope)
            && strtolower($this->reflectionProvider
                ->getFunction($namespacedName, $scope)
                ->getName()) !== 'validator'
        ) {
            return false;
        }

        return $this->reflectionProvider->hasFunction($call->name, $scope)
            && strtolower($this->reflectionProvider
                ->getFunction($call->name, $scope)
                ->getName()) === 'validator';
    }

    private function isFormRequestRulesReturn(Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        $functionReflection = $scope->getFunction();

        return $classReflection !== null
            && $functionReflection instanceof MethodReflection
            && !$scope->isInAnonymousFunction()
            && strtolower($functionReflection->getName()) === 'rules'
            && ($classReflection->getName() === FormRequest::class
                || $classReflection->getAncestorWithClassName(FormRequest::class) !== null);
    }

    /** @param class-string $parentClass */
    private function isSubtypeOf(Type $type, string $parentClass): bool
    {
        return $this->allObjectAlternativesMatch(
            $type,
            static fn ($classReflection): bool => $classReflection->getName() === $parentClass
                || $classReflection->getAncestorWithClassName($parentClass) !== null
        );
    }

    private function usesValidatesRequests(Type $type): bool
    {
        return $this->allObjectAlternativesMatch(
            $type,
            static fn ($classReflection): bool => $classReflection
                ->hasTraitUse(ValidatesRequests::class)
        );
    }

    /**
     * @param callable(\PHPStan\Reflection\ClassReflection): bool $predicate
     */
    private function allObjectAlternativesMatch(Type $type, callable $predicate): bool
    {
        $type = TypeCombinator::removeNull($type);
        if ($type instanceof NeverType) {
            return false;
        }

        $alternatives = TypeUtils::flattenTypes($type);
        if ($alternatives === []) {
            return false;
        }

        foreach ($alternatives as $alternative) {
            $matches = false;
            foreach ($alternative->getObjectClassReflections() as $classReflection) {
                if ($predicate($classReflection)) {
                    $matches = true;
                    break;
                }
            }

            if (!$matches) {
                return false;
            }
        }

        return true;
    }
}
