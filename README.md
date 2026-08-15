![Inferences for Laravel Validation + PHPStan](docs/pages/images/phpstan-laravel-validation-banner.png)

# phpstan-laravel-validation

[![ci](https://github.com/jbboehr/phpstan-laravel-validation/actions/workflows/ci.yml/badge.svg)](https://github.com/jbboehr/phpstan-laravel-validation/actions/workflows/ci.yml)
[![License: AGPL v3+](https://img.shields.io/badge/License-AGPL_v3%2b-blue.svg)](https://www.gnu.org/licenses/agpl-3.0)
![stability-experimental](https://img.shields.io/badge/stability-experimental-orange.svg)
[![AI burn](https://img.shields.io/endpoint?url=https%3A%2F%2Fgist.githubusercontent.com%2Fjbboehr%2F6f9fb059bb0ebba82e194e886cb3cc97%2Fraw%2Fagent-badge.json&cacheSeconds=300)](https://github.com/arlegotin/agent-badge)

> [!CAUTION]
>
> ## CONSIDER AN ALTERNATIVE FOR NEW CODE
>
> Laravel validation is not a typed data boundary. Successful validation commonly preserves original values rather than producing the native PHP types suggested by rule names. Presence conditions, cross-field rules, wildcards, exclusions, and nested projection can also change the returned shape in surprising ways.
>
> `phpstan-laravel-validation` aims to recover sound and useful structural types from that behavior. Some inferred types are necessarily broader than expected because they describe what Laravel can actually return.
>
> For new type-conscious code, consider a boundary with an explicit, normalized output contract, such as [`cuyz/valinor`](https://github.com/CuyZ/Valinor), typed DTOs, schema objects, or explicit parsers.
>
> See [Laravel validation and type safety](docs/laravel-validation-and-type-safety.md) for verified examples and the detailed rationale.
>
> **This library is a mitigation, not an endorsement of Laravel validation for new code.**

## Explanation

For constant, supported rule sets, this extension aims to infer a sound type for Laravel's validated output. The inferred type may be broader than expected because Laravel preserves input types and can produce dynamic output shapes.

```php
$request = new \Illuminate\Http\Request();

$data = \Illuminate\Support\Facades\Validator::make($request->all(), [
    'person' => 'required|array',
    'person.*.email' => 'required|string|email|unique:users',
    'person.*.first_name' => 'required|string',
    'person.*.age' => 'required|integer|string',
])->validated();

\PHPStan\dumpType($data);
// array{person: array<int|string, array{email: non-empty-string, first_name: string, age: numeric-string}>}

$data = $request->validate([
    'person' => 'required|array',
    'person.*.email' => 'required|string|email|unique:users',
    'person.*.first_name' => 'required|string',
    'person.*.age' => 'required|integer|string',
]);

\PHPStan\dumpType($data);
// array{person: array<int|string, array{email: non-empty-string, first_name: string, age: numeric-string}>}
```

The explicit `person` rule makes that offset required. Without it, wildcard rules only constrain matching elements, so the inferred shape uses `person?`: the offset may be absent, but its value is still a non-null array when present.

The same rule-set inference applies to
`Illuminate\Validation\Factory::make($data, $rules)` and direct
`Factory::validate($data, $rules)` calls, including when `data` and `rules` are
passed as named arguments. Dynamic rule sets retain Laravel's broad declared
return types. Calls that supply the relevant argument only through `...`
unpacking also retain those broad types rather than guessing which unpacked
element contains the rules.

A successful direct facade call can also refine safe, statically resolvable
top-level fields in the caller's original array:

```php
/** @var array<string, mixed> $input */
Validator::validate($input, ['name' => 'required|string']);

\PHPStan\dumpType($input['name']); // string
```

This is an input constraint, not a claim that the original array was replaced
by `validated()` output. Unrelated input keys may still exist. Refinement is
limited to a simple input variable and arguments whose evaluation is known not
to mutate program state. Nested and wildcard paths, exclusion and missing
rules, and rule sets containing custom or opaque runtime behavior are not used
to narrow the caller's array. Guaranteed fields are added; an optional field is
narrowed only when the input's existing type already proves that the field is
present.

If the input data does not match the rules array, an `\Illuminate\Validation\ValidationException` is thrown. For successful input, this extension conservatively infers the values and shape Laravel may preserve in the validated output.

## Installation

To use this extension, require it in [Composer](https://getcomposer.org/):

```bash
composer require --dev jbboehr/phpstan-laravel-validation
```

If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

### Manual installation

If you don't want to use `phpstan/extension-installer`, include `extension.neon` in your project's PHPStan config:

```neon
includes:
    - vendor/jbboehr/phpstan-laravel-validation/extension.neon
```

### Laravel version detection

By default, the extension uses Composer's installed-version data for the
project root matching PHPStan's working directory. This follows the Laravel
code actually installed for analysis rather than trusting a potentially stale
lockfile. If no matching installed-package data is available, it falls back to
the analyzed project's `composer.lock`. It does not use Laravel versions from
unrelated Composer roots that happen to be loaded in the PHPStan process.

The detected `laravel/framework` version selects verified release boundaries
such as `integer:strict`, `ascii`, and Laravel's default request-trimming
exceptions. A standalone `illuminate/validation` installation can select
rule-level behavior, but cannot establish full-framework middleware defaults.

For monorepos or other layouts where PHPStan's working directory is not the
relevant Composer project root, set the analyzed Laravel version explicitly:

```neon
parameters:
    phpstanLaravelValidation:
        laravelVersion: '13.4.0'
```

The default value is `auto`. If the version is unavailable, malformed, or
outside the supported Laravel 10–13 range, inference retains the conservative
cross-version type. The effective version context participates in PHPStan's
result-cache metadata, so changing Laravel versions automatically invalidates
cached inference.

### HTTP input normalization

By default, the extension models the validator itself and therefore includes
blank strings that can bypass optional non-implicit rules. Applications whose
request validation is guaranteed to run after Laravel's standard
`TrimStrings` and `ConvertEmptyStringsToNull` middleware may opt into narrower
request types:

```neon
parameters:
    phpstanLaravelValidation:
        assumeHttpInputNormalization: true
```

This option affects `Request::validate()`, controller `validate()`, and inferred
`FormRequest::validated()` calls. It does not affect direct validators,
factories, facades, or validators passed to `validateWith()`.

For example, an optional `array` field normally has the value type
`array|string` because a blank string may bypass the rule. With this option it
has type `array`; `nullable|array` has type `array|null`. Laravel 11 through 13
exclude `current_password`, `password`, and `password_confirmation` from
trimming by default, so those paths still include strings. Laravel 10 trims
them and receives the narrower type. If a supported full-framework version
cannot be established, the extension conservatively includes strings.

Enable this option only if neither middleware is skipped or removed and
validation cannot observe values introduced afterward by request mutation.
Projects with custom trimming exceptions or `skipWhen()` callbacks should
leave it disabled.

### Unvalidated nested array keys

Laravel's validation factory excludes unvalidated nested array keys by
default. An application that calls `includeUnvalidatedArrayKeys()` changes the
shape returned from bare `array` and, where supported by Laravel, `list`
parents with nested child rules. Configure that factory-wide behavior
explicitly:

```neon
parameters:
    phpstanLaravelValidation:
        includeUnvalidatedArrayKeys: true
```

The default is `false`, matching Laravel's factory default. When enabled, the
extension conservatively widens affected nested parents because unmentioned
keys may survive in `validated()`. This applies consistently to inferred
factory, facade, request, controller, validator-helper, and FormRequest output.
Affected bare `array` parents widen to `array`, so the inferred types of their
validated children are no longer retained. Bare `list` parents retain only
their listness unless a direct exclusion rule can remove an element.

The extension does not boot the application or attempt to discover a call in a
service provider. Treat this option as an assertion about the factories whose
output the extension infers: enable it only when those factories include
unvalidated keys. It is not a conservative setting for mixed factory modes. In
particular, an excluding factory can reconstruct a bare `list` parent with
sparse keys. An including factory normally preserves the parent, but nested
exclusion rules mutate its data before `validated()` reads it and can also make
the result sparse. The extension widens parents with direct exclusion rules to
cover that behavior. A single global option cannot precisely model mixed
factory modes. A directly constructed `Illuminate\Validation\Validator`
retains Laravel's broad declared return type and is not narrowed from this
assumption.

PHPStan reports
`laravelValidation.unvalidatedArrayKeysConfiguration` when a direct
`Factory` method call or statically resolved `Validator` facade call switches
to the mode opposite this option. The diagnostic is intentionally call-local:
it does not execute service providers, follow arbitrary container aliases, or
claim to determine the final mode after later calls. It makes visible
configuration contradictions that analysis can actually see; the option
remains the source of truth for inferred output.

### Form requests

FormRequest inference is experimental and disabled by default. Enable it
explicitly:

```neon
parameters:
    phpstanLaravelValidation:
        formRequests:
            enabled: true
```

When enabled, the extension resolves statically available return expressions
from `rules()` on conventional concrete `FormRequest` classes and applies the
resulting shape to whole-payload and supported keyed `validated()` calls:

```php
final class StorePersonRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'age' => 'integer',
        ];
    }
}

function store(StorePersonRequest $request): void
{
    \PHPStan\dumpType($request->validated());
    // array{name: string, age?: float|int|string|Stringable|true}
}
```

Literal returns, resolvable branches, inherited or trait-provided methods,
class constants, typed method parameters, and declared custom-rule contracts
can participate in inference. If any possible return expression cannot be
resolved, the call retains Laravel's broad return type rather than inferring
from only part of the method. Inherited rules that depend on late-bound
`static::` or `$this::` references are deliberately left broad. Calls that
compose another rules body, such as `array_merge(parent::rules(), [...])`, are
not currently expanded unless PHPStan can expose the complete constant result.

`FormRequest` is a validator lifecycle, not just a `rules()` method. Inference
therefore falls back when the request overrides `validated()`,
`getValidatorInstance()`, `createDefaultValidator()`, `validationRules()`, or
`passedValidation()`, or declares `validator()`, a non-empty
`withValidator()`, or `after()`. Those hooks can replace the effective
validator, mutate its rules, or alter what a later `validated()` call returns.
A userland `withValidator()` body containing no executable statements is
recognized as a no-op, including when inherited or provided by a trait. Any
executable statement or body the parser cannot verify restores the conservative
fallback.

A project can explicitly assert that a particular class's lifecycle hooks do
not invalidate its `rules()` contract:

```neon
parameters:
    phpstanLaravelValidation:
        formRequests:
            trustedClasses:
                - App\Http\Requests\StorePersonRequest
```

Trust is exact: subclasses are not trusted implicitly. It bypasses the
lifecycle-hook checks, but does not make unresolved rule expressions
resolvable and does not override a custom `validated()` implementation. A
false trust declaration can produce an unsound type.

FormRequest inference does not require Larastan. Concrete requests are
discovered from PHPStan's analysed and scan paths and from the root project's
Composer `autoload` and `autoload-dev` source mappings. Classes outside those
paths, including undiscovered vendor requests, retain Laravel's broad type.
Adding an exact class to `trustedClasses` also makes it discoverable, but that
setting simultaneously asserts that its lifecycle hooks are safe; it is not a
risk-free discovery-only option.

Constant string and integer keys, ordinary dotted paths, finite constant-key
unions, and explicit defaults participate in `validated($key, $default)`
inference. Optional paths include the default type; an omitted default is
`null`. Dynamic keys, wildcard or first/last traversal, segment arrays,
object-property traversal, and `Closure` defaults remain `mixed` when their
runtime result cannot be described soundly. `safe()` calls are still left to
Laravel's declared type. The inferred contract also assumes callers do not
replace the resolved validator through the inherited public `setValidator()`
method before calling `validated()`.

### Enum rule objects

Fresh inline Laravel `Enum` rules retain enough syntax for the extension to
recover their enum class and literal filter state:

```php
$validated = Validator::make($input, [
    'status' => ['required', Rule::enum(Status::class)->only(Status::Published)],
])->validated();

\PHPStan\dumpType($validated);
// array{status: Status::Published}
```

Both `Rule::enum(Status::class)` and `new
Illuminate\Validation\Rules\Enum(Status::class)` are supported. Literal
`only()` and `except()` calls are modeled from Laravel 10.46 onward. Backed
enums also include the original backing values and weakly coerced native
values that Laravel can accept and preserve; they are not assumed to return
only enum objects.

The expression must remain statically visible. Assigned rule objects, dynamic
enum class strings, callback-based `when()` or `unless()` mutations, and
non-literal filter state fall back to `mixed` rather than guessing the mutable
object's runtime state.

### `Rule::in()` builders

Fresh inline `Rule::in()` calls with literal scalar values are recovered as
the equivalent parameterized `in` rule:

```php
$validated = Validator::make($input, [
    'status' => ['required', Rule::in(['draft', 'published'])],
])->validated();

\PHPStan\dumpType($validated);
// array{status: 'draft'|'published'|Stringable}
```

The union includes every native value Laravel can accept and preserve through
its loose string comparison. Numeric parameters can narrow safely
representable native integers to literals, but retain broad `float`,
`numeric-string`, and `Stringable` branches because PHP's string conversion
and Laravel's loose comparison admit equivalence classes PHPStan cannot
express. A builder containing a float also retains broad `int`: application
code can change PHP's `precision` before Laravel stringifies the builder, so
the analyzed spelling need not be the runtime spelling. From Laravel 10.21.1,
literal enum arguments are serialized to their case names or backing values.
This does not make
`Rule::in([Status::Draft])` an enum-object rule: it validates the serialized
scalar parameter and preserves the original accepted input.

Constant scalar arrays are supported while the factory call remains visible.
Assigned builder objects, unpacked or dynamic arguments, `Arrayable` and
runtime `Stringable` arguments, and direct `In` construction remain
conservative rather than executing application code during analysis.

### `Rule::notIn()` builders

Fresh inline `Rule::notIn()` calls are recovered as Laravel's type-neutral
`not_in` predicate, allowing adjacent rules to retain their useful type and
presence information:

```php
$validated = Validator::make($input, [
    'role' => ['required', 'string', Rule::notIn(['admin'])],
])->validated();

\PHPStan\dumpType($validated);
// array{role: string}
```

The extension deliberately does not attempt to express “every string except
`admin`.” Laravel applies loose comparisons across preserved native values,
and PHPStan has no useful general complement type for that runtime contract.
Because the forbidden set does not affect this neutral contribution, its
expression may be dynamic while the fresh factory call remains visible.
Assigned builders, dynamic factory or method calls, and direct `NotIn`
construction remain opaque.

### `Rule::array()` builders

Laravel 11.7 introduced `Rule::array()`. Fresh inline calls with statically
visible scalar or enum keys are recovered as the equivalent `array` rule:

```php
$validated = Validator::make($input, [
    'payload' => ['required', Rule::array(['name', 'email'])],
])->validated();

\PHPStan\dumpType($validated);
// array{payload: array{name?: mixed, email?: mixed}}
```

The distinction between bare and parameterized arrays also affects Laravel's
nested-output projection. `Rule::array()` and `Rule::array([])` serialize to a
bare `array` rule, so nested child rules rebuild the returned parent. A
non-empty key list preserves the complete permitted parent instead. Explicit
`null` is observably different from omitting the argument: it serializes to
`array:` and permits only the empty-string key.

Inference follows Laravel's complete stringification round trip, including its
unquoted comma joining. Consequently, `Rule::array(['a,b'])` becomes
`array:a,b` and permits the two keys `a` and `b`; it does not permit one key
literally named `a,b`.

Before Laravel 11.7, or when arguments are dynamic, unpacked, `Arrayable`, or
runtime `Stringable` values, inference remains conservative. Assigned builder
objects and direct `ArrayRule` construction also remain opaque.

### `Rule::arrayKeys()` builders

Laravel 13.24 introduced `Rule::arrayKeys()`. Fresh inline calls with
statically visible scalar or enum keys are recovered as the equivalent
`array_keys` rule:

```php
$validated = Validator::make($input, [
    'payload' => ['required', Rule::arrayKeys(['name', 'email'])],
])->validated();

\PHPStan\dumpType($validated);
// array{payload: array{name?: mixed, email?: mixed}}
```

Unlike bare `array`, this rule preserves the complete permitted parent around
nested child rules rather than rebuilding it from validated descendants. Its
stringification is also lossy: commas split parameters, and an empty key list
becomes `array_keys:`, which permits the empty-string key. Inference follows
those runtime contracts.

Before Laravel 13.24, or when arguments are dynamic, unpacked, `Arrayable`, or
otherwise unavailable to analysis, inference remains conservative. Assigned
builder objects and direct `ArrayKeys` construction also remain opaque.

### Numeric rule builders

Laravel 11.42 introduced `Rule::numeric()` and the corresponding `Numeric`
builder. Fresh inline factory calls, direct construction, and their declared
predicate methods retain Laravel's preserved numeric representations:

```php
$validated = Validator::make($input, [
    'amount' => ['required', Rule::numeric()->between(1, 100)],
    'count' => ['required', Rule::numeric()->integer(strict: true)],
])->validated();

\PHPStan\dumpType($validated);
// Laravel 12.55+: array{amount: float|int|numeric-string, count: int}
```

The non-strict `integer()`, `digits()`, `digitsBetween()`, and `exactly()`
methods do not imply a native `int`: Laravel still preserves integral floats
and numeric strings, and PHPStan has no ordinary type for only the integral
members of those families. Laravel 12.55 adds the builder's
`integer(strict: true)` option, which does justify `int`. Earlier releases
ignore a positional boolean passed to `integer()`, so inference retains the
broader numeric union there. Other fluent methods constrain which numeric
values pass without changing their possible native PHP representations.

Optional blank strings retain Laravel's ordinary non-implicit-rule bypass.
Assigned builders, subclasses, conditional `when()` or `unless()` chains,
dynamic calls, and unknown methods remain conservative because their complete
runtime state is not statically visible.

### String rule builders

Laravel 12.55 introduced `Rule::string()` and `StringRule`. Fresh inline
factory calls, direct construction, and chains of the builder's declared
predicate methods infer a native `string`:

```php
$validated = Validator::make($input, [
    'name' => ['required', Rule::string()->between(1, 100)],
    'code' => ['required', Rule::string()->uppercase()],
])->validated();

\PHPStan\dumpType($validated);
// Laravel 12.55+: array{name: string, code: string}
```

The builder begins with Laravel's native `string` rule. Its fluent predicates
constrain the accepted contents or length but do not convert other values into
strings. Inference currently recovers that native representation rather than
every content refinement: for example, `Rule::string()->min(1)` remains
`string`, while the equivalent `string|min:1` rule string can be refined to
`non-empty-string`. Optional blank strings retain Laravel's ordinary
non-implicit-rule bypass. Assigned builders, subclasses, conditional `when()`
or `unless()` chains, dynamic calls, and unknown methods remain conservative
because their complete runtime state is not statically visible.

### Date rule builders

Laravel 11.40 introduced `Rule::date()` and the corresponding `Date` builder,
but its parser initially understood only builders that serialized to one rule.
Laravel 11.41 began expanding pipe-delimited fluent chains inside rule lists,
and Laravel 11.43.2 extended that behavior to a builder used as a field's
standalone rule. At the applicable boundary, fresh inline factory calls, direct
construction, and the builder's declared comparison predicates recover
Laravel's preserved date family. Calling `format()` changes that family because
Laravel's `date_format` rule rejects `DateTimeInterface` objects:

```php
$validated = Validator::make($input, [
    'published_on' => ['required', Rule::date()->format('Y-m-d')],
    'deadline' => ['required', Rule::date()->afterToday()],
])->validated();

\PHPStan\dumpType($validated);
// Laravel 11.41+: array{
//   published_on: float|int|non-empty-string,
//   deadline: DateTimeInterface|float|int|non-empty-string
// }
```

Laravel 12.44 adds `Rule::dateTime()` and the `past()`, `future()`,
`nowOrPast()`, and `nowOrFuture()` predicates. `dateTime()` has the same native
family as a formatted date. Laravel 12.3 changed how `format()` serializes, but
both forms produce that same sound output family.

These builders validate and preserve successful input; they do not parse it
into a canonical date object. Optional blank strings retain Laravel's ordinary
non-implicit-rule bypass. Assigned builders, subclasses, conditional
`when()` or `unless()` chains, macros, dynamic calls, and unknown methods
remain conservative because their runtime state is not statically visible.

### File rule builders

Fresh inline file builders are recovered as Symfony file predicates:

```php
$validated = Validator::make($input, [
    'document' => ['required', Rule::file()->extensions(['pdf'])->max('10mb')],
    'avatar' => ['required', File::image()->dimensions(
        Rule::dimensions(['max_width' => 2048, 'max_height' => 2048])
    )],
])->validated();

\PHPStan\dumpType($validated);
// array{document: Symfony\Component\HttpFoundation\File\File,
//   avatar: Symfony\Component\HttpFoundation\File\File}
```

Exact `Rule::file()`, `Rule::imageFile()`, `File::types()`, `File::image()`,
`new File()`, and `new ImageFile()` expressions are recognized. Size, MIME,
extension, encoding, dimension, and additional-rule fluent constraints retain
the same successful native value type. The `extensions()` and `encoding()`
methods follow their Laravel 10.34 and 12.40 introduction boundaries.

Assigned builders, subclasses and their late-bound `self` / `parent` / `static`
forwarding calls, global `File::default()` configuration, conditional `when()`
or `unless()` chains, dynamic calls, macros, and unknown methods remain
conservative because their runtime contract is not visible in the expression.

### Database rule builders

Fresh inline `Rule::exists()` and `Rule::unique()` builders are recognized as
type-neutral predicates. Their database query changes whether validation
succeeds, but successful validation preserves the original input value, so an
adjacent value rule remains responsible for the native PHP type:

```php
$validated = Validator::make($input, [
    'parent_id' => [
        'nullable',
        'uuid',
        Rule::exists('folders', 'id')->where('owner_id', $ownerId),
    ],
])->validated();

\PHPStan\dumpType($validated);
// array{parent_id?: string|null}
```

Direct construction of Laravel's exact `Exists` and `Unique` classes is also
recognized. The supported fluent chain includes their `where*()`, soft-delete,
query-callback, and unique-ignore methods because those methods return the same
rule object and do not transform validated output. Assigned builders,
subclasses, conditional `when()` or `unless()` chains, dynamic factory calls,
and unknown methods remain conservative.

### Custom validation rules

Unknown custom rule objects and closures no longer prevent inference for the
rest of a statically resolvable rule set. They contribute no value narrowing,
so built-in rules remain useful:

```php
$validated = Validator::make($input, [
    'reference' => ['required', 'string', new ValidReference()],
])->validated();

\PHPStan\dumpType($validated);
// array{reference: string}
```

When a custom rule has a trustworthy static contract, declare the upper bound
of the original values it can preserve after successful validation. A class
can use the provided attribute:

```php
use jbboehr\PhpstanLaravelValidation\Attribute\ValidationRuleType;

#[ValidationRuleType('non-empty-string')]
final class ValidReference implements \Illuminate\Contracts\Validation\ValidationRule
{
    // ...
}
```

The equivalent PHPDoc form is useful when an attribute is undesirable:

```php
/** @laravel-validation-type non-empty-string */
final class ValidReference implements \Illuminate\Contracts\Validation\ValidationRule
{
    // ...
}
```

Configuration can override either class-local declaration and can also type
third-party rule classes or registered string rule names:

```neon
parameters:
    phpstanLaravelValidation:
        customRules:
            classes:
                App\Rules\ValidReference: non-empty-string
            names:
                valid_reference: non-empty-string
```

Precedence is configuration, then the attribute, then PHPDoc. Registered names
are normalized like Laravel rule names, so `valid_reference`,
`valid-reference`, and `ValidReference` use the same configured contract.
Malformed configuration, attribute, or PHPDoc contracts fail analysis when the
corresponding rule is encountered rather than silently widening it to `mixed`.

These are value-only contracts. They do not make a field required, declare a
rule implicit, transform a value, or control output projection. For example,
an optional custom `int` rule still produces `int|string` because a blank
string may bypass a non-implicit rule; combining it with `required` produces
`int`. The extension assumes custom rules act as predicates and preserve the
input value. A contract is unsound if the rule accepts values outside its
declared type or mutates validator data, rules, or validated output.

Other stringable rule builders, conditional rule builders, and nested rule
builders may encode presence or projection behavior. When their structure
cannot be recovered statically, the affected path is widened rather than
pretending they are ordinary value predicates. Larastan is not required for
custom-rule support, and this extension does not boot the application to
discover registered aliases.

## Caveats

* Laravel validation generally does not normalize returned values, so, for example, `numeric` produces the type union `int|float|numeric-string`. If you know it will always be a string, you can refine the type by using `numeric|string` and get a plain `numeric-string`.
* Literal integer rule keys are version-dependent: Laravel 10 and 11 reindex
  them from `0`, while Laravel 12 and later preserve them. The extension follows
  the detected or configured Laravel version and falls back to a conservative
  array type when that version is unavailable.
* Wildcard collections may have integer or string keys. When wildcard and named rules share a parent, inference conservatively unions their possible projected value types because it cannot preserve every key correlation.
* Larastan provides its own stub for `Illuminate\Validation\Validator`, and PHPStan does not merge multiple stubs for the same class. When both extensions are installed, Larastan's stub takes precedence, so an ignored `setRules()` return can leave the validator's previously inferred rules in place. Chain the call (`$validator->setRules($rules)->validated()`) or assign its return value (`$validator = $validator->setRules($rules)`) to infer constant replacement rules correctly.
* Custom-rule contracts describe accepted values only. Custom implicitness and
  custom output mutation remain conservatively modeled. Dynamically composed
  built-in rule objects also remain conservative when their state is not
  statically visible.
* Experimental FormRequest inference is opt-in. It models conventional request
  validation and falls back for known lifecycle customization, but cannot
  globally track an inherited `setValidator()` call that replaces the
  validator before `validated()`.

## Development

Enter the reproducible development environment and install dependencies into
the ordinary mutable local `vendor/` directory:

```bash
nix develop
composer install
nix flake check --keep-going -L
```

The flake check is the complete normal validation surface. It runs PHPUnit on
PHP 8.1 through 8.5, the full suite against the latest supported Laravel
majors, every pinned Laravel runtime-audit profile, PHPStan, php-cs-fixer,
documentation formatting, Composer validation, PHP linting, and the Larastan
and minimum-PHPStan consumer checks. Each is a separate derivation for useful
failure attribution and caching. Nix-managed Composer repositories are built
from committed lockfiles and do not use the local `vendor/` directory.

Focused Composer commands remain available during interactive development:

```bash
composer exec phpunit
composer exec phpstan analyse
composer cs
```

The [testing and runtime verification guide](docs/testing.md) explains which
test layer to use, how to write named Laravel runtime cases, how to replay Eris
seeds, and how to run the portable cross-version Composer audit. The
[contributing guide](CONTRIBUTING.md) documents Nix dependency-hash updates.
Apply PHP source formatting changes with `composer cs:fix`. Akashi's
documentation-fence formatter is currently check-only; correct those fences by
applying the diff reported by `composer docs:format`.

Mutation testing uses an isolated toolchain because Infection requires PHP 8.3
or newer while this package supports PHP 8.1. It is deliberately excluded from
`nix flake check`; run the explicit Nix package from the project root with:

```bash
nix build -L .#mutation
```

PHPStan type-inference fixtures intended to cover extension code must run their
first `gatherAssertTypes()` analysis inside the test body. Data providers run
before PHPUnit starts coverage and can also warm PHPStan's process-level
caches. The test-only `AssertsFixtureUnderCoverage` trait implements this
pattern. Infection runs only the individual test cases that cover each mutant.
Tests in the `subprocess` group remain part of the normal suite but are
excluded from mutation testing because child processes cannot observe the
active in-process mutant. The `property` group is also excluded because
rerunning hundreds of generated cases for each mutant would be
disproportionate; promoted deterministic regressions remain available to
Infection. A coverage driver supported by Infection, such as PCOV, is
required. The mutation package supplies PHP 8.5 with PCOV and preserves the
thresholds, timeouts, test exclusions, and worker count from
`infection.json5.dist`. It divides the source into four cached shard
derivations, each using its configured four Infection workers, then aggregates
the existing project-wide thresholds. GitHub builds those shards serially so a
four-core runner is not oversubscribed. The `php85` Nix development shell also
includes PCOV for focused manual investigation.

## License

This project is licensed under the [AGPL v3+](https://www.gnu.org/licenses/agpl-3.0) License - see the LICENSE.md file for details.
