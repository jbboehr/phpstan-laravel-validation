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

use jbboehr\Rensei\Internal\FloatGrammar;
use jbboehr\Rensei\ParseFailure;

/**
 * Parses canonical decimal syntax into a finite float.
 *
 * Pair with Laravel's `numeric` or `decimal` rule when size comparisons
 * matter. Ordinary rules run before parser write-back, and Laravel chooses
 * numeric size semantics from those named predicates rather than from this
 * rule's produced type.
 *
 * @extends BaseParsingRule<float>
 */
final class FloatRule extends BaseParsingRule
{
    public function parse(mixed $value): float
    {
        $parsed = FloatGrammar::parse($value);

        if ($parsed === null) {
            throw new ParseFailure();
        }

        return $parsed;
    }

    protected function message(): string
    {
        return 'The :attribute field must be a finite decimal number.';
    }
}
