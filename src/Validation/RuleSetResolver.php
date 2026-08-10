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

use jbboehr\PhpstanLaravelValidation\Evaluator\UnsafeConstExprEvaluator;
use PhpParser\ConstExprEvaluationException;
use PhpParser\Node\Expr;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

final class RuleSetResolver
{
    private const MAX_ALTERNATIVES = 128;

    public function __construct(
        private UnsafeConstExprEvaluator $constExprEvaluator,
        private CustomRuleTypeResolver $customRuleTypeResolver,
        private EnumRuleExpressionResolver $enumRuleExpressionResolver,
        private LaravelVersionContext $laravelVersionContext
    ) {
    }

    /**
     * @return list<RuleTreeNode>
     */
    public function resolve(Expr $expression, Scope $scope): array
    {
        try {
            $values = $this->expandExpression($expression, $scope)
                ?? $this->expandType($scope->getType($expression));
            $trees = [];
            foreach ($values as $value) {
                if (!is_array($value)) {
                    continue;
                }
                $trees[] = RuleParser::parse(
                    $this->materializeRuleValues($value),
                    $this->laravelVersionContext
                );
            }

            if ($trees !== []) {
                return $trees;
            }
        } catch (TooManyRuleAlternativesException) {
            return [$this->createOpaqueTree()];
        }

        try {
            $value = $this->constExprEvaluator->evaluate($expression, $scope);
            if (is_array($value)) {
                return [RuleParser::parse($value, $this->laravelVersionContext)];
            }
        } catch (ConstExprEvaluationException) {
        }

        return [];
    }

    /**
     * Preserve built-in rule-object state while it is still visible in the
     * original expression. Ordinary PHPStan object types retain only the rule
     * class and lose constructor arguments and fluent mutations.
     *
     * @return list<mixed>|null
     */
    private function expandExpression(Expr $expression, Scope $scope): ?array
    {
        $rule = $this->enumRuleExpressionResolver->resolve($expression, $scope);
        if ($rule !== null) {
            return [$rule];
        }

        if (!$expression instanceof Expr\Array_) {
            return null;
        }

        if (!$this->containsResolvableEnumExpression($expression, $scope)) {
            return null;
        }

        $results = [[]];
        $specialized = false;
        $nextIndex = 0;

        foreach ($expression->items as $item) {
            if ($item->unpack) {
                return null;
            }

            if ($item->key === null) {
                $key = $nextIndex++;
            } else {
                $keys = $scope->getType($item->key)->getConstantScalarValues();
                if (count($keys) !== 1 || (!is_int($keys[0]) && !is_string($keys[0]))) {
                    return null;
                }

                $normalized = [];
                $normalized[$keys[0]] = true;
                $key = array_key_first($normalized);
                if (is_int($key) && $key >= $nextIndex && $key < PHP_INT_MAX) {
                    $nextIndex = $key + 1;
                }
            }

            $values = $this->expandExpression($item->value, $scope);
            if ($values === null) {
                $values = $this->expandType($scope->getType($item->value));
            } else {
                $specialized = true;
            }

            $expanded = [];
            foreach ($results as $result) {
                foreach ($values as $value) {
                    $copy = $result;
                    $copy[$key] = $value;
                    $expanded[] = $copy;
                }
            }

            $this->guardAlternatives($expanded);
            $results = $expanded;
        }

        return $specialized ? $results : null;
    }

    private function containsResolvableEnumExpression(Expr\Array_ $expression, Scope $scope): bool
    {
        foreach ($expression->items as $item) {
            if ($this->enumRuleExpressionResolver->resolve($item->value, $scope) !== null) {
                return true;
            }

            if (
                $item->value instanceof Expr\Array_
                && $this->containsResolvableEnumExpression($item->value, $scope)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<mixed>
     */
    private function expandType(Type $type): array
    {
        if ($type instanceof UnionType) {
            $values = [];
            foreach ($type->getTypes() as $innerType) {
                array_push($values, ...$this->expandType($innerType));
                $this->guardAlternatives($values);
            }
            return $values;
        }

        $constantArrays = $type->getConstantArrays();
        if ($constantArrays !== []) {
            $values = [];
            foreach ($constantArrays as $constantArray) {
                array_push($values, ...$this->expandConstantArray($constantArray));
                $this->guardAlternatives($values);
            }
            return $values;
        }

        if ($type->isConstantScalarValue()->yes()) {
            $values = $type->getConstantScalarValues();
            if ($values !== []) {
                return $values;
            }
        }

        return [new StaticRuleValue($type)];
    }

    /**
     * @return list<array<array-key, mixed>>
     */
    private function expandConstantArray(ConstantArrayType $type): array
    {
        $results = [[]];
        $keyTypes = $type->getKeyTypes();
        $valueTypes = $type->getValueTypes();

        foreach ($keyTypes as $index => $keyType) {
            $keys = $keyType->getConstantScalarValues();
            if (count($keys) !== 1 || (!is_int($keys[0]) && !is_string($keys[0]))) {
                throw new TooManyRuleAlternativesException();
            }
            $key = $keys[0];
            $values = $this->expandType($valueTypes[$index]);
            $expanded = [];

            foreach ($results as $result) {
                if ($type->isOptionalKey($index)) {
                    $expanded[] = $result;
                }
                foreach ($values as $value) {
                    $copy = $result;
                    $copy[$key] = $value;
                    $expanded[] = $copy;
                }
            }

            $this->guardAlternatives($expanded);
            $results = $expanded;
        }

        return $results;
    }

    private function materializeRuleValues(mixed $value): mixed
    {
        if ($value instanceof StaticRuleValue) {
            return $this->customRuleTypeResolver->resolveRule($value->getType());
        }
        if (!is_array($value)) {
            return $value;
        }

        $result = [];
        foreach ($value as $key => $innerValue) {
            $result[$key] = $this->materializeRuleValues($innerValue);
        }
        return $result;
    }

    /**
     * @param array<mixed> $alternatives
     */
    private function guardAlternatives(array $alternatives): void
    {
        if (count($alternatives) > self::MAX_ALTERNATIVES) {
            throw new TooManyRuleAlternativesException();
        }
    }

    private function createOpaqueTree(): RuleTreeNode
    {
        return RuleParser::parse(['*' => Rule::opaque()], $this->laravelVersionContext);
    }
}

/** @internal */
final class TooManyRuleAlternativesException extends \RuntimeException
{
}
