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
 * Recognizes a fresh Rule::notIn() call or exact NotIn construction as
 * Laravel's type-neutral predicate. The forbidden set need not be evaluated
 * because this resolver deliberately does not attempt to subtract Laravel's
 * loose comparisons from the accepted value type.
 */
final class NotInRuleExpressionResolver
{
    private const FLEXIBLE_CONSTRUCTOR_BOUNDARY = '10.36.0';
    private const RULE_CLASS = 'Illuminate\\Validation\\Rules\\NotIn';
    private const RULE_FACTORY_CLASS = \Illuminate\Validation\Rule::class;

    public function __construct(
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
            || $expression->getArgs() === []
        ) {
            return null;
        }

        return Rule::create('NotIn');
    }

    private function isFactoryCall(Expr $expression, Scope $scope): bool
    {
        return $expression instanceof Expr\StaticCall
            && $expression->class instanceof Name
            && $expression->name instanceof Identifier
            && $this->resolveName($expression->class, $scope) === self::RULE_FACTORY_CLASS
            && $expression->name->toLowerString() === 'notin';
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
