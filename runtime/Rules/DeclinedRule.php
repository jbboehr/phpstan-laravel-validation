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

use function in_array;

/**
 * Parses Laravel's declined token set into literal false.
 *
 * @extends BaseParsingRule<false>
 */
final class DeclinedRule extends BaseParsingRule
{
    /** @return false */
    public function parse(mixed $value): bool
    {
        if (!in_array($value, ['no', 'off', '0', 0, false, 'false'], true)) {
            throw new ParseFailure();
        }

        return false;
    }

    protected function message(): string
    {
        return 'The :attribute field must be declined.';
    }
}
