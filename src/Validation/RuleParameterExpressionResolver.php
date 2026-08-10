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

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;

/**
 * Recovers scalar parameter lists shared by Laravel's stringable built-in
 * rule factories. Runtime Arrayable and Stringable values remain opaque
 * rather than being executed during analysis.
 */
final class RuleParameterExpressionResolver
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private LaravelVersionContext $laravelVersionContext
    ) {
    }

    /**
     * @param list<Arg> $arguments
     * @return list<string>|null
     */
    public function resolve(array $arguments, Scope $scope, string $enumValueBoundary): ?array
    {
        foreach ($arguments as $argument) {
            if ($argument->unpack) {
                return null;
            }
        }

        if ($arguments === []) {
            return [];
        }

        $firstExpression = $arguments[0]->value;
        if ($firstExpression instanceof Expr\Array_) {
            foreach ($firstExpression->items as $item) {
                if ($item->unpack) {
                    return null;
                }
            }
        }

        $parameters = $this->resolveConstantArrayType(
            $scope->getType($firstExpression),
            $enumValueBoundary
        );
        if ($parameters !== null) {
            return $parameters;
        }

        $parameters = [];
        foreach ($arguments as $argument) {
            $parameter = $this->resolveExpressionValue(
                $argument->value,
                $scope,
                $enumValueBoundary
            );
            if ($parameter === null) {
                return null;
            }
            $parameters[] = $parameter;
        }

        return $parameters;
    }

    /** @return list<string>|null */
    private function resolveConstantArrayType(Type $type, string $enumValueBoundary): ?array
    {
        $arrays = $type->getConstantArrays();
        if (count($arrays) !== 1) {
            return null;
        }

        $parameters = [];
        foreach ($arrays[0]->getValueTypes() as $index => $valueType) {
            if ($arrays[0]->isOptionalKey($index)) {
                return null;
            }

            $parameter = $this->resolveTypeValue($valueType, $enumValueBoundary);
            if ($parameter === null) {
                return null;
            }
            $parameters[] = $parameter;
        }

        return $parameters;
    }

    private function resolveExpressionValue(
        Expr $expression,
        Scope $scope,
        string $enumValueBoundary
    ): ?string {
        $value = $this->resolveTypeValue($scope->getType($expression), $enumValueBoundary);
        if ($value !== null) {
            return $value;
        }

        if (
            !$expression instanceof Expr\ClassConstFetch
            || !$expression->class instanceof Name
            || !$expression->name instanceof Identifier
        ) {
            return null;
        }

        return $this->resolveEnumCaseValue(
            $this->resolveName($expression->class, $scope),
            $expression->name->toString(),
            $enumValueBoundary
        );
    }

    private function resolveTypeValue(Type $type, string $enumValueBoundary): ?string
    {
        if ($type->isNull()->yes()) {
            return '';
        }

        $enumCases = $type->getEnumCases();
        if (count($enumCases) === 1) {
            return $this->resolveEnumCaseValue(
                $enumCases[0]->getClassName(),
                $enumCases[0]->getEnumCaseName(),
                $enumValueBoundary
            );
        }

        if (!$type->isConstantScalarValue()->yes()) {
            return null;
        }

        $values = $type->getConstantScalarValues();
        if (count($values) !== 1) {
            return null;
        }

        return (string) $values[0];
    }

    private function resolveEnumCaseValue(
        string $className,
        string $caseName,
        string $enumValueBoundary
    ): ?string {
        if (
            !$this->laravelVersionContext->isAtLeast($enumValueBoundary)
            || !$this->reflectionProvider->hasClass($className)
        ) {
            return null;
        }

        $enum = $this->reflectionProvider->getClass($className);
        if (!$enum->isEnum() || !$enum->hasEnumCase($caseName)) {
            return null;
        }

        foreach ($enum->getEnumCases() as $case) {
            if ($case->getName() !== $caseName) {
                continue;
            }

            if (!$enum->isBackedEnum()) {
                return $caseName;
            }

            $backingType = $case->getBackingValueType();
            if ($backingType === null || !$backingType->isConstantScalarValue()->yes()) {
                return null;
            }

            $values = $backingType->getConstantScalarValues();
            return count($values) === 1 && (is_int($values[0]) || is_string($values[0]))
                ? (string) $values[0]
                : null;
        }

        return null;
    }

    private function resolveName(Name $name, Scope $scope): string
    {
        $resolvedName = $name->getAttribute('resolvedName');
        return $resolvedName instanceof Name
            ? $resolvedName->toString()
            : $scope->resolveName($name);
    }
}
