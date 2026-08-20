# Changelog

## Unreleased

### Added

- Experimental opt-in parsing rules under the `jbboehr\Rensei` namespace.
  `Parse::integer()` produces an `int` in successful `validated()` and
  `safe()` output, or fails validation; ordinary rules continue to observe the
  original representation and the request itself is never rewritten.
  PHPStan infers the produced type, reading it from the `ParsingRule<T>`
  binding, so a parser defined outside this package needs no support here.
- Requires `laravel/framework` 10.7.0 or later, which introduced
  `Validator::setValue()`. Static analysis still supports Laravel 10.0; the
  narrower floor applies only to code that uses a parsing rule, and is
  reported at runtime rather than through a Composer constraint.

  The grammar is deliberately narrower than Laravel's `integer` rule: it
  rejects `'042'`, `'+42'`, `' 42'`, `'42.0'`, the float `42.0`, and values
  beyond the platform integer width. Size rules still compare the original
  representation, so pair the parser with `integer` or `numeric` when
  `min`, `max`, `between`, or `size` matter. A validator that completes parser
  write-back is single-use: another validation run fails closed. Laravel
  exposes no hook that can distinguish residual parser output from equal new
  data supplied through `setData()`, so attempting to restore it would risk
  corrupting caller data. `valid()` on failed or short-circuited validation is
  not parsed output and may contain raw values from rules Laravel never ran.

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

- Invalidate returned validator contracts after `setData()`, `setRules()`, and
  imperative `sometimes()` calls. A statically resolvable
  `setRules()` chained directly from a fresh factory, facade, or helper call is
  re-inferred, and fresh `setData()` chains retain their rules. Mutations of an
  existing inferred validator still produce
  `laravelValidation.validatorMutation`, because Laravel retains validation
  state and PHPStan cannot invalidate ignored calls or aliases. `setValue()`
  and `addRules()` are diagnostic-only because their APIs do not return a
  validator contract that can be replaced portably.

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
