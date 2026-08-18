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

use jbboehr\Rensei\ParseFailure;
use jbboehr\Rensei\Rules\BaseParsingRule;

/**
 * A parser defined outside the package, to prove the analyzer reads the
 * produced type from the generic binding rather than from a table of known
 * rule classes.
 *
 * @extends BaseParsingRule<non-empty-string>
 */
final class MoneyParsingRule extends BaseParsingRule
{
    public function parse(mixed $value): string
    {
        if (!is_string($value) || preg_match('/^-?[0-9]+\.[0-9]{2}\z/', $value) !== 1) {
            throw new ParseFailure();
        }

        return $value;
    }

    protected function message(): string
    {
        return 'The :attribute field must be a monetary amount.';
    }
}
