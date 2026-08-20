# Parsing Validated Output

Laravel's built-in validation rules normally test the original input and
preserve its PHP representation. The experimental Rensei rules provide an
explicit exception: a parsing rule either produces a value of its declared
type or fails validation.

> [!WARNING]
> Rensei is experimental. Its runtime and inferred contracts may change before
> the feature is declared stable.

## Installation and compatibility

Parsing rules execute inside the application, not only during PHPStan
analysis. If a deployed code path uses `Parse::*`, this package must remain
installed in production. Installing it only with `composer require --dev` and
then deploying with `composer install --no-dev` removes the runtime classes.

The analysis extension supports Laravel 10.0 through 13. The parsing runtime
requires Laravel 10.7 or newer because it uses `Validator::setValue()` for
checked final write-back. Composer cannot express a version floor that applies
only when an optional class is used, so an older Laravel release fails with
`UnsupportedLaravelVersion` when it attempts to run a parser. PHPStan does not
currently diagnose that mismatch: it can infer the produced type below 10.7,
but the unsupported runtime still fails closed.

Literal dots in attribute names have a narrower compatibility window. Parser
rules on a path such as `a\.b` cannot recover Laravel's unmarked internal key
on Laravel 10.7.0–10.48.28, 11.0.0–11.44.0, or 12.0.0–12.1.0, so validation
fails rather than writing to the wrong path. Later releases and Laravel 13 use
a recoverable marked key.

No Rensei-specific PHPStan configuration flag is required. The extension reads
the produced type from the concrete parsing rule's `ParsingRule<T>` binding.

