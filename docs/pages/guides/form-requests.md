# FormRequest Inference

FormRequest inference is experimental and disabled by default. Enable it
only when you want `validated()` and supported `safe()` projections on
conventional FormRequest classes with statically resolvable payloads.

```neon
parameters:
    phpstanLaravelValidation:
        formRequests:
            enabled: true
```

## What is inferred

The extension resolves statically available return expressions from `rules()`
and applies that shape to whole-payload and supported keyed `validated()`
calls:

```php
final class StorePersonRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'age' => 'integer',
        ];
    }
}

function store(StorePersonRequest $request): void
{
    \PHPStan\dumpType($request->validated());
    // array{name: string, age?: float|int|string|Stringable|true}

    \PHPStan\dumpType($request->safe(['name']));
    // array{name: string}
}
```

Literal returns, resolvable branches, inherited or trait-provided methods,
class constants, typed method parameters, and declared custom-rule contracts
can participate. If any possible return expression cannot be resolved, the
call keeps Laravel's broad return type rather than inferring from only part
of the method.

Inherited rules that depend on late-bound `static::` or `$this::` references
stay broad. Calls that compose another rules body, such as
`array_merge(parent::rules(), [...])`, are not expanded unless PHPStan can
expose the complete constant result.

## Receiver types

A parent-typed receiver can hold a child with different rules or lifecycle
hooks. The extension considers the concrete class and its discovered
descendants. A request with no discovered descendants retains its own shape
without requiring `final`. Identical contracts retain that shape; different
contracts form a union. An abstract receiver can use the contracts of its
known concrete descendants.

For example, if a parent requires `value` to be a string and a child requires
an array, the parent receiver's `validated('value')` type is `array|string`.
The child receiver retains `array` when its own descendants allow it.

Every possible concrete class must pass the rule-resolution and lifecycle
checks. An unsafe or unresolved possibility makes the receiver fall back to
Laravel's declared type. A discovered anonymous child also causes a fallback
because its contract is not resolved. A child override of `safe()` prevents
parent `safe()` inference without discarding an otherwise valid `validated()`
contract. A final `rules()` method does not prevent lifecycle overrides, and
PHPDoc `@final` does not hide discovered descendants.

