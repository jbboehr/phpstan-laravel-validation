# BookStack compatibility investigation

This report records a real-application compatibility test of
`phpstan-laravel-validation` against BookStack. It is an investigation
snapshot, not a new supported-platform promise or a permanent integration
test.

Investigation date: 2026-08-09.

## Executive summary

The current `develop` revision was installed into an isolated checkout of
[BookStack v26.05.3](https://github.com/BookStackApp/BookStack/releases/tag/v26.05.3),
a Laravel 12 application which already uses Larastan.

The investigation found two actionable issues:

1. The unmodified extension aborts analysis when PHPStan materializes a `null`
   element inside a rule list. BookStack reaches this through
   `PasswordRule::defaults()`, whose Laravel PHPDoc return type is
   `static|void`. Laravel accepts a literal `null` rule-list element as a
   no-op, but [`RuleParser`](../../src/Validation/RuleParser.php) currently
   throws `InvalidRuleException`. This is an extension compatibility bug.
2. Once an audit-only workaround ignored that `null` element, the extension
   exposed a likely BookStack bug. An image upload field has only non-implicit
   rules, so Laravel permits the field to be absent and omits it from
   `validated()`. BookStack nevertheless reads the offset unconditionally.
   Direct runtime reproduction against BookStack's bootstrapped Laravel
   validator confirmed the inferred optional key.

After the audit-only workaround, BookStack's complete configured PHPStan scan
passed with both Larastan and `phpstan-laravel-validation` loaded. A separate
level-`max` differential produced 13 fewer `argument.type` diagnostics and one
new `offsetAccess.notFound` diagnostic. The new diagnostic is the genuine
missing-upload case above.

BookStack does not use Laravel FormRequests in its `app` tree, so this
investigation does not exercise the extension's FormRequest support.

## Scope

The investigation asked four questions:

- Can the extension be installed into a large, independently developed
  Laravel application without changing that application's source?
- Can it coexist with the application's existing Larastan setup?
- Does it produce useful types at ordinary Laravel validation entry points?
- Does it expose compatibility, soundness, or application-level problems that
  the repository's focused fixtures do not reveal?

The investigation did not attempt to make BookStack pass PHPStan at level
`max`, exercise BookStack's test suite, test every supported Laravel major, or
establish BookStack as an officially supported downstream project.

## Revisions and environment

| Component | Investigated revision |
| --- | --- |
| `phpstan-laravel-validation` | `develop` at [`77db90e6a960`](https://github.com/jbboehr/phpstan-laravel-validation/commit/77db90e6a9604fa92eb6276faf24ae7548f022ca) |
| BookStack | tag `v26.05.3`, commit [`e1cd3229966d`](https://github.com/BookStackApp/BookStack/commit/e1cd3229966d939a75a74a2224ff0643d8af337b) |
| PHP runtime used for the audit | 8.4.23 |
| BookStack PHP constraint | `^8.2.0`, with Composer's platform set to 8.2.0 |
| Laravel | `v12.64.0`, commit [`727a8ea2949c`](https://github.com/laravel/framework/commit/727a8ea2949c23ca8b5316b86a00984b6017b7a0) |
| Larastan | `v3.10.0`, commit [`2970f8339815`](https://github.com/larastan/larastan/commit/2970f83398154178a739609c244577267c7ee8eb) |
| PHPStan before installing the extension | 2.2.6 |
| PHPStan after installing the extension | 2.2.8 |

BookStack's `phpstan.neon.dist` analyzes `app` at level 4, includes Larastan,
and bootstraps `bootstrap/phpstan.php`. The PHPStan 2.2.6 to 2.2.8 update was a
Composer side effect of installing the local path package. The native
BookStack baseline was rerun at 2.2.8 before comparing it with the extension,
so the PHPStan patch update did not account for the observed differential.

The extension was installed as a mirrored Composer path repository rather
than a symlink. All BookStack changes and audit configuration lived in a
disposable checkout under `/tmp`; the extension repository was not modified
while gathering these results.

## Application validation usage

A syntactic scan of BookStack's `app` tree found 77 Laravel validation entry
points:

- 72 controller `$this->validate(...)` calls;
- two `$request->validate(...)` calls;
- two calls to `Validator::make(...)`; and
- one application validator-builder call followed by `->validate()`.

Six other `->validate()` calls belong to unrelated URI, OpenID Connect, and
ZIP-import objects and were excluded from the count.

This gives the test meaningful coverage of controller and request validation,
including cases where validated values flow directly into typed application
methods. BookStack also registers application-defined string rules with
`Validator::extend()`.

No class extending `Illuminate\Foundation\Http\FormRequest` was found under
`app`. The absence matters: a successful BookStack scan says nothing about
FormRequest discovery, lifecycle hooks, inherited rules, or the project's
opt-in FormRequest inference.

## Method

1. Clone the exact BookStack release into a disposable directory.
2. Install BookStack's locked dependencies using the PHP 8.4 development
   shell.
3. Run BookStack's own PHPStan configuration before adding the extension.
4. Add the current extension as a mirrored Composer path dependency.
5. Rerun the native BookStack configuration at the newly resolved PHPStan
   2.2.8 to keep the baseline comparison fair.
6. Create a small audit configuration which includes BookStack's configuration
   and the extension's `extension.neon`, using an independent PHPStan cache
   directory.
7. Run the complete BookStack analysis with the extension.
8. After reproducing the null-rule crash, apply a one-line workaround only to
   the disposable installed copy and rerun the scan.
9. Run baseline and extension configurations at level `max`, with separate
   caches, and compare diagnostics by identifier, file, line, and message.
10. Reproduce both disputed runtime behaviors through BookStack's bootstrapped
    Laravel validation factory.

Separate cache directories prevented a result produced under one extension
configuration from being reused by the other.

## Baseline results

BookStack passed its native PHPStan configuration both before the extension
installation and after Composer updated PHPStan to 2.2.8:

```text
[OK] No errors
```

This established a clean application baseline. Any failure at BookStack's
configured level after loading the extension was therefore introduced by the
extension or its interaction with the existing Larastan setup.

## Finding 1: nullable rule-list elements abort analysis

### Reproduction

The first unmodified extension run failed while analyzing BookStack's
[`ResetPasswordController`](https://github.com/BookStackApp/BookStack/blob/v26.05.3/app/Access/Controllers/ResetPasswordController.php):

```php
$request->validate([
    'token' => 'required',
    'email' => 'required|email',
    'password' => ['required', 'confirmed', PasswordRule::defaults()],
]);
```

The internal error was:

```text
Invalid rule type: NULL NULL
```

The exception originated in `RuleParser::parseRule(null)`. The current parser
accepts `Rule`, array, and string inputs, then throws for every other native
value.

### Why PHPStan sees `null`

Laravel documents `Illuminate\Validation\Rules\Password::defaults()` as
returning `static|void`. With no argument, its implementation returns
`static::default()`. With an argument, it configures the default and returns
nothing.

BookStack configures the default in
[`AuthServiceProvider`](https://github.com/BookStackApp/BookStack/blob/v26.05.3/app/App/Providers/AuthServiceProvider.php):

```php
Password::defaults(fn () => Password::min(8));
```

Consequently, the no-argument controller call returns a `Password` rule object
when BookStack runs. The extension does not bootstrap and execute the
application to discover that global configuration. PHPStan can therefore
materialize the declared `void` branch as `null` while resolving the rule
array.

This is exactly the sort of runtime dependency for which inference must remain
conservative. It must not abort the entire analysis.

### Laravel runtime verification

The narrower parser question was reproduced directly against BookStack's
installed Laravel 12.64 validator:

```php
$validator = $factory->make(
    ['value' => 'x'],
    ['value' => ['required', null]],
);
```

Laravel accepted the rule list, validation succeeded, and `validated()`
preserved:

```php
['value' => 'x']
```

A literal `null` element is therefore a no-op in the observed Laravel release.
Rejecting it in the extension is a compatibility difference, not a stricter
description of Laravel behavior.

### Audit-only workaround

The disposable installed copy was changed to return `null` immediately from
`RuleParser::parseRule()` when the supplied rule is `null`. The surrounding
`array_filter()` already discards the result:

```php
if ($rule === null) {
    return null;
}
```

This workaround was not applied to the source repository. It was used only to
continue the downstream investigation after recording the failure.

### Recommended production fix

Before changing production behavior, verify null rule-list elements against
every supported Laravel major. If the behavior is stable, the minimum fix is
to ignore explicit `null` elements while retaining every statically available
non-null rule branch.

Regression coverage should include:

- a parser unit test for `['required', null]`;
- focused Laravel runtime cases across supported majors;
- a PHPStan call-site fixture containing a nullable rule expression;
- the `Password::defaults()` pattern without assuming that application
  bootstrap has configured its runtime result; and
- a check that genuinely unsupported non-null scalar rule entries still fail
  or widen according to the project's chosen policy.

The safe static outcome for unavailable password-rule configuration is a broad
type. Executing the application or pretending the default rule is statically
known would be less defensible than ignoring Laravel's no-op null branch and
retaining conservative inference.

## Whole-application result after the workaround

With the audit-only null handling in the disposable vendor copy, the complete
BookStack scan passed at the application's configured level:

```text
[OK] No errors
```

Both Larastan and `phpstan-laravel-validation` were loaded. No stub collision,
duplicate extension registration, or container bootstrap failure was observed
in this application.

This is useful coexistence evidence, but it is bounded evidence. It covers one
BookStack release, one Laravel 12 patch release, one Larastan release, and the
validation entry points that BookStack actually uses.

## Level-max differential

BookStack's own configured level is 4. Level `max` was used only as a
differential probe: it makes changes from `mixed` to structural validation
types visible even though BookStack is not currently expected to pass at that
level.

| Diagnostic measurement | Baseline | With extension | Change |
| --- | ---: | ---: | ---: |
| Total file errors | 2,329 | 2,317 | -12 |
| `argument.type` | 789 | 776 | -13 |
| `offsetAccess.notFound` | 0 | 1 | +1 |

Counts for other diagnostic identifiers were unchanged. Several remaining
diagnostics also became more precise, replacing `mixed` or unshaped `array`
with a structural type while still correctly failing the receiving method's
narrower contract.

### Argument diagnostics eliminated

The 13 eliminated `argument.type` diagnostics occurred at these flows:

1. The confirmation-email token becomes `string` before it is passed to the
   token service.
2. Comment creation HTML becomes `string`.
3. An optional comment content reference becomes `string` after its explicit
   fallback.
4. Comment update HTML becomes `string`.
5. A watch-level value becomes `string`.
6. Webhook creation data becomes an array shape acceptable to Eloquent
   `fill()`/construction.
7. Webhook creation events become an array accepted by `array_values()`.
8. Webhook update data becomes an array shape accepted by `fill()`.
9. Webhook update events become an array accepted by
   `updateTrackedEvents()`.
10. An optional import parent becomes `string|null`.
11. Draw.io image content becomes `string` before `saveDrawing()`.
12. Notification settings become an array before they reach the preferences
    updater.
13. A validated list used by `implode()` receives a compatible array element
    type.

These examples demonstrate practical value from validation-result inference
without implying that every Laravel rule produces the narrow native type its
name suggests.

### Broad types that correctly remain broad

Some diagnostics did not disappear because Laravel's runtime contract does
not justify the application's expected type. For example, values validated by
Laravel's ordinary `integer` rule were inferred as a union including preserved
integer-like strings and other coercively accepted values rather than simply
`int`.

Those remaining diagnostics are not a failure to infer a prettier type. They
are evidence that the receiving BookStack method expects a stronger contract
than Laravel validation establishes.

Other calls changed from a generic `array` to shapes with optional offsets but
continued to fail because BookStack expected all offsets to be required. This
is likewise useful precision: the validation rules did not guarantee those
keys.

## Finding 2: BookStack assumes an optional upload exists

The sole new level-`max` diagnostic was reported in
[`GalleryImageController`](https://github.com/BookStackApp/BookStack/blob/v26.05.3/app/Uploads/Controllers/GalleryImageController.php):

```text
Offset 'file' might not exist on
array{file?: mixed, uploaded_to: float|int|numeric-string|Stringable|true}.
```

The controller validates:

```php
$validated = $this->validate($request, [
    'file' => $this->getImageValidationRules(),
    'uploaded_to' => ['required', 'integer'],
]);
```

The file rules resolve to:

```php
[
    'image_extension',
    'mimes:jpeg,png,gif,webp,avif',
    'max:' . (config('app.upload_limit') * 1000),
]
```

The application registers `image_extension` using `Validator::extend()`, not
`extendImplicit()`. Neither it nor `mimes` nor `max` requires a missing field
to exist. After validation, the controller nevertheless performs:

```php
$imageUpload = $validated['file'];
```

### Runtime verification

The exact rule family was run through BookStack's bootstrapped validator with
only `uploaded_to` supplied. The observed result was:

```php
[
    'passes' => true,
    'errors' => [],
    'validated' => [
        'uploaded_to' => 1,
    ],
]
```

Laravel skips all of the non-implicit file rules for the absent value and does
not put `file` into `validated()`. The extension's optional offset is therefore
sound for this input. This is not an analyzer-invented edge case.

The likely application correction would be to add a required-presence rule to
the upload field or handle its absence before indexing the result. No BookStack
source was changed and no upstream issue was opened during this investigation.

## Larastan coexistence

BookStack's configuration loads Larastan before the validation extension. With
the null-rule parser problem bypassed, the combined scan completed normally.
This provides a useful real-application check that the two extensions can
coexist for:

- `Request::validate()`;
- controller `$this->validate()`;
- `Validator::make()`;
- custom string validators registered during application bootstrap; and
- validation results passed into typed application methods.

The result does not prove ordering stability for every extension, stub, or
dynamic-return-type combination. It establishes that no such conflict was
observed in this pinned BookStack configuration.

## Limitations

- Only BookStack v26.05.3 was tested.
- Only Laravel 12.64 was exercised; the null-rule behavior must be reproduced
  across Laravel 10 through 13 before changing inference.
- The commands ran on PHP 8.4.23 while BookStack declares PHP 8.2 as its
  Composer platform.
- BookStack has no FormRequest classes in the analyzed tree.
- The application was analyzed statically and bootstrapped for focused
  validator probes; the complete HTTP or database-backed test suite was not
  run.
- The successful extension scan depended on an audit-only vendor patch. The
  source revision named above still fails out of the box on the password rule.
- Level `max` counts are comparative evidence, not a claim that the extension
  makes BookStack level-`max` clean.
- A finite downstream application cannot establish universal soundness or
  compatibility.

## Recommended follow-up

1. Fix nullable rule-list handling, after runtime verification across every
   supported Laravel major, and add parser, runtime, and PHPStan regression
   tests.
2. Preserve conservative inference for `Password::defaults()` and similar
   runtime-configured rule factories when their effective contract is not
   statically available.
3. Consider keeping a documented, manually runnable BookStack smoke procedure.
   A pinned external application is valuable release-candidate evidence, but
   its download size and dependency churn may make it unsuitable for the
   default unit-test matrix.
4. Decide separately whether to report the optional upload field to BookStack.
   That is an external action and was intentionally not performed here.
5. Repeat the downstream scan after the null-rule fix without any vendor
   patch. The acceptance criterion should be a clean BookStack level-4 run and
   retention of the sound level-`max` optional-offset finding.

## Commands and observed results

The clone and dependency installation used:

```sh
git clone --branch v26.05.3 --depth 1 \
    https://github.com/BookStackApp/BookStack.git bookstack

nix develop /home/sandbox/Code/phpstan-laravel-validation#php84 \
    --command composer install --prefer-dist --no-interaction --no-progress
```

The disposable BookStack manifest added
`jbboehr/phpstan-laravel-validation: dev-develop` under `require-dev` and this
path repository:

```json
{
    "name": "phpstan-validation",
    "type": "path",
    "url": "/home/sandbox/Code/phpstan-laravel-validation",
    "options": {
        "symlink": false
    }
}
```

Composer recorded the extension at the revision in the environment table and
updated PHPStan to 2.2.8. The combined audit configuration was:

```neon
includes:
    - phpstan.neon.dist
    - vendor/jbboehr/phpstan-laravel-validation/extension.neon

parameters:
    tmpDir: /tmp/phpstan-validation-bookstack/phpstan-default
```

The two level-`max` configurations respectively included BookStack's native
configuration and the combined configuration, overrode `level: max`, and used
different `tmpDir` values.

The native and combined configured scans were equivalent to:

```sh
nix develop /home/sandbox/Code/phpstan-laravel-validation#php84 \
    --command vendor/bin/phpstan analyse \
    --configuration=phpstan.neon.dist --no-progress

nix develop /home/sandbox/Code/phpstan-laravel-validation#php84 \
    --command vendor/bin/phpstan analyse \
    --configuration=phpstan-validation.neon --no-progress
```

Both completed with `No errors` after the audit-only workaround; the native
configuration also passed before the workaround. The unmodified combined run
terminated with `Invalid rule type: NULL NULL`.

The stricter differential used separate level-`max` configurations and cache
directories:

```sh
nix develop /home/sandbox/Code/phpstan-laravel-validation#php84 \
    --command vendor/bin/phpstan analyse \
    --configuration=phpstan-max-baseline.neon \
    --error-format=json --no-progress

nix develop /home/sandbox/Code/phpstan-laravel-validation#php84 \
    --command vendor/bin/phpstan analyse \
    --configuration=phpstan-max-validation.neon \
    --error-format=json --no-progress
```

The JSON diagnostics were compared as sorted tuples of file, line, identifier,
and message. The runtime upload reproduction bootstrapped BookStack's console
kernel, resolved the application's validation factory, and called it with the
controller's rule strings and an input containing only `uploaded_to`.

No changes from the disposable BookStack checkout or its installed vendor copy
were copied into this repository.
