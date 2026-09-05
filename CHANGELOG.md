# Changelog

## Unreleased

### Added

- Add `formRequests.additionalClasses` as a discovery-only exact class list.
  These classes still pass the ordinary lifecycle-safety checks;
  `trustedClasses` remains the separate explicit bypass.
- Infer direct FormRequest `safe()->except(...)` projections for constant
  string and integer paths, including dotted and numeric paths. Unbounded dynamic
  selectors and stored mutable `ValidatedInput` wrappers remain broad.
  Multi-selector calls conservatively remain broad before Laravel 13.24 when
  an earlier dotted traversal can affect a later selector.
- Preserve inferred FormRequest shapes through direct
  `safe()->merge([...])->all()`, `toArray()`, `only()`, and `except()` chains
  when the merged array is statically bounded. String keys follow replacement
  semantics. Numeric keys in direct array expressions are reindexed as
  Laravel's `array_merge()` does; bounded variables with ambiguous integer-key
  insertion order remain broad.
- Experimental opt-in parsing rules under the `jbboehr\Rensei` namespace.
  `Parse::integer()`, `Parse::float()`, `Parse::boolean()`,
  `Parse::string()`, `Parse::base64()`, `Parse::accepted()`,
  `Parse::declined()`, `Parse::enum()`, `Parse::dateTime()`, and
  `Parse::timezone()` produce canonical values of declared PHP types in
  successful `validated()` and `safe()` output, or fail validation; ordinary
  rules continue to observe the original representation and the request itself
  is never rewritten.
  PHPStan infers the produced type, reading it from the `ParsingRule<T>`
  binding, so a parser defined outside this package needs no support here.
  `ValueParser<T>` and `Parse::using()` separate application conversion logic
  from Laravel's validation lifecycle. The final adapter preserves `T` through
  polymorphic parser abstractions. Parsing rules reject serialization and
  unserialization because validator-scoped lifecycle state is not transferable.
- Requires `laravel/framework` 10.7.0 or later, which introduced
  `Validator::setValue()`. Static analysis still supports Laravel 10.0; the
  narrower floor applies only to code that uses a parsing rule, and is
  reported statically as `laravelValidation.parsingRuleLaravelVersion` and
  enforced at runtime rather than through a Composer constraint.

  The grammar is deliberately narrower than Laravel's `integer` rule: it
  rejects `'042'`, `'+42'`, `' 42'`, `'42.0'`, the float `42.0`, and values
  beyond the platform integer width. Size rules still compare the original
  representation, so pair the parser with `integer`, `numeric`, or `decimal` when
  `min`, `max`, `between`, or `size` matter. PHPStan reports a missing
  `integer`, `numeric`, or `decimal` companion as
  `laravelValidation.parsingNumericSize`. A validator that completes parser
  write-back is single-use: another validation run fails closed. Laravel
  exposes no hook that can distinguish residual parser output from equal new
  data supplied through `setData()`, so attempting to restore it would risk
  corrupting caller data. `valid()` on failed or short-circuited validation is
  not parsed output and may contain raw values from rules Laravel never ran.

  `Parse::float()` accepts finite native floats, widens native ints, and parses
  canonical ASCII decimal strings. It rejects scientific notation, leading
  zeroes, whitespace, booleans, `INF`, `NAN`, and decimal strings that overflow
  to infinity. Precision loss and underflow remain properties of PHP's native
  `float` representation.

  `Parse::boolean()` accepts exactly Laravel's strict `boolean` input set:
  `true`, `false`, `0`, `1`, `'0'`, and `'1'`. It maps those representations to
  `bool` and rejects textual forms such as `'true'`, `'false'`, `'on'`, and
  `'off'` rather than applying PHP truthiness.

  `Parse::string()` preserves native strings, converts native integers to
  lossless decimal strings, and converts `Stringable` objects through their
  declared representation. It rejects floats and booleans rather than
  inheriting mutable float precision or PHP's `false`-to-empty-string cast;
  failed `Stringable` conversions become ordinary validation failures.

  `Parse::base64()` accepts non-empty native strings in canonical standard
  Base64 and produces the represented bytes as `non-empty-string`. Strict
  decoding plus an exact encode/decode round trip rejects whitespace, URL-safe
  input, extra padding, and omitted required padding. The parser supplies this
  grammar on every parser-supported Laravel release; it does not depend on the
  native preserving `base64` predicate introduced in Laravel 13.21.

  `Parse::accepted()` and `Parse::declined()` accept Laravel's respective
  strict token sets and normalize them to literal `true` and `false`. They do
  not widen `Parse::boolean()` and, like every parsing rule, do not imply that
  the field is present; add `required` when absence must fail.

  `Parse::enum(Status::class)` accepts an existing case or an exact native
  backing value and produces the case object. String-backed enums accept only
  strings; int-backed enums accept only ints. This deliberately refuses the
  scalar coercions used by Laravel's `Rule::enum()`. Pure enums, `only()` and
  `except()` filters, and name-based matching are not supported.

  `Parse::dateTime($format, $timezone)` produces `DateTimeImmutable`. With no
  format it follows Laravel's ordinary `date` acceptance as long as an
  immutable object can be constructed. A string or ordered non-empty list of
  formats selects exact parsing: input must parse without warnings or
  normalization and round-trip byte-for-byte through the matching format. UTC
  is the stable output fallback; an input offset or timezone takes precedence,
  while `@` timestamps and explicit Unix-timestamp formats are represented in
  the configured zone. NUL bytes are rejected even where Laravel's predicate
  accepts them. Parse-only format controls are rejected unless escaped as
  literal input. Existing immutable values pass through, and other
  `DateTimeInterface` values are copied to immutable objects.

  `Parse::timezone()` produces `DateTimeZone` from Laravel's default timezone
  identifier set. It rejects constructor-only offset strings, abbreviations,
  and backward-compatible aliases; an existing `DateTimeZone` passes through
  unchanged.

  Callbacks registered with `Validator::after()` before validation run before
  parsing-rule write-back and therefore observe the original values. Read
  parsed values only after validation completes; in a FormRequest, use
  `passedValidation()`. PHPStan does not currently model this phase boundary:
  a callback that captures the rule-aware validator may still be given its
  final parsed type. This is a known limitation of the experimental feature.
  Executable custom or opaque rules can also mutate validator data outside the
  parser's finalization point; PHPStan therefore returns `mixed` when they are
  combined with parsing rules.

