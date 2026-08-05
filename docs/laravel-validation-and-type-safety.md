# Laravel validation and type safety

> [!CAUTION]
>
> Laravel validation is not a typed data boundary. It is a collection of
> runtime predicates, coercive comparisons, presence rules, cross-field
> conditions, wildcard traversal, and output-projection behavior encoded
> largely through strings.
>
> Successful validation commonly preserves the original input value rather
> than producing the native PHP type suggested by the rule name. The same rule
> set also determines whether keys are required, skipped, included, excluded,
> or rebuilt from nested children. Its apparent declaration and its actual
> runtime contract are often very different things.
>
> `phpstan-laravel-validation` attempts to describe that contract soundly. The
> resulting types are sometimes unexpectedly broad because Laravel's successful
> output is unexpectedly broad. The extension can mitigate the problem for
> existing applications; it cannot make the underlying design coherent.

## Laravel validation is not typed parsing

A typed parser consumes an input representation and produces a value whose
native type is part of its contract. Laravel validation usually answers a
different question: does this original value happen to satisfy these rules?
When the answer is yes, `validated()` generally returns that original value.

That distinction is visible in rules whose names sound like output types. At
the pinned Laravel 10 through 13 releases, both of these validations succeed:

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

The `integer` rule has not produced an `int`. It has accepted values that PHP's
filter semantics consider integer-like and then preserved their original
native types. Similarly, `required|numeric` may return an `int`, a `float`, or
a `numeric-string`; it does not produce one canonical numeric representation.

Laravel validation can still enforce runtime domain constraints such as email
syntax, ranges, and membership. The problem is not that predicates are useless.
The problem is that successful predicates are easily mistaken for declarations
about the returned data. A rule name describes a test, not necessarily an
output type, and the validator performs little normalization to close that gap.

## Rule names do not describe the output type

The scalar `in` rule is a particularly clear example. This validation accepts
and preserves `true`:

```php
$validated = Validator::make(
    ['value' => true],
    ['value' => 'required|in:1'],
)->validated();

// ['value' => true]
```

The following inputs all pass and remain unchanged in the output:

| Rules | Input | Preserved output |
| --- | --- | --- |
| `required\|in:1` | `'1'` | `'1'` |
| `required\|in:1` | `1` | `1` |
| `required\|in:1` | `1.0` | `1.0` |
| `required\|in:1` | `true` | `true` |
| `required\|in:1` | `'01'` or `'1e0'` | the unchanged string |
| `required\|in:one` | a `Stringable` returning `one` | the same object |

At every pinned Laravel revision, the relevant scalar implementation is:

```php
return ! is_array($value) && in_array((string) $value, $parameters);
```

Laravel casts the value for a non-strict comparison, then discards the cast and
returns the original value. `in:1` therefore is not an enum-like declaration of
the literal string `'1'`. A sound description of `required|in:1` needs a union
such as:

```php
float|int|numeric-string|Stringable|true
```

`phpstan-laravel-validation` infers that union because giving downstream code
the prettier literal type would be false. This is not an analyzer inventing an
inconvenient edge case. It is Laravel preserving values admitted by the runtime
contract Laravel created.

The more faithfully static analysis models this behavior, the less the rule
resembles the narrow declaration it appears to be.

## Optionality is not merely key presence

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

The field may therefore be absent, or it may be present with an array, or it
may be present with a blank string for which the `array` predicate never ran.
Whitespace-only strings have the same validator-level behavior. Adding
`required` changes the accepted value domain as well as key presence.

This is more than an optional-offset problem. Presence, blankness, whether a
rule executes, and which original value reaches the output are separate states
compressed into the same rule list.

Laravel's standard HTTP middleware stack commonly trims strings and converts
empty strings to `null`. That often prevents this exact empty-string path in an
ordinary HTTP request, and nullable fields must then account for `null`.
Direct `Validator::make()` calls, jobs, tests, JSON or programmatically
assembled data, and customized middleware stacks still expose the underlying
validator behavior. Static reasoning about the validator cannot assume that a
particular application middleware pipeline always ran first.

## Validation rules also control output projection

Laravel's rule array is not merely a set of acceptance predicates. It is also a
projection specification whose branches decide which successful input values
appear in the result.

### Exclusion rules remove accepted input

An exclusion rule can remove a present value from successful validated output:

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

$included = Validator::make([
    'kind' => 'member',
    'value' => 'visible',
], $rules)->validated();
// ['kind' => 'member', 'value' => 'visible']

\PHPStan\dumpType($included);
// array{kind: string, value?: string}
```

The input value is not merely conditionally valid. It is conditionally absent
from the output. Without preserving the relationship to `kind`, the only
honest structural summary gives `value` an optional offset. Treating validation
as a field-by-field map of names to predicates misses this behavior entirely.

### Nested arrays may retain keys no child rule validated

A bare array rule validates the parent as an array and then preserves every key
inside it:

```php
$input = [
    'user' => [
        'name' => 'Ada',
        'admin' => true,
        'metadata' => ['source' => 'import'],
    ],
];

