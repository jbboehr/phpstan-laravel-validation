# Laravel validation rule coverage survey

This document inventories Laravel's built-in validation-rule surface and maps
it to the inference currently implemented by `phpstan-laravel-validation`. It
is an audit and roadmap, not a claim that every listed rule is completely or
universally modeled.

For a lookup table of emitted types, see
[Validation Rules](validation-rules.md).

The important distinction is between a rule that is unknown, a rule that is
deliberately type-neutral, and a rule whose value type is modeled while its
presence or output behavior is not. Treating all three as merely "supported"
would hide the most useful findings.

## Scope and evidence

The inventory was derived from Laravel's `validate*` methods, Validator rule
classifications, and built-in rule objects at the exact commits represented by
the repository's generated fixtures:

| Laravel | Fixture source | Trait validators | Notes |
| --- | --- | ---: | --- |
| 10.50.2 | [`3ff39b7a`](https://github.com/laravel/framework/commit/3ff39b7a9b83e633383ec9b019827ed54b6d38bc) | 102 | Pinned fixture |
| 11.55.0 | [`dc7ec34a`](https://github.com/laravel/framework/commit/dc7ec34ae95bacf4a63b96ec81482b4f3e702289) | 107 | Pinned fixture |
| 12.64.0 | [`727a8ea2`](https://github.com/laravel/framework/commit/727a8ea2949c23ca8b5316b86a00984b6017b7a0) | 110 | Pinned fixture; 12.65.0 and 12.66.0 have the same rule inventory |
| 13.23.0 | [`92a70722`](https://github.com/laravel/framework/commit/92a707229148e57f08a249211c8a5a194159c619) | 111 | Pinned fixture |
| 13.24.0 | [`6d481710`](https://github.com/laravel/framework/commit/6d481710375d2aa67656922ef760cdd2b18bcfe0) | 112 | Pinned boundary fixture; adds `array_keys`; 13.25.0 has the same rule inventory |

`Enum` and `Password` are rule objects rather than `validate*` methods. With
those included, the current Laravel 13.25 surface corresponds exactly to the
114 names reserved by `TypeResolver::BUILT_IN_RULE_NAMES`.

Laravel added these rules and rule-object features during the supported major
range:

- Laravel 10.21.1: `In` and `NotIn` builders gain enum-value serialization,
  and their concrete constructors gain scalar, variadic, and `Arrayable`
  inputs in 10.36;
- Laravel 10.33: `hex_color` and `Rule::unless()`, followed by `extensions` in
  10.34 and `Enum::only()` / `Enum::except()` in 10.46;
- Laravel 11: `prohibited_if_accepted`, `prohibited_if_declined`, and
  `required_if_declined`, followed by `list` in Laravel 11.0.3, the
  `Rule::array()` builder in 11.7, and `contains` in 11.8; Laravel 11.23 later
  makes a literal `list` participate in nested-output reconstruction and adds
  the extended `Dimensions` ratio methods; Laravel 11.40 adds the fluent
  `Date` builder, 11.41 makes its chains usable inside
  rule lists, 11.42 adds the fluent `Numeric` builder, and 11.43.2 makes Date
  chains usable as standalone field rules;
- Laravel 12: `in_array_keys` and `Rule::contains()` in 12.16, followed by
  `doesnt_contain` and `Rule::doesntContain()` in 12.22, `encoding` in 12.40,
  `Rule::dateTime()` and the date builder's
  now-relative predicates in 12.44, and strict integer mode on the `Numeric`
  builder plus the fluent `StringRule` builder in 12.55;
- Laravel 13.21: `base64`, followed by `array_keys` in 13.24.

The generated fixtures contain runtime results from Laravel's own tests. The
focused inference audit adds adversarial witnesses for selected interactions,
but neither source inspection nor a finite fixture corpus proves universal
soundness. A candidate below still needs a focused runtime probe on every
supported major before its inferred type is narrowed.

`CustomRulesInferenceTest::testEveryInstalledLaravelAttributeRuleNameIsReservedFromCustomAliases`
also reflects the installed `ValidatesAttributes` trait. The floating Laravel
CI profiles will therefore detect a newly added `validate*` method even before
the next pinned fixture refresh.

## Status definitions

The tables use three accepted-value statuses:

- **direct**: the rule contributes a non-`mixed` PHPStan type;
- **neutral**: the rule is explicitly recognized but contributes no local
  accepted-value type, leaving adjacent rules to determine it;
- **mixed**: the rule falls through to the conservative default because no
  built-in accepted-value model exists.

These statuses describe only accepted native values. Presence, exclusion,
nested projection, wildcard traversal, and correlations with other fields are
separate dimensions.

## Summary

| Accepted-value handling | Rule names | Focused static coverage | Meaning |
| --- | ---: | ---: | --- |
| Direct type contribution | 57 | 57 | A native value type is emitted and has dedicated focused static coverage |
| Explicitly neutral | 49 | 18 | The rule does not independently narrow the local value type, whether intentionally or because a correlated model is unavailable |
| Conservative `mixed` fallback | 8 | 0 | No built-in accepted-value model is applied |
| **Total reserved names** | **114** | **89 files** | Covers the current Laravel 13.25 name inventory, including `Enum` and `Password` |

The repository's generated Laravel fixtures provide broader conformance
coverage than the focused-file count suggests. Focused files are still
important because they state the intended PHPStan type directly and can include
adversarial native values that Laravel's upstream tests do not exercise.

## Rules with direct accepted-value inference

The following 57 names contribute a concrete type today:

| Family | Rules | Current contribution |
| --- | --- | --- |
| Exact accepted sets | `Accepted`, `Boolean`, `Declined`, `In` | Literal unions or parameter-aware scalar unions; numeric `In` parameters narrow safely representable native integers while retaining broader float, numeric-string, and object equivalence classes; fresh inline `Rule::in()` builders can supply the parameters, with float-bearing builders retaining `int` for runtime precision changes |
| String predicates | `ActiveUrl`, `Alpha`, `CurrentPassword`, `Email`, `Ip`, `Ipv4`, `Ipv6`, `MacAddress`, `Timezone`, `Ulid`, `Url`, `Uuid` | Usually `non-empty-string` |
| Native string checks | `Lowercase`, `String`, `Uppercase` | `string` |
| Coercive text checks | `AlphaDash`, `AlphaNum`, `Json`, `NotRegex`, `Regex` | Unions containing the native scalar or `Stringable` values Laravel preserves |
| Date checks | `After`, `AfterOrEqual`, `Before`, `BeforeOrEqual`, `Date`, `DateEquals`, `DateFormat` | Numeric scalars, non-empty strings, and where applicable `DateTimeInterface` |
| Numeric checks | `Decimal`, `Digits`, `DigitsBetween`, `Integer`, `MaxDigits`, `MinDigits`, `MultipleOf`, `Numeric` | Numeric strings and the native numeric values Laravel accepts and preserves |
| Arrays and files | `Array`, `RequiredArrayKeys`, `Dimensions`, `File`, `Image`, `Mimes`, `Mimetypes` | Array shapes, required-offset constraints, or Symfony file objects; fresh inline array and file builders recover their built-in rule semantics at the applicable Laravel version |
| Built-in object rules | `Enum` | Statically visible enum cases, backing values, and the weakly coerced native values Laravel preserves; literal `only`/`except` state is modeled from Laravel 10.46 |
| Version-sensitive | `ArrayKeys`, `Ascii`, `Base64`, `Contains`, `DoesntContain`, `Encoding`, `Extensions`, `HexColor`, `InArrayKeys`, `List` | `ArrayKeys` contributes an optional-key shape from Laravel 13.24, including from fresh inline `Rule::arrayKeys()` builders; `Encoding` contributes its preserved array, scalar, `Stringable`, and null union from 12.40; `Extensions` contributes a Symfony file from 10.34; `Contains`, `DoesntContain`, and `InArrayKeys` contribute `array<mixed>` from 11.8, 12.22, and 12.16; `Base64`, `HexColor`, and `List` remain `mixed` before 13.21, 10.33, and 11.0.3; `List` changes nested projection in 11.23 |

This is not synonymous with complete rule support. For example, `Accepted`
and `Declined` contribute exact value unions and required matched paths, while
`Array` also participates in nested projection behavior.

Laravel's `int` and `bool` aliases are normalized to the canonical `Integer`
and `Boolean` rules before type resolution, matching
`ValidationRuleParser::normalizeRule()` on every supported major.

Allowed-key `Array` and `ArrayKeys` rules also interact with `List`. Inference
retains only the longest permitted consecutive integer prefix beginning at
zero, including the empty-array-only overlap when all allowed keys are
strings. This avoids incorrectly reducing a successful Laravel contract to
`never`.

Every direct rule has a dedicated static fixture under
[`tests/rules`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/tests/rules) or [`tests/version-aware`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/tests/version-aware).

## Explicitly neutral rules

These 49 names are recognized and deliberately contribute no local value type:

| Family | Rules | Why a neutral contribution is currently conservative |
| --- | --- | --- |
| Size and comparison | `Between`, `Gt`, `Gte`, `Lt`, `Lte`, `Max`, `Min`, `Size` | The accepted native family depends on adjacent numeric, array, string, or file rules and on runtime values |
| Cross-field and domain predicates | `AcceptedIf`, `Confirmed`, `DeclinedIf`, `Different`, `Distinct`, `DoesntEndWith`, `DoesntStartWith`, `EndsWith`, `Exists`, `Filled`, `InArray`, `NotIn`, `Password`, `Same`, `StartsWith`, `Unique` | These are predicates or environment-dependent checks; several need correlated types to improve safely |
| Flow and output rules | `Bail`, `Exclude`, `ExcludeIf`, `ExcludeUnless`, `ExcludeWith`, `ExcludeWithout`, `Missing`, `MissingIf`, `MissingUnless`, `Nullable`, `Present`, `PresentIf`, `PresentUnless`, `Prohibited`, `ProhibitedIf`, `ProhibitedUnless`, `Prohibits`, `Required`, `RequiredIf`, `RequiredUnless`, `RequiredWith`, `RequiredWithAll`, `RequiredWithout`, `RequiredWithoutAll`, `Sometimes` | Their primary effect is validation flow, nullability, presence, or projection rather than a standalone native value type |

Neutral does not mean ignored. `Required`, `Present`, `Missing`, `Nullable`,
`Sometimes`, and the `Exclude*` family have separate tree-level handling.
`Min` also refines a known adjacent string or collection type to its non-empty
form when its parameter is definitely positive; it remains neutral without a
native-family rule because Laravel may instead measure a number or file.
Conditional required and exclusion rules remain conservative because the
output is not represented as a correlated union over the controlling field.
With the default-off experimental option, direct `PresentIf`, `PresentUnless`,
`MissingIf`, and `MissingUnless` rules can refine output when a required
top-level controller has a finite scalar-literal domain that makes the
condition definitely active or definitely inactive. A domain containing both
outcomes remains conservative. `PresentIf` and `PresentUnless` require a
detected Laravel 10.32-or-later version; earlier or unknown versions retain the
conservative shape because those names may have application-defined behavior.

Wildcard expansion adds another projection branch. When an array parent's
only descendants are below a wildcard, Laravel may expand no nested rules and
preserve the raw parent value. Inference therefore retains blank strings that
bypass `array`, and for deeper wildcards retains the parent array's unprojected
keys. A matched unconditional `missing` descendant may instead project that
parent away, so the combined output key remains optional where necessary.
Laravel performs that rebuild only for a literal, parameterless `array` rule;
from Laravel 11.23 it also uses a literal `list`. An allowed-key form such as
`array:name` preserves its complete permitted parent value even when every
nested rule is `missing`.

A literal `list` can retain listness when the first effective wildcard
projection path emits every matched element in input order. Direct scalar
children keep their element type from the rule's 11.0.3 introduction; required
nested children gain a precise projected element shape when literal-list
reconstruction begins in 11.23. An earlier optional path can make Laravel append
numeric keys out of order even when a later required path eventually emits
every element. Such paths, deeper wildcards, and element exclusions remain
broad when Laravel can produce sparse or reordered integer keys.

## Rules currently falling back to `mixed`

These 8 reserved names have no built-in accepted-value contribution. The
fallback is generally sound because it is broad, but it loses useful
information and can hide structural guarantees.

| Rules | Introduced | Laravel consequence | Existing runtime evidence | Candidate treatment |
| --- | --- | --- | --- | --- |
| `MissingWith`, `MissingWithAll` | 10 | Conditionally constrain whether a path may exist | Fixtures for all supported majors | Correlated optionality for the remaining conditional family |
| `PresentWith`, `PresentWithAll` | 10 | Conditionally constrain path presence without requiring a non-blank value | Fixtures for all supported majors | Correlated conditional presence |
| `RequiredIfAccepted`, `RequiredIfDeclined` | 10 / 11 | Conditionally require a field based on another field's accepted or declined value | Fixtures from introduction onward | Correlated presence unions |
| `ProhibitedIfAccepted`, `ProhibitedIfDeclined` | 11 | Conditionally restrict a field based on another field | Laravel 11 through 13 fixtures | Correlated optional value domains; prohibition is not equivalent to exclusion |

`Enum` remains absent from the generated corpus because it requires rule-object
setup that the exporter does not retain. It now has dedicated adversarial
runtime and static coverage instead. `Encoding` and `Extensions` also have
focused file coverage, while `ArrayKeys` is newer than the pinned Laravel 13
fixture but has focused runtime and static coverage.

## Presence and output-shape findings

Comparing Laravel's implicit and dependent rule lists with `RuleTreeNode`
reveals precision gaps that an accepted-value-only inventory would miss.

| Rule family | Laravel behavior | Current shape | Finding |
| --- | --- | --- | --- |
| `Required` | Key must exist and contain a non-empty value | Required key | Modeled |
| `Accepted`, `Declined` | At a matched path, each calls Laravel's required check before checking its exact accepted set | Required matched path with an exact value union; zero-match wildcard parents remain optional | Modeled |
| `Present` | A matched path must exist, but blank and null values are not rejected by presence alone | Required matched path with blank-value bypass preserved; zero-match wildcard parents remain optional | Modeled |
| `Missing` | A matched path must not exist | Omitted named path and bare-array missing-only projection; parameterized array parents remain | Modeled |
| `Exclude` | Removes the path from validated output | Omitted key | Modeled |
| Conditional `Exclude*` | May remove the path according to other runtime data | Optional key | Conservative aggregate model; correlation is lost |
| Conditional required, accepted, declined, present, missing, and prohibited rules | Presence or permitted emptiness depends on other fields | Usually an optional broad key; an experimental option resolves definite direct `PresentIf`, `PresentUnless`, `MissingIf`, and `MissingUnless` outcomes | Conservative but often imprecise; a precise model may require correlated shape unions |
| `RequiredArrayKeys` | Requires named offsets inside a present array, but does not itself project those keys into output | General arrays intersected with required-offset constraints; matching direct child rules become required only when projection guarantees them | Modeled |

`Prohibited` deserves particular care. It is not an alias for exclusion or
missingness: Laravel can accept a present value when it satisfies Laravel's
definition of empty, and that value may remain in validated output. It must not
be modeled by simply deleting the key.

Wildcard presence also remains quantified over runtime matches. A `present`
or `required` wildcard descendant does not imply that the wildcard collection
has any elements, so presence improvements must preserve the existing
wildcard-boundary behavior.

Nested-projection descriptions assume Laravel's default factory setting that
excludes unvalidated array keys. Projects that call
`includeUnvalidatedArrayKeys()` must enable the extension's matching option;
affected bare `array` and version-aware `list` parents then widen so
unmentioned nested keys remain possible.

## Built-in rule objects and fluent builders

String rules are only part of Laravel's public surface. Laravel 13.24 exposes
fluent builders through `Illuminate\Validation\Rule` and classes under
`Illuminate\Validation\Rules`.

Current static extraction treats them in four ways:

- fresh inline `Enum`, `Rule::in()`, `Rule::notIn()`, `Rule::array()`,
  literal-boolean `Rule::requiredIf()`, `Rule::excludeIf()`, and
  `Rule::prohibitedIf()`, literal-boolean `Rule::when()` and `Rule::unless()`,
  `Rule::arrayKeys()`, `Rule::contains()`, `Rule::doesntContain()`, `Rule::date()`,
  `Rule::dateTime()`, `Rule::numeric()`, `Rule::string()`, `Rule::dimensions()`,
  `Rule::file()`, `Rule::imageFile()`, `File::types()`, `File::image()`,
  `Rule::exists()`, and `Rule::unique()` expressions receive dedicated
  extraction of their statically visible semantics, as does exact construction
  of the supported concrete builder classes;
- predicate objects implementing Laravel's rule contracts are treated like
  custom predicates and contribute `mixed` unless they have an explicit custom
  contract;
- `Stringable` builders that do not implement a predicate contract are opaque,
  making the affected path optional and `mixed`.
- callbacks, macros, and other open-ended runtime programs remain opaque.

| Current extraction | Representative Laravel objects | Consequence |
| --- | --- | --- |
| Dedicated built-in extraction | `Enum`, `Rule::in()`, `Rule::notIn()`, literal-boolean `Rule::requiredIf()`, `Rule::excludeIf()`, `Rule::prohibitedIf()`, `Rule::when()`, `Rule::unless()`, `Rule::array()`, `Rule::arrayKeys()`, `Rule::contains()`, `Rule::doesntContain()`, `Rule::date()`, `Rule::dateTime()`, `Rule::numeric()`, `Rule::string()`, `Rule::dimensions()`, `Rule::file()`, `Rule::imageFile()`, exact `In` / `NotIn` / `RequiredIf` / `ExcludeIf` / `ProhibitedIf` / `ArrayRule` / `ArrayKeys` / `Contains` / `DoesntContain` / `Date` / `Numeric` / `StringRule` / `Dimensions` / `File` / `ImageFile` construction, `Rule::exists()`, `Rule::unique()` | Fresh inline expressions recover statically visible enum, accepted-set, literal branch, presence or projection, allowed-key, array-predicate, date, numeric, string, dimensions, file, image, exclusion, and database-predicate semantics without executing application code; dynamic or unsupported object state stays `mixed` |
| Custom predicate with `mixed` accepted type | `AnyOf`, `Can`, `Email`, `Password`, assigned or unsupported `File` / `ImageFile` builders | Adjacent built-in string rules survive, but object state and built-in semantics are not recovered |
| Opaque `Stringable` builder | Callback-driven, dynamic, or assigned `ExcludeIf` / `ProhibitedIf` / `RequiredIf`; `ExcludeUnless`, `ProhibitedUnless`, `RequiredUnless`; assigned or unsupported `In` / `NotIn` / `ArrayRule` / `ArrayKeys` / array-predicate / `Date` / `Numeric` / `StringRule` / `Dimensions` builders; and assigned or unsupported `Exists` / `Unique` chains | The path widens to optional `mixed`, even when the builder serializes to a supported string rule |
| Opaque runtime program | Dynamic or callback-driven `Rule::when()` / `Rule::unless()`, `Rule::forEach`, `NestedRules`, macros | Runtime callbacks or macro state provide no generally available static contract |

Built-in builder support remains a separate implementation track. Treating
these objects as arbitrary third-party validators is safe, but needlessly
imprecise for constant builder expressions whose constructor and fluent-call
state are statically available. Fresh `Rule::in()` / `Rule::notIn()` calls and
exact `In` / `NotIn` construction recover membership predicates. Their direct
constructors accept only arrays before Laravel 10.36; scalar and variadic
forms are recovered from that boundary. Fresh inline `Rule::array()` / `Rule::arrayKeys()`
calls and exact `ArrayRule` / `ArrayKeys` construction recover the same string
contracts confirmed by focused runtime coverage; assigned objects, subclasses,
and dynamic construction still lose their key state and remain opaque. Fresh
exact `Rule::contains()` / `Rule::doesntContain()` calls and
direct `Contains` / `DoesntContain` construction recover the built-in array
predicate at their respective Laravel 12.16 and 12.22 boundaries. Assigned
instances remain opaque. Fresh exact `Exists` and `Unique` objects contribute
neutral predicates through verified fluent query modifiers; their database
state affects acceptance, not the native type Laravel preserves. Fresh exact
`File` and `ImageFile` builders contribute Symfony file types through their verified
fluent size, MIME, extension, encoding, dimension, and additional-rule
constraints. Defaults, callbacks, macros, subclasses, and assigned builders
remain opaque, as do late-bound `self`, `parent`, and `static` forwarding calls.
Fresh exact `Dimensions` builders contribute the same Symfony file type through
their declared width, height, and ratio constraints. The extended
`minRatio()`, `maxRatio()`, and `ratioBetween()` methods begin at Laravel
11.23; assigned builders, subclasses, callbacks, macros, and dynamic chains
remain opaque.
Fresh exact `Date` builders recover the preserved date or formatted-date family
from their statically visible fluent predicates. Their comparisons still
validate and preserve input rather than parsing it into a date object. The
resolver retains Laravel 11's distinct parser boundaries: bare builders begin
at 11.40, chains in rule lists at 11.41, and standalone chains at 11.43.2.
Literal-boolean `RequiredIf`, `ExcludeIf`, and `ProhibitedIf` factories and
exact constructions collapse to their unconditional rule or to Laravel's
constraint-free empty-rule projection marker. Callback and dynamic conditions
remain opaque.
Literal-boolean `Rule::when()` expressions select and flatten their statically
resolvable string or array branch. `Rule::unless()` does the same from Laravel
10.33 after inverting the condition. An empty selected branch retains the
explicit field marker that Laravel uses during nested output projection.
Laravel does not recursively expand conditional wrappers selected by another
wrapper. Dynamic conditions, callback-produced branches, branches containing
executable calls, assigned wrappers, and unpacked arguments remain opaque.

## Prioritized work

### 1. Extend the experimental conditional presence model

The tree can say "the key must exist" without saying "blank values fail," and
unconditional `missing` paths are omitted from output. The first experimental
slice now resolves definite `PresentIf`, `PresentUnless`, `MissingIf`, and
`MissingUnless` outcomes for a finite literal controller. Future work may
extend this to nested paths, multiple conditions, or genuine correlated unions
only where PHPStan can preserve the relationship and runtime evidence keeps it
sound.

### 2. Complete statically resolvable built-in builders

Recover the string-rule equivalent or direct contract for constant fluent
builders where the native output contract is useful and stable. Callback
builders must remain opaque when their branch cannot be resolved; date,
numeric, string, and file builders now provide the conservative models
described above.

## What the survey did not find

This inventory did not produce a new example where a successful Laravel output
is rejected by the current inferred type. Most uncovered rules fall back to
`mixed` or optional shapes, so their immediate cost is lost precision rather
than a newly demonstrated soundness failure.

That statement is deliberately limited. The survey compared rule inventories,
implementation branches, and existing evidence; it did not generate an
adversarial runtime corpus for every rule and parameter combination. The
project's conformance direction remains the authority: every successful
Laravel output exercised by a test must be accepted by the inferred type.

## Relevant project evidence

- [`src/Validation/TypeResolver.php`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/src/Validation/TypeResolver.php)
  contains accepted-value and version-aware inference.
- [`src/Validation/RuleTreeNode.php`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/src/Validation/RuleTreeNode.php)
  contains current presence, exclusion, nullability, and wildcard state.
- [`src/Validation/RuleSetResolver.php`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/src/Validation/RuleSetResolver.php)
  and
  [`src/Validation/CustomRuleTypeResolver.php`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/src/Validation/CustomRuleTypeResolver.php)
  determine how static rule objects are extracted.
- [`tests/LaravelInferenceTest.php`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/tests/LaravelInferenceTest.php) checks
  generated Laravel outputs against inferred types.
- [`tests/Support/InferenceAuditCases.php`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/tests/Support/InferenceAuditCases.php)
  defines the focused cross-version runtime and reverse-precision probes.
- [Laravel Version Inference Audit](../contributing/laravel-version-inference-audit.md)
  documents the version-profile methodology and known release boundaries.
