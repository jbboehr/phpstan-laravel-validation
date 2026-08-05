
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

## Caveats

* Laravel validation generally does not normalize returned values, so, for example, `numeric` produces the type union `int|float|numeric-string`. If you know it will always be a string, you can refine the type by using `numeric|string` and get a plain `numeric-string`.
* Wildcard collections may have integer or string keys. Wildcard rules can't currently be mixed with non-wildcard rules beneath the same parent.
* Larastan provides its own stub for `Illuminate\Validation\Validator`, and PHPStan does not merge multiple stubs for the same class. When both extensions are installed, Larastan's stub takes precedence, so an ignored `setRules()` return can leave the validator's previously inferred rules in place. Chain the call (`$validator->setRules($rules)->validated()`) or assign its return value (`$validator = $validator->setRules($rules)`) to infer constant replacement rules correctly.
* Custom validation rules, implicit rules, and enums are not currently supported.

## Development

Install the project dependencies and run the test suite with:

```bash
composer install
composer exec phpunit
```

Mutation testing uses an isolated toolchain because Infection requires PHP 8.3 or newer while this package supports PHP 8.1. Install it and run it from the project root with:

```bash
composer --working-dir=tools/infection install
composer infection
```

The PHPStan type-inference test cases execute analysis in the PHPUnit process, so Infection can exercise them normally. A coverage driver supported by Infection, such as PCOV, is required; the `php85` Nix development shell includes PCOV.

## License

This project is licensed under the [AGPL v3+](https://www.gnu.org/licenses/agpl-3.0) License - see the LICENSE.md file for details.
