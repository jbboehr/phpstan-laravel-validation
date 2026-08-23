# JSON parser investigation

This report asks whether the parsing API should add a first-party JSON rule.
It distinguishes three operations that Laravel's `json` rule makes unusually
easy to confuse:

1. recognizing text that follows the JSON grammar;
2. decoding that text into PHP values;
3. validating the decoded structure.

Laravel performs only the first operation and preserves the original input.
A `Parse::*` rule could perform the second. It cannot make Laravel's nested
rules perform the third, because those rules run against the original input
before parser write-back.

Investigation date: 2026-08-22.

## Decision

**Do not add a general `Parse::json()` now.** A top-level-specific decoder is
technically viable, but its useful structural information is limited and its
interaction with Laravel child rules is actively misleading.

If a concrete consumer justifies the feature, start with explicitly terminal,
string-only APIs such as `Parse::jsonList()` or `Parse::jsonObject()`. They
must document their numeric and object-decoding policies, and PHPStan should
reject or prominently diagnose descendant Laravel rules on the same field.
Those descendants inspect the encoded string, not the decoded output.

Applications that need a declared nested shape should use a custom
`BaseParsingRule<array{...}>` backed by a decoder and schema or typed mapper
that validates the decoded value itself. Laravel wildcard rules cannot supply
that contract after the fact.

## Recorded revisions

| Component | Version | Commit |
| --- | --- | --- |
| Extension | `develop` | `a124227` |
| PHP | 8.5.9 | |
| PHPStan | 2.2.7 | `692db47b9dddb0487934e5236e77d48594aef921` |
| `nikic/php-parser` | v5.8.0 | `044a6a392ff8ad0d61f14370a5fbbd0a0107152f` |
| Laravel 10 | 10.50.3 | `74e222cee687f957d95aaadddae69270e3205cf7` |
| Laravel 11 | 11.55.1 | `8d786e25c5fb41eb472e86b465b328b494a0da89` |
| Laravel 12 | 12.66.0 | `82a53323c701a668f9054cbeb1d6b6cdbb6a5e10` |
| Laravel 13 | 13.25.0 | `ed36fe882bd4eed4e6ff75343cbad8dbda03fdba` |

The PHP 8.1 compatibility probe used PHP 8.1.34. Symfony's JSON polyfill made
`json_validate()` available after Composer autoload, and the measured results
agreed with PHP 8.5's native implementation for the adversarial cases below.

## Laravel's `json` rule is a coercive predicate

Every supported Laravel major first rejects arrays and null, then admits any
scalar or object with `__toString()` to a weakly typed JSON function. Laravel
10 and 11 retain a `json_decode()` fallback; the measured Laravel 12 and 13
revisions call `json_validate()` directly.

The common contract is equivalent to:

```php
if (is_array($value) || is_null($value)) {
    return false;
}

if (! is_scalar($value) && ! method_exists($value, '__toString')) {
    return false;
}

return json_validate($value);
```

The framework call site is not strict. PHP therefore coerces admitted values
before checking their JSON spelling. Successful validation then returns the
original value from `validated()`.

| Input | Result | Successful output |
| --- | --- | --- |
| `'{"answer":42}'` | passes | the original string |
| `42` | passes | `int(42)` |
| `1.5` | passes | `float(1.5)` |
| `true` | passes | `true` |
| compatible `Stringable` | passes | the original object |
| `false`, `null`, array, ordinary object | fails | none |

All four recorded Laravel majors produced the same results. Existing runtime
and inference tests consequently model `required|json` as the preserved-value
union `float|int|non-empty-string|Stringable|true`. An optional blank string is
a separate Laravel behavior: because `json` is non-implicit, `''` bypasses the
predicate and remains in `validated()`.

This behavior is evidence for a parser, but it is not a suitable parser
grammar. A decoding rule should accept an encoded representation deliberately,
not inherit Laravel's accidental weak coercions.

## Decoding has policy, not just a return type

A probe decoded valid and adversarial strings with `json_decode()` in
associative and object modes. The ordinary cases are unsurprising:

| JSON input | Associative result |
| --- | --- |
| `{"name":"Ada","count":1}` | `array{name: 'Ada', count: 1}` at runtime |
| `[1,"2",true,null]` | `list{1, '2', true, null}` at runtime |
| `"hello"` | `'hello'` |
| `42`, `1.5`, `true`, `false`, `null` | the corresponding PHP scalar |

The boundary cases determine the public contract:

| Input | Observed behavior | Consequence |
| --- | --- | --- |
| `9223372036854775808` | default decoding produces a rounded float | use `JSON_BIGINT_AS_STRING` or document precision loss |
| `1e400` | Laravel accepts it and decoding produces `INF` | reject non-finite decoded numbers if output is promised to remain a JSON value |
| `{"a":1,"a":2}` | the last member silently wins | document last-write-wins or use a decoder that rejects duplicates |
| `{"0":"zero","01":"leading","-1":"minus"}` | associative keys are `0`, `'01'`, and `-1` | object arrays require `array-key`, not `string`, keys |
| `{}` and `[]` | associative decoding produces `[]` for both | top-level kind must be checked before or separately from associative output |
| UTF-8 BOM, malformed UTF-8, trailing data | rejected | retain strict rejection |
| nesting depth 513 | rejected at the default depth of 512 | make any different depth an explicit API decision |

