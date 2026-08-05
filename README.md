
# phpstan-laravel-validation

[![ci](https://github.com/jbboehr/phpstan-laravel-validation/actions/workflows/ci.yml/badge.svg)](https://github.com/jbboehr/phpstan-laravel-validation/actions/workflows/ci.yml)
[![License: AGPL v3+](https://img.shields.io/badge/License-AGPL_v3%2b-blue.svg)](https://www.gnu.org/licenses/agpl-3.0)
![stability-experimental](https://img.shields.io/badge/stability-experimental-orange.svg)

## Explanation

If the rules given to a laravel validator are a constant expression, then the shape of the array returned by `\Illuminate\Validation\Validator::validated()` is known at compile-time, and can be statically analyzed.

```php
$request = new \Illuminate\Http\Request();

$data = \Illuminate\Support\Facades\Validator::make($request->all(), [
    'person' => 'required|array',
    'person.*.email' => 'required|email|unique:users',
    'person.*.first_name' => 'required|string',
    'person.*.age' => 'required|integer|string',
])->validated();

\PHPStan\dumpType($data);
// array{person: array<int|string, array{email: non-empty-string, first_name: string, age: numeric-string}>}

$data = $request->validate([
    'person' => 'required|array',
    'person.*.email' => 'required|email|unique:users',
    'person.*.first_name' => 'required|string',
    'person.*.age' => 'required|integer|string',
]);

\PHPStan\dumpType($data);
// array{person: array<int|string, array{email: non-empty-string, first_name: string, age: numeric-string}>}
```

The explicit `person` rule makes that offset required. Without it, wildcard rules only constrain matching elements, so the inferred shape uses `person?`: the offset may be absent, but its value is still a non-null array when present.

If the input data does not match the rules array, an `\Illuminate\Validation\ValidationException` is thrown, thus preserving type safety.

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

* Laravel's validation does not cast anything, so, for example, `numeric` produces the type union `int|float|numeric-string`. If you know it will always be a string, you can refine the type by using `numeric|string` and get a plain `numeric-string`.
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
