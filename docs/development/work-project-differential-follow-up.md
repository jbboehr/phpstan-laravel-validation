# Work-project differential follow-up

This record tracks actionable findings from
[issue #21](https://github.com/jbboehr/phpstan-laravel-validation/issues/21),
which compared Laravel runtime output with extension inference in a large
Laravel 11 application. Application names and rules were replaced with
synthetic reproductions. The original pass found no unconditional soundness
failure.

All six slices have been implemented. This document preserves their original
motivation, acceptance criteria, and verification; it is not a pinned
compatibility claim for the proprietary application.

## Priorities

| Slice | Finding | Kind | Priority | Status |
| ---: | --- | --- | --- | --- |
| 1 | Normalize Laravel's `int` and `bool` aliases | Precision defect | High | Implemented |
| 2 | Infer `Illuminate\Validation\Factory::validate()` | Missing entrypoint | High | Implemented |
| 3 | Model `includeUnvalidatedArrayKeys()` configuration | Conditional soundness | Highest risk | Implemented |
| 4 | Preserve listness through safe nested projection | Precision defect | Medium | Implemented |
| 5 | Narrow native numerics for numeric `in` parameters | Precision improvement | Low | Implemented |
| 6 | Refine positive `min` constraints on known strings and collections | Precision improvement | Low | Implemented |

The implementation order balanced practical impact and isolation. Slice 3
carried the greatest soundness risk, while slices 1 and 2 were small,
independent corrections. For any downstream application that called
`includeUnvalidatedArrayKeys()`, slice 3 took priority over precision work.

## Slice 1: Laravel rule aliases

Laravel normalizes `Int` to `Integer` and `Bool` to `Boolean` after converting
rule names to StudlyCase. The extension previously stopped after StudlyCase,
leaving both common aliases as unknown rules and discarding all value
narrowing.

The slice required normalization at the shared parser boundary so direct
validators, facades, request helpers, controllers, and FormRequest rule
resolution all use the same canonical names. Its acceptance criteria were:

- parser assertions for both aliases;
- exact TypeResolver equivalence with the canonical rules;
- a PHPStan fixture through an ordinary Laravel validation entrypoint;
- FormRequest coverage; and
- runtime equivalence checks exercised by every supported Laravel CI profile.

The implementation normalizes both aliases at the shared parser boundary.
Local source verification found Laravel's identical mapping in every cached CI
profile from 10.0.0 through 13.25.0. Focused parser, resolver, facade,
FormRequest, and runtime tests pass, and the runtime test is exercised by the
complete supported-version matrix in CI.

## Slice 2: factory direct validation

`Illuminate\Validation\Factory::validate($data, $rules)` returns the validated
array directly. At the time of the finding, the extension handled
`Factory::make()` but not this direct entrypoint even though rule-set and shape
evaluation already existed for the facade equivalent.

The slice required extending factory inference without changing `make()`'s
validator-object result. Its coverage had to include nested dotted rules,
unresolved rules, custom contracts, named arguments, and coexistence with
Larastan.

The existing factory method extension now evaluates direct `validate()` calls
while preserving `make()`'s validator-object result. Static fixtures cover
nested dotted rules, named arguments in source order, broad fallback for
dynamic or unpacked rules, declared custom-rule contracts, and both Larastan
registration orders. A shared argument resolver applies the same named and
unpacked-argument handling across every validation entrypoint. A runtime
witness calls the direct method positionally and with named arguments and is
exercised by every supported Laravel CI profile.

## Slice 3: unvalidated nested array keys

Laravel's validation factory excludes unvalidated array keys by default on
every supported major. Calling `includeUnvalidatedArrayKeys()` flips that
factory-wide setting and allows nested values not represented by child rules
to survive in `validated()`.

Before this slice, inference modeled only the default exclusion behavior and
became too narrow when an application flipped the factory setting. Cross-file
detection of a service-provider call would have been incomplete, so the chosen
design used an explicit configuration option with documented defaults and
conservative widening. Optional automatic detection could improve usability,
but could not be the sole soundness mechanism.

Completion required runtime witnesses for both factory modes on Laravel 10
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

The extension now reports direct factory calls and statically resolved facade
calls that switch to the mode opposite the configured option. Both directions
matter: included keys can invalidate closed parent shapes, while restoring
exclusion can reconstruct a list with sparse keys. The diagnostic remains
call-local and cannot replace the explicit option. Application boot code may
be outside analyzed paths, container aliases may be indirect, and a later call
can reverse the mode again.

## Slice 4: nested list projection

A bare `list` rule proves that successful input keys are consecutive integers.
For a direct required wildcard child such as `items.* => required|string`,
Laravel's reconstructed output remains a list, but inference before this slice
emitted `array<int|string, string>`.

The slice could not simply intersect every nested list with PHPStan's list
accessory. Exclusion, missing-child projection, and conditional branches can
remove individual elements or the parent and can produce sparse output. Rule
insertion order also matters: an optional path can emit key `1` before a later
required path appends key `0`. Listness is preserved only when the first
effective projection path emits every matched input element in original order.
Coverage therefore includes the Laravel 11.22/11.23 reconstruction boundary,
zero matches, exclusions, conditional rules, and deeper wildcard paths.

The implementation now retains PHPStan's list accessory when a bare `list`
parent has one wildcard projection whose first effective path emits every
matched element in input order. A direct scalar child keeps its element type on
every version that provides the built-in rule, including the parent's blank
bypass and allowed-key or required-offset constraints. A required nested child
keeps listness before Laravel 11.23 and gains its projected element shape from
11.23, when Laravel begins rebuilding literal-list parents from nested rules.

Runtime witnesses cover the 11.22/11.23 boundary and continue through the
supported 12 and 13 profiles. Optional descendants, deeper wildcard paths,
zero-match branches, earlier optional projection paths, and exclusions remain
broad where output can become sparse, reordered, or disappear. The exclusion
probes also exposed a neighboring soundness issue: Laravel removes excluded
descendants before either factory mode returns, so listness and
`required_array_keys` guarantees are now widened
whenever that mutation can escape the projected shape. Widening stops at a
surviving intermediate rule, and nested field removal in inclusion mode does
not discard the outer list guarantee.

## Slice 5: numeric membership precision

Implemented. Numeric `in` parameters now narrow native integer
alternatives to constants when PHP's comparison has one safely representable
integer equivalence class. This covers canonical integers, signs, whitespace,
leading zeroes, integer-valued decimals and exponents, negative zero, and
underflow to zero. Multiple parameters contribute the union of their safe
integer alternatives. Fresh `Rule::in()` builders use the same model unless a
parameter originated as a float; those builders retain broad `int` because
application code can change PHP's `precision` before Laravel performs the
runtime stringification.

The `numeric-string` and `Stringable` branches remain broad. The native
`float` branch also remains broad: PHP's configurable float-to-string
precision lets nearby floats produce the same comparison string, so a
constant-float union would be unsound. Integer-valued decimal or exponent
parameters above the exactly representable float range retain broad `int`
when adjacent native integers can compare equal. Runtime and static tests cover
these limits, including non-finite spellings and rejected integer candidates.

## Slice 6: positive minimum size precision

Implemented. A definitely positive `min` parameter now refines an
adjacent native `string` rule to `non-empty-string` and an adjacent `array` or
supported `list` rule to its non-empty form. `Min` remains neutral by itself:
without an adjacent native-family rule, Laravel may measure a number, string,
array, or file, so inferring a standalone type would be false.

Optional blank strings still bypass both the native-family rule and `min`, so
they remain in non-normalized output. HTTP-normalized analysis can remove that
blank branch where the configured request path justifies it. Zero, negative,
non-numeric, and missing parameters do not refine. Exact decimal parsing
recognizes positive values too small for a native float, such as `1e-4000`,
without rounding them to zero during analysis.

Runtime witnesses cover strings, arrays, lists where supported, optional blank
bypass, empty-collection rejection, and exponent parameters across the Laravel
10 through 13 CI matrix. Structural property cases exercise nested projection,
including allowed-key arrays emptied by exclusion, and missing-child
interactions so an input-size constraint is not confused with the shape Laravel
returns after projection.

## Lower-priority observations

- `integer` accepts a subset of `numeric-string` that PHPStan does not expose
  as a standard accessory type. Do not replace the sound broad branch with a
  convenient but false narrower type.
- The report's escaped paths, nullable values, exclusions, enums, JSON,
  numeric rule keys, and date behaviors agreed with current inference and need
  no corrective work.
