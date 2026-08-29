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
use LogicException;
use WeakMap;

use function array_key_exists;
use function is_string;
use function preg_match;
use function sprintf;
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
 * A validator that completes parsing is single-use. Laravel exposes no hook
 * that can distinguish parsed output left by one run from equal new data
 * supplied through `setData()`, so another run fails validation rather than
 * guessing and potentially rewriting caller data.
 *
 * @template-covariant T
 *
 * @implements ParsingRule<T>
 *
 * @property-read true $implicit
 */
abstract class BaseParsingRule implements ParsingRule, ValidatorAwareRule
{
    /**
     * Validator-scoped parsing lifecycle state cannot be transferred safely.
     *
     * Rejecting both directions also prevents unserialization from injecting
     * an `implicit` property without passing through the immutable magic
     * marker below.
     *
     * @return never
     */
    final public function __serialize(): array
    {
        throw new LogicException('Parsing rules cannot be serialized.');
    }

    /**
     * @param array<array-key, mixed> $_data
     */
    final public function __unserialize(array $_data): void
    {
        throw new LogicException('Parsing rules cannot be unserialized.');
    }

    /**
     * Expose an immutable implicit marker to Laravel.
     *
     * `InvokableValidationRule::make()` reads `$rule->implicit ?? false`.
     * Magic access makes that value permanently true without requiring every
     * parser constructor to initialize a readonly property.
     */
    final public function __isset(string $name): bool
    {
        return $name === 'implicit';
    }

    final public function __get(string $name): mixed
    {
        return $name === 'implicit' ? true : null;
    }

    /**
     * Parsing lifecycle markers cannot be changed at runtime.
     */
    final public function __set(string $name, mixed $value): void
    {
        throw new LogicException(sprintf(
            'Cannot write inaccessible property %s::$%s.',
            static::class,
            $name
        ));
    }

    /**
     * Laravel's escaped-dot placeholder: the marker plus one process-wide
     * Str::random() value.
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
        $messages = $validator->errors();

        if ($state->runMessages !== $messages) {
            // A thrown rule can unwind the preceding run before after-callback
            // cleanup. Results from that run must never leak into this one.
            $state->pending = [];
            $state->runMessages = $messages;

            if ($state->completed) {
                $fail('A validator containing parsing rules cannot be reused.');

                return;
            }
        }

        if (!Arr::has($validator->getData(), $key)) {
            // Absent. Presence is `required`, `present`, and `sometimes` to
            // decide; a parser only describes the values that are there.
            return;
        }

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

        $state->pending[$key] = [$value, $parsed, $attribute];

        $this->registerWriteBack($validator, $state);
    }

    /**
     * The failure message for an unparsable value.
     */
    abstract protected function message(): string;

    private function stateFor(Validator $validator): ParseState
    {
        $states = $this->states;
        if ($states === null) {
            /** @var WeakMap<Validator, ParseState> $states */
            $states = new WeakMap();
            $this->states = $states;
        }

        if (!isset($states[$validator])) {
            $states[$validator] = new ParseState();
        }

        return $states[$validator];
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
     * process, marked with `__dot__`. Earlier supported releases -- Laravel
     * 10 up to 10.48.28, 11 up to 11.44.0, and 12 up to 12.1.0 -- substituted
     * a bare random string with nothing to anchor on, so there the attribute
     * is reported as unaddressable rather than silently mishandled.
     */
    private function resolveDataKey(Validator $validator, string $attribute): ?string
    {
        $rules = $validator->getRules();
        $keys = array_keys($rules);

        if (array_key_exists($attribute, $rules)) {
            return $attribute;
        }

        foreach ($keys as $key) {
            if (!is_string($key) || preg_match(self::PLACEHOLDER_PATTERN, $key, $matches) !== 1) {
                continue;
            }

            // One placeholder per process, so the first is the only one.
            foreach ($keys as $candidate) {
                if (str_replace($matches[0], '.', (string) $candidate) === $attribute) {
                    return (string) $candidate;
                }
            }

            break;
        }

        return null;
    }

    /**
     * Apply parsed values once every ordinary rule has run.
     *
     * Registered once per validator. The callback remains attached after its
     * run even though another run will fail closed.
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
            $state->completed = true;
            $writeBack = [];

            foreach ($pending as $key => [$raw, $parsed, $attribute]) {
                // An exclude_* rule removes the attribute from both the rules
                // and the data. Writing it back would resurrect it in
                // getData() and attributes().
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
                $current = Arr::get($data, $key);
                if ($current === $parsed) {
                    continue;
                }

                if ($current !== $raw) {
                    $validator->errors()->add(
                        $attribute,
                        sprintf('The %s field changed before parsing could be applied.', $attribute)
                    );

                    continue;
                }

                $writeBack[$key] = $parsed;
            }

            // Do not apply this callback's queued results after a validation
            // error. This keeps ordinary failed runs raw and avoids surprising
            // exception renderers and logs. Another parser's earlier callback
            // may already have finalized its own independent state, so failed
            // getData() is deliberately outside the parsing contract.
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            foreach ($writeBack as $key => $parsed) {
                $validator->setValue($key, $parsed);
            }
        });
    }
}
