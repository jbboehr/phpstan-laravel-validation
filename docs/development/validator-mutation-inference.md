# Validator mutation inference

## Runtime problem

Laravel validators combine mutable data and rules with cached validation
state. `setData()`, `setValue()`, `setRules()`, `addRules()`, and imperative
`sometimes()` can therefore change the apparent contract without clearing the
result of an earlier validation.

The runtime suite verifies two particularly hostile cases on every supported
Laravel major:

- after a successful validation, `setData()` can make `validated()` return
  replacement data that was never checked against the rules;
- after a successful validation, `setRules()` can leave `validated()` returning
  data accepted under the old rules rather than the replacement rules.

An earlier failure can remain stale as well. This means constant replacement
rules are not enough to recover a precise type for a validator held in a
variable: PHPStan would also need to prove that validation has never run.

## Current hybrid model

The implementation combines conservative invalidation with a diagnostic where
invalidation cannot cover every reference to the same object.

| Mutation form | Result |
| --- | --- |
| Fresh `make(...)->setData(...)->validated()` | Retain the fresh validator's resolved rules |
| Fresh `make(...)->setRules($constant)->validated()` | Infer the statically resolved replacement rules |
| Returned `setData()`, `setRules()`, or `sometimes()` on an existing validator | Widen to Laravel's plain validator type |
| Ignored `setData()`, `setRules()`, `addRules()`, or `sometimes()` on an existing inferred validator | Report `laravelValidation.validatorMutation`; PHPStan cannot rewrite the ignored receiver |
| `setValue()` on an existing inferred validator | Report the mutation |
| Mutation of a broad Laravel validator | Retain Laravel's broad declared type without an extension diagnostic |

“Fresh” is deliberately syntactic. The receiver must be a statically resolved
`Factory::make()`, validator facade `make()`, or `validator()` helper call in
the same expression. A validator first stored in a variable is not assumed to
be unused, even if local code happens not to show an earlier validation.

`setValue()` is absent from Laravel 10.0 through 10.6, and `addRules()` does not
return the validator. Neither exposes a portable returned contract to replace.
The Rensei parsing runtime's package-owned `setValue()` call is exempt from the
diagnostic; it runs during invariant-checked finalization. Application and
subclass calls are not exempt.

Non-empty FormRequest lifecycle hooks already make FormRequest inference broad
unless the request is explicitly trusted. Their broadly typed validator
parameters therefore do not produce mutation diagnostics. The extension does
not turn ordinary `withValidator()->sometimes()` usage into a repository-wide
style prohibition when no inferred validator contract is at stake.

## Why the diagnostic remains

PHPStan can widen the value returned from a mutation, but an ignored method
call does not replace its receiver, and PHPStan does not provide a general
object-identity model that invalidates every alias:

```php
$alias = $validator;
$validator->setRules($replacement);
$alias->validated();
```

Even a hypothetical widening of `$validator` would leave `$alias` carrying
obsolete rule metadata. The diagnostic makes that residual risk visible.
Suppressing or bypassing it through a first-class callable, reflection,
`call_user_func()`, `mixed`, or arbitrary runtime dispatch can therefore make
later inference unsound.

Laravel also exposes relevant mutable state outside these methods. Assigning
`Validator::$excludeUnvalidatedArrayKeys`, for example, changes output
projection without a method call. The current diagnostic does not detect that
assignment.

The return-type extension only specializes Laravel's base mutator methods.
Overrides remain conservative. Larastan-first and extension-first consumer
tests verify that returned-value invalidation is independent of stub
precedence. An earlier receiver-stub prototype was rejected because PHPStan
does not merge it with Larastan's validator stub; the result changed depending
on which package supplied the selected declaration.

## Downstream smoke test

The strict diagnostic prototype and the hybrid follow-up were exercised on
2026-08-20 against the same disposable, pinned application checkouts. Each
checkout loaded this repository through a Composer path package. The hybrid
follow-up used the working tree based on `b1f9d65334ed`.

| Application | Application revision | Laravel | PHPStan | Strict prototype | Hybrid model |
| --- | --- | --- | --- | ---: | ---: |
| BookStack | `e1cd3229966d` | 12.64.0 | 2.2.8 | 0 | 0 |
| Koel | `dfec91ff2905` | 13.24.0 | 2.1.55 | 0 | 0 |
| Pterodactyl | `850f2b9a4ff9` | 12.64.0 | 2.2.6 | 5 | 0 |

BookStack's application-level `setValue()` call targets its own MFA value
object and was correctly ignored. Koel contains no mutation of an inferred
Laravel validator.

The strict Pterodactyl result consisted of one safe pre-validation `setData()`
pattern and four conventional `sometimes()` calls in
`StoreServerRequest::withValidator()`. They were adoption cost rather than
five demonstrated stale-state bugs. Under the hybrid model, Pterodactyl
returned to its native 29 diagnostics with no added mutation diagnostics,
while BookStack and Koel remained clean.

These were compatibility checks, not new performance benchmarks. The earlier
BookStack and FormRequest reports remain the performance evidence.

## Remaining refinement boundary

A stronger model would require first-class validator typestate and alias
tracking: fresh, validated, failed, mutated, and explicitly revalidated states
would all need distinct treatment across every reference to the object. PHPStan
does not currently expose that model to this extension. The hybrid policy
therefore takes the useful precision available for provably fresh chains,
widening elsewhere and retaining a diagnostic at the alias-unsafe boundary.
