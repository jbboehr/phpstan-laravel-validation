# Understanding Inferred Types

The extension describes what Laravel can return after successful validation.
It does not describe the type a rule name appears to promise.

## Soundness

Every successful Laravel output must be a subtype of the inferred type. When
Laravel preserves several native representations, the inferred type is a
union of those representations.

```php
$validated = Validator::make($input, [
    'age' => 'required|integer',
])->validated();

\PHPStan\dumpType($validated);
// array{age: float|int|numeric-string|Stringable|true}
```

`integer` accepts and preserves `1`, `1.0`, `true`, numeric strings, and
compatible `Stringable` objects. Narrowing that to `int` would be false.
See
[Laravel Validation and Type Safety](laravel-validation-and-type-safety.md)
and [Validation Rules](../reference/validation-rules.md).

Add an adjacent native-family rule when you know the input representation:

```php
$validated = Validator::make($input, [
    'age' => 'required|integer|string',
])->validated();

\PHPStan\dumpType($validated);
// array{age: numeric-string}
```

## Presence is separate from the value type

`required` makes a key required. It does not change the value family.
`present` requires the key and still allows a blank string to bypass
non-implicit adjacent rules. `missing` and `exclude` omit the path from
successful output.

Details are in
[Presence and Output Projection](../reference/presence-and-projection.md).

## Nested arrays and wildcards

An explicit parent `array` rule makes that offset required. Wildcard children
alone do not: the parent may be absent, and when present it is still a
non-null array.

```php
$validated = Validator::make($input, [
    'person' => 'required|array',
    'person.*.email' => 'required|string|email',
])->validated();

\PHPStan\dumpType($validated);
// array{person: array<int|string, array{email: non-empty-string}>}
```

Without `'person' => 'required|array'`, the parent is `person?`.

Bare `array` parents with nested children are rebuilt from those children.
Parameterized `array:name,email` and `array_keys` preserve the permitted
parent instead. See
[Presence and Output Projection](../reference/presence-and-projection.md).

## When inference stays conservative

Precise inference requires a complete, statically visible rule expression.
Assigned builders, dynamic calls, callbacks, macros, and unknown custom
predicates fall back rather than guessing. The common rule is in
[Static Resolvability](../reference/static-resolvability.md).

## Input refinement after `validate()`

A successful direct facade or `Factory::validate()` call can narrow safe
top-level fields on the caller's original array. That is an input constraint,
not a claim that the array was replaced by `validated()` output.

```php
/** @var array<string, mixed> $input */
Validator::validate($input, ['name' => 'required|string']);

\PHPStan\dumpType($input['name']); // string
```

Limits are listed under
[Supported Entry Points](../reference/entry-points.md#input-refinement).
