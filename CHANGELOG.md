# Changelog

## Unreleased

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
