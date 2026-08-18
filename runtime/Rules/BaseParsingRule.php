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

use Closure;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use jbboehr\Rensei\Internal\ParseState;
use jbboehr\Rensei\Internal\ValidatorCapabilities;
use jbboehr\Rensei\ParseFailure;
use jbboehr\Rensei\ParsingRule;
use WeakMap;

use function array_key_exists;
use function is_string;
use function strtolower;

/**
 * Shared lifecycle for a parsing rule.
 *
 * Subclasses implement `parse()` and `message()`. Everything that makes a
 * parsing rule sound lives here.
 *
 * Two properties are load-bearing.
 *
 * The rule is implicit. Laravel skips a non-implicit rule for a blank or
 * whitespace-only string, so a non-implicit parser would leave `''` in the
 * validated output while its declared type promised otherwise. Being implicit
 * also means Laravel stops applying `nullable` and presence on the rule's
 * behalf, so this class takes both over.
 *
 * The write-back is delayed. Parsed values are recorded during validation and
 * applied from an `after()` callback, once every ordinary rule has run. A rule
 * that wrote immediately would be observed by later rules: `Validator` refetches
 * the value for every rule, so `['a' => [Parse::integer()], 'b' => ['same:a']]`
 * would fail because one side had become an int.
 *
 * @template-covariant T
 *
 * @implements ParsingRule<T>
 */
abstract class BaseParsingRule implements ParsingRule, ValidatorAwareRule
{
    /**
     * Marks the rule implicit.
     *
     * `InvokableValidationRule::make()` reads this public property and wraps
     * the rule in an `ImplicitRule` decorator when it is true.
     */
    public bool $implicit = true;

    protected ?Validator $validator = null;

    /**
     * Pending results, keyed by the validator that produced them.
     *
     * Laravel expands `users.*.age` before validation and reuses one rule
     * object for every expanded attribute, so per-attribute state cannot live
     * on `$this`. A WeakMap keyed by validator scopes results correctly and
     * releases them when the validator is collected, which matters in
     * long-lived workers.
     *
     * @var WeakMap<Validator, ParseState>
     */
    private WeakMap $states;

    public function __construct()
    {
        /** @var WeakMap<Validator, ParseState> $states */
        $states = new WeakMap();
        $this->states = $states;
    }

    /**
     * @param Validator $validator
     *
     * @return $this
     */
    public function setValidator($validator)
    {
        ValidatorCapabilities::assertCanSetValue($validator);

        $this->validator = $validator;

        return $this;
    }

    final public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = $this->validator;
        if ($validator === null) {
            return;
        }

        if (!$this->attributeIsAddressable($validator, $attribute)) {
            $fail('Parsing rules do not support escaped dots in attribute names.');

            return;
        }

        if (!Arr::has($validator->getData(), $attribute)) {
            // Absent. Presence is `required`, `present`, and `sometimes` to
            // decide; a parser only describes the values that are there.
            return;
        }

        if ($value === null) {
            if ($this->attributeIsNullable($validator, $attribute)) {
                return;
            }

            $fail($this->message());

            return;
        }

        try {
            $parsed = $this->parse($value);
        } catch (ParseFailure) {
            $fail($this->message());

            return;
        }

        $state = $this->stateFor($validator);
        $state->pending[$attribute] = $parsed;

        $this->registerWriteBack($validator, $state);
    }

    /**
     * The failure message for an unparsable value.
     */
    abstract protected function message(): string;

    private function stateFor(Validator $validator): ParseState
    {
        return $this->states[$validator] ??= new ParseState();
    }

    /**
     * Honour an explicit `nullable` rule on the same attribute.
     *
     * Laravel normally applies `nullable` on a rule's behalf, but
     * `isNotNullIfMarkedAsNullable()` short-circuits for implicit rules, so
     * this rule reads the rule set itself. `getRules()` is keyed by concrete
     * attribute after wildcard expansion, so this works for `users.*.age`.
     */
    private function attributeIsNullable(Validator $validator, string $attribute): bool
    {
        /** @var array<string, mixed> $rules */
        $rules = $validator->getRules();
        $attributeRules = $rules[$attribute] ?? [];

        if (!is_iterable($attributeRules)) {
            return false;
        }

        foreach ($attributeRules as $rule) {
            if (is_string($rule) && strtolower($rule) === 'nullable') {
                return true;
            }
        }

        return false;
    }

    /**
     * Whether the rule can address the attribute it was handed.
     *
     * Laravel rewrites an escaped dot in `'a\.b'` to an internal placeholder
     * key, then hands rules the decoded name. A parser given `a.b` cannot
     * address the stored value: writing to `a.b` would build an unrelated
     * nested branch and leave the real value unparsed, so the declared type
     * would be wrong.
     *
     * The decoded name is recognizable because it is not itself a key of the
     * rule set, whereas an ordinary attribute -- including one expanded from
     * a wildcard, and including one that is simply absent from the data --
     * always is. Testing that rather than decoding the placeholder keeps this
     * independent of a format that has already changed once across the
     * supported releases.
     */
    private function attributeIsAddressable(Validator $validator, string $attribute): bool
    {
        return array_key_exists($attribute, $validator->getRules());
    }

    /**
     * Apply parsed values once every ordinary rule has run.
     *
     * Registered once per validator. The callback outlives a single `passes()`
     * call and refires on the next one, by which time the rules have refilled
     * the pending map.
     */
    private function registerWriteBack(Validator $validator, ParseState $state): void
    {
        if ($state->registered) {
            return;
        }

        $state->registered = true;

        $validator->after(static function (Validator $validator) use ($state): void {
            $rules = $validator->getRules();

            // Take and clear first: a failure mid-write must not leave values
            // to be replayed against different data on the next run.
            $pending = $state->pending;
            $state->pending = [];

            foreach ($pending as $attribute => $value) {
                // An exclude_* rule removes the attribute from both the rules
                // and the data. Writing it back would resurrect it in
                // getData(), valid(), and attributes().
                if (!array_key_exists($attribute, $rules)) {
                    continue;
                }

                if (!Arr::has($validator->getData(), $attribute)) {
                    continue;
                }

                $validator->setValue($attribute, $value);
            }
        });
    }
}
