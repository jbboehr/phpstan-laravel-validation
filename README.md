
# phpstan-laravel-validation

[![ci](https://github.com/jbboehr/phpstan-laravel-validation/actions/workflows/ci.yml/badge.svg)](https://github.com/jbboehr/phpstan-laravel-validation/actions/workflows/ci.yml)
[![License: AGPL v3+](https://img.shields.io/badge/License-AGPL_v3%2b-blue.svg)](https://www.gnu.org/licenses/agpl-3.0)
![stability-experimental](https://img.shields.io/badge/stability-experimental-orange.svg)

## Explanation

If the rules given to a laravel validator are a constant expression, then the shape of the array returned by `\Illuminate\Validation\Validator::validated()` is known at compile-time, and can be statically analyzed.

```php
$request = new \Illuminate\Http\Request();

$data = \Illuminate\Support\Facades\Validator::make($request->all(), [
    'person.*.email' => 'required|email|unique:users',
    'person.*.first_name' => 'required|string',
    'person.*.age' => 'required|integer|string',
])->validated();

\PHPStan\dumpType($data);
// array{person: array<int|string, array{email: non-empty-string, first_name: string, age: numeric-string}>}

$data = $request->validate([
    'person.*.email' => 'required|email|unique:users',
    'person.*.first_name' => 'required|string',
    'person.*.age' => 'required|integer|string',
]);

\PHPStan\dumpType($data);
// array{person: array<int|string, array{email: non-empty-string, first_name: string, age: numeric-string}>}
```

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
* Wildcards must be indexed by integer and can't be mixed with non-wildcard rules.
* Custom validation rules, implicit rules, and enums are not currently supported.

## License

This project is licensed under the [AGPL v3+](https://www.gnu.org/licenses/agpl-3.0) License - see the LICENSE.md file for details.
