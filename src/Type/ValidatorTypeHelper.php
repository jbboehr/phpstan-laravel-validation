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

namespace jbboehr\PhpstanLaravelValidation\Type;

use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;

final class ValidatorTypeHelper
{
    public function __construct(
        private TypeResolver $typeResolver
    ) {
    }

    public function resolveValidatedType(Type\Type $type): ?Type\Type
    {
        if ($type instanceof ValidatorType) {
            return $this->typeResolver->evaluate($type->getValidatorRules());
        }

        if (!$type instanceof UnionType) {
            return null;
        }

        $validatedTypes = [];
        foreach ($type->getTypes() as $innerType) {
            $validatedType = $this->resolveValidatedType($innerType);
            if ($validatedType === null) {
                return null;
            }

            $validatedTypes[] = $validatedType;
        }

        return TypeCombinator::union(...$validatedTypes);
    }
}
