# Rule Builders

Fresh inline factory calls and exact construction of the listed classes can
recover the equivalent built-in rule. Shared conservative cases are in
[Static Resolvability](static-resolvability.md). This page records support,
version boundaries, and behavior unique to each API.

## Enum

Fresh `Rule::enum(Status::class)` and
`new Illuminate\Validation\Rules\Enum(Status::class)` recover the enum class
and literal filter state.

```php
$validated = Validator::make($input, [
    'status' => ['required', Rule::enum(Status::class)->only(Status::Published)],
])->validated();

\PHPStan\dumpType($validated);
// array{status: Status::Published}
```

Literal `only()` and `except()` calls are modeled from Laravel 10.46.
Backed enums also include the original backing values and weakly coerced
native values that Laravel can accept and preserve. They are not assumed to
return only enum objects.

## `Rule::in()`

Fresh `Rule::in()` calls with literal scalar values recover the equivalent
parameterized `in` rule.

```php
$validated = Validator::make($input, [
    'status' => ['required', Rule::in(['draft', 'published'])],
])->validated();

\PHPStan\dumpType($validated);
// array{status: 'draft'|'published'|Stringable}
```

The union includes every native value Laravel can accept and preserve through
its loose string comparison. Numeric parameters can narrow safely
representable native integers to literals, but retain broad `float`,
`numeric-string`, and `Stringable` branches. A builder containing a float
also retains broad `int`: application code can change PHP's `precision`
before Laravel stringifies the builder.

From Laravel 10.21.1, literal enum arguments are serialized to their case
names or backing values. `Rule::in([Status::Draft])` is not an enum-object
rule.

Exact fresh `new In([...])` matches the factory. Laravel 10.36 expands the
constructor to accept scalar and variadic inputs.

## `Rule::notIn()`

Fresh `Rule::notIn()` calls are a type-neutral `not_in` predicate. Adjacent
value and presence rules remain responsible for the useful type.

```php
$validated = Validator::make($input, [
    'role' => ['required', 'string', Rule::notIn(['admin'])],
])->validated();

\PHPStan\dumpType($validated);
// array{role: string}
```

The extension does not express “every string except `admin`.” Because the
forbidden set does not affect this neutral contribution, its expression may
be dynamic while a fresh factory call or exact `new NotIn(...)` remains
visible. Direct array construction works throughout the supported range;
scalar, variadic, and `Arrayable` constructor inputs begin in Laravel 10.36.

## Literal conditional builders

Fresh `Rule::requiredIf()`, `Rule::excludeIf()`, and `Rule::prohibitedIf()`
calls with a statically known boolean become the corresponding unconditional
`required`, `exclude`, or `prohibited` rule. Exact `new RequiredIf`,
`ExcludeIf`, and `ProhibitedIf` construction is supported.

```php
$validated = Validator::make($input, [
    'name' => ['string', Rule::requiredIf(true)],
    'legacy_name' => ['string', Rule::excludeIf(true)],
])->validated();

\PHPStan\dumpType($validated);
// array{name: string}
```

A false condition serializes to an empty rule that contributes no validation
constraint. Adjacent rules remain. Present input is preserved when the empty
rule stands alone. The explicit rule path still participates in output
projection, so an empty rule on a nested parent can preserve unvalidated
sibling keys.

Fresh `Rule::when()` calls with a statically known boolean expose the
selected string or array branch. `Rule::unless()` has the same support from
Laravel 10.33, with the condition inverted. Selected rules are flattened
into surrounding rule lists as Laravel flattens them. An empty selected
branch still marks an explicit parent path for nested projection. Nested
conditional wrappers are not recursively expanded because Laravel performs
only one expansion pass.

## `Rule::array()`

Introduced in Laravel 11.7. Fresh calls with statically visible scalar or
enum keys recover the equivalent `array` rule.

```php
$validated = Validator::make($input, [
    'payload' => ['required', Rule::array(['name', 'email'])],
])->validated();

\PHPStan\dumpType($validated);
// array{payload: array{name?: mixed, email?: mixed}}
```

`Rule::array()` and `Rule::array([])` serialize to a bare `array` rule, so
nested child rules rebuild the returned parent. A non-empty key list
preserves the complete permitted parent. Explicit `null` serializes to
`array:` and permits only the empty-string key.

Unquoted comma joining is lossy: `Rule::array(['a,b'])` becomes `array:a,b`
and permits keys `a` and `b`. Float keys remain conservative because PHP's
runtime `precision` can change the serialized spelling.

Exact fresh `new ArrayRule(...)` matches the factory.

## `Rule::arrayKeys()`

Introduced in Laravel 13.24. Fresh calls recover the equivalent
`array_keys` rule.

