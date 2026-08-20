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

namespace jbboehr\Rensei\Rules;

use BackedEnum;
use InvalidArgumentException;
use jbboehr\Rensei\ParseFailure;
use TypeError;

use function enum_exists;
use function is_a;
use function is_int;
use function is_string;
use function sprintf;

/**
 * Parses a backed enum's native representation into its case object.
 *
 * Unlike Laravel's `Rule::enum()`, this rule does not let PHP coerce values
 * before calling `tryFrom()`. An int-backed enum accepts only ints, and a
 * string-backed enum accepts only strings. An existing case of the configured
 * enum passes through unchanged.
 *
 * @template T of BackedEnum
 *
 * @extends BaseParsingRule<T>
 */
final class EnumRule extends BaseParsingRule
{
    /** @var class-string<T> */
    private readonly string $enum;

    /**
     * @param class-string<T> $enum
     */
    public function __construct(string $enum)
    {
        if (!self::isBackedEnumClass($enum)) {
            throw new InvalidArgumentException(sprintf(
                '%s is not a backed enum.',
                $enum
            ));
        }

        $this->enum = $enum;
    }

    /** @return T */
    public function parse(mixed $value): BackedEnum
    {
        $enum = $this->enum;

        if ($value instanceof $enum) {
            return $value;
        }

        if (!is_int($value) && !is_string($value)) {
            throw new ParseFailure();
        }

        // This file declares strict_types, so tryFrom() rejects a string for
        // an int-backed enum and an int for a string-backed enum. Laravel's
        // own rule calls it from a weakly typed file and therefore permits
        // scalar coercion before preserving the original representation.
        try {
            $case = $enum::tryFrom($value);
        } catch (TypeError) {
            throw new ParseFailure();
        }
        if ($case === null) {
            throw new ParseFailure();
        }

        return $case;
    }

    protected function message(): string
    {
        return 'The selected :attribute is invalid.';
    }

    private static function isBackedEnumClass(string $enum): bool
    {
        return enum_exists($enum) && is_a($enum, BackedEnum::class, true);
    }
}
