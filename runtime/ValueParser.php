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

namespace jbboehr\Rensei;

/**
 * Converts one runtime value into a declared type or rejects it.
 *
 * This contract is independent of Laravel's validation lifecycle. Adapt a
 * value parser with {@see Parse::using()} before placing it in a rule list.
 *
 * @template-covariant T
 */
interface ValueParser
{
    /**
     * @return T
     *
     * @throws ParseFailure when the value has no representation in T
     */
    public function parse(mixed $value): mixed;
}
