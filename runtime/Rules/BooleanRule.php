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

use jbboehr\Rensei\ParseFailure;

/**
 * Parses Laravel's canonical boolean input set into a bool.
 *
 * The accepted inputs deliberately match Laravel's `boolean` predicate, but
 * the output does not preserve their representation. PHP truthiness is not a
 * boolean grammar: in particular, `(bool) 'false'` is true.
 *
 * @extends BaseParsingRule<bool>
 */
final class BooleanRule extends BaseParsingRule
{
    public function parse(mixed $value): bool
    {
        if ($value === true || $value === 1 || $value === '1') {
            return true;
        }

        if ($value === false || $value === 0 || $value === '0') {
            return false;
        }

        throw new ParseFailure();
    }

    protected function message(): string
    {
        return 'The :attribute field must be true or false.';
    }
}
