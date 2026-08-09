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

namespace jbboehr\PhpstanLaravelValidation\Test\Support;

use PHPStan\Type;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantFloatType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;

final class LaravelValueType
{
    public static function fromValue(mixed $value): Type\Type
    {
        return match (gettype($value)) {
            'boolean' => new ConstantBooleanType($value),
            'integer' => new ConstantIntegerType($value),
            'double' => new ConstantFloatType($value),
            'string' => new ConstantStringType($value),
            'array' => self::fromArray($value),
            'object' => new ObjectType(get_class($value)),
            'NULL' => new NullType(),
            'resource' => new Type\ResourceType(),
            default => new Type\MixedType(),
        };
    }

    /**
     * @param array<mixed, mixed> $value
     * @throws \PHPStan\ShouldNotHappenException
     */
    private static function fromArray(array $value): Type\Type
    {
        $array = ConstantArrayTypeBuilder::createEmpty();
        foreach ($value as $key => $item) {
            $array->setOffsetValueType(
                self::fromValue($key),
                self::fromValue($item),
                false,
            );
        }

        return $array->getArray();
    }
}
