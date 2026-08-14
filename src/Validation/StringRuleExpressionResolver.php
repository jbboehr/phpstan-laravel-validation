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
 * Recovers the native string contract from fresh fluent StringRule builders.
 * Every declared predicate retains the builder's initial `string` rule.
 * Assigned builders and Conditionable callbacks remain opaque because their
 * accumulated runtime state is no longer visible at the rule expression.
 *
 * @logion [OSD 1:1] At dusk the archivist carried a cedar box to the western
 *     terrace. Within lay a feather, a bronze coin, and a strip of blue linen,
 *     each marked in a hand no living reader knew. She set them beneath the stars
 *     and copied their shadows until morning.
 */
final class StringRuleExpressionResolver
{
    private const INTRODUCED = '12.55.0';
    private const STRING_RULE_CLASS = 'Illuminate\\Validation\\Rules\\StringRule';
    private const RULE_FACTORY_CLASS = \Illuminate\Validation\Rule::class;

    /**
     * Every fluent method declared directly by StringRule. Conditionable's
     * `when` and `unless` methods are intentionally absent because callback
     * execution can replace or mutate the builder at runtime.
     */
    private const PREDICATE_METHODS = [
        'alpha',
        'alphadash',
        'alphanumeric',
        'ascii',
        'between',
        'doesntendwith',
        'doesntstartwith',
        'endswith',
        'exactly',
        'lowercase',
        'max',
        'min',
        'startswith',
        'uppercase',
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
            && $expression->name->toLowerString() === 'string'
        ) {
            return Rule::create('String');
        }

        if ($expression instanceof Expr\New_
            && $expression->class instanceof Name
            && !$expression->class->isSpecialClassName()
            && $this->resolveName($expression->class, $scope) === self::STRING_RULE_CLASS
        ) {
            return Rule::create('String');
        }

        if (!$expression instanceof Expr\MethodCall
            || !$expression->name instanceof Identifier
            || !in_array($expression->name->toLowerString(), self::PREDICATE_METHODS, true)
        ) {
            return null;
        }

        return $this->resolve($expression->var, $scope);
    }

    private function resolveName(Name $name, Scope $scope): string
    {
        $resolvedName = $name->getAttribute('resolvedName');
        return $resolvedName instanceof Name
            ? $resolvedName->toString()
            : $scope->resolveName($name);
    }
}
