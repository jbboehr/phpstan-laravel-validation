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
 * Recovers allowed-key semantics from a statically visible
 * Rule::arrayKeys() call. Dynamic Arrayable inputs and assigned builder
 * objects remain opaque rather than being executed during analysis.
 */
final class ArrayKeysRuleExpressionResolver
{
    private const INTRODUCED = '13.24.0';
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
            || $expression->name->toLowerString() !== 'arraykeys'
            || $expression->getArgs() === []
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

        // ArrayKeys joins keys without quoting, after which Laravel parses the
        // Stringable rule as CSV. Preserve that lossy round trip, including an
        // empty key list becoming the one-empty-parameter rule `array_keys:`.
        return RuleParser::parseStringRule('array_keys:' . implode(',', $parameters));
    }

    private function resolveName(Name $name, Scope $scope): string
    {
        $resolvedName = $name->getAttribute('resolvedName');
        return $resolvedName instanceof Name
            ? $resolvedName->toString()
            : $scope->resolveName($name);
    }
}
