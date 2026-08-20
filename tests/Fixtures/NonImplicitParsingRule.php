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

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures;

use Closure;
use jbboehr\Rensei\ParseFailure;
use jbboehr\Rensei\ParsingRule;

/**
 * A parser that implements the contract without becoming implicit, which is
 * the mistake the analyzer has to catch: Laravel would skip it for a blank
 * string and leave that string in the validated output.
 *
 * @implements ParsingRule<int>
 */
final class NonImplicitParsingRule implements ParsingRule
{
    public function parse(mixed $value): int
    {
        if (!is_string($value) || preg_match('/^[0-9]+\z/', $value) !== 1) {
            throw new ParseFailure();
        }

        return (int) $value;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $this->parse($value);
        } catch (ParseFailure) {
            $fail('The :attribute field must be an integer.');
        }
    }
}
