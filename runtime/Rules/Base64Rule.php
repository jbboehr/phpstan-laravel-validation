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

use function base64_decode;
use function base64_encode;
use function is_string;

/**
 * Decodes canonical standard Base64 into bytes.
 *
 * @extends BaseParsingRule<non-empty-string>
 */
final class Base64Rule extends BaseParsingRule
{
    /** @return non-empty-string */
    public function parse(mixed $value): string
    {
        if (!is_string($value)) {
            throw new ParseFailure();
        }

        $decoded = base64_decode($value, true);
        if (
            $decoded === false
            || $decoded === ''
            || base64_encode($decoded) !== $value
        ) {
            throw new ParseFailure();
        }

        return $decoded;
    }

    protected function message(): string
    {
        return 'The :attribute field must be canonical Base64.';
    }
}