Unlike bare `array`, this rule preserves the complete permitted parent around
nested child rules. Commas split parameters. An empty key list becomes
`array_keys:` and permits the empty-string key. Float keys remain
conservative for the same `precision` reason as `Rule::array()`.

Exact fresh `new ArrayKeys(...)` matches the factory.

## Array predicates

Laravel 12.16 introduced `Rule::contains()` and `Contains`. Laravel 12.22
introduced `Rule::doesntContain()` and `DoesntContain`. Fresh factory calls
and exact direct construction recover the built-in array predicate.

```php
$validated = Validator::make($input, [
    'features' => ['required', Rule::contains('search')],
    'roles' => ['required', Rule::doesntContain('blocked')],
])->validated();

\PHPStan\dumpType($validated);
// array{features: array, roles: array}
```

Laravel checks and preserves the original array. These builders do not
describe its keys or values.

## Numeric builders

Laravel 11.42 introduced `Rule::numeric()` and `Numeric`. Fresh factory
calls, direct construction, and declared predicate methods retain Laravel's
preserved numeric representations.

```php
$validated = Validator::make($input, [
    'amount' => ['required', Rule::numeric()->between(1, 100)],
    'count' => ['required', Rule::numeric()->integer(strict: true)],
])->validated();

\PHPStan\dumpType($validated);
// Laravel 12.55+: array{amount: float|int|numeric-string, count: int}
```

Non-strict `integer()`, `digits()`, `digitsBetween()`, and `exactly()` do
not imply a native `int`. Laravel 12.55 adds `integer(strict: true)`, which
does justify `int`. Earlier releases ignore a positional boolean passed to
`integer()`. Other fluent methods constrain which values pass without
changing their possible native PHP representations.

## String builders

Laravel 12.55 introduced `Rule::string()` and `StringRule`. Fresh factory
calls, direct construction, and declared predicate methods infer a native
`string`.

The builder begins with Laravel's native `string` rule. Fluent predicates
constrain contents or length but do not convert other values into strings.
Inference currently recovers that native representation rather than every
content refinement: `Rule::string()->min(1)` remains `string`, while
`string|min:1` can be refined to `non-empty-string`.

## Date builders

Laravel 11.40 introduced `Rule::date()`. The parser understood only builders
that serialized to one rule until 11.41 (chains inside rule lists) and
11.43.2 (standalone field rules). At the applicable boundary, fresh factory
calls, direct construction, and declared comparison predicates recover
Laravel's preserved date family.

`format()` changes that family because `date_format` rejects
`DateTimeInterface` objects.

```php
$validated = Validator::make($input, [
    'published_on' => ['required', Rule::date()->format('Y-m-d')],
    'deadline' => ['required', Rule::date()->afterToday()],
])->validated();

\PHPStan\dumpType($validated);
// Laravel 11.41+: array{
//   published_on: non-empty-string,
//   deadline: DateTimeInterface|float|int|non-empty-string
// }
```

Laravel 12.44 adds `Rule::dateTime()` and the `past()`, `future()`,
`nowOrPast()`, and `nowOrFuture()` predicates. `dateTime()` uses
`Y-m-d H:i:s` and therefore infers `non-empty-string`. Formatted builders
follow the same parameter-sensitive contract as `date_format`: separator
formats such as `Y-m-d` exclude native numerics, while a numeric format such
as `Ymd` retains `float|int|non-empty-string`. Laravel 12.3 changed how
`format()` serializes, but both forms preserve this contract.

These builders validate and preserve successful input. They do not parse it
into a canonical date object.

## Dimensions

Fresh `Rule::dimensions()` and `new Dimensions()` recover the same Symfony
`File` type as the `dimensions` string rule, including width, height, and
ratio constraints. Laravel 11.23 adds `minRatio()`, `maxRatio()`, and
`ratioBetween()`. Laravel validates and preserves the original file.

## File builders

Fresh `Rule::file()`, `Rule::imageFile()`, `File::types()`, `File::image()`,
`new File()`, and `new ImageFile()` recover Symfony file predicates. Size,
MIME, extension, encoding, dimension, and additional-rule fluent constraints
retain the same successful native value type.

`extensions()` begins at Laravel 10.34. `encoding()` begins at Laravel
12.40.

Late-bound `self` / `parent` / `static` forwarding calls and global
`File::default()` configuration remain conservative.

## Database builders

Fresh `Rule::exists()` and `Rule::unique()`, and exact `Exists` / `Unique`
construction, are type-neutral predicates. The database query changes
whether validation succeeds. Successful validation preserves the original
input, so an adjacent value rule remains responsible for the native PHP
type.

Supported fluent methods include `where*()`, soft-delete, query-callback,
and unique-ignore methods. Those methods return the same rule object and do
not transform validated output.
