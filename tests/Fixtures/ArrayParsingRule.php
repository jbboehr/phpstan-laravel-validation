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
 * A structural parser used to exercise parent parsing with nested projection.
 *
 * @extends BaseParsingRule<array{parsed: true}>
 */
final class ArrayParsingRule extends BaseParsingRule
{
    /**
     * @return array{parsed: true}
     */
    public function parse(mixed $value): array
    {
        if (!is_array($value)) {
            throw new ParseFailure();
        }

        return ['parsed' => true];
    }

    protected function message(): string
    {
        return 'The :attribute field must be an array.';
    }
}
