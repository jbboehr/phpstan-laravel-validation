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
 * Recovers the parameter list from a statically visible Rule::in() call or
 * exact In construction. Runtime Arrayable and Stringable inputs remain
 * opaque rather than being executed during analysis.
 */
final class InRuleExpressionResolver
{
    private const FLEXIBLE_CONSTRUCTOR_BOUNDARY = '10.36.0';
    private const RULE_CLASS = 'Illuminate\\Validation\\Rules\\In';
    private const RULE_FACTORY_CLASS = \Illuminate\Validation\Rule::class;
    private const ENUM_VALUE_BOUNDARY = '10.21.1';

    public function __construct(
        private RuleParameterExpressionResolver $parameterExpressionResolver,
        private LaravelVersionContext $laravelVersionContext
    ) {
    }

    public function resolve(Expr $expression, Scope $scope): ?Rule
    {
        if (
            !$this->laravelVersionContext->isSupported()
            || !$expression instanceof Expr\CallLike
            || $expression->isFirstClassCallable()
            || (!$this->isFactoryCall($expression, $scope)
                && !$this->isExactConstruction($expression, $scope))
        ) {
            return null;
        }

        $arguments = $expression->getArgs();
        if ($arguments === []) {
            return null;
        }

        $resolution = $this->parameterExpressionResolver->resolveWithMetadata(
            array_values($arguments),
            $scope,
            self::ENUM_VALUE_BOUNDARY
        );
        if ($resolution === null) {
            return null;
        }
        $parameters = $resolution['parameters'];

        // An empty builder serializes as `in:`. Laravel's CSV parser exposes
        // that sole empty parameter as null; resolveTypeIn normalizes it back
        // to the empty string used by the loose runtime comparison.
        return Rule::inBuilder(
            $parameters === [] ? [null] : $parameters,
            $resolution['hasRuntimeFormattedFloatParameter']
        );
    }

    private function isFactoryCall(Expr $expression, Scope $scope): bool
    {
        return $expression instanceof Expr\StaticCall
            && $expression->class instanceof Name
            && $expression->name instanceof Identifier
            && $this->resolveName($expression->class, $scope) === self::RULE_FACTORY_CLASS
            && $expression->name->toLowerString() === 'in';
    }

    private function isExactConstruction(Expr $expression, Scope $scope): bool
    {
        if (
            !$expression instanceof Expr\New_
            || !$expression->class instanceof Name
            || $expression->class->isSpecialClassName()
            || $this->resolveName($expression->class, $scope) !== self::RULE_CLASS
        ) {
            return false;
        }

        if ($this->laravelVersionContext->isAtLeast(self::FLEXIBLE_CONSTRUCTOR_BOUNDARY)) {
            return true;
        }

        $arguments = array_values($expression->getArgs());

        return $arguments !== []
            && !$arguments[0]->unpack
            && $scope->getType($arguments[0]->value)->isArray()->yes();
    }

    private function resolveName(Name $name, Scope $scope): string
    {
        $resolvedName = $name->getAttribute('resolvedName');
        return $resolvedName instanceof Name
            ? $resolvedName->toString()
            : $scope->resolveName($name);
    }
}
