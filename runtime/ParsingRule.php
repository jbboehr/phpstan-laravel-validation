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
 * The type argument is the produced type. It is what successful
 * `validated()` and `safe()` calls return for the attribute, and it is what
 * static analysis infers. Implementations must keep those two in agreement.
 *
 * A validator that completes parsing is single-use. Revalidating it fails
 * rather than attempting to distinguish residual parser output from equal new
 * input supplied through `setData()`.
 *
 * Parsing rules cannot be serialized or unserialized. Their validator-scoped
 * lifecycle state is not transferable, and deserialization could otherwise
 * inject state that bypasses the immutable implicit marker.
 *
 * An implementation used directly as a Laravel rule must also be permanently
 * implicit. Extend
 * {@see Rules\BaseParsingRule}, which exposes the immutable marker Laravel
 * reads. Laravel skips a non-implicit rule for a blank or whitespace-only
 * string, so without it the raw string survives into validated output while
 * this interface's type argument promises otherwise.
 *
 * Static analysis trusts final concrete subclasses of that base class only
 * when no declared `implicit` property shadows its marker. A non-final class
 * can acquire such a property through a runtime subclass, so it is declined
 * along with direct implementations of this interface and abstractly typed
 * expressions. Declare a final concrete parser where the produced type is
 * wanted. Application parsing logic that should not implement Laravel's
 * lifecycle can instead implement `ValueParser<T>` and pass through
 * `Parse::using()`. Its final adapter restores the lifecycle guarantee while
 * preserving `T`.
 *
 * Laravel runs callbacks registered with `Validator::after()` before
 * validation ahead of a parsing rule's delayed write-back. Such callbacks
 * therefore observe the original value, not `T`; the produced type applies
 * after validation completes.
 *
 * @template-covariant T
 *
 * @extends ValueParser<T>
 */
interface ParsingRule extends ValueParser, ValidationRule
{
}
