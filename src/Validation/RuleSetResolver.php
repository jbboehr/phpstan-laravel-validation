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
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
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
        private InRuleExpressionResolver $inRuleExpressionResolver,
        private NotInRuleExpressionResolver $notInRuleExpressionResolver,
        private ArrayRuleExpressionResolver $arrayRuleExpressionResolver,
        private ArrayKeysRuleExpressionResolver $arrayKeysRuleExpressionResolver,
        private NumericRuleExpressionResolver $numericRuleExpressionResolver,
        private StringRuleExpressionResolver $stringRuleExpressionResolver,
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
        $rule = $this->resolveBuiltInRuleExpression($expression, $scope);
        if ($rule !== null) {
            return [$rule];
        }

        if (!$expression instanceof Expr\Array_) {
            return null;
        }

        if (!$this->containsResolvableBuiltInRuleExpression($expression, $scope)) {
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

    private function resolveBuiltInRuleExpression(Expr $expression, Scope $scope): ?Rule
    {
        if ($expression instanceof Expr\CallLike && $expression->isFirstClassCallable()) {
            return null;
        }

        return $this->enumRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->inRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->notInRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->arrayRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->arrayKeysRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->numericRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->stringRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->resolveFileRuleExpression($expression, $scope)
            ?? $this->resolveDatabaseRuleExpression($expression, $scope);
    }

    private function resolveFileRuleExpression(Expr $expression, Scope $scope): ?Rule
    {
        if (!$this->laravelVersionContext->isSupported()) {
            return null;
        }

        if ($expression instanceof Expr\StaticCall
            && $expression->class instanceof Name
            && $expression->name instanceof Identifier
        ) {
            if ($expression->class->isSpecialClassName()) {
                return null;
            }

            $className = $this->resolveName($expression->class, $scope);
            $methodName = $expression->name->toLowerString();

            if ($className === \Illuminate\Validation\Rule::class) {
                return match ($methodName) {
                    'file' => Rule::create('File'),
                    'imagefile' => Rule::create('Image'),
                    default => null,
                };
            }

            if (in_array($className, [
                \Illuminate\Validation\Rules\File::class,
                \Illuminate\Validation\Rules\ImageFile::class,
            ], true)) {
                return match ($methodName) {
                    'image' => Rule::create('Image'),
                    'types' => Rule::create(
                        $className === \Illuminate\Validation\Rules\ImageFile::class ? 'Image' : 'File'
                    ),
                    default => null,
                };
            }
        }

        if ($expression instanceof Expr\New_
            && $expression->class instanceof Name
        ) {
            return match ($this->resolveName($expression->class, $scope)) {
                \Illuminate\Validation\Rules\File::class => Rule::create('File'),
                \Illuminate\Validation\Rules\ImageFile::class => Rule::create('Image'),
                default => null,
            };
        }

        if (!$expression instanceof Expr\MethodCall
            || !$expression->name instanceof Identifier
        ) {
            return null;
        }

        $rule = $this->resolveFileRuleExpression($expression->var, $scope);
        if ($rule === null) {
            return null;
        }

        $methodName = $expression->name->toLowerString();
        if (in_array($methodName, ['size', 'between', 'min', 'max', 'rules'], true)
            || ($methodName === 'extensions' && $this->laravelVersionContext->isAtLeast('10.34.0'))
            || ($methodName === 'encoding' && $this->laravelVersionContext->isAtLeast('12.40.0'))
            || ($methodName === 'dimensions' && $rule->getRuleName() === 'Image')
        ) {
            return $rule;
        }

        return null;
    }

    private function resolveDatabaseRuleExpression(Expr $expression, Scope $scope): ?Rule
    {
        if (!$this->laravelVersionContext->isSupported()) {
            return null;
        }

        if ($expression instanceof Expr\StaticCall
            && $expression->class instanceof Name
            && $expression->name instanceof Identifier
            && $this->resolveName($expression->class, $scope) === \Illuminate\Validation\Rule::class
        ) {
            return match ($expression->name->toLowerString()) {
                'exists' => Rule::create('Exists'),
                'unique' => Rule::create('Unique'),
                default => null,
            };
        }

        if ($expression instanceof Expr\New_
            && $expression->class instanceof Name
        ) {
            return match ($this->resolveName($expression->class, $scope)) {
                \Illuminate\Validation\Rules\Exists::class => Rule::create('Exists'),
                \Illuminate\Validation\Rules\Unique::class => Rule::create('Unique'),
                default => null,
            };
        }

        if (!$expression instanceof Expr\MethodCall
            || !$expression->name instanceof Identifier
        ) {
            return null;
        }

        $rule = $this->resolveDatabaseRuleExpression($expression->var, $scope);
        if ($rule === null) {
            return null;
        }

        $methodName = $expression->name->toLowerString();
        $sharedMethods = [
            'where',
            'wherenot',
            'wherenull',
            'wherenotnull',
            'wherein',
            'wherenotin',
            'withouttrashed',
            'onlytrashed',
            'using',
        ];
        if (in_array($methodName, $sharedMethods, true)
            || ($rule->getRuleName() === 'Unique' && in_array($methodName, ['ignore', 'ignoremodel'], true))
        ) {
            return $rule;
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

    private function containsResolvableBuiltInRuleExpression(Expr\Array_ $expression, Scope $scope): bool
    {
        foreach ($expression->items as $item) {
            if ($this->resolveBuiltInRuleExpression($item->value, $scope) !== null) {
                return true;
            }

            if (
                $item->value instanceof Expr\Array_
                && $this->containsResolvableBuiltInRuleExpression($item->value, $scope)
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
