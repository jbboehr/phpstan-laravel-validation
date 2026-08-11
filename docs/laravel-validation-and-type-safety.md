# Laravel validation and type safety

> [!CAUTION]
>
> Laravel validation is not a typed data boundary. Its compact rule syntax
> combines value predicates with presence rules, cross-field control flow,
> wildcard traversal, and output projection. Successful validation often
> preserves a native value that the rule name appears to exclude or normalize.
>
> `phpstan-laravel-validation` describes that runtime contract as honestly as
> possible. It can mitigate the problem for existing applications; it cannot
> turn the underlying design into a coherent typed transformation.

## TL;DR

**[Validates, doesn't parse](https://lexi-lambda.github.io/blog/2019/11/05/parse-don-t-validate/).**

Laravel validation mostly establishes that an input value satisfies some
predicates. It generally does not construct a correspondingly typed
representation of that value. A rule such as `integer` therefore cannot
soundly mean that `validated()` returns an `int`.

That is the central problem, but not the only one. The same rule array also
projects the output by including, excluding, and rebuilding keys, while
conditional rules, wildcards, callbacks, and runtime services make it behave
more like a program than a static schema.

## Laravel validation is not typed parsing

A typed parser consumes one representation and produces a value whose native
type is part of its contract. Laravel validation usually answers a different
question: does this original value happen to satisfy these rules? When the
answer is yes, `validated()` generally returns that original value.

The `integer` rule is the canonical example. At the pinned Laravel 10
through 13 releases, both of these validations succeed:

```php
Validator::make(
    ['value' => 1.0],
    ['value' => 'required|integer'],
)->validated();
// ['value' => 1.0]

Validator::make(
    ['value' => true],
    ['value' => 'required|integer'],
)->validated();
// ['value' => true]
```

A `Stringable` object returning an accepted integer string also passes and is
returned as the same object. The rule has not produced an `int`. It has
accepted values that PHP's filter semantics consider integer-like and preserved
their original native types. The sound inferred value type is therefore:

```php
float|int|numeric-string|Stringable|true
```

This union is necessarily broader than Laravel's successful subset because
PHPStan cannot express “an integral float” or “an object whose string
representation passes this PHP filter.” Narrowing it to `int` would be more
attractive and false.

Laravel 12.22 added one revealing exception: `integer:strict` begins
requiring a native integer. Laravel 10, Laravel 11, and Laravel 12.0 through
12.21 accept the same spelling but ignore the `strict` parameter. The rule's
meaning therefore depends on the installed framework release as well as its
text.

The scalar `in` rule provides a particularly sharp second example. At every
pinned Laravel revision, its relevant implementation is:

```php
return ! is_array($value) && in_array((string) $value, $parameters);
```

For `required|in:1`, Laravel accepts and preserves `'1'`, `1`,
`1.0`, `true`, numeric-equivalent strings such as `'01'`, and a
compatible `Stringable` object. The cast is used for comparison and then
discarded, so a sound analyzer again needs the same broad preserved-value
union. `in:1` is not an enum-like declaration of a literal output value. This
is not an analyzer inventing an inconvenient edge case. It is Laravel
preserving values admitted by the runtime contract Laravel created.

The more faithfully static analysis models this behavior, the less the rule
resembles the narrow declaration it appears to be.

Laravel validation can still enforce useful runtime domain constraints such as
email syntax, ranges, and membership. The problem is not that predicates are
useless. The problem is mistaking successful predicates for a declaration of
the returned native representation.

## Optionality changes the accepted value domain

Laravel overloads optionality with blank-value behavior. Many non-implicit
rules are skipped when an optional field is a blank string, but the present
field can still be returned by `validated()`:

```php
$validated = Validator::make(
    ['filters' => ''],
    ['filters' => 'array'],
)->validated();

// ['filters' => '']

\PHPStan\dumpType($validated);
// array{filters?: array|string}
```

The field may be absent, present with an array, or present with a blank string
for which the `array` predicate never ran. Whitespace-only strings have the
same validator-level behavior. Adding `required` changes the accepted value
domain as well as key presence.

Laravel's standard HTTP middleware commonly trims strings and converts empty
strings to `null`, so ordinary request flows may not expose this exact branch.
Direct validators, jobs, tests, programmatically assembled data, and customized
middleware stacks still do. Trimming alone is insufficient: it produces the
empty string that bypasses the rule.

The extension offers an explicit HTTP-normalization assumption for request and
controller inference. It is an application assertion, not automatic middleware
detection; skip callbacks, trim exceptions, and request mutation can invalidate
it. Laravel's default password-related trim exceptions also differ between
Laravel 10 and later supported majors, so even this preprocessing assumption is
version-sensitive.

## Validation is also projection

Laravel's rule array does not merely decide whether input is acceptable. It
also decides which successful values appear in the result and how nested
output is reconstructed.

### Exclusion rules remove accepted input

An exclusion rule can remove a present value from successful output:

```php
$rules = [
    'kind' => 'required|string',
    'value' => 'required|string|exclude_if:kind,guest',
];

Validator::make([
    'kind' => 'guest',
    'value' => 'secret',
], $rules)->validated();
// ['kind' => 'guest']

Validator::make([
    'kind' => 'member',
    'value' => 'visible',
], $rules)->validated();
// ['kind' => 'member', 'value' => 'visible']
```

The value is not merely conditionally valid. It is conditionally absent from
the output. Without preserving the relationship to `kind`, the honest
structural summary gives `value` an optional offset.

### Nested rules decide which keys survive

A bare array rule validates the parent and preserves every nested key:

```php
$input = [
    'user' => [
        'name' => 'Ada',
        'admin' => true,
        'metadata' => ['source' => 'import'],
    ],
];

Validator::make($input, [
    'user' => 'required|array',
])->validated();

// The complete user array is preserved.
```

Laravel provides several different mechanisms that are easy to conflate:

- `array:name` rejects an input array containing keys other than
  `name`.
- With the validator factory's default exclusion setting, adding
  `user.name => required|string` below a bare `array` parent rebuilds the
  parent from validated children and omits unmentioned siblings.
- A parameterized parent such as `array:name` is not Laravel's literal
  reconstruction marker. It preserves the complete permitted parent around
  nested rules, even when those rules emit nothing.

The key list restricts acceptable input keys. Nested rules can project selected
children into the output, but whether that projection replaces the parent also
depends on the exact parent-rule spelling. A bare `array` rule without child
rules preserves undeclared nested keys, so inferring a closed nested shape from
it would be unsound. Whether a key survives depends on parent rules, child
rules, and validator-factory configuration—not simply on a predicate attached
to that key.

## Rules are runtime programs, not static schemas

A rule attached to one field cannot necessarily be interpreted from that field
alone. Paths may traverse runtime collections, other fields may activate or
deactivate constraints, and callbacks or services may supply behavior that is
not present in the rule expression.

### Cross-field rules require correlated types

`accepted_if` changes its accepted values according to another field:

```php
$rules = [
    'other' => 'required|string',
    'value' => 'required|accepted_if:other,match',
];

Validator::make([
    'other' => 'different',
    'value' => 42,
], $rules)->validated();
// ['other' => 'different', 'value' => 42]

Validator::make([
    'other' => 'match',
    'value' => 'yes',
], $rules)->validated();
// ['other' => 'match', 'value' => 'yes']
```

Reading the rule as an unconditional local restriction excludes the valid
`42` branch. A precise model must correlate `other` with the value
domain of `value`. `required_if`, `required_with`,
`exclude_if`, `exclude_unless`, and related rules introduce similar
relationships between values, presence, and output shape.

Such conditions can be represented as unions of correlated shapes in
principle. Interacting conditions, blank states, wildcards, and exclusions
multiply branches quickly; callback conditions may provide no static contract
at all. The apparently local declaration is a runtime program over the rest of
the input.

### Wildcards are quantified traversal

A required wildcard descendant does not require any match to exist:

```php
$validated = Validator::make([], [
    'person.*.email' => 'required|string|email',
])->validated();

// []

\PHPStan\dumpType($validated);
// array{person?: array<int|string, array{email: non-empty-string}>}
```

`required` applies to each element discovered by wildcard expansion. If
there are no elements, there are no failed checks and no `person` key in
the result. The descendant is required while its containing collection remains
optional.

This path is not a shape declaration. It combines traversal, quantification
over runtime elements, validation of each match, and construction of matching
output paths.

### The language is open-ended at runtime

Rules can be assembled dynamically or obtained from arbitrary services:

```php
$rules = app(TenantValidationRules::class)->forRequest($request);
$validated = Validator::make($request->all(), $rules)->validated();
```

A declared return type, source analysis, or project-specific PHPStan extension
may recover a contract. Larastan can go further by
[booting the Laravel application](https://github.com/larastan/larastan/blob/89ee3e54b6f6bd5aec43da8e9d4c2ac6b36e6ffc/bootstrap.php)
and sometimes
[resolving services through the container](https://github.com/larastan/larastan/blob/89ee3e54b6f6bd5aec43da8e9d4c2ac6b36e6ffc/src/ReturnTypes/AppMakeHelper.php).
That is analysis-time execution of application infrastructure, not a contract
expressed at the call site, and its result depends on the available bootstrap
and application state. `phpstan-laravel-validation` does not currently use
that strategy.

Custom rule objects, closures, and registered validator extensions add runtime
semantics absent from Laravel's built-in language. This extension preserves
conservative inference for unknown custom predicates and lets projects provide
a trusted accepted-value contract through configuration, a
`ValidationRuleType` attribute, or an
`@laravel-validation-type` PHPDoc tag. Registered string rule names require
configuration because the extension does not boot the application to discover
them.

Those declarations describe original values preserved after a custom predicate
succeeds. They do not infer arbitrary mutation, implicitness, or output
projection, and an incorrect declaration is unsound just like incorrect
PHPDoc. Widening is not a tooling failure when analysis has no usable contract
for runtime behavior. Silently inventing one would be.

### One string language combines unrelated responsibilities

A Laravel rule array encodes all of the following:

- key presence and blank-value policy;
- value predicates and coercive comparisons;
- cross-field dependencies;
- wildcard traversal;
- output inclusion, exclusion, and nested reconstruction;
- database-backed checks such as `exists` and `unique`; and
- application-defined callbacks, objects, and registered extensions.

These are not one clean operation. Their interactions determine validation
success, native value types, key presence, and output shape. Laravel validation
is difficult to type soundly because its rule language describes several
loosely coupled runtime operations rather than one coherent data
transformation.

## Soundness versus precision

Here, **soundness** means that a static type includes every value Laravel can
return after successful validation. If Laravel can preserve `true` but the
type reports only `string`, the type is unsound.

**Precision** describes how much useful information remains. `mixed` may be
sound but nearly useless; a union can be sound and substantially more
informative. Neither property requires a class. A sound inferred array shape
provides genuine static type safety.

| Situation | Honest structural description | Distinct cause |
| --- | --- | --- |
| `required` with `integer` | `float`, `int`, `numeric-string`, `Stringable`, or `true` | Laravel preserves several native representations admitted by the predicate. |
| optional `array` | optional `array` or `string` | A blank string can bypass the predicate. |
| conditional acceptance | a broad value when the branch is unknown | An inactive branch accepts values excluded by the active branch. |
| conditional exclusion | an optional output offset | A present input can be removed from the result. |
| wildcard-only descendants | an optional parent offset | Wildcard expansion can find no elements. |
| bare `array` | a general `array` value | Unspecified nested keys are retained. |
| unknown custom predicate | `mixed`, intersected with adjacent known predicates | Runtime behavior has no usable static contract. |

Some breadth is required by Laravel's runtime behavior; some reflects static
information that is unavailable or not yet supported by the analyzer. That
distinction matters. It prevents incomplete tooling from being excused as a
framework limitation, while preventing tooling from disguising Laravel's
behavior with a narrower false type.

A broad inferred type is sometimes the only honest description of successful
Laravel output.

## What static analysis can salvage

Laravel's validation APIs normally expose successful output as a general
`array`. For supported, statically resolvable rule expressions,
`phpstan-laravel-validation` can recover a useful structural type:

```php
$validated = Validator::make($input, [
    'email' => 'required|string|email',
    'amount' => 'required|numeric|string',
])->validated();

\PHPStan\dumpType($validated);
// array{email: non-empty-string, amount: numeric-string}
```

For combinations covered by the implementation and conformance tests, the
extension can infer nested shapes, optional offsets, preserved-value unions,
and verified Laravel-version boundaries. It tracks supported validator unions
and constant `setRules()` replacements, applies declared custom-rule
contracts, and its optional experimental FormRequest inference can recover the
whole-payload `validated()` and `validated(null)` shapes of conventional
`FormRequest` subclasses from statically resolvable `rules()` returns. It
retains `mixed` where a field has no usable value contract. When the rule
expression itself cannot be resolved, PHPStan generally keeps Laravel's broad
declared return type.

Form requests make the runtime-program problem especially concrete. Their
effective validator can be replaced or modified by `validator()`,
`withValidator()`, `after()`, `getValidatorInstance()`,
`createDefaultValidator()`, `validationRules()`, and `passedValidation()`.
The extension declines to infer from `rules()` when it detects those lifecycle
customizations, unless the exact class is explicitly trusted. This safeguard
does not turn mutable request state into a declared contract: replacing the
validator later through public `setValidator()` remains outside the automatic
inference assumption.

It cannot make Laravel normalize values, derive precise contracts from
arbitrary runtime code, model arbitrary validator mutation or projection, or
guarantee behavior for unsupported combinations and future framework releases.
The extension aims to infer sound and useful structural types for supported
combinations covered by adversarial conformance tests; it remains experimental,
and finite tests do not prove universal soundness.

The extension cannot repair Laravel's runtime contract. It can prevent
downstream code from assuming a prettier and false one. An ugly union is often
evidence of ugly framework behavior rather than analyzer failure.

## Architectural alternatives for new code

Structural array-shape inference is genuine static type safety. The objection
to Laravel validation is its irregular runtime contract, not the fact that
`validated()` returns an array.

Applications may prefer DTOs, schema objects, explicit parsers, or a typed
object mapper such as
[`cuyz/valinor`](https://github.com/CuyZ/Valinor) when they also want
normalization, nominal identity, runtime-enforced properties, or a named
architectural boundary. Those are separate advantages, not prerequisites for
useful array-shape inference.

Laravel validation can still perform runtime domain checks while static
analysis describes its result. For new type-conscious code, a boundary whose
output contract is explicit and normalized is usually easier to understand
than reconstructing a type from Laravel's interacting rule semantics.

## Verification methodology

The concrete behaviors in this document are tested against Laravel itself
rather than inferred from rule names. The repository supports Laravel 10
through 13, and its committed upstream fixtures are pinned to:

| Laravel | Release | Commit |
| --- | --- | --- |
| 10 | 10.50.2 | [`3ff39b7a9b83`](https://github.com/laravel/framework/commit/3ff39b7a9b83e633383ec9b019827ed54b6d38bc) |
| 11 | 11.55.0 | [`dc7ec34ae95b`](https://github.com/laravel/framework/commit/dc7ec34ae95bacf4a63b96ec81482b4f3e702289) |
| 12 | 12.66.0 | [`82a53323c701`](https://github.com/laravel/framework/commit/82a53323c701a668f9054cbeb1d6b6cdbb6a5e10) |
| 13 | 13.25.0 | [`ed36fe882bd4`](https://github.com/laravel/framework/commit/ed36fe882bd4eed4e6ff75343cbad8dbda03fdba) |

The [CI matrix](../.github/workflows/ci.yml) installs every supported major,
its first release, and known semantic boundary releases, then runs the complete
PHPUnit suite. The separate
[Laravel-version inference audit](laravel-version-inference-audit.md) records
boundary profiles, runtime snapshots, and audit limitations.

Runtime methods in the table below are defined in
[`tests/LaravelInferenceTest.php`](../tests/LaravelInferenceTest.php) and
[`tests/CustomRulesLaravelRuntimeTest.php`](../tests/CustomRulesLaravelRuntimeTest.php),
with FormRequest lifecycle behavior covered by
[`tests/FormRequestLaravelRuntimeTest.php`](../tests/FormRequestLaravelRuntimeTest.php).

| Claim | Laravel runtime coverage | PHPStan inference coverage |
| --- | --- | --- |
| `integer` can preserve non-integers | `LaravelInferenceTest::testIntegerRuleCanPreserveNonIntegerValues` | [`tests/rules/integer.php`](../tests/rules/integer.php) |
| `integer:strict` differs by Laravel release | `LaravelInferenceTest::testIntegerStrictRuleFollowsRuntimeSupport` and `testIntegerStrictRuleAcceptsAndPreservesNativeInteger` | Boundary coverage in [`tests/TypeResolverTest.php`](../tests/TypeResolverTest.php), [`tests/version-aware/inference.php`](../tests/version-aware/inference.php), and the version-audit snapshots |
| `base64` exists only from Laravel 13.21 and requires a native non-empty string | `LaravelInferenceTest::testBase64RuleFollowsRuntimeVersionBoundary` | Boundary coverage in [`tests/TypeResolverTest.php`](../tests/TypeResolverTest.php) and [`tests/version-aware/base64.php`](../tests/version-aware/base64.php) |
| Scalar `in` preserves coercible inputs | `LaravelInferenceTest::testScalarInRuleAcceptsRuntimeValues` | [`tests/rules/in.php`](../tests/rules/in.php) |
| Optional blanks bypass non-implicit rules | `LaravelInferenceTest::testBlankStringBypassesOptionalNonImplicitRules` | [`tests/structure/empty-string.php`](../tests/structure/empty-string.php) |
| HTTP normalization changes blank behavior | `LaravelInferenceTest::testDefaultHttpInputNormalizationChangesOptionalBlankBehavior`, `testTrimStringsAloneDoesNotEliminateBlankStringBypass`, and `testDefaultPasswordTrimExceptionVariesByLaravelMajor` | [`tests/normalized/request.php`](../tests/normalized/request.php), [`tests/structure/request.php`](../tests/structure/request.php), and [`tests/version-aware/inference.php`](../tests/version-aware/inference.php) |
| Conditional acceptance broadens values | `LaravelInferenceTest::testConditionalValueRulesRemainConservative` | [`tests/rules/accepted-if.php`](../tests/rules/accepted-if.php) |
| Conditional exclusion changes shape | `LaravelInferenceTest::testConditionalExclusionChangesTheValidatedShape` | [`tests/rules/exclude-if.php`](../tests/rules/exclude-if.php) |
| Required wildcard descendants may match nothing | `LaravelInferenceTest::testRequiredWildcardDescendantDoesNotRequireMissingParent` | [`tests/structure/wildcard.php`](../tests/structure/wildcard.php) |
| Bare arrays preserve nested keys | `LaravelInferenceTest::testArrayRuleWithoutKeyParametersPreservesNestedKeys` | [`tests/rules/array.php`](../tests/rules/array.php) |
| Array key lists reject undeclared keys | `LaravelInferenceTest::testArrayRuleKeyParametersRejectUndeclaredNestedKeys` | [`tests/rules/array.php`](../tests/rules/array.php) |
| Nested child rules project validated keys | `LaravelInferenceTest::testParentAndChildRulesAcceptRuntimeOutput` | [`tests/structure/parent-rules.php`](../tests/structure/parent-rules.php) |
| Parameterized arrays preserve the permitted parent around nested rules | `PresenceLaravelRuntimeTest::testRuntimeProjection` (named parameterized-parent cases) and the version-audit snapshots | [`tests/rules/missing.php`](../tests/rules/missing.php) and `TypeResolverTest::testParameterizedArrayParentIsPreservedAroundNestedRules` |
| Literal `list` joins nested reconstruction in Laravel 11.23 | `LaravelInferenceTest::testListRuleFollowsRuntimeVersionBoundary` on the 11.22 and 11.23 profiles | [`tests/version-aware/list.php`](../tests/version-aware/list.php), [`tests/version-aware/list-projection.php`](../tests/version-aware/list-projection.php), and `TypeResolverTest::testListParentProjectionChangesInLaravel1123` |
| Custom predicates preserve successful original values | `CustomRulesLaravelRuntimeTest::testObjectRulesPreserveSuccessfulValuesAndRejectOthers`, `testClosureRulePreservesSuccessfulOriginalValue`, and `testRegisteredStringRulePreservesSuccessfulOriginalValue` | [`tests/custom-rules/inference.php`](../tests/custom-rules/inference.php) |
| FormRequest lifecycle hooks can change effective rules and later output | `FormRequestLaravelRuntimeTest::testWithValidatorCanReplaceTheEffectiveRules`, `testIntermediateWithValidatorHookCanReplaceTheEffectiveRules`, `testTraitWithValidatorHookCanReplaceTheEffectiveRules`, `testPassedValidationCanReplaceRulesAfterSuccessfulValidation`, and `testCustomValidatorCanIgnoreRulesMethod` | [`tests/form-request/inference.php`](../tests/form-request/inference.php) |

Generated fixtures under [`tests/fixtures`](../tests/fixtures) add broad
coverage from Laravel's own validation tests and record exact upstream
provenance. A fixture proves that an inferred type accepts an observed
successful output; it does not prove that one observation exhausts every value
a rule can accept. Focused adversarial tests support the specific claims above.

Static coverage confirms the emitted type. Runtime coverage checks Laravel's
actual successful output. Expected types are changed only after checking
Laravel behavior, and runtime-only evidence is not presented as completed
static support.

Last reviewed: 2026-08-07.

## Conclusion

Laravel validation does not perform one coherent typed transformation. It
tests predicates against preserved input, projects output through interacting
rules, and executes conditional traversal over runtime data.

A sound analyzer cannot make that contract cleaner than it is. It can only
prevent downstream code from relying on a prettier fiction.

`phpstan-laravel-validation` is a mitigation for existing applications, not a
vindication of Laravel validation as a foundation for new type-conscious code.
