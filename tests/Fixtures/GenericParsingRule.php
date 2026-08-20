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
use jbboehr\Rensei\Rules\BaseParsingRule;

/**
 * A parser whose produced type comes from its own generic binding.
 *
 * Two things need one of these. Discovery has to answer differently for
 * `GenericParsingRule<int>` and `GenericParsingRule<string>`, which no
 * non-generic parser can demonstrate; and a parser with its own constructor
 * has to work without calling `parent::__construct()`, which every parser
 * taking an argument -- an enum class string, a format, a scale -- will be.
 *
 * @template-covariant TParsed
 *
 * @extends BaseParsingRule<TParsed>
 */
final class GenericParsingRule extends BaseParsingRule
{
    /**
     * @param Closure(mixed): TParsed $parse
     */
    public function __construct(
        private Closure $parse
    ) {
    }

    /**
     * @return TParsed
     */
    public function parse(mixed $value): mixed
    {
        return ($this->parse)($value);
    }

    protected function message(): string
    {
        return 'The :attribute field could not be parsed.';
    }
}
