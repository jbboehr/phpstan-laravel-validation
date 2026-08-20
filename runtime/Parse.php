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
 * and `$request->input()` return what was sent, while successful
 * `validated()` and `safe()` calls return parsed values. `valid()` on failed
 * validation is not parsed output: Laravel may include raw attributes whose
 * parsing rules were skipped by an earlier failure.
 *
 * A callback registered with `Validator::after()` before validation runs
 * before parsing-rule write-back and observes the original values. Read
 * parsed output only after validation completes. In a FormRequest,
 * `passedValidation()` is the corresponding post-write-back hook.
 *
 * A validator that completes parser write-back cannot be validated again.
 * Reuse fails validation rather than guessing whether an equal value is old
 * parser output or new data supplied through `setData()`. Arbitrary custom
 * rules and runtime validation extensions can mutate data outside the
 * parser's finalization point and are not supported alongside parsing rules.
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
     * The concrete class is the declared return type, not `ParsingRule<int>`.
     * Static analysis reads implicitness from a real class and declines an
     * abstract one, so naming the interface here would cost the factory its
     * own inference.
     */
    public static function integer(): IntegerRule
    {
        return new IntegerRule();
    }
}
