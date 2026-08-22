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

use DateTimeZone;
use jbboehr\Rensei\ParseFailure;

use function in_array;
use function is_string;
use function timezone_identifiers_list;

/**
 * Parses a Laravel timezone identifier into a DateTimeZone.
 *
 * String input follows Laravel's default `timezone` predicate rather than the
 * broader DateTimeZone constructor, which also accepts offsets,
 * abbreviations, and backward-compatible aliases.
 *
 * @extends BaseParsingRule<DateTimeZone>
 */
final class TimezoneRule extends BaseParsingRule
{
    public function parse(mixed $value): DateTimeZone
    {
        if ($value instanceof DateTimeZone) {
            return $value;
        }

        if (!is_string($value)
            || !in_array($value, timezone_identifiers_list(), true)
        ) {
            throw new ParseFailure();
        }

        return new DateTimeZone($value);
    }

    protected function message(): string
    {
        return 'The :attribute field must be a valid timezone.';
    }
}
