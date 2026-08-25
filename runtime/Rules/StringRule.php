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
use Throwable;

use function is_int;
use function is_string;

/**
 * Parses deliberately bounded scalar-ish representations into a string.
 *
 * Native strings pass through, integers use their decimal representation,
 * and Stringable objects contribute exactly the string declared by the
 * object. Floats and booleans are deliberately rejected: their generic PHP
 * casts introduce precision policy or surprising empty-string semantics.
 * A failed Stringable conversion is invalid input rather than an exception
 * escaping the validation run.
 *
 * @extends BaseParsingRule<string>
 */
final class StringRule extends BaseParsingRule
{
    public function parse(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (!$value instanceof \Stringable) {
            throw new ParseFailure();
        }

        try {
            return $value->__toString();
        } catch (Throwable) {
            // A Stringable that cannot produce its declared representation is
            // invalid input, not an exception that should escape validation.
            throw new ParseFailure();
        }
    }

    protected function message(): string
    {
        return 'The :attribute field must be text or a whole number.';
    }
}
