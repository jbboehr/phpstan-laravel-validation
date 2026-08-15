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
    /**
     * Exact static factories whose arguments do not affect the inferred value
     * type. Stateful builders remain in their dedicated resolvers.
     *
     * @var array<string, array<string, array{rule: string, since?: string, allowSpecialClassName?: true}>>
     */
    private const SIMPLE_STATIC_RULE_EXPRESSIONS = [
        \Illuminate\Validation\Rule::class => [
            'contains' => ['rule' => 'Contains', 'since' => '12.16.0'],
            'doesntcontain' => ['rule' => 'DoesntContain', 'since' => '12.22.0'],
            'exists' => ['rule' => 'Exists', 'allowSpecialClassName' => true],
            'file' => ['rule' => 'File'],
            'imagefile' => ['rule' => 'Image'],
            'unique' => ['rule' => 'Unique', 'allowSpecialClassName' => true],
        ],
        \Illuminate\Validation\Rules\File::class => [
            'image' => ['rule' => 'Image'],
            'types' => ['rule' => 'File'],
        ],
        \Illuminate\Validation\Rules\ImageFile::class => [
            'image' => ['rule' => 'Image'],
            'types' => ['rule' => 'Image'],
        ],
    ];

    /**
     * Exact built-in rule objects whose constructor arguments do not affect
     * the inferred value type.
     *
     * @var array<string, array{rule: string, since?: string, allowSpecialClassName?: true}>
     */
    private const SIMPLE_NEW_RULE_EXPRESSIONS = [
        'Illuminate\\Validation\\Rules\\Contains' => ['rule' => 'Contains', 'since' => '12.16.0'],
        'Illuminate\\Validation\\Rules\\DoesntContain' => ['rule' => 'DoesntContain', 'since' => '12.22.0'],
        \Illuminate\Validation\Rules\Exists::class => ['rule' => 'Exists', 'allowSpecialClassName' => true],
        \Illuminate\Validation\Rules\File::class => ['rule' => 'File', 'allowSpecialClassName' => true],
        \Illuminate\Validation\Rules\ImageFile::class => ['rule' => 'Image', 'allowSpecialClassName' => true],
        \Illuminate\Validation\Rules\Unique::class => ['rule' => 'Unique', 'allowSpecialClassName' => true],
    ];

    private const MAX_ALTERNATIVES = 128;
    private const RULE_LIST_EXPRESSION_DEPTH = 2;

    public function __construct(
        private UnsafeConstExprEvaluator $constExprEvaluator,
        private CustomRuleTypeResolver $customRuleTypeResolver,
        private EnumRuleExpressionResolver $enumRuleExpressionResolver,
        private InRuleExpressionResolver $inRuleExpressionResolver,
        private NotInRuleExpressionResolver $notInRuleExpressionResolver,
        private ArrayRuleExpressionResolver $arrayRuleExpressionResolver,
        private ArrayKeysRuleExpressionResolver $arrayKeysRuleExpressionResolver,
        private DateRuleExpressionResolver $dateRuleExpressionResolver,
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
            $values = $this->expandExpression($expression, $scope, 0)
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
    private function expandExpression(Expr $expression, Scope $scope, int $depth): ?array
    {
        $rule = $this->resolveBuiltInRuleExpression(
            $expression,
            $scope,
            $depth >= self::RULE_LIST_EXPRESSION_DEPTH
        );
        if ($rule !== null) {
            return [$rule];
        }

        if (!$expression instanceof Expr\Array_) {
            return null;
        }

        if (!$this->containsResolvableBuiltInRuleExpression($expression, $scope, $depth)) {
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

            $values = $this->expandExpression($item->value, $scope, $depth + 1);
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

    private function resolveBuiltInRuleExpression(
        Expr $expression,
        Scope $scope,
        bool $insideRuleList
    ): ?Rule {
        if ($expression instanceof Expr\CallLike && $expression->isFirstClassCallable()) {
            return null;
        }

        return $this->enumRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->inRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->notInRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->arrayRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->arrayKeysRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->dateRuleExpressionResolver->resolve($expression, $scope, $insideRuleList)
            ?? $this->numericRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->stringRuleExpressionResolver->resolve($expression, $scope)
            ?? $this->resolveSimpleRuleExpression($expression, $scope)
            ?? $this->resolveFileRuleExpression($expression, $scope)
            ?? $this->resolveDatabaseRuleExpression($expression, $scope);
    }

    private function resolveSimpleRuleExpression(Expr $expression, Scope $scope): ?Rule
    {
        if (!$this->laravelVersionContext->isSupported()
            || ($expression instanceof Expr\CallLike && $expression->isFirstClassCallable())
        ) {
            return null;
        }

        if ($expression instanceof Expr\StaticCall
            && $expression->class instanceof Name
            && $expression->name instanceof Identifier
        ) {
            $specification = self::SIMPLE_STATIC_RULE_EXPRESSIONS[
                $this->resolveName($expression->class, $scope)
            ][$expression->name->toLowerString()] ?? null;

            return $this->materializeSimpleRule(
                $specification,
                $expression->class->isSpecialClassName()
            );
        }

        if (!$expression instanceof Expr\New_
            || !$expression->class instanceof Name
        ) {
            return null;
        }

        return $this->materializeSimpleRule(
            self::SIMPLE_NEW_RULE_EXPRESSIONS[$this->resolveName($expression->class, $scope)] ?? null,
            $expression->class->isSpecialClassName()
        );
    }

    /**
     * @param array{rule: string, since?: string, allowSpecialClassName?: true}|null $specification
     */
    private function materializeSimpleRule(?array $specification, bool $specialClassName): ?Rule
    {
        if ($specification === null
            || ($specialClassName && !($specification['allowSpecialClassName'] ?? false))
            || (isset($specification['since'])
                && !$this->laravelVersionContext->isAtLeast($specification['since']))
        ) {
            return null;
        }

        return Rule::create($specification['rule']);
    }

    private function resolveFileRuleExpression(Expr $expression, Scope $scope): ?Rule
    {
        if (!$expression instanceof Expr\MethodCall
            || !$expression->name instanceof Identifier
        ) {
            return null;
        }

        $rule = $this->resolveSimpleRuleExpression($expression->var, $scope)
            ?? $this->resolveFileRuleExpression($expression->var, $scope);
        if ($rule === null || !in_array($rule->getRuleName(), ['File', 'Image'], true)) {
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
        if (!$expression instanceof Expr\MethodCall
            || !$expression->name instanceof Identifier
        ) {
            return null;
        }

        $rule = $this->resolveSimpleRuleExpression($expression->var, $scope)
            ?? $this->resolveDatabaseRuleExpression($expression->var, $scope);
        if ($rule === null || !in_array($rule->getRuleName(), ['Exists', 'Unique'], true)) {
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

    private function containsResolvableBuiltInRuleExpression(
        Expr\Array_ $expression,
        Scope $scope,
        int $depth
    ): bool {
        foreach ($expression->items as $item) {
            if ($this->resolveBuiltInRuleExpression(
                $item->value,
                $scope,
                $depth + 1 >= self::RULE_LIST_EXPRESSION_DEPTH
            ) !== null) {
                return true;
            }

            if (
                $item->value instanceof Expr\Array_
                && $this->containsResolvableBuiltInRuleExpression($item->value, $scope, $depth + 1)
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