### Changed

- Make `date_format` inference parameter-sensitive. Formats that cannot
  produce a PHP numeric string, such as `Y-m-d`, now infer
  `non-empty-string`; numeric formats such as `Ymd` retain
  `float|int|non-empty-string` because Laravel can accept and preserve native
  numerics.
- Invalidate returned validator contracts after `setData()`, `setRules()`, and
  imperative `sometimes()` calls. A statically resolvable
  `setRules()` chained directly from a fresh factory, facade, or helper call is
  re-inferred, and fresh `setData()` chains retain their rules. Mutations of an
  existing inferred validator still produce
  `laravelValidation.validatorMutation`, because Laravel retains validation
  state and PHPStan cannot invalidate ignored calls or aliases. `setValue()`
  and `addRules()` are diagnostic-only because their APIs do not return a
  validator contract that can be replaced portably.

### Fixed

- Keep parsing rules on escaped literal-dot keys separate from nested paths
  with the same decoded name. Separate parser instances transform their own
  fields; sharing one instance across colliding paths fails validation.
  Escaped paths now also work with Laravel's older unmarked placeholders.

### Documentation

- Split the project manual into a concise README and an mdBook site covering
  guides, reference material, and contributor documentation.
- Update Doctrine of the Second Sun to the latest `dev-master` revision and
  mount The Document Looks Back as a separate optional documentation
  integration.

## 0.1.0 (2026-08-15)

This is the first experimental release of `phpstan-laravel-validation`.

### Highlights

- Infers structural types for validated output from statically resolvable
  Laravel rule sets, including nested arrays, wildcards, presence rules,
  exclusions, and output projection.
- Supports Laravel validation entry points on validators, factories, facades,
  requests, and controllers, including `validated()`, `validate()`, and
  supported `safe()` projections.
- Models supported fluent and directly constructed Laravel rule objects, with
  version-aware behavior at verified Laravel release boundaries.
- Accepts explicit static contracts for custom validation rules without
  requiring Larastan or application bootstrapping.
- Provides default-off experimental inference for conventional FormRequests
  and definite conditional presence or absence.
- Uses conservative unions or broad fallback types when Laravel behavior or a
  runtime rule contract is unavailable to static analysis.

### Compatibility

- PHP 8.1 through 8.5
- PHPStan 2.1.5 or later
- Laravel 10 through 13

The test suite includes pinned Laravel runtime audits, supported-PHP and
supported-Laravel matrices, Larastan compatibility checks, downstream consumer
tests, property tests, and mutation testing. This evidence covers the supported
combinations under test; it is not a claim of universal soundness for arbitrary
runtime extensions.

### Known limitations

- Dynamic rule construction, callbacks, custom validator behavior, and custom
  rules without an accurate static contract may require conservative types.
- FormRequest inference and definite conditional-presence inference are
  experimental, disabled by default, and deliberately decline unsupported
  lifecycle or correlation patterns.
- Laravel validation commonly preserves input values rather than normalizing
  them. Sound inferred types may therefore be broader than rule names suggest.

See the [README](README.md) for installation and
[Laravel validation and type safety](docs/pages/guides/laravel-validation-and-type-safety.md)
for the technical rationale and runtime evidence.
