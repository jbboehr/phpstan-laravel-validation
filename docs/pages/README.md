{{#title phpstan-laravel-validation}}

![Inferences for Laravel Validation + PHPStan](images/phpstan-laravel-validation-banner.png)

# phpstan-laravel-validation

Laravel validation is not a typed data boundary. This extension recovers
sound structural types for successful `validated()` output from statically
resolvable rule sets.

The inferred type may be broader than a rule name suggests because Laravel
usually preserves the original native value. See
[Laravel Validation and Type Safety](guides/laravel-validation-and-type-safety.md)
for the evidence.

> [!CAUTION]
> For new type-conscious code, prefer a boundary with an explicit, normalized
> output contract. This library is a mitigation for existing Laravel
> validation, not an endorsement of that design.

## Start here

```php
$data = \Illuminate\Support\Facades\Validator::make($request->all(), [
    'person' => 'required|array',
    'person.*.email' => 'required|string|email|unique:users',
    'person.*.first_name' => 'required|string',
    'person.*.age' => 'required|integer|string',
])->validated();

\PHPStan\dumpType($data);
// array{person: array<int|string, array{
//   email: non-empty-string,
//   first_name: string,
//   age: numeric-string
// }>}
```

- **Install and run a first analysis.** [Getting Started](getting-started.md)
- **Understand why a type is broader than the rule name.**
  [Understanding Inferred Types](guides/inferred-types.md)
- **Look up a rule, builder, or configuration key.**
  [Configuration](reference/configuration.md),
  [Validation Rules](reference/validation-rules.md),
  [Rule Builders](reference/rule-builders.md)

The 0.1 line is an experimental public release. Compatibility and known
limitations are in [Getting Started](getting-started.md#compatibility) and
[Limitations](reference/limitations.md).