This model assumes the [discovery scope](#discovery) covers the request
implementations used by the application. Undiscovered vendor classes and
dynamically generated subclasses can violate that assumption. Include
relevant sources in PHPStan's analysed or scan paths, or explicitly list the
request classes. Inference does not prove that an open class has no other
runtime descendants.

## Lifecycle hooks

`FormRequest` is a validator lifecycle, not just a `rules()` method.
Inference falls back when the request overrides `validated()`,
`getValidatorInstance()`, `createDefaultValidator()`, `validationRules()`,
or `passedValidation()`, or declares `validator()`, a non-empty
`withValidator()`, or `after()`. Those hooks can replace the validator,
mutate its rules, or change what `validated()` returns.

A userland `withValidator()` body with no executable statements is a no-op,
including when inherited or provided by a trait. Any executable statement, or
a body the parser cannot verify, restores the conservative fallback.

## Trusted classes

A project can assert that a particular class's lifecycle hooks do not
invalidate its `rules()` contract:

```neon
parameters:
    phpstanLaravelValidation:
        formRequests:
            trustedClasses:
                - App\Http\Requests\StorePersonRequest
```

Trust is exact: subclasses are checked separately and are not trusted
implicitly. Trust bypasses lifecycle-hook checks for the configured class.
It does not make unresolved rule expressions resolvable and does not override
a custom `validated()` implementation. A false trust declaration can produce
an unsound type.

## Discovery

FormRequest inference does not require Larastan. Request classes are
discovered from PHPStan's analysed and scan paths and from the root project's
Composer `autoload` and `autoload-dev` PSR-0, PSR-4, and classmap mappings.
Composer discovery reads `.php` sources. Classes outside this scope, including
undiscovered vendor requests, retain Laravel's broad type.

Explicit analysed files and `scanFiles` entries are read regardless of their
extension. PHPStan directory paths use the configured `fileExtensions` list.
Discovery follows directory symlinks in PHPStan paths and Composer source mappings.
If a source directory cannot be read or a source symlink cannot be resolved,
non-final receivers retain the broad fallback until source discovery can complete.

Use `additionalClasses` to discover exact classes outside those paths without
weakening lifecycle checks:

```neon
parameters:
    phpstanLaravelValidation:
        formRequests:
            additionalClasses:
                - Vendor\Package\SomeRequest
```

The list does not implicitly include subclasses. A configured class with an
unsafe lifecycle hook still retains Laravel's broad type. Adding an exact
class to `trustedClasses` also makes it discoverable, but additionally asserts
that its lifecycle hooks are safe enough to bypass those checks.

## `validated($key)` and `safe()`

Store the terminal array, not the mutable `ValidatedInput` wrapper, when the
validated shape matters to downstream analysis:

```php
$validated = $request->safe()->all();
$selected = $request->safe(['name', 'age']);

$wrapper = $request->safe();
$validatedLater = $wrapper->all(); // plain array
```

The first two expressions retain the inferred shape. The last deliberately
does not. Laravel permits array-offset and property writes and unsets on a
`ValidatedInput`, and every alias observes those mutations. A wrapper can also
escape to code that PHPStan does not analyse. Preserving an old payload shape
on the stored object would therefore turn an ordinary refactor into an unsound
type promise.

Constant string and integer keys, ordinary dotted paths, finite constant-key
unions, and explicit defaults participate in `validated($key, $default)`
inference. Optional paths include the default type; an omitted default is
`null`. Dynamic keys, wildcard or first/last traversal, segment arrays,
object-property traversal, and `Closure` defaults remain `mixed`.

Constant string and integer paths passed to `safe([...])` are projected from
the same validated shape. Direct `safe()->all()`, `safe()->toArray()`,
`safe()->only([...])`, and `safe()->except([...])` chains retain or project
that shape for registry-verified FormRequests. `only()` and `except()` follow
Laravel's dotted-path behavior; a literal top-level key containing a dot takes
precedence when `except()` removes a key. Before Laravel 13.24, `Arr::forget()`
can retain a nested-array reference between selectors. Multi-selector
`except()` calls therefore remain broad when an earlier dotted traversal could
change the meaning of a later selector.

Direct `safe()->merge([...])` chains also preserve the inferred payload for
subsequent `all()`, `toArray()`, `only()`, and `except()` calls when the merged
array has a statically bounded shape. This models Laravel's shallow
`array_merge()` behavior: later string keys replace earlier values and numeric
keys in direct array expressions are appended and reindexed. PHPStan array
shapes do not guarantee the insertion order of multiple integer keys, so
bounded variables with ambiguous numeric ordering remain broad. Dynamic merge
arrays, state-changing arguments, and stored mutable wrappers also remain
broad.

Validator instances retain Laravel's declared `safe()` types because
`Factory::resolver()` may return a custom Validator whose virtual
`validated()` implementation changes the payload. The `ValidatedInput`
wrapper is mutable: Laravel exposes array-offset and property writes and
unsets. Once a wrapper is stored in a variable, later accessors keep
Laravel's broad declared array type. `merge()` returns a new wrapper rather
than mutating the original, but storing either wrapper still creates the same
alias and escape problem. Dynamic selectors and selector expressions that may
execute user code also remain broad.

## Residual assumptions

The inferred contract assumes callers do not replace a FormRequest's resolved
validator through the inherited public `setValidator()` method before calling
`validated()` or `safe()`. A custom `safe()` override retains its declared
return type.

Configuration keys are documented in
[Configuration](../reference/configuration.md#form-requests).
