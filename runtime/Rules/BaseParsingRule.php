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
use function in_array;
use function is_string;
use function preg_match;
use function str_replace;

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

    /**
     * Laravel's escaped-dot placeholder: the marker plus one Str::random().
     */
    private const PLACEHOLDER_PATTERN = '/__dot__[A-Za-z0-9]{16}/';

    private ?Validator $validator = null;

    /**
     * Pending results, keyed by the validator that produced them.
     *
     * Laravel expands `users.*.age` before validation and reuses one rule
     * object for every expanded attribute, so per-attribute state cannot live
     * on `$this`. A WeakMap keyed by validator scopes results correctly and
     * releases them when the validator is collected, which matters in
     * long-lived workers.
     *
     * Created on first use rather than in a constructor. A parser that takes
     * arguments -- an enum class string, a format, a scale -- would otherwise
     * have to remember `parent::__construct()`, and forgetting it leaves this
     * property uninitialized until validation runs and fails on it.
     *
     * @var WeakMap<Validator, ParseState>|null
     */
    private ?WeakMap $states = null;

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

        // Every other unsupported condition below fails closed. This one must
        // too: without a validator the value cannot be parsed or written back,
        // so passing would leave the raw value behind while the declared type
        // promises otherwise.
        if ($validator === null) {
            $fail('Parsing rules require a validator-aware validation run.');

            return;
        }

        // Release the reference once the attribute is handled. Laravel injects
        // it again before every invocation, and holding it would pin the
        // validator -- and the whole request's data with it -- on a rule
        // instance an application may cache.
        $this->validator = null;

        $key = $this->resolveDataKey($validator, $attribute);
        if ($key === null) {
            $fail('Parsing rules cannot address this attribute name on this Laravel release.');

            return;
        }

        $state = $this->stateFor($validator);
        $this->undoPreviousWriteBack($validator, $state);

        if (!Arr::has($validator->getData(), $key)) {
            // Absent. Presence is `required`, `present`, and `sometimes` to
            // decide; a parser only describes the values that are there.
            return;
        }

        // Laravel fetched $value before this rule ran, so on a repeated run it
        // holds the previous run's parsed value rather than what the undo just
        // restored. Read the data instead, so the recorded original is the one
        // the write-back will check against.
        $value = Arr::get($validator->getData(), $key);

        if ($value === null) {
            if ($validator->hasRule($key, ['Nullable'])) {
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

        $state->pending[$key] = [$value, $parsed];

        $this->registerWriteBack($validator, $state);
    }

    /**
     * The failure message for an unparsable value.
     */
    abstract protected function message(): string;

    private function stateFor(Validator $validator): ParseState
    {
        /** @var WeakMap<Validator, ParseState> $states */
        $states = $this->states ??= new WeakMap();

        return $states[$validator] ??= new ParseState();
    }

    /**
     * Undo the write-back left by an earlier run on this validator.
     *
     * Laravel offers no hook before the rule loop, so the earliest a parsing
     * rule can act on a new run is its own first invocation. Restoring here
     * keeps cross-field rules reading the original representation on a second
     * `passes()`, provided the parsed attribute is reached before the rule
     * that depends on it -- rules run in the order the rule set declares them.
     * A value something else has changed since is left alone.
     */
    private function undoPreviousWriteBack(Validator $validator, ParseState $state): void
    {
        if ($state->applied === []) {
            return;
        }

        $applied = $state->applied;
        $state->applied = [];

        foreach ($applied as $key => [$raw, $parsed]) {
            $data = $validator->getData();
            if (!Arr::has($data, $key) || Arr::get($data, $key) !== $parsed) {
                continue;
            }

            $validator->setValue($key, $raw);
        }
    }

    /**
     * The rule-set key under which this attribute's value is stored.
     *
     * Ordinarily that is the attribute itself. An escaped dot is different:
     * Laravel keys the rule and the data by a placeholder and hands rules the
     * decoded name, so writing to the decoded name would build an unrelated
     * nested branch and leave the real value unparsed.
     *
     * The placeholder is recoverable because it is one fixed random string per
     * validator, marked with `__dot__`. Releases before that marker existed --
     * Laravel 10 up to 10.48 -- substituted a bare random string with nothing
     * to anchor on, so there the attribute is reported as unaddressable rather
     * than silently mishandled.
     */
    private function resolveDataKey(Validator $validator, string $attribute): ?string
    {
        $keys = array_keys($validator->getRules());

        if (in_array($attribute, $keys, true)) {
            return $attribute;
        }

        foreach ($keys as $key) {
            if (!is_string($key) || preg_match(self::PLACEHOLDER_PATTERN, $key, $matches) !== 1) {
                continue;
            }

            // One placeholder per validator, so the first is the only one.
            foreach ($keys as $candidate) {
                if (is_string($candidate) && str_replace($matches[0], '.', $candidate) === $attribute) {
                    return $candidate;
                }
            }

            break;
        }

        return null;
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
            $state->applied = [];

            foreach ($pending as $key => [$raw, $parsed]) {
                // An exclude_* rule removes the attribute from both the rules
                // and the data. Writing it back would resurrect it in
                // getData(), valid(), and attributes().
                if (!array_key_exists($key, $rules)) {
                    continue;
                }

                $data = $validator->getData();
                if (!Arr::has($data, $key)) {
                    continue;
                }

                // Apply only to the value that was actually parsed. A run that
                // unwinds before its after callbacks -- because another rule
                // threw -- leaves its results pending, and the next run may be
                // reading different data. Without this the stale result would
                // be written over a value nobody parsed.
                if (Arr::get($data, $key) !== $raw) {
                    continue;
                }

                $validator->setValue($key, $parsed);
                $state->applied[$key] = [$raw, $parsed];
            }
        });
    }
}
