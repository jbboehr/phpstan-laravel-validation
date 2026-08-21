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

use function is_finite;
use function is_float;
use function is_int;
use function is_string;
use function preg_match;

/**
 * The lexical grammar `Parse::float()` accepts.
 *
 * Accepted:
 *   - any finite `float`, unchanged;
 *   - any `int`, widened to `float`;
 *   - a string containing an optional `-`, a canonical integer part, and an
 *     optional decimal fraction, provided conversion produces a finite float.
 *
 * Scientific notation, leading zeroes, a leading `+`, whitespace, locale
 * separators, `INF`, and `NAN` are deliberately outside the grammar.
 * Conversion to PHP's native float may lose precision or underflow to zero
 * for either integer or string input. Overflow to infinity is rejected rather
 * than admitted under a false finite-float contract. Negative zero is
 * preserved.
 *
 * @internal
 */
final class FloatGrammar
{
    /** Canonical ASCII decimal syntax, anchored at both ends. */
    public const PATTERN = '/^-?(0|[1-9][0-9]*)(\.[0-9]+)?\z/';

    /**
     * Parse a value, or return null when it has no canonical finite-float
     * representation.
     */
    public static function parse(mixed $value): ?float
    {
        if (is_int($value)) {
            return (float) $value;
        }

        if (is_float($value)) {
            return is_finite($value) ? $value : null;
        }

        if (!is_string($value) || preg_match(self::PATTERN, $value) !== 1) {
            return null;
        }

        $parsed = (float) $value;

        return is_finite($parsed) ? $parsed : null;
    }
}
