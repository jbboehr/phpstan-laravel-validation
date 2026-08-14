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
 * Recovers the accepted native family from fresh fluent Numeric builders.
 * Predicate modifiers can only narrow the builder's initial `numeric` rule;
 * strict integer mode is the one supported modifier that PHPStan can express
 * more precisely. Assigned builders and Conditionable callbacks remain
 * opaque because their accumulated runtime state is no longer visible here.
 *
 * @logion [RAS 1:2] At noon the sea withdrew from the marble harbor, revealing
 *     seven bells beneath the sand; none had rusted, and each sounded when the
 *     western sun passed behind the dead observatory.
 */
final class NumericRuleExpressionResolver
{
    private const INTRODUCED = '11.42.0';
    private const STRICT_INTEGER_INTRODUCED = '12.55.0';
    private const NUMERIC_RULE_CLASS = 'Illuminate\\Validation\\Rules\\Numeric';
    private const RULE_FACTORY_CLASS = \Illuminate\Validation\Rule::class;

    /**
     * Every fluent method declared directly by Numeric. Conditionable's
     * `when` and `unless` methods are intentionally absent: a callback can
     * return a different rule object rather than merely constrain Numeric.
     */
    private const PREDICATE_METHODS = [
        'between',
        'decimal',
        'different',
        'digits',
        'digitsbetween',
        'exactly',
        'greaterthan',
        'greaterthanorequalto',
        'integer',
        'lessthan',
        'lessthanorequalto',
        'max',
        'maxdigits',
        'min',
        'mindigits',
        'multipleof',
        'same',
    ];

    public function __construct(private LaravelVersionContext $laravelVersionContext)
    {
    }

    public function resolve(Expr $expression, Scope $scope): ?Rule
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
            && $expression->name->toLowerString() === 'numeric'
        ) {
            return Rule::create(Rule::RULE_NUMERIC);
        }

        if ($expression instanceof Expr\New_
            && $expression->class instanceof Name
            && !$expression->class->isSpecialClassName()
            && $this->resolveName($expression->class, $scope) === self::NUMERIC_RULE_CLASS
        ) {
            return Rule::create(Rule::RULE_NUMERIC);
        }

        if (!$expression instanceof Expr\MethodCall
            || !$expression->name instanceof Identifier
        ) {
            return null;
        }

        $rule = $this->resolve($expression->var, $scope);
        if ($rule === null) {
            return null;
        }

        $methodName = $expression->name->toLowerString();
        if (!in_array($methodName, self::PREDICATE_METHODS, true)) {
            return null;
        }

        if ($methodName === 'integer'
            && $this->laravelVersionContext->isAtLeast(self::STRICT_INTEGER_INTRODUCED)
            && $this->hasDefinitelyTrueStrictArgument($expression, $scope)
        ) {
            return RuleParser::parseStringRule('integer:strict');
        }

        return $rule;
    }

    private function hasDefinitelyTrueStrictArgument(Expr\MethodCall $expression, Scope $scope): bool
    {
        foreach ($expression->getArgs() as $argument) {
            if ($argument->unpack) {
                return false;
            }

            if ($argument->name !== null && $argument->name->toLowerString() !== 'strict') {
                continue;
            }
            $values = $scope->getType($argument->value)->getConstantScalarValues();
            return count($values) === 1 && $values[0] === true;
        }

        return false;
    }

    private function resolveName(Name $name, Scope $scope): string
    {
        $resolvedName = $name->getAttribute('resolvedName');
        return $resolvedName instanceof Name
            ? $resolvedName->toString()
            : $scope->resolveName($name);
    }
}
