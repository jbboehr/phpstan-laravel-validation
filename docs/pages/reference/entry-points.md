# Supported Entry Points

The same rule-set inference applies to these statically resolvable calls.

| Entry point | Inferred return |
| --- | --- |
| `Validator::make($data, $rules)->validated()` | Validated shape |
| `Factory::make($data, $rules)->validated()` | Validated shape |
| `Factory::validate($data, $rules)` | Validated shape |
| `Validator::validate($data, $rules)` facade | Validated shape |
| `Request::validate($rules)` | Validated shape |
| Controller `$this->validate($request, $rules)` | Validated shape |
| `validator($data, $rules)->validated()` helper | Validated shape |
| `FormRequest::validated()` / supported `safe()` | Validated shape when [FormRequest inference](../guides/form-requests.md) is enabled |

Named `data` and `rules` arguments are supported. Dynamic rule sets retain
Laravel's broad declared return types. Calls that supply the relevant
argument only through `...` unpacking also retain those broad types rather
than guessing which unpacked element contains the rules.

If the input does not match the rules, Laravel throws
`Illuminate\Validation\ValidationException`. For successful input, the
extension infers the values and shape Laravel may preserve.

A directly constructed `Illuminate\Validation\Validator` retains Laravel's
broad declared return type and is not narrowed from configuration
assumptions such as `includeUnvalidatedArrayKeys`.

## Validator mutation is prohibited

The extension reports statically identifiable calls to these methods as
errors:

- `setData()`;
- `setValue()`;
- `setRules()`;
- `addRules()`;
- imperative `$validator->sometimes()`.

The diagnostic identifier is `laravelValidation.validatorMutation`.

The `sometimes` validation rule remains supported. This restriction applies
only to the method that mutates an existing validator.

Laravel retains the validator's previous message state when its data or rules
change. After validation has already run, `validated()` can therefore return
data that was never checked against the current rules, or reject valid data
because an earlier input failed. Rule mutation also invalidates the static
metadata attached to an inferred validator. Construct a new validator with
the complete data and rule set instead.

The experimental Rensei runtime uses `setValue()` internally during its
invariant-checked final write-back. Calls made from the package-owned
`BaseParsingRule` implementation are exempt; subclasses and application code
are not.

A future implementation may replace some diagnostics with sound type
invalidation or lifecycle-aware inference. Widening only the variable through
which a mutation occurs is insufficient because another alias can retain the
old inferred type.

## Input refinement

A successful direct facade or `Factory::validate()` call can refine safe,
statically resolvable top-level fields in the caller's original array.

```php
/** @var array<string, mixed> $input */
Validator::validate($input, ['name' => 'required|string']);

\PHPStan\dumpType($input['name']); // string
```

This is an input constraint, not a claim that the original array was
replaced by `validated()` output. Unrelated input keys may still exist.

Refinement is limited to:

- a simple input variable;
- arguments whose evaluation is known not to mutate program state.

The following are not used to narrow the caller's array:

- nested and wildcard paths;
- exclusion and missing rules;
- rule sets containing custom or opaque runtime behavior.

Guaranteed fields are added. An optional field is narrowed only when the
input's existing type already proves that the field is present.

This post-call refinement assumes Laravel's ordinary factory and validator
execution. Application-defined replacements for that execution path can
invalidate the inferred constraint.
