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

use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

final class CustomRuleTypeResolver
{
    private const PREDICATE_TYPES = [
        \Closure::class,
        \Illuminate\Contracts\Validation\ImplicitRule::class,
        \Illuminate\Contracts\Validation\InvokableRule::class,
        \Illuminate\Contracts\Validation\Rule::class,
        \Illuminate\Contracts\Validation\ValidationRule::class,
    ];

    public function resolveRule(Type $type): Rule
    {
        if (!$this->isPredicateType($type)) {
            return Rule::opaque();
        }

        return Rule::custom(new MixedType());
    }

    public function isPredicateType(Type $type): bool
    {
        foreach (self::PREDICATE_TYPES as $predicateType) {
            if ((new ObjectType($predicateType))->isSuperTypeOf($type)->yes()) {
                return true;
            }
        }

        return false;
    }
}
