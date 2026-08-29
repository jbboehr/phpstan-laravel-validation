# FormRequest Inference

FormRequest inference is experimental and disabled by default. Enable it
only when you want `validated()` and supported `safe()` projections on
conventional concrete `FormRequest` classes.

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

Trust is exact: subclasses are not trusted implicitly. It bypasses
lifecycle-hook checks. It does not make unresolved rule expressions
resolvable and does not override a custom `validated()` implementation. A
false trust declaration can produce an unsound type.

## Discovery

FormRequest inference does not require Larastan. Concrete requests are
discovered from PHPStan's analysed and scan paths and from the root project's
Composer `autoload` and `autoload-dev` source mappings. Classes outside those
paths, including undiscovered vendor requests, retain Laravel's broad type.

Adding an exact class to `trustedClasses` also makes it discoverable, but
that setting simultaneously asserts that its lifecycle hooks are safe. It is
not a risk-free discovery-only option.

## `validated($key)` and `safe()`

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

Validator instances retain Laravel's declared `safe()` types because
`Factory::resolver()` may return a custom Validator whose virtual
`validated()` implementation changes the payload. The `ValidatedInput`
wrapper is mutable: Laravel exposes array-offset and property writes and
unsets. Once a wrapper is stored in a variable, later accessors keep
Laravel's broad declared array type. Dynamic selectors and selector
expressions that may execute user code also remain broad.

## Residual assumptions

The inferred contract assumes callers do not replace a FormRequest's resolved
validator through the inherited public `setValidator()` method before calling
`validated()` or `safe()`. A custom `safe()` override retains its declared
return type.

Configuration keys are documented in
[Configuration](../reference/configuration.md#form-requests).
