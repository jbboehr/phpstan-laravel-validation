# Configuration

All options live under `parameters.phpstanLaravelValidation`. Defaults match
Laravel's ordinary factory and validator behavior.

```neon
parameters:
    phpstanLaravelValidation:
        laravelVersion: auto
        assumeHttpInputNormalization: false
        includeUnvalidatedArrayKeys: false
        experimentalConditionalPresenceInference: false
        formRequests:
            enabled: false
            trustedClasses: []
        customRules:
            classes: []
            names: []
```

## `laravelVersion`

Default: `auto`.

`auto` uses Composer's installed-version data for the project root matching
PHPStan's working directory. That follows the Laravel code actually installed
for analysis rather than a potentially stale lockfile. If no matching
installed-package data is available, the extension falls back to the analyzed
project's `composer.lock`. It does not use Laravel versions from unrelated
Composer roots that happen to be loaded in the PHPStan process.

The detected `laravel/framework` version selects verified release boundaries
such as `integer:strict`, `ascii`, and Laravel's default request-trimming
exceptions. A standalone `illuminate/validation` installation can select
rule-level behavior, but cannot establish full-framework middleware defaults.

For monorepos or other layouts where PHPStan's working directory is not the
relevant Composer project root, set the version explicitly:

```neon
parameters:
    phpstanLaravelValidation:
        laravelVersion: '13.4.0'
```

If the version is unavailable, malformed, or outside the supported Laravel
10–13 range, inference retains the conservative cross-version type. The
effective version context participates in PHPStan's result-cache metadata, so
changing Laravel versions invalidates cached inference.

Boundaries are listed in
[Laravel Version Behavior](laravel-versions.md).

## `assumeHttpInputNormalization`

Default: `false`.

By default the extension models the validator itself and therefore includes
blank strings that can bypass optional non-implicit rules. Applications whose
request validation is guaranteed to run after Laravel's standard
`TrimStrings` and `ConvertEmptyStringsToNull` middleware may opt into
narrower request types:

```neon
parameters:
    phpstanLaravelValidation:
        assumeHttpInputNormalization: true
```

This option affects `Request::validate()`, controller `validate()`, and
inferred `FormRequest::validated()` calls. It does not affect direct
validators, factories, facades, or validators passed to `validateWith()`.

An optional `array` field normally has the value type `array|string` because
a blank string may bypass the rule. With this option it has type `array`;
`nullable|array` has type `array|null`. Laravel 11 through 13 exclude
`current_password`, `password`, and `password_confirmation` from trimming by
default, so those paths still include strings. Laravel 10 trims them and
receives the narrower type. If a supported full-framework version cannot be
established, the extension conservatively includes strings.

Enable this option only if neither middleware is skipped or removed and
validation cannot observe values introduced afterward by request mutation.
Projects with custom trimming exceptions or `skipWhen()` callbacks should
leave it disabled.

## `includeUnvalidatedArrayKeys`

Default: `false`, matching Laravel's factory default.

Laravel's validation factory excludes unvalidated nested array keys by
default. An application that calls `includeUnvalidatedArrayKeys()` changes
the shape returned from bare `array` and, where supported by Laravel, `list`
parents with nested child rules.

```neon
parameters:
    phpstanLaravelValidation:
        includeUnvalidatedArrayKeys: true
```

When enabled, the extension conservatively widens affected nested parents
because unmentioned keys may survive in `validated()`. This applies to
inferred factory, facade, request, controller, validator-helper, and
FormRequest output. Affected bare `array` parents widen to `array`, so the
inferred types of their validated children are no longer retained. Bare
`list` parents retain only their listness unless a direct exclusion rule can
remove an element.

