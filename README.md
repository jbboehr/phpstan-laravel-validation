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
> See [Laravel validation and type safety](docs/pages/guides/laravel-validation-and-type-safety.md) for verified examples and the detailed rationale.
>
> **This library is a mitigation, not an endorsement of Laravel validation for new code.**

## Should I use it?

Use this extension when an existing application already validates with
Laravel and you want PHPStan to describe the successful `validated()`
shape honestly.

Do not use it as a reason to keep Laravel validation as the typed
boundary for new code. Prefer an explicit, normalized output contract
there. This package is a mitigation layer.

## What this extension does

For supported, statically resolvable rule expressions, this PHPStan 2.x
extension infers a sound type for Laravel's validated output. Every
successful Laravel value must be a subtype of that type. The inferred type
may be broader than a rule name suggests because Laravel preserves input
types and can produce dynamic output shapes.

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
```

The explicit `person` rule makes that offset required. Without it, wildcard
rules only constrain matching elements, so the inferred shape uses
`person?`.

The same rule-set inference applies to factory `make()` / `validate()`,
`Request::validate()`, and controller `validate()`. Dynamic or unpacked
rule sets retain Laravel's broad declared return types.

A successful direct facade or `Factory::validate()` call can also refine
safe top-level fields on the caller's original array. That is an input
constraint, not a claim that the array was replaced by `validated()`
output. Details are in
[Supported Entry Points](docs/pages/reference/entry-points.md).

## Installation

Requires PHP 8.1. Supported on PHP 8.1 through 8.5, PHPStan 2.1.5 or later,
and Laravel 10 through 13.

```bash
composer require --dev jbboehr/phpstan-laravel-validation
```

If you also install
[phpstan/extension-installer](https://github.com/phpstan/extension-installer),
the extension is registered automatically.

Otherwise include `extension.neon` in your PHPStan config:

```neon
includes:
    - vendor/jbboehr/phpstan-laravel-validation/extension.neon
```

## Configuration

Defaults match Laravel's ordinary factory and validator behavior. Most
projects can start with no extra options.

```neon
parameters:
    phpstanLaravelValidation:
        laravelVersion: auto
```

Set `laravelVersion` explicitly when PHPStan's working directory is not
the Composer project that owns Laravel. Opt into
`assumeHttpInputNormalization` only when request validation always runs
after Laravel's default trim/empty-string middleware. FormRequest inference
and definite conditional-presence inference are experimental and off by
default.

The full option list is in
[Configuration](docs/pages/reference/configuration.md).

## Status

The 0.1 line is an experimental public release.

- PHP 8.1 through 8.5
- PHPStan 2.1.5 or later
- Laravel 10 through 13

Sound inferred types may be broader than rule names suggest. Dynamic rule
construction, callbacks, and custom rules without an accurate static
contract stay conservative. See
[Limitations](docs/pages/reference/limitations.md)
and
[Laravel Version Behavior](docs/pages/reference/laravel-versions.md).

## Documentation

The published book is
[https://jbboehr.github.io/phpstan-laravel-validation/](https://jbboehr.github.io/phpstan-laravel-validation/).
Source pages live under [`docs/pages/`](docs/pages/):

- [Getting Started](docs/pages/getting-started.md)
- [Understanding Inferred Types](docs/pages/guides/inferred-types.md)
- [Laravel Validation and Type Safety](docs/pages/guides/laravel-validation-and-type-safety.md)
- [FormRequest Inference](docs/pages/guides/form-requests.md)
- [Custom Validation Rules](docs/pages/guides/custom-rules.md)
- [Validation Rules](docs/pages/reference/validation-rules.md)
- [Rule Builders](docs/pages/reference/rule-builders.md)
- [Static Resolvability](docs/pages/reference/static-resolvability.md)
- [Testing and Runtime Verification](docs/pages/contributing/testing.md)

## Development

```bash
nix develop
composer install
nix flake check --keep-going -L
```

See [Development](docs/pages/contributing/development.md)
and [CONTRIBUTING.md](CONTRIBUTING.md).

## License

This project is licensed under the [AGPL v3+](https://www.gnu.org/licenses/agpl-3.0) License - see the LICENSE.md file for details.
