# Validator mutation inference

## Current decision

PHPStan reports direct, statically identifiable calls to Laravel validator
methods that mutate data or rules:

- `setData()`;
- `setValue()`;
- `setRules()`;
- `addRules()`;
- imperative `$validator->sometimes()`.

Laravel does not clear its existing validation message state when these
methods change data or rules. A validator that has already run can consequently
return unchecked replacement data from `validated()` or retain an obsolete
failure. Changing rules also detaches the extension's inferred rule metadata
from the validator's runtime rules.

The current policy is deliberately simple: construct a new validator with its
complete data and rules instead of mutating an existing instance. The Rensei
parsing runtime's package-owned `setValue()` call is a narrow exception. It
runs during controlled finalization and checks that the value still equals the
input that was parsed before writing the produced value.

The rule recognizes direct calls and finite dynamic method names whose every
possible value is a prohibited mutator. It cannot follow a mutator through a
first-class callable, reflection, `call_user_func()`, `mixed`, or arbitrary
runtime dispatch. Suppressing or bypassing the diagnostic can therefore leave
obsolete inferred metadata in place. This is an intentional limit of the
current hard-error policy, not a supported mutation path.

Laravel also exposes mutable state outside these methods. In particular,
assigning `Validator::$excludeUnvalidatedArrayKeys` changes output projection
without a method call. The current diagnostic does not detect that assignment.

## Possible future refinement

A later implementation could replace selected errors with lifecycle-aware type
invalidation. A sound design would need to account for all of the following:

- fresh, validated, failed, and mutated validator states;
- mutations whose return value is ignored;
- `$this`-returning chains;
- aliases that still refer to the same mutable object;
- explicit revalidation after mutation;
- Larastan or other stubs that could take precedence over a future
  invalidation stub;
- custom validator implementations and factory resolvers.

Merely widening the variable used for the mutation is not enough:

```php
$alias = $validator;
$validator->setRules($replacement);
$alias->validated();
```

If PHPStan widens only `$validator`, `$alias` can retain the obsolete inferred
shape. A future implementation should therefore prove that its invalidation is
alias-safe or retain the diagnostic. A smaller safe exception may be possible
for mutation chained directly from a freshly constructed validator, where no
earlier validation or alias can exist.