The extension does not boot the application or attempt to discover a call in
a service provider. Treat this option as an assertion about the factories
whose output the extension infers. It is not a conservative setting for mixed
factory modes. In particular, an excluding factory can reconstruct a bare
`list` parent with sparse keys. An including factory normally preserves the
parent, but nested exclusion rules mutate its data before `validated()`
reads it and can also make the result sparse. The extension widens parents
with direct exclusion rules to cover that behavior. A single global option
cannot precisely model mixed factory modes. A directly constructed
`Illuminate\Validation\Validator` retains Laravel's broad declared return
type and is not narrowed from this assumption.

PHPStan reports `laravelValidation.unvalidatedArrayKeysConfiguration` when a
direct `Factory` method call or statically resolved `Validator` facade call
switches to the mode opposite this option. The diagnostic is call-local: it
does not execute service providers, follow arbitrary container aliases, or
claim to determine the final mode after later calls. The option remains the
source of truth for inferred output.

## `experimentalConditionalPresenceInference`

Default: `false`.

Laravel's dependent presence rules normally leave the affected output key
optional because their result depends on another runtime value. This
option recovers definite cases where a required top-level controlling field
has a finite scalar-literal type.

```neon
parameters:
    phpstanLaravelValidation:
        experimentalConditionalPresenceInference: true
```

```php
$validated = Validator::make($input, [
    'mode' => 'required|string|in:create',
    'name' => 'present_if:mode,create|string',
])->validated();

\PHPStan\dumpType($validated);
// array{mode: 'create', name: string}
```

The option handles definite outcomes for `present_if`, `present_unless`,
`missing_if`, and `missing_unless`. Active presence requires the key but
still permits blank strings to bypass adjacent non-implicit rules; it is not
treated as `required`. Active missing rules omit the key from successful
output. `present_if` and `present_unless` refinement requires a detected
Laravel version of 10.32 or later; earlier or unknown versions remain
conservative.

The first experimental slice supports only one conditional field whose
controller is a required direct top-level sibling. The controller's entire
inferred domain must either match or not match the dependent values. Mixed
matching and non-matching domains, boolean controllers, nested or wildcard
paths, multiple conditional fields, exclusions, and custom or opaque rules
retain the ordinary conservative optional shape.

See [Presence and Output Projection](presence-and-projection.md).

## Form requests

See [FormRequest Inference](../guides/form-requests.md) for behavior.
The keys are:

```neon
parameters:
    phpstanLaravelValidation:
        formRequests:
            enabled: false
            trustedClasses: []
```

`trustedClasses` is an exact class list. Subclasses are not trusted
implicitly.

## Custom rules

See [Custom Validation Rules](../guides/custom-rules.md).

```neon
parameters:
    phpstanLaravelValidation:
        customRules:
            classes: []
            names: []
```

## Diagnostics

Validator-contract invalidation operates independently of the inference
options. PHPStan reports mutations of existing inferred validators under
`laravelValidation.validatorMutation` while allowing conservative or precise
handling of supported fresh chains. See
[Supported Entry Points](entry-points.md#validator-mutation-and-contract-invalidation).

Statically resolvable parsing rules on a detected `laravel/framework` version
below 10.7 are reported as
`laravelValidation.parsingRuleLaravelVersion`. The analyzer remains silent
when the framework version is unavailable; the parser's runtime capability
guard still rejects validators without `setValue()`.

Numeric parsing rules combined with `min`, `max`, `between`, or `size` without
Laravel's `integer`, `numeric`, or `decimal` marker are reported as
`laravelValidation.parsingNumericSize`. Laravel otherwise measures the
original representation rather than the parsed numeric value. For a
float-producing parser, the diagnostic recommends `numeric` or an appropriate
`decimal` rule rather than `integer`, which rejects non-integral values. The
diagnostic inspects supported factory, facade, helper, request, and controller
validation calls, including `validateWith()` and `validateWithBag()`, as well
as returns from `FormRequest::rules()`. FormRequest rules are checked
independently of `formRequests.enabled` because the diagnostic concerns runtime
behavior rather than inferred FormRequest output. See
[Parsing validated output](../guides/parsing-validated-output.md#presence-and-adjacent-laravel-rules).
