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

namespace jbboehr\Rensei;

use jbboehr\Rensei\Rules\IntegerRule;

/**
 * Opt-in parsing rules for Laravel validation.
 *
 * Laravel's built-in rules are predicates: `integer` establishes that a value
 * looks like an integer and leaves the original string in the validated
 * output. `Parse::integer()` instead produces an int, or fails validation.
 *
 *     'age' => ['required', 'integer'],        // validated() holds "42"
 *     'age' => ['required', Parse::integer()], // validated() holds 42
 *
 * Only the validated output changes. Ordinary rules still see the original
 * representation, and the request itself is never modified: `$request->all()`
 * and `$request->input()` return what was sent, while `validated()`, `safe()`,
 * and `valid()` return parsed values.
 *
 * Requires laravel/framework 10.7.0 or later, which introduced
 * `Validator::setValue()`.
 */
final class Parse
{
    /**
     * Parse canonical decimal integer syntax into an int.
     *
     * Accepts any int, and strings such as `'42'`, `'-42'`, and `'0'`.
     * Rejects `'042'`, `'+42'`, `' 42'`, `'42.0'`, the float `42.0`, `true`,
     * blank strings, and values beyond the platform integer width.
     *
     * @return ParsingRule<int>
     */
    public static function integer(): ParsingRule
    {
        return new IntegerRule();
    }
}
