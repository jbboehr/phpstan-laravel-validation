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

## Validator mutation and contract invalidation

Laravel validators are mutable, but Laravel does not clear their previous
validation message state when their data or rules change. After validation has
run, `validated()` can therefore return data that was never checked against the
current rules, or reject valid data because an earlier input failed.

The extension tracks these mutation methods:

- `setData()`;
- `setValue()`;
- `setRules()`;
- `addRules()`;
- imperative `$validator->sometimes()`.

When one of these methods is called on an existing validator carrying an
inferred rule contract, PHPStan reports
`laravelValidation.validatorMutation`. When the return value of `setData()`,
`setRules()`, or `sometimes()` is used, that returned contract is also widened
to a plain validator unless the call is one of the fresh cases below.

The `sometimes` validation rule remains supported. This behavior concerns only
the method that mutates an existing validator.

Two syntactically fresh cases are safe enough to retain useful inference:

- `setData()` chained directly from a statically resolved factory, facade, or
  `validator()` helper call retains the freshly constructed validator's rule
  contract;
- a statically resolvable `setRules()` chain from the same fresh entry points
  receives the replacement rules' inferred contract.

Other returned mutation chains are broad. Calling `setRules()` through a
variable is broad even when its argument is constant: static analysis cannot
prove that the validator has not already validated, cached a result, or been
aliased.

Broadly typed Laravel validators do not carry a contract for this extension to
invalidate, so their mutations are not diagnosed. This includes ordinary
`FormRequest::withValidator()` hooks: non-empty lifecycle hooks already make
FormRequest inference fall back unless the request is explicitly trusted.
Constructing a new validator with complete data and rules remains the clearest
general solution.

The experimental Rensei runtime uses `setValue()` internally during its
invariant-checked final write-back. Calls made from the package-owned
`BaseParsingRule` implementation are exempt; subclasses and application code
are not.

Return-value widening cannot change the type of an ignored receiver or another
alias. Suppressing the diagnostic can therefore make later analysis unsound.
PHPStan has no general object-identity or validator-typestate model with which
to invalidate every reference to the mutable object.

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
