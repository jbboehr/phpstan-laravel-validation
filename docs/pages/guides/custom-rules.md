# Custom Validation Rules

Unknown custom rule objects and closures do not prevent inference for the
rest of a statically resolvable rule set. They contribute no value
narrowing, so adjacent built-in rules remain useful:

```php
$validated = Validator::make($input, [
    'reference' => ['required', 'string', new ValidReference()],
])->validated();

\PHPStan\dumpType($validated);
// array{reference: string}
```

When a custom rule has a trustworthy static contract, declare the upper bound
of the original values it can preserve after successful validation.

`@phpstan-assert` on `validate()` is not read. Use one of the contracts
below.

## Attribute

```php
use jbboehr\PhpstanLaravelValidation\Attribute\ValidationRuleType;

#[ValidationRuleType('non-empty-string')]
final class ValidReference implements \Illuminate\Contracts\Validation\ValidationRule
{
    // ...
}
```

## PHPDoc

```php
/** @laravel-validation-type non-empty-string */
final class ValidReference implements \Illuminate\Contracts\Validation\ValidationRule
{
    // ...
}
```

## Configuration

Configuration overrides either class-local declaration. It can also type
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

Precedence is configuration, then the attribute, then PHPDoc. Registered
names are normalized like Laravel rule names, so `valid_reference`,
`valid-reference`, and `ValidReference` use the same configured contract.

Malformed configuration, attribute, or PHPDoc contracts fail analysis when
the corresponding rule is encountered. They are not silently widened to
`mixed`.

## What a contract does not do

These are value-only contracts. They do not:

- make a field required;
- declare a rule implicit;
- transform a value; or
- control output projection.

An optional custom `int` rule still produces `int|string` because a blank
string may bypass a non-implicit rule. Combining it with `required` produces
`int`.

The extension assumes custom rules act as predicates and preserve the input
value. A contract is unsound if the rule accepts values outside its declared
type or mutates validator data, rules, or validated output.

Stringable builders, conditional builders, and nested rule builders may
encode presence or projection behavior. When their structure cannot be
recovered statically, the affected path is widened rather than treated as an
ordinary value predicate. See
[Static Resolvability](../reference/static-resolvability.md).

Larastan is not required. The extension does not boot the application to
discover registered aliases.
