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

use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;

/**
 * Recovers the parameter list from a statically visible Rule::in() call.
 * Runtime Arrayable and Stringable inputs remain opaque rather than being
 * executed during analysis.
 */
final class InRuleExpressionResolver
{
    private const RULE_FACTORY_CLASS = \Illuminate\Validation\Rule::class;
    private const ENUM_VALUE_BOUNDARY = '10.21.1';

    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private LaravelVersionContext $laravelVersionContext
    ) {
    }

    public function resolve(Expr $expression, Scope $scope): ?Rule
    {
        if (
            !$this->laravelVersionContext->isSupported()
            || !$expression instanceof Expr\StaticCall
            || !$expression->class instanceof Name
            || !$expression->name instanceof Identifier
            || $this->resolveName($expression->class, $scope) !== self::RULE_FACTORY_CLASS
            || $expression->name->toLowerString() !== 'in'
        ) {
            return null;
        }

        $arguments = $expression->getArgs();
        if ($arguments === []) {
            return null;
        }
        foreach ($arguments as $argument) {
            if ($argument->unpack) {
                return null;
            }
        }

        $firstExpression = $arguments[0]->value;
        if ($firstExpression instanceof Expr\Array_) {
            $parameters = $this->resolveArrayExpression($firstExpression, $scope);
        } else {
            $parameters = $this->resolveConstantArrayType($scope->getType($firstExpression));
            if ($parameters === null) {
                $parameters = [];
                foreach ($arguments as $argument) {
                    $parameter = $this->resolveExpressionValue($argument->value, $scope);
                    if ($parameter === null) {
                        return null;
                    }
                    $parameters[] = $parameter;
                }
            }
        }

        if ($parameters === null) {
            return null;
        }

        // An empty builder serializes as `in:`. Laravel's CSV parser exposes
        // that sole empty parameter as null; resolveTypeIn normalizes it back
        // to the empty string used by the loose runtime comparison.
        return Rule::create('In', $parameters === [] ? [null] : $parameters);
    }

    /** @return list<string>|null */
    private function resolveArrayExpression(Expr\Array_ $expression, Scope $scope): ?array
    {
        $parameters = [];
        foreach ($expression->items as $item) {
            if ($item->unpack) {
                return null;
            }

            $parameter = $this->resolveExpressionValue($item->value, $scope);
            if ($parameter === null) {
                return null;
            }
            $parameters[] = $parameter;
        }

        return $parameters;
    }

    /** @return list<string>|null */
    private function resolveConstantArrayType(Type $type): ?array
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

            $parameter = $this->resolveTypeValue($valueType);
            if ($parameter === null) {
                return null;
            }
            $parameters[] = $parameter;
        }

        return $parameters;
    }

    private function resolveExpressionValue(Expr $expression, Scope $scope): ?string
    {
        $value = $this->resolveTypeValue($scope->getType($expression));
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
            $expression->name->toString()
        );
    }

    private function resolveTypeValue(Type $type): ?string
    {
        if ($type->isNull()->yes()) {
            return '';
        }

        $enumCases = $type->getEnumCases();
        if (count($enumCases) === 1) {
            return $this->resolveEnumCaseValue(
                $enumCases[0]->getClassName(),
                $enumCases[0]->getEnumCaseName()
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

    private function resolveEnumCaseValue(string $className, string $caseName): ?string
    {
        if (
            !$this->laravelVersionContext->isAtLeast(self::ENUM_VALUE_BOUNDARY)
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