$validated = Validator::make($input, [
    'user' => 'required|array',
])->validated();

// The complete user array is preserved, including admin and metadata.
// array{user: array}
```

Laravel provides two different mechanisms that are easy to conflate:

- `array:name` rejects an input array containing keys other than `name`.
- With the validator factory's default exclusion setting, adding
  `user.name => required|string` rebuilds the parent from validated children
  and omits siblings such as `admin` and `metadata`.

The first mechanism restricts which input keys may exist. The second projects
selected children into the returned data. A bare `array` rule does neither, so
inferring a closed nested shape from it would be unsound.

This is a remarkable amount of output-construction policy hidden inside what
looks like validation metadata. Whether an unmentioned nested key survives
depends not only on the parent rule but also on the existence of child rules
and validator-factory configuration.

## Cross-field rules turn local declarations into runtime programs

A rule attached to one field cannot necessarily be interpreted from that field
alone. `accepted_if` changes its accepted values according to another field:

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

Reading `accepted_if` as an unconditional restriction would exclude the valid
`42` branch. A precise model must correlate the value of `other` with the value
domain of `value`. Rules such as `required_if`, `required_with`, `exclude_if`,
`exclude_unless`, and their relatives introduce similar relationships between
one field's value, another field's presence, and the final output shape.

Declarative conditions can be modeled as unions of correlated shapes in
principle. In practice, interacting conditions, missing and blank states,
wildcards, and exclusions multiply branches quickly. Callback conditions and
runtime services may not expose a static contract at all.

The rule appears local and declarative, but its actual contract is a small
runtime program over the rest of the input.

## Wildcards are runtime traversal, not shape declarations

A required wildcard descendant does not require any matching element to exist:

```php
$validated = Validator::make([], [
    'person.*.email' => 'required|string|email',
])->validated();

// []

\PHPStan\dumpType($validated);
// array{person?: array<int|string, array{email: non-empty-string}>}
```

`required` applies to each element discovered by wildcard expansion. If there
are no elements, there are no failed required checks and no `person` key in the
result. The descendant is required while the collection containing it remains
optional.

The path is therefore not a shape declaration. It describes traversal over
runtime data, quantification over whatever elements happen to be present,
validation of each match, and construction of corresponding output paths. A
rule name cannot be understood without its position in that traversal.

## One rule language combines unrelated responsibilities

Consider a compact rule set:

```php
[
    'status' => 'required|string|in:draft,published',
    'payload' => 'required_if:status,published|exclude_unless:status,published|array:id',
    'payload.id' => 'required_with:payload|integer|string',
]
```

The same array encodes all of the following:

- key presence;
- blank-value policy and whether predicates run;
- value predicates and coercive comparisons;
- cross-field dependencies;
- wildcard traversal over runtime collections;
- inclusion in or exclusion from validated output;
- reconstruction and projection of nested arrays;
- database-backed checks such as `exists` and `unique`; and
- application-defined rules, callbacks, and validator extensions.

These are not one clean operation. Their interactions determine validation
success, native value types, key presence, and output shape. The string syntax
provides little language-level identity for IDE navigation or refactoring, and
a change that looks local can alter several parts of the runtime contract.

Laravel validation is difficult to type soundly because its rule language
describes several loosely coupled runtime operations rather than one coherent
data transformation. The resulting complexity belongs to the validation
system, even when static analysis is where it finally becomes visible.

### The language is open-ended at runtime

Rule expressions can also be assembled dynamically or obtained from arbitrary
services:

```php
$rules = app(TenantValidationRules::class)->forRequest($request);
$validated = Validator::make($request->all(), $rules)->validated();
```

Runtime-aware tooling can sometimes recover this information. Larastan, for
example, boots the application and may resolve services through Laravel's
container during analysis. That is analysis-time execution of application
infrastructure, not a contract expressed by the rule expression itself, and it
makes the result dependent on application bootstrap and state available during
analysis. `phpstan-laravel-validation` does not currently use that strategy.

A declared return type, source analysis, or a project-specific PHPStan
extension may provide a static contract for such code. Without one, neither a
human reader nor an expression-level analyzer can derive a precise shape from
the call site. Custom rule objects and validator extensions can add semantics
that are absent from Laravel's built-in rule language altogether.

## Soundness versus precision

In this document, **soundness** means that a static type includes every value
Laravel can return after successful validation. If Laravel can preserve `true`
but a type reports only `string`, the type is unsound.

**Precision** describes how much useful information remains in a sound type.
`mixed` may be sound but nearly useless; a union can be sound and substantially
more informative. Neither property requires a class. A sound inferred array
shape provides genuine static type safety for downstream PHPStan analysis.

Laravel's semantics force some types to be broad or optional:

| Situation | Honest structural description | Why |
| --- | --- | --- |
| `required\|in:1` | `float\|int\|numeric-string\|Stringable\|true` | Coercive comparison accepts multiple preserved native types. |
| optional `array` | optional `array\|string` | A blank string can bypass the predicate. |
| conditional acceptance | a broad value when the branch is unknown | The inactive branch accepts values excluded by the active branch. |
| conditional exclusion | an optional output offset | A present input can be removed from the result. |
| wildcard-only descendants | an optional parent offset | Wildcard expansion can find no elements. |
| bare `array` | a general `array` value | Unspecified nested keys are retained. |

For a static analyzer, an attractive type that excludes successful Laravel
output is worse than a broad type. A broad inferred type is sometimes the only
honest description of Laravel's contract.

Not every loss of precision is inherent. There are two distinct sources:

- breadth required by Laravel's actual runtime semantics; and
- breadth caused by static information that is unavailable or not yet
  supported by a particular analyzer.

That distinction prevents Laravel's design problems from becoming an excuse
for incomplete tooling, while also preventing tooling from disguising the
framework's behavior with a narrower and false type.

## What static analysis can salvage

Laravel's validation APIs normally expose successful output as a general
`array`. For supported, statically resolvable rule expressions,
`phpstan-laravel-validation` can recover a much more useful structural type:

```php
$validated = Validator::make($input, [
    'email' => 'required|string|email',
    'amount' => 'required|numeric|string',
])->validated();

