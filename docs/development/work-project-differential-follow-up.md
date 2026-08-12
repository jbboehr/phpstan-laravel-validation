# Work-project differential follow-up

This plan tracks actionable findings from
[issue #21](https://github.com/jbboehr/phpstan-laravel-validation/issues/21),
which compared Laravel runtime output with extension inference in a large
Laravel 11 application. Application names and rules were replaced with
synthetic reproductions. The original pass found no unconditional soundness
failure.

This is a forward-looking development plan, not a pinned compatibility claim
for the proprietary application.

## Priorities

| Slice | Finding | Kind | Priority | Status |
| ---: | --- | --- | --- | --- |
| 1 | Normalize Laravel's `int` and `bool` aliases | Precision defect | High | Implemented locally |
| 2 | Infer `Illuminate\Validation\Factory::validate()` | Missing entrypoint | High | Implemented locally |
| 3 | Model `includeUnvalidatedArrayKeys()` configuration | Conditional soundness | Highest risk | Implemented locally |
| 4 | Preserve listness through safe nested projection | Precision defect | Medium | Pending |
| 5 | Narrow native numerics for numeric `in` parameters | Precision improvement | Low | Pending |

Slice order balances practical impact and isolation. Slice 3 carries the
greatest soundness risk, but slices 1 and 2 are small, independent corrections.
If a downstream application calls `includeUnvalidatedArrayKeys()`, slice 3
must move ahead of all precision work for that application.

## Slice 1: Laravel rule aliases

Laravel normalizes `Int` to `Integer` and `Bool` to `Boolean` after converting
rule names to StudlyCase. The extension previously stopped after StudlyCase,
leaving both common aliases as unknown rules and discarding all value
narrowing.

Normalize the aliases at the shared parser boundary so direct validators,
facades, request helpers, controllers, and FormRequest rule resolution all use
the same canonical names. Completion requires:

- parser assertions for both aliases;
- exact TypeResolver equivalence with the canonical rules;
- a PHPStan fixture through an ordinary Laravel validation entrypoint;
- FormRequest coverage; and
- runtime equivalence checks exercised by every supported Laravel CI profile.

The implementation normalizes both aliases at the shared parser boundary.
Local source verification found Laravel's identical mapping in every cached CI
profile from 10.0.0 through 13.25.0. Focused parser, resolver, facade,
FormRequest, and runtime tests pass; the runtime test will execute against the
complete supported-version matrix in CI.

## Slice 2: factory direct validation

`Illuminate\Validation\Factory::validate($data, $rules)` returns the validated
array directly. The extension handles `Factory::make()` but not this direct
entrypoint even though rule-set and shape evaluation already exist for the
facade equivalent.

Add a dynamic method return-type extension or extend the existing factory
extension without changing `make()`'s validator-object result. Cover nested
dotted rules, unresolved rules, custom contracts, named arguments, and
coexistence with Larastan.

The existing factory method extension now evaluates direct `validate()` calls
while preserving `make()`'s validator-object result. Static fixtures cover
nested dotted rules, named arguments in source order, broad fallback for
dynamic or unpacked rules, declared custom-rule contracts, and both Larastan
registration orders. A shared argument resolver applies the same named and
unpacked-argument handling across every validation entrypoint. A runtime
witness calls the direct method positionally and with named arguments and will
run against every supported Laravel CI profile.

## Slice 3: unvalidated nested array keys

Laravel's validation factory excludes unvalidated array keys by default on
every supported major. Calling `includeUnvalidatedArrayKeys()` flips that
factory-wide setting and allows nested values not represented by child rules
to survive in `validated()`.

Current inference models the default exclusion behavior. It becomes too
narrow when an application flips the factory setting. Cross-file detection of
a service-provider call would be incomplete, so the primary design should be
an explicit configuration option with documented defaults and conservative
widening. Any optional automatic detection must only improve usability; it
must not be the sole soundness mechanism.

Completion requires runtime witnesses for both factory modes on Laravel 10
through 13, nested arrays and lists, facade/factory/request/FormRequest static
fixtures, result-cache coverage, and a prominent statement of the modeled
assumption. Directly constructed `Validator` instances remain outside current
inference and must not be accidentally narrowed.

The `includeUnvalidatedArrayKeys` option defaults to `false`, matching
Laravel's factory default. Enabling it prevents bare `array` and version-aware
`list` parents from being treated as closed nested projections. Runtime tests
cover both factory modes for associative and list-shaped data on every Laravel
CI profile; a Laravel 11.23+ branch also covers literal `list` reconstruction.
One configuration-specific fixture covers factory, facade, request,
controller, and FormRequest output while confirming that direct Validator
construction remains broad. PHPStan's project-configuration hash invalidates
cached results when the option changes, with a subprocess regression test
covering the transition. Direct exclusion rules receive separate runtime and
static witnesses because Laravel mutates the validated parent before returning
it; listness and required-offset guarantees are widened when that mutation can
remove an immediate child.

A possible follow-up is a PHPStan rule that reports direct calls to
`Factory::includeUnvalidatedArrayKeys()` (including facade passthroughs) while
the matching extension option remains disabled. Such a diagnostic would make
common configuration mismatches visible, but it cannot replace the explicit
option: application boot code may be outside analyzed paths, and a later
`excludeUnvalidatedArrayKeys()` call can restore the default behavior.

## Slice 4: nested list projection

A bare `list` rule proves that successful input keys are consecutive integers.
For a direct required wildcard child such as `items.* => required|string`,
Laravel's reconstructed output remains a list, but current wildcard evaluation
emits `array<int|string, string>`.

Do not intersect every nested list with PHPStan's list accessory. Exclusion,
missing-child projection, and conditional branches can remove individual
elements or the parent and can produce sparse output. Preserve listness only
when every matched input element is guaranteed to contribute output. Test the
Laravel 11.22/11.23 reconstruction boundary, zero matches, exclusions,
conditional rules, and deeper wildcard paths before narrowing.

## Slice 5: numeric membership precision

Numeric `in` parameters require a broad `numeric-string` and `Stringable`
branch because Laravel casts values to strings and compares loosely. Current
inference also uses unrestricted `int` and `float` branches. Finite literal
parameters could narrow those native branches to the corresponding constant
integers and floats while leaving the string and object branches broad.

This improvement must cover exponent notation, whitespace and leading-zero
strings, integer-valued floats, negative zero, non-finite float spellings,
multiple numeric parameters, and both string rules and fresh `Rule::in()`
builders. If PHPStan cannot represent a runtime equivalence class faithfully,
retain the broader type.

## Lower-priority observations

- `integer` accepts a subset of `numeric-string` that PHPStan does not expose
  as a standard accessory type. Do not replace the sound broad branch with a
  convenient but false narrower type.
- Positive `min` parameters can sometimes prove `non-empty-string`,
  `non-empty-array`, or size bounds. This is independent parameter-sensitive
  inference work and should not be folded into the slices above.
- The report's escaped paths, nullable values, exclusions, enums, JSON,
  numeric rule keys, and date behaviors agreed with current inference and need
  no corrective work.