`JSON_BIGINT_AS_STRING` avoids silent integer rounding, but it means JSON
numbers have no single PHP numeric representation. Duplicate member handling
is not a PHPStan problem; it is a data-integrity decision that a first-party
decoder must nevertheless own rather than inherit invisibly.

Associative decoding gives these defensible top-level PHPStan contracts:

```php
Parse::jsonList();   // list<mixed>
Parse::jsonObject(); // array<array-key, mixed>
Parse::jsonScalar(); // bool|float|int|string|null
```

A general decoder could return
`array<array-key, mixed>|bool|float|int|string|null`, which is narrower than
`mixed` but still says little about structured application data. Object-mode
decoding preserves the distinction between `{}` and `[]`, but `stdClass`
provides even less useful static structure than an associative array.

## Nested Laravel rules do not inspect decoded values

The parsing lifecycle deliberately writes produced values from an `after()`
callback. Every ordinary Laravel rule therefore sees the original value. That
preserves rule-order semantics for scalar parsers, but it is a sharp boundary
for structural decoding.

The probe used a temporary parser that accepted a JSON string, decoded it to
an associative array, and wrote it back with the same delayed callback model
as `BaseParsingRule`.

An explicit child rule sees a string parent and fails:

```php
$data = ['payload' => '{"name":"Ada"}'];
$rules = [
    'payload' => ['required', $jsonArrayParser],
    'payload.name' => ['required', 'string'],
];

// fails: payload.name is missing from the original input tree
```

A wildcard descendant is worse because it can appear to work:

```php
$data = ['payload' => '[{"id":"wrong"}]'];
$rules = [
    'payload' => ['required', $jsonArrayParser],
    'payload.*.id' => ['required', 'integer'],
];

// passes on Laravel 10, 11, 12, and 13
// validated(): ['payload' => [['id' => 'wrong']]]
```

The wildcard expands over the original string, matches no elements, and
therefore checks nothing. Parser write-back later installs the decoded list,
including the non-integer `id`. This is ordinary Laravel wildcard semantics
combined with delayed parsing, not a viable way to validate decoded JSON.

The extension can remain sound by retaining the parser-produced
`list<mixed>` or `array<array-key, mixed>` branch instead of pretending the
descendant established a shape. That conservative type does not solve the API
ergonomics: the rule set still reads as though it validated something it never
visited. A first-party structural parser should therefore diagnose descendant
rules rather than merely rely on widening to protect PHPStan.

## Candidate assessment

| Candidate | Static value | Main problem | Disposition |
| --- | --- | --- | --- |
| `Parse::json()` | modest top-level union | encourages callers to expect a typed structure while nested values remain `mixed` | do not add now |
| `Parse::jsonList()` | useful `list<mixed>` boundary | descendants cannot validate decoded elements | viable only as a terminal decoder |
| `Parse::jsonObject()` to array | useful array boundary | numeric key coercion, empty-object identity loss, descendants do not validate members | viable only with explicit policy |
| `Parse::jsonObject()` to `stdClass` | preserves object identity | little useful static structure | not recommended |
| `Parse::jsonScalar()` | precise top-level scalar union | duplicates existing scalar parsers and still needs numeric policy | low value |
| custom typed parser | declared application shape | application owns decoder/schema contract | recommended for typed structured data |

## Contract required before implementation

If consumer demand justifies a first-party terminal decoder, its initial
contract should be all of the following:

1. Accept strings only. Do not reproduce Laravel's native scalar or
   `Stringable` coercions.
2. Separate list and object entry points. Do not infer the expected top-level
   family from a broad union after decoding.
3. Decode objects to associative arrays only if `array<array-key, mixed>` is
   acceptable. Do not claim string-only keys.
4. Use `JSON_BIGINT_AS_STRING` to avoid silent integer precision loss.
5. Reject decoded non-finite floats recursively, even though Laravel accepts
   an exponent such as `1e400`.
6. Fix and document the maximum depth, invalid UTF-8 behavior, duplicate-key
   policy, and empty object/list behavior.
7. Treat the decoder as terminal. A PHPStan rule should reject or diagnose
   descendant validation paths attached to the parsed field.
8. Test optional, nullable, exclusion, wildcard attribute, escaped-dot,
   lifecycle, and all supported PHP/Laravel profiles just as the scalar
   parsers are tested.

The output policy and the PHPStan type are one contract. Implementing the
decoder first and deciding what its values mean afterward would recreate the
very ambiguity the parsing API is meant to remove.

## Reproduction

The value probe loaded each `laravel-audit-*-latest` Composer closure,
constructed an `Illuminate\Validation\Factory` with an `ArrayLoader`, and ran
`required|json` against the cases above. It then decoded string inputs with
`JSON_THROW_ON_ERROR`, both associative modes, and
`JSON_BIGINT_AS_STRING`. The PHP 8.1 run used the repository's `php81` Nix
toolchain.

The structural probe declared one temporary `ValidationRule` plus
`ValidatorAwareRule`. It decoded during `validate()` and registered an
`after()` callback that called `Validator::setValue()`, reproducing the
ordering relevant to `BaseParsingRule`. The three cases were the parent-only,
explicit-child, and wildcard-child examples above. The complete output was
identical on the four recorded Laravel majors.

The repository already retains the ordinary JSON-predicate witnesses in
`LaravelInferenceTest::testJsonRuleCanPreserveNonStringValues()`, its rejected
value provider, and the `json.*` inference-audit cases. The throwaway decoder
probes are not retained because no JSON parser was implemented.
