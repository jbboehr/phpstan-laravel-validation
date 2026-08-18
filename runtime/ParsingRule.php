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

use Illuminate\Contracts\Validation\ValidationRule;

/**
 * A validation rule that produces a value of a declared type.
 *
 * An ordinary Laravel rule is a predicate: it answers whether a value is
 * acceptable and leaves the value alone. A parsing rule answers a different
 * question, and answering it changes the validated output:
 *
 *     ordinary rule:  mixed -> pass | fail
 *     parsing rule:   mixed -> T    | fail
 *
 * The type argument is the produced type. It is what `validated()`, `safe()`,
 * and `valid()` return for the attribute, and it is what static analysis
 * infers. Implementations must keep those two in agreement.
 *
 * `parse()` must be a fixed point: `parse(parse($value)) === parse($value)`.
 * Laravel invokes `passes()` more than once on ordinary paths, so a parser
 * that rejects its own output breaks on the second run.
 *
 * An implementation must also be implicit -- it must carry
 * `public bool $implicit = true`, which {@see Rules\BaseParsingRule} provides.
 * Laravel skips a non-implicit rule for a blank or whitespace-only string, so
 * without it the raw string survives into the validated output while this
 * interface's type argument promises otherwise. Static analysis enforces this
 * on concrete implementations and declines to infer a produced type without
 * it; on an abstract type there is nothing to inspect, so the requirement
 * stands on this contract alone.
 *
 * @template-covariant T
 */
interface ParsingRule extends ValidationRule
{
    /**
     * Parse a value, or reject it.
     *
     * @return T
     *
     * @throws ParseFailure when the value has no representation in T
     */
    public function parse(mixed $value): mixed;
}
