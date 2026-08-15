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

/**
 * Recovers the accepted native family from fresh fluent Date builders.
 * Formatting removes DateTimeInterface from the accepted family, while the
 * remaining predicates retain whichever date family the root established.
 * Laravel's separate rule-list and standalone parser boundaries are preserved.
 * Assigned builders, macros, and Conditionable callbacks remain opaque.
 *
 * @logion [OSD 1:2] Set the broken ivory horn above the southern gate, and sound
 *     it not for victory. Give the conqueror the silence owed to the buried; for
 *     a city may surrender its walls without teaching its children to praise the
 *     hand that cast them down.
 */
final class DateRuleExpressionResolver
{
    private const INTRODUCED = '11.40.0';
    private const ARRAY_CHAIN_PARSER_INTRODUCED = '11.41.0';
    private const STANDALONE_CHAIN_PARSER_INTRODUCED = '11.43.2';
    private const NOW_METHODS_INTRODUCED = '12.44.0';
    private const DATE_RULE_CLASS = 'Illuminate\\Validation\\Rules\\Date';
    private const RULE_FACTORY_CLASS = \Illuminate\Validation\Rule::class;

    /**
     * Public predicates present when Date was introduced. Macroable and
     * Conditionable methods are intentionally absent because they execute
     * application-defined runtime behavior.
     */
    private const PREDICATE_METHODS = [
        'after',
        'afterorequal',
        'aftertoday',
        'before',
        'beforeorequal',
        'beforetoday',
        'between',
        'betweenorequal',
        'format',
        'todayorafter',
        'todayorbefore',
    ];

    /**
     * Public predicates added with Rule::dateTime().
     */
    private const NOW_PREDICATE_METHODS = [
        'future',
        'noworfuture',
        'noworpast',
        'past',
    ];

    public function __construct(private LaravelVersionContext $laravelVersionContext)
    {
    }

    public function resolve(Expr $expression, Scope $scope, bool $insideRuleList = false): ?Rule
    {
        if (!$this->laravelVersionContext->isAtLeast(self::INTRODUCED)) {
            return null;
        }

        if ($expression instanceof Expr\CallLike && $expression->isFirstClassCallable()) {
            return null;
        }

        if ($expression instanceof Expr\StaticCall
            && $expression->class instanceof Name
            && !$expression->class->isSpecialClassName()
            && $expression->name instanceof Identifier
            && $this->resolveName($expression->class, $scope) === self::RULE_FACTORY_CLASS
        ) {
            return match ($expression->name->toLowerString()) {
                'date' => Rule::create('Date'),
                'datetime' => $this->laravelVersionContext->isAtLeast(self::NOW_METHODS_INTRODUCED)
                    ? Rule::create('DateFormat', ['Y-m-d H:i:s'])
                    : null,
                default => null,
            };
        }

        if ($expression instanceof Expr\New_
            && $expression->class instanceof Name
            && !$expression->class->isSpecialClassName()
            && $this->resolveName($expression->class, $scope) === self::DATE_RULE_CLASS
        ) {
            return Rule::create('Date');
        }

        if (!$expression instanceof Expr\MethodCall
            || !$expression->name instanceof Identifier
        ) {
            return null;
        }

        if (!$this->laravelVersionContext->isAtLeast(
            $insideRuleList
                ? self::ARRAY_CHAIN_PARSER_INTRODUCED
                : self::STANDALONE_CHAIN_PARSER_INTRODUCED
        )) {
            return null;
        }

        $rule = $this->resolve($expression->var, $scope, $insideRuleList);
        if ($rule === null) {
            return null;
        }

        $methodName = $expression->name->toLowerString();
        if (in_array($methodName, self::PREDICATE_METHODS, true)) {
            return $methodName === 'format'
                ? Rule::create('DateFormat', $this->resolveFormatParameters($expression, $scope))
                : $rule;
        }

        if ($this->laravelVersionContext->isAtLeast(self::NOW_METHODS_INTRODUCED)
            && in_array($methodName, self::NOW_PREDICATE_METHODS, true)
        ) {
            return $rule;
        }

        return null;
    }

    /** @return list<string> */
    private function resolveFormatParameters(Expr\MethodCall $expression, Scope $scope): array
    {
        foreach ($expression->getArgs() as $argument) {
            if ($argument->unpack) {
                return [];
            }

            if ($argument->name !== null && $argument->name->toLowerString() !== 'format') {
                continue;
            }

            $values = $scope->getType($argument->value)->getConstantScalarValues();
            return count($values) === 1 && is_string($values[0])
                ? [$values[0]]
                : [];
        }

        return [];
    }

    private function resolveName(Name $name, Scope $scope): string
    {
        $resolvedName = $name->getAttribute('resolvedName');
        return $resolvedName instanceof Name
            ? $resolvedName->toString()
            : $scope->resolveName($name);
    }
}
