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

namespace jbboehr\Rensei\Internal;

use function filter_var;
use function is_int;
use function is_string;
use function preg_match;

use const FILTER_VALIDATE_INT;

/**
 * The lexical grammar `Parse::integer()` accepts.
 *
 * This is deliberately narrower than Laravel's `integer` rule, which is
 * `filter_var($value, FILTER_VALIDATE_INT) !== false` and therefore accepts
 * `' 42'`, `'42 '`, `'+42'`, the float `42.0`, and `true`. Whitespace
 * tolerance, sign decoration, and boolean promotion are not integer syntax,
 * and a parser that silently accepted them would produce values its callers
 * did not write.
 *
 * Accepted:
 *   - any `int`, unchanged;
 *   - a string of optional `-` followed by `0`, or a non-zero leading digit
 *     and further digits, that also survives `FILTER_VALIDATE_INT`.
 *
 * Rejected, with the reason each is excluded:
 *   `'042'`, `'00'`   leading zeroes are not canonical
 *   `'+42'`           sign decoration
 *   `' 42'`, `"42\n"` surrounding whitespace; filter_var would trim it
 *   `'42.0'`, `'1.5'` a decimal point is not integer syntax
 *   `'1e3'`           scientific notation
 *   `'0x1A'`, `'4_2'` alternate bases and separators
 *   `42.0`, `42.9`    floats; an integral float is still a schema mismatch
 *   `true`, `false`   booleans are not numbers
 *   `''`, `'abc'`     no integer present
 *   overflow          values beyond the platform integer width are rejected
 *                     rather than saturated at PHP_INT_MAX
 *
 * Platform integer width is therefore observable: a 32-bit build rejects
 * values a 64-bit build accepts. That is documented rather than hidden.
 *
 * @internal
 */
final class IntegerGrammar
{
    /**
     * Canonical decimal integer syntax, anchored at both ends.
     *
     * The trailing anchor is `\z`, not `$`. PCRE's `$` also matches before a
     * final newline, so `$` would accept "42\n" -- exactly the trailing
     * whitespace this grammar exists to reject.
     */
    public const PATTERN = '/^-?(0|[1-9][0-9]*)\z/';

    /**
     * Parse a value, or return null when it is not canonical integer syntax.
     *
     * Passing an `int` through unchanged is what makes the parser a fixed
     * point, which Laravel requires because it invokes `passes()` more than
     * once on ordinary paths.
     */
    public static function parse(mixed $value): ?int
    {
        if (is_int($value)) {
            return $value;
        }

        if (!is_string($value)) {
            return null;
        }

        if (preg_match(self::PATTERN, $value) !== 1) {
            return null;
        }

        // The pattern admits arbitrarily long digit strings. filter_var
        // rejects those that exceed the platform integer width.
        $parsed = filter_var($value, FILTER_VALIDATE_INT);

        return is_int($parsed) ? $parsed : null;
    }
}
