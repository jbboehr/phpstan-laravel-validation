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
        $resolution = $this->resolveWithMetadata($arguments, $scope, $enumValueBoundary);
        return $resolution['parameters'] ?? null;
    }

    /**
     * @param list<Arg> $arguments
     * @return array{parameters: list<string>, hasRuntimeFormattedFloatParameter: bool}|null
     */
    public function resolveWithMetadata(
        array $arguments,
        Scope $scope,
        string $enumValueBoundary
    ): ?array {
        foreach ($arguments as $argument) {
            if ($argument->unpack) {
                return null;
            }
        }

        if ($arguments === []) {
            return [
                'parameters' => [],
                'hasRuntimeFormattedFloatParameter' => false,
            ];
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
        $hasRuntimeFormattedFloatParameter = false;
        foreach ($arguments as $argument) {
            $resolution = $this->resolveExpressionValue(
                $argument->value,
                $scope,
                $enumValueBoundary
            );
            if ($resolution === null) {
                return null;
            }
            $parameters[] = $resolution['parameter'];
            $hasRuntimeFormattedFloatParameter = $hasRuntimeFormattedFloatParameter
                || $resolution['runtimeFormattedFloat'];
        }

        return [
            'parameters' => $parameters,
            'hasRuntimeFormattedFloatParameter' => $hasRuntimeFormattedFloatParameter,
        ];
    }

    /**
     * @return array{parameters: list<string>, hasRuntimeFormattedFloatParameter: bool}|null
     */
    private function resolveConstantArrayType(Type $type, string $enumValueBoundary): ?array
    {
        $arrays = $type->getConstantArrays();
        if (count($arrays) !== 1) {
            return null;
        }

        $parameters = [];
        $hasRuntimeFormattedFloatParameter = false;
        foreach ($arrays[0]->getValueTypes() as $index => $valueType) {
            if ($arrays[0]->isOptionalKey($index)) {
                return null;
            }

            $resolution = $this->resolveTypeValue($valueType, $enumValueBoundary);
            if ($resolution === null) {
                return null;
            }
            $parameters[] = $resolution['parameter'];
            $hasRuntimeFormattedFloatParameter = $hasRuntimeFormattedFloatParameter
                || $resolution['runtimeFormattedFloat'];
        }

        return [
            'parameters' => $parameters,
            'hasRuntimeFormattedFloatParameter' => $hasRuntimeFormattedFloatParameter,
        ];
    }

    /** @return array{parameter: string, runtimeFormattedFloat: bool}|null */
    private function resolveExpressionValue(
        Expr $expression,
        Scope $scope,
        string $enumValueBoundary
    ): ?array {
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

        $value = $this->resolveEnumCaseValue(
            $this->resolveName($expression->class, $scope),
            $expression->name->toString(),
            $enumValueBoundary
        );
        return $value === null
            ? null
            : ['parameter' => $value, 'runtimeFormattedFloat' => false];
    }

    /** @return array{parameter: string, runtimeFormattedFloat: bool}|null */
    private function resolveTypeValue(Type $type, string $enumValueBoundary): ?array
    {
        if ($type->isNull()->yes()) {
            return ['parameter' => '', 'runtimeFormattedFloat' => false];
        }

        $enumCases = $type->getEnumCases();
        if (count($enumCases) === 1) {
            $value = $this->resolveEnumCaseValue(
                $enumCases[0]->getClassName(),
                $enumCases[0]->getEnumCaseName(),
                $enumValueBoundary
            );
            return $value === null
                ? null
                : ['parameter' => $value, 'runtimeFormattedFloat' => false];
        }

        if (!$type->isConstantScalarValue()->yes()) {
            return null;
        }

        $values = $type->getConstantScalarValues();
        if (count($values) !== 1) {
            return null;
        }

        return [
            'parameter' => (string) $values[0],
            'runtimeFormattedFloat' => is_float($values[0]),
        ];
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