Using the rules at runtime also makes this package a deployed dependency. It is
licensed under
[AGPL-3.0-or-later](https://www.gnu.org/licenses/agpl-3.0), which should be
considered before moving it from an analysis-only dependency.

## Basic use

```php
use Illuminate\Support\Facades\Validator;
use jbboehr\Rensei\Parse;

enum AccountStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
}

$validator = Validator::make($input, [
    'age' => ['required', Parse::integer()],
    'enabled' => ['required', Parse::boolean()],
    'status' => ['required', Parse::enum(AccountStatus::class)],
]);

$validated = $validator->validated();
\PHPStan\dumpType($validated);
// array{age: int, enabled: bool, status: AccountStatus}

$safe = $validator->safe()->all();
```

For input such as `['age' => '42', 'enabled' => '0', 'status' =>
'active']`, both output calls contain `42`, `false`, and
`AccountStatus::Active`.

PHPStan deliberately retains Laravel's broad `array` type for
`$validator->safe()->all()`. A factory may use `Factory::resolver()` to return
a custom Validator whose virtual `validated()` method changes the payload, so
the wrapper cannot receive the ordinary validator's structural contract
soundly. Supported `safe()` projections on conventional FormRequests can carry
the parsed shape when FormRequest inference is enabled.

The caller's `$input` array is not rewritten. A request also retains its
original values through `$request->all()` and `$request->input()`. Parsing
changes the successful validator output, not the incoming request.

## Exact parser grammars

### `Parse::integer()`

Accepts any native `int` and canonical decimal strings matching
`^-?(0|[1-9][0-9]*)\z` within the platform integer range. The `\z` anchor is
intentional: unlike `$`, it does not match before a final newline. It produces
`int`.

It rejects floats, booleans, leading `+`, leading zeroes, whitespace,
decimals, scientific notation, trailing data, and integer overflow. For
example, `'42'` becomes `42` and `'-0'` becomes `0`, while `'042'`, `'+42'`,
`'42.0'`, and `42.0` fail validation.

### `Parse::boolean()`

Accepts exactly Laravel's strict boolean input set and produces `bool`:

```text
true, 1, '1'   -> true
false, 0, '0'  -> false
```

Values such as `'true'`, `'false'`, `'on'`, `'off'`, floats, and blank
strings fail. PHP truthiness is deliberately not used.

### `Parse::enum()`

Accepts an existing case of the configured backed enum or a value with exactly
the enum's native backing type. It produces the concrete enum case.

String-backed enums accept only strings; int-backed enums accept only ints.
Unlike Laravel's `Rule::enum()`, an int-backed enum does not accept the string
`'1'`, and a string-backed enum does not accept the integer `1` through PHP
coercion.

Passing a pure enum throws `InvalidArgumentException` when the rule is
constructed because it has no canonical wire representation. Name matching
and `only()` / `except()` filters are not supported.

## Presence and adjacent Laravel rules

A parser controls the value it produces, not whether the key must exist:

```php
['age' => [Parse::integer()]]                       // array{age?: int}
['age' => ['required', Parse::integer()]]           // array{age: int}
['age' => ['nullable', Parse::integer()]]           // array{age?: int|null}
```

Parsing rules are implicit, so a present blank string cannot bypass them. The
value is passed to the parser and fails unless that parser's grammar accepts
it. In particular, a string-backed enum may legitimately declare `''` as a
backing value. `nullable` preserves a present `null`; it does not make other
blank values nullable.

Ordinary Laravel rules always observe the original representation. Write-back
happens only after they finish. This matters for Laravel's size rules:

```php
'age' => ['required', 'integer', Parse::integer(), 'min:18']
```

The `integer` predicate tells Laravel to compare `min` numerically. Without a
named numeric rule, Laravel may compare the raw string by length even though
`validated()` later contains an `int`. Rule order does not change that phase
boundary.

PHPStan reports this combination as
`laravelValidation.parsingNumericSize` when a numeric parser is paired with
`min`, `max`, `between`, or `size` but the rule list has no `integer`,
`numeric`, or `decimal` marker. Add the marker when the bound is numeric. If
a custom numeric parser intentionally accepts values whose original string,
array, or file representation should be measured, the diagnostic can be
ignored by identifier at that site.

## Which values each phase observes

| Location | Representation |
| --- | --- |
| Ordinary Laravel rules | Original input |
| An `after()` callback registered before validation | Original input |
| Successful `validated()` and `safe()` output | Parsed values |
| The caller's array or request input | Original input |
| FormRequest `passedValidation()` at runtime | Parsed values |

Do not read `validated()`, `safe()`, `valid()`, or `getData()` from an
`after()` callback. Normal callbacks run before parser write-back and therefore
observe the pre-parse state. PHPStan does not currently model this callback
phase and may still expose the final parsed type there.

## FormRequests

Parsing rules can be returned from `FormRequest::rules()` like any other rule.
After successful validation, `validated()` and `safe()` contain parsed values,
while `all()` and `input()` retain the request values.

Enable [FormRequest inference](form-requests.md) if PHPStan should infer the
parsed shape for conventional FormRequests, including supported direct
`safe()` projections. If application code must consume the parsed values
during the FormRequest lifecycle, `passedValidation()` is the runtime
post-write-back hook. Declaring that hook currently makes the extension
conservatively decline FormRequest inference for the class because the hook
can mutate application state or replace the effective contract.

## Lifecycle and soundness limits

- A validator that completes parser write-back is single-use. `passes()`,
  `fails()`, and `validate()` each start a validation run and must not be called
  again on that validator. After one run, `validated()`, `safe()`, `valid()`,
  `invalid()`, and `errors()` reuse its result. Use `fails()` followed by
  `validated()`, not `fails()` followed by `validate()`; repeated `validate()`
  also fails. A first call to `validated()` may perform the one allowed run and
  preserves the inferred shape, whereas direct `Validator::validate()`
  currently retains Laravel's broad `array` return type.
- `valid()` on failed or short-circuited validation is not parsed output. It
  may contain raw attributes whose parsing rules Laravel never reached.
- Executable custom rules and runtime validation extensions can mutate data
  outside the parser's finalization contract. When they are combined with a
  parser and no usable static lifecycle contract exists, PHPStan returns
  `mixed` rather than promising the parser's produced type.
- Mutating an inferred validator through `setData()`, `setValue()`,
  `setRules()`, `addRules()`, or imperative `sometimes()` is subject to the
  diagnostics and widening described under
  [Validator mutation](../reference/entry-points.md#validator-mutation-and-contract-invalidation).

Applications may define another parsing rule through the runtime API:

```php
use jbboehr\Rensei\ParseFailure;
use jbboehr\Rensei\Rules\BaseParsingRule;

/** @extends BaseParsingRule<non-empty-string> */
final class NonEmptyStringRule extends BaseParsingRule
{
    public function parse(mixed $value): string
    {
        if (!is_string($value) || $value === '') {
            throw new ParseFailure();
        }

        return $value;
    }

    protected function message(): string
    {
        return 'The :attribute field must not be empty.';
    }
}
```

`parse()` returns the declared `T` or throws `ParseFailure`; `message()` is
required for validation failures. Static inference deliberately requires a
concrete `BaseParsingRule<T>` subclass. Do not declare an `implicit` property:
it would shadow the base class's immutable marker, so PHPStan conservatively
declines the produced type.
