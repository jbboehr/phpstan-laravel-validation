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

use jbboehr\Rensei\Internal\IntegerGrammar;
use jbboehr\Rensei\ParseFailure;

/**
 * Parses canonical decimal integer syntax into an int.
 *
 * Pair with Laravel's `integer` or `numeric` rule when size comparisons
 * matter. Laravel decides whether `min`, `max`, `between`, and `size` compare
 * numerically or by string length from the presence of one of those rule
 * names, and a rule object cannot register as one, so
 * `[Parse::integer(), 'min:10']` compares the raw string's length.
 * `['integer', Parse::integer(), 'min:10']` is the idiomatic form.
 *
 * @extends BaseParsingRule<int>
 */
final class IntegerRule extends BaseParsingRule
{
    public function parse(mixed $value): int
    {
        $parsed = IntegerGrammar::parse($value);

        if ($parsed === null) {
            throw new ParseFailure();
        }

        return $parsed;
    }

    protected function message(): string
    {
        return 'The :attribute field must be an integer.';
    }
}