\PHPStan\dumpType($validated);
// array{email: non-empty-string, amount: numeric-string}
```

The extension can infer nested shapes, optional offsets, and unions; track
supported validator unions and constant `setRules()` replacements; and retain
`mixed` where a known field has no usable static value contract. When the rule
array itself cannot be resolved, PHPStan generally keeps Laravel's broad
declared return type.

The extension cannot repair Laravel's runtime semantics. It can stop
downstream code from assuming a prettier contract than Laravel actually
provides. In that sense, the inferred type is both mitigation and evidence: an
ugly union often records an ugly framework behavior rather than an analyzer
failure.

Soundness claims remain deliberately scoped. The project aims to infer sound
and useful structural types and provides sound inference for supported
combinations covered by adversarial conformance tests. It remains experimental;
finite fixtures and tests do not prove every Laravel rule, custom extension, or
runtime configuration universally sound.

The `integer` preservation examples earlier in this document illustrate that
boundary. They are covered by a Laravel runtime regression test, but the
current inferred type does not yet include every verified non-integer value.
They are evidence about Laravel, not a claim of completed inference support.

### Additional sources of static information

Precision for dynamic rule sources can sometimes be recovered through declared
contracts or other PHPStan extensions. Larastan, for example,
[`boots the Laravel application`](https://github.com/larastan/larastan/blob/89ee3e54b6f6bd5aec43da8e9d4c2ac6b36e6ffc/bootstrap.php),
and some of its return-type support
[`resolves services through the container`](https://github.com/larastan/larastan/blob/89ee3e54b6f6bd5aec43da8e9d4c2ac6b36e6ffc/src/ReturnTypes/AppMakeHelper.php).

`phpstan-laravel-validation` does not currently boot the application or execute
services to discover rules. Runtime-aware analysis can recover information, but
it also makes results depend on application bootstrap, configuration,
registered providers, and the analysis environment.

Widening is not a tooling failure when analysis has no usable contract for
runtime behavior. Silently inventing one would be.

## Architectural alternatives for new code

Structural inference is genuine static type safety. The objection to Laravel
validation is its irregular runtime contract, not the mere fact that
`validated()` returns an array.

Applications may independently prefer DTOs, schema objects, explicit parsers,
or a typed object mapper such as
[`cuyz/valinor`](https://github.com/CuyZ/Valinor) when they also want
normalization, nominal identity, runtime-enforced properties, or a named
architectural boundary. Those are separate advantages rather than prerequisites
for useful array-shape inference.

Laravel validation can continue to perform runtime domain checks while static
analysis describes its result. For new type-conscious code, a boundary whose
output contract is explicit and normalized is usually easier to understand and
maintain than reconstructing a type from Laravel's interacting rule semantics.

## What the project can and cannot promise

For supported, statically resolvable rule combinations covered by conformance
tests, `phpstan-laravel-validation` can:

- infer useful nested array shapes and optional offsets;
- preserve sound unions when Laravel accepts multiple native input types;
- track supported validator unions and constant `setRules()` replacements;
- expose assumptions that conflict with Laravel's successful output; and
- make incremental static typing practical in applications that already use
  Laravel validation.

It cannot:

- make Laravel normalize values that it preserves;
- infer arbitrary callbacks, custom rule objects, validator extensions, or
  runtime-generated rule arrays without a static contract;
- guarantee precise types for unsupported rule combinations or runtime
  configuration invisible to analysis;
- eliminate behavior differences introduced by future Laravel versions; or
- provide nominal identity or runtime property enforcement for an array.

This library is a compatibility-focused static-analysis layer, not an
endorsement of Laravel validation for new code.

## Verification methodology

The concrete Laravel behaviors in this document are tested against Laravel
itself rather than inferred from rule names. The repository supports Laravel
10 through 13, and its committed upstream fixtures are pinned to:

| Laravel | Release | Commit |
| --- | --- | --- |
| 10 | 10.50.2 | [`3ff39b7a9b83`](https://github.com/laravel/framework/commit/3ff39b7a9b83e633383ec9b019827ed54b6d38bc) |
| 11 | 11.55.0 | [`dc7ec34ae95b`](https://github.com/laravel/framework/commit/dc7ec34ae95bacf4a63b96ec81482b4f3e702289) |
| 12 | 12.64.0 | [`727a8ea2949c`](https://github.com/laravel/framework/commit/727a8ea2949c23ca8b5316b86a00984b6017b7a0) |
| 13 | 13.23.0 | [`92a707229148`](https://github.com/laravel/framework/commit/92a707229148e57f08a249211c8a5a194159c619) |

The [CI matrix](../.github/workflows/ci.yml) installs every supported Laravel
major and runs the complete PHPUnit suite. Runtime methods in the table below
are defined in
[`tests/LaravelInferenceTest.php`](../tests/LaravelInferenceTest.php). The main
documented claims map to the following persistent coverage:

| Claim | Laravel runtime coverage | PHPStan inference coverage |
| --- | --- | --- |
| `integer` can preserve non-integers | `LaravelInferenceTest::testIntegerRuleCanPreserveNonIntegerValues` | Known inference gap; no sound static assertion yet |
| Scalar `in` preserves coercible inputs | `LaravelInferenceTest::testScalarInRuleAcceptsRuntimeValues` | [`tests/rules/in.php`](../tests/rules/in.php) |
| Optional blanks bypass non-implicit rules | `LaravelInferenceTest::testBlankStringBypassesOptionalNonImplicitRules` | [`tests/structure/empty-string.php`](../tests/structure/empty-string.php) |
| Conditional acceptance broadens values | `LaravelInferenceTest::testConditionalValueRulesRemainConservative` | [`tests/rules/accepted-if.php`](../tests/rules/accepted-if.php) |
| Conditional exclusion changes shape | `LaravelInferenceTest::testConditionalExclusionChangesTheValidatedShape` | [`tests/rules/exclude-if.php`](../tests/rules/exclude-if.php) |
| Required wildcard descendants may match nothing | `LaravelInferenceTest::testRequiredWildcardDescendantDoesNotRequireMissingParent` | [`tests/structure/wildcard.php`](../tests/structure/wildcard.php) |
| Bare arrays preserve nested keys | `LaravelInferenceTest::testArrayRuleWithoutKeyParametersPreservesNestedKeys` | [`tests/rules/array.php`](../tests/rules/array.php) |
| Array key lists reject undeclared keys | `LaravelInferenceTest::testArrayRuleKeyParametersRejectUndeclaredNestedKeys` | [`tests/rules/array.php`](../tests/rules/array.php) |
| Nested child rules project validated keys | `LaravelInferenceTest::testParentAndChildRulesAcceptRuntimeOutput` | [`tests/structure/parent-rules.php`](../tests/structure/parent-rules.php) |

The generated fixtures under [`tests/fixtures`](../tests/fixtures) add broad
coverage from Laravel's own validation tests and record their exact upstream
provenance. They include Laravel's array, allowed-key, and nested projection
cases. A fixture proves that an inferred type accepts the observed successful
output; it does not prove that one observation exhausts every value a rule can
accept. The focused adversarial tests above support the specific claims made
here.

Static coverage confirms the type the extension emits. Runtime coverage checks
Laravel's actual successful output. Expected types are changed only after
checking Laravel behavior, and runtime-only evidence is not presented as
completed static inference support.

Last reviewed: 2026-08-05.

## Conclusion

Laravel validation is not a typed data boundary. It is a pile of coercive
predicates, presence rules, cross-field conditions, wildcard traversal, and
output projection disguised as a compact schema language.

A sound analyzer cannot make that underlying contract coherent. It can only
expose it. When a simple rule produces an ugly union, the ugly type is Laravel's
behavior with the wishful thinking removed.

`phpstan-laravel-validation` mitigates the damage for existing applications.
For new type-conscious code, Laravel validation is the wrong foundation.

This project is a mitigation—not a vindication. This document is a
condemnation.
