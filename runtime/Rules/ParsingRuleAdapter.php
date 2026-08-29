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

use jbboehr\Rensei\ValueParser;

/**
 * Adapts an abstractly typed parser to the sound Laravel parsing lifecycle.
 *
 * The delegate contributes only `parse()`. This final concrete rule supplies
 * the immutable implicit marker and delayed write-back inherited from
 * BaseParsingRule, so static analysis can trust the delegate's produced type
 * even when application code exposes only a `ValueParser<T>`.
 *
 * @template T
 *
 * @extends BaseParsingRule<T>
 */
final class ParsingRuleAdapter extends BaseParsingRule
{
    /**
     * @param ValueParser<T> $parser
     */
    public function __construct(private ValueParser $parser)
    {
    }

    /**
     * @return T
     */
    public function parse(mixed $value): mixed
    {
        return $this->parser->parse($value);
    }

    protected function message(): string
    {
        return 'The :attribute field could not be parsed.';
    }
}
