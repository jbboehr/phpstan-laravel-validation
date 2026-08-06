![Inferences for Laravel Validation + PHPStan](docs/pages/images/phpstan-laravel-validation-banner.png)

# phpstan-laravel-validation

[![ci](https://github.com/jbboehr/phpstan-laravel-validation/actions/workflows/ci.yml/badge.svg)](https://github.com/jbboehr/phpstan-laravel-validation/actions/workflows/ci.yml)
[![License: AGPL v3+](https://img.shields.io/badge/License-AGPL_v3%2b-blue.svg)](https://www.gnu.org/licenses/agpl-3.0)
![stability-experimental](https://img.shields.io/badge/stability-experimental-orange.svg)

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

This option affects `Request::validate()` and controller `validate()` calls.
It does not affect direct validators, factories, facades, or validators passed
to `validateWith()`.

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

## Caveats

* Laravel validation generally does not normalize returned values, so, for example, `numeric` produces the type union `int|float|numeric-string`. If you know it will always be a string, you can refine the type by using `numeric|string` and get a plain `numeric-string`.
* Literal integer rule keys are version-dependent: Laravel 10 and 11 reindex
  them from `0`, while Laravel 12 and later preserve them. The extension follows
  the detected or configured Laravel version and falls back to a conservative
  array type when that version is unavailable.
* Wildcard collections may have integer or string keys. When wildcard and named rules share a parent, inference conservatively unions their possible projected value types because it cannot preserve every key correlation.
* Larastan provides its own stub for `Illuminate\Validation\Validator`, and PHPStan does not merge multiple stubs for the same class. When both extensions are installed, Larastan's stub takes precedence, so an ignored `setRules()` return can leave the validator's previously inferred rules in place. Chain the call (`$validator->setRules($rules)->validated()`) or assign its return value (`$validator = $validator->setRules($rules)`) to infer constant replacement rules correctly.
* Custom validation rules, implicit rules, and enums are not currently supported.

## Development

Install the project dependencies and run the test suite with:

```bash
composer install
composer exec phpunit
```

Check code style with `composer cs`. Apply formatting changes with
`composer cs:fix`.

Mutation testing uses an isolated toolchain because Infection requires PHP 8.3 or newer while this package supports PHP 8.1. Install it and run it from the project root with:

```bash
composer --working-dir=tools/infection install
composer infection
```

The PHPStan type-inference test cases execute analysis in the PHPUnit process, so Infection can exercise them normally. A coverage driver supported by Infection, such as PCOV, is required; the `php85` Nix development shell includes PCOV.

## License

This project is licensed under the [AGPL v3+](https://www.gnu.org/licenses/agpl-3.0) License - see the LICENSE.md file for details.
