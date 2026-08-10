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
 * Recovers bare versus allowed-key semantics from a statically visible
 * Rule::array() call. Dynamic key expressions remain opaque because they can
 * change both the accepted array shape and nested-output projection.
 */
final class ArrayRuleExpressionResolver
{
    private const INTRODUCED = '11.7.0';
    private const RULE_FACTORY_CLASS = \Illuminate\Validation\Rule::class;

    public function __construct(
        private RuleParameterExpressionResolver $parameterExpressionResolver,
        private LaravelVersionContext $laravelVersionContext
    ) {
    }

    public function resolve(Expr $expression, Scope $scope): ?Rule
    {
        if (
            !$this->laravelVersionContext->isAtLeast(self::INTRODUCED)
            || !$expression instanceof Expr\StaticCall
            || !$expression->class instanceof Name
            || !$expression->name instanceof Identifier
            || $this->resolveName($expression->class, $scope) !== self::RULE_FACTORY_CLASS
            || $expression->name->toLowerString() !== 'array'
        ) {
            return null;
        }

        $parameters = $this->parameterExpressionResolver->resolve(
            array_values($expression->getArgs()),
            $scope,
            self::INTRODUCED
        );
        if ($parameters === null) {
            return null;
        }

        // ArrayRule joins keys without quoting, after which Laravel parses the
        // Stringable rule as CSV. Reproduce that lossy round trip: for example,
        // one builder key `a,b` becomes the two validator parameters `a` and
        // `b`. The empty argument list is the only form that stays a bare rule.
        return $parameters === []
            ? Rule::create(Rule::RULE_ARRAY)
            : RuleParser::parseStringRule('array:' . implode(',', $parameters));
    }

    private function resolveName(Name $name, Scope $scope): string
    {
        $resolvedName = $name->getAttribute('resolvedName');
        return $resolvedName instanceof Name
            ? $resolvedName->toString()
            : $scope->resolveName($name);
    }
}
