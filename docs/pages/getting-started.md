# Getting Started

Install the extension as a development dependency, register it with PHPStan,
and analyse a `validated()` call. Full configuration is in
[Configuration](reference/configuration.md).

## Installation

Requires PHP 8.1. Supported on PHP 8.1 through 8.5, PHPStan 2.1.5 or later,
and Laravel 10 through 13.

```bash
composer require --dev jbboehr/phpstan-laravel-validation
```

If you also install
[`phpstan/extension-installer`](https://github.com/phpstan/extension-installer),
the extension is registered automatically.

Otherwise include it from `phpstan.neon`:

```neon
includes:
    - vendor/jbboehr/phpstan-laravel-validation/extension.neon
```

## First analysis

```php
$validated = \Illuminate\Support\Facades\Validator::make($input, [
    'name' => 'required|string',
    'age' => 'integer',
])->validated();

\PHPStan\dumpType($validated);
// array{name: string, age?: float|int|string|Stringable|true}
```

`name` is required. `age` is optional, and its value type includes every
native representation Laravel can accept and preserve for `integer`.

The same rule-set inference applies to factory `make()` / `validate()`,
`Request::validate()`, and controller `validate()`. See
[Supported Entry Points](reference/entry-points.md).

## Common first options

Most projects can start with the defaults. These are the options most
projects are likely to consider first:

| Option | Default | When to change it |
| --- | --- | --- |
| `laravelVersion` | `auto` | PHPStan's working directory is not the Composer project that owns Laravel |
| `assumeHttpInputNormalization` | `false` | Request validation always runs after Laravel's default trim/empty-string middleware |
| `formRequests.enabled` | `false` | You want experimental `FormRequest::validated()` inference |

```neon
parameters:
    phpstanLaravelValidation:
        laravelVersion: auto
```

Details, diagnostics, and the remaining options are in
[Configuration](reference/configuration.md).

## Compatibility

- PHP 8.1 through 8.5
- PHPStan 2.1.5 or later
- Laravel 10 through 13

Version-specific inference boundaries are listed in
[Laravel Version Behavior](reference/laravel-versions.md).

## Next

- [Understanding Inferred Types](guides/inferred-types.md)
- [FormRequest Inference](guides/form-requests.md)
- [Custom Validation Rules](guides/custom-rules.md)
- [Limitations](reference/limitations.md)
