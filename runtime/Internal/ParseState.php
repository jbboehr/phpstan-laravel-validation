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

namespace jbboehr\Rensei\Internal;

use Illuminate\Support\MessageBag;

/**
 * One parsing rule's pending results for one validator.
 *
 * Laravel expands `users.*.age` before validation and reuses a single rule
 * object for every expanded attribute, so a rule instance cannot hold the
 * attribute or the value it is working on. Results are therefore keyed by
 * concrete attribute inside a record scoped to the validator that produced
 * them.
 *
 * @internal
 */
final class ParseState
{
    /**
     * Results awaiting write-back, keyed by concrete attribute.
     *
     * Each entry keeps the value that was parsed alongside the result, so the
     * write-back can confirm the data still holds what it was derived from.
     *
     * @var array<array-key, array{mixed, mixed, string}>
     */
    public array $pending = [];

    /**
     * The message bag identifies the validation run filling pending results.
     * Laravel replaces it at the start of every `passes()` call.
     */
    public ?MessageBag $runMessages = null;

    /**
     * Whether a run reached this rule's write-back callback.
     *
     * Laravel has no hook that can distinguish parser output left by that run
     * from equal new data supplied through `setData()`. Reuse therefore fails
     * closed instead of guessing which representation the caller intended.
     */
    public bool $completed = false;

    /**
     * Whether the write-back callback has been registered on the validator.
     *
     * The callback remains attached to the validator, so it is registered
     * once even though a completed parsing validator cannot be reused.
     */
    public bool $registered = false;
}
