# Validation parsing investigation

This report asks whether `phpstan-laravel-validation` should grow an opt-in
parsing rule that turns selected Laravel validation values into a declared
runtime type, rather than only describing the values Laravel's predicate rules
accept:

```php
'age' => ['required', 'integer'],        // validates; validated() keeps "42"
'age' => ['required', Parse::integer()], // parses; validated() holds 42
```

It is a pinned development investigation, not a feature announcement or a
supported-behavior promise. No production implementation exists. The
[development report index](README.md) explains how pinned investigation
reports relate to current project documentation.

Investigation date: 2026-08-17. Prototype built and corrections folded in:
2026-08-17.

## Status

A prototype of `Parse::integer()` has since been built on this design, which
proved the mechanism and corrected the report in four places. Each correction
is recorded where the original claim appeared; they are collected here because
a reader following the report to implement the remaining parsers needs them.

1. `['required', 'nullable', Parse::integer()]` infers `array{age: int}`, not
   `int|null` (§5.3). `required` rejects null outright.
2. `TypeResolver::hasExecutableRule()` must recognize parsing rules (§15). The
   omission narrowed a caller's own array to `never` after a successful
   validation.
3. Per-validator state belongs in a `WeakMap`, not a static `SplObjectStorage`
   keyed by `spl_object_id` (§23). The original leaks, and ids are reused.
4. An escaped-dot attribute is recognized by not being a key of its own rule
   set, not by decoding the placeholder (§23). The placeholder format differs
   across the releases in scope.

Building it also surfaced two limitations the report did not anticipate.

**The write-back outlives the run, so a repeated run has to undo it.** Nothing
in Laravel restores the data, and a second `passes()` would otherwise evaluate
cross-field rules against parsed values — `['a' => [Parse::integer()], 'b' =>
['same:a']]` would pass on the first run and fail on the second, with `same`
comparing `'42'` to `42`. A parsing rule now restores what it wrote at its own
first invocation of the next run, which is the earliest point available: there
is no hook before the rule loop.

That leaves a residual case. A rule declared *before* the parsed attribute
still reads the previous run's parsed value, because the undo has not happened
yet when it runs. `['b' => ['same:a'], 'a' => [Parse::integer()]]` therefore
still fails on a second run. The result is a loud failure rather than a wrong
type, and every ordinary path runs the rules exactly once — `validate()`,
`fails()` followed by `validated()`, and FormRequest resolution.

**Implicitness has to be verified, not assumed.** The produced type is only
sound because Laravel treats the rule as implicit; an implementation of
`ParsingRule` that omits `public bool $implicit = true` is skipped for a blank
string and leaves that string in the output while the type promises otherwise.
Static analysis therefore reads implicitness the same way
`InvokableValidationRule::make()` does and declines to infer a produced type
without it. An abstract type names no runtime class, so there the requirement
rests on the interface contract alone.

Everything else the report concluded held up, including the delayed write-back
model, the implicit-rule requirement, the presence and null contract, wildcard
behavior, the exclusion guard, and the generic `ParsingRule<T>` discovery.

## Recorded revisions

| Component | Revision |
| --- | --- |
| Extension | `ebe1d77` on `develop` |
| PHP | 8.5.9 |
| PHPStan | 2.2.7 |
| `nikic/php-parser` | v5.8.0 |
| Laravel majors exercised | 10, 11, 12, 13 |

Laravel source was read and executed at `v10.0.0`, `v10.32.1`, `v10.33.0`,
`v10.34.0`, `v10.50.3` (`10-latest`), `v11.0.0`, `v11.22.0`, `v11.23.0`,
`v11.55.1` (`11-latest`), `v12.0.0`, `v12.21.0`, `v12.22.0`, `v12.39.0`,
`v12.40.0`, `v12.66.0` (`12-latest`), `v13.0.0`, `v13.3.0`, `v13.4.0`,
`v13.20.0`, `v13.21.0`, `v13.23.0`, `v13.24.0`, and `v13.25.0`
(`13-latest`), using the disposable per-version installs the
[Laravel version inference audit](../pages/contributing/laravel-version-inference-audit.md)
already provisions.

Prior-art packages were cloned and read rather than described from
documentation. Upstream Laravel pull requests and the release tag that first
carried `Validator::setValue()` were resolved through the GitHub API.

## Reproducing the measurements

The prototypes behind every measured table were throwaway harnesses under
gitignored `tmp/parsing-experiment/`. **They are not retained in the
repository.** This report records their observed output, the Laravel source
positions that explain it, and enough construction detail in
[§23](#23-implementation-sketch) to rebuild them.

Reconstructing the harness needs three pieces:

1. A per-version Laravel install. The version audit already produces these;
   any `composer require laravel/framework:^13.0` checkout works.
1. A minimal validator factory — an `ArrayLoader`, a `Translator`, an
   `Illuminate\Validation\Factory`, and a `Container`. No application boot is
   required for anything in this report except the `FormRequest` probes, which
   additionally need a container bound as `translator` and `validator`.
1. The parser prototypes described in [§5.3](#53-the-refined-prototype) and
   [§23](#23-implementation-sketch).

Promote this tooling into `scripts/` or `tests/` only if the feature is
implemented, and pair it with the runtime and static assertions in
[§21](#21-test-matrix) rather than keeping it as a standalone harness.

---

## 1. Executive conclusion

**Viable with caveats.**

The mechanism works, and it works identically on every Laravel major this
project supports at its current patch levels. A rule that implements
`ValidationRule` + `ValidatorAwareRule`, declares itself implicit, records a
parsed value during `validate()`, and writes it back from a
`Validator::after()` callback produces exactly the intended semantics:

```php
$input['age'];     // "42"   — request/input untouched
$validated['age']; // 42     — validated()/safe()/valid() transformed
```

with `array{age: int}` / `array{age?: int}` / `array{age: int|null}` all
soundly achievable. Byte-identical prototype output across Laravel
`10-latest`, `11-latest`, `12-latest`, and `13-latest`.

The caveats are real and must be designed around, not discovered later:

1. **`Validator::setValue()` does not exist before Laravel `v10.7.0`**, and
   `Validator::getValue()` is `protected` before `v10.33.0`. This package
   declares `illuminate/validation: ^10.0`. A parsing feature therefore has a
   narrower supported floor than the analyzer does — and that floor **cannot be
   expressed in Composer**, because it applies only to users of `Parse::*`.
   Enforce it with a runtime guard plus a PHPStan check (§17, §20).
2. **The parsing rule must be implicit.** Without it, Laravel skips the rule
   for blank strings, and `validated()` returns `''` where the inferred type
   says `int`. That is precisely the unsoundness the brief warns about, and it
   was reproduced.
3. **Escaped-dot attributes (`'a\.b'`) silently defeat the mechanism.** The
   rule receives the decoded attribute `a.b` while the validator's data is
   keyed by `a__dot__<random-hash>`. Naive `setValue()` corrupts the data
   array; a presence-guarded parser no-ops. Either way, runtime and static
   types diverge unless explicitly handled.
4. **Delayed transformation makes sizing rules keep string semantics.**
   `[Parse::integer(), 'min:10']` evaluates `min` against `"42"` as a string
   length. This is a direct consequence of the (correct) decision that
   ordinary rules see the raw representation, and it will surprise users.
5. **Excluded attributes need a write-back guard**, or the flush reintroduces
   removed keys into `Validator::getData()`.
6. **`validated()` inside an `after()` callback returns un-parsed values, and
   the ordering cannot be fixed.** Any callback registered before validation
   runs before the parser's write-back, so `validated()`, `safe()`, `valid()`,
   and `getData()` all observe raw data there. This is a genuine
   static-soundness hole — PHPStan would infer `int` where the runtime yields
   `string('42')` — and it needs a dedicated PHPStan rule, not only
   documentation (§8.1, §15).

None of these is fatal. All are enumerable and testable, which matters more
than their absence.

---

## 2. Laravel validation lifecycle

All line numbers are from `laravel/framework` `v13.25.0`
(`src/Illuminate/Validation/Validator.php`) unless noted. Structure is
unchanged from `v10.0.0` except where called out.

Upstream excerpts are quoted for behavior, not byte-for-byte: this repository
formats inline PHP examples with its own PHP-CS-Fixer configuration, and some
excerpts elide branches irrelevant to parsing. The cited file and line locate
the original.

### 2.1 Rule preparation

`ValidationRuleParser::explodeRules()` → `explodeWildcardRules()` expands
`users.*.age` into concrete attributes (`users.0.age`, `users.1.age`) **before
validation begins**, driven by the data. `ValidationRuleParser::prepareRule()`
wraps any `ValidationRule` or `InvokableRule` in
`InvokableValidationRule::make()`.

`InvokableValidationRule::make()` (`InvokableValidationRule.php:68`):

```php
if ($invokable->implicit ?? false) {
    return new class ($invokable) extends InvokableValidationRule implements ImplicitRule { };
}
```

A **public `$implicit` property** on the rule object is therefore the supported
way to make a `ValidationRule` implicit. This is stable across `10.0.0`–`13.25.0`;
`InvokableValidationRule` is essentially unchanged over that range.

**No cloning occurs.** `explodeWildcardRules()` merges the same rule object
into every expanded attribute. Confirmed at runtime by `spl_object_id`: one
instance served `users.0.age` and `users.1.age`.

### 2.2 Per-rule execution

`passes()` (`:467`):

```php
$this->messages = new MessageBag();
[$this->distinctValues, $this->failedRules] = [[], []];

foreach ($this->rules as $attribute => $rules) {
    if ($this->shouldBeExcluded($attribute)) {
        $this->removeAttribute($attribute);
        continue;
    }
    if ($this->stopOnFirstFailure && $this->messages->isNotEmpty()) {
        break;
    }
    foreach ($rules as $rule) {
        $this->validateAttribute($attribute, $rule);
        if ($this->shouldBeExcluded($attribute)) {
            break;
        }
        if ($this->shouldStopValidating($attribute)) {
            break;
        }
    }
}
foreach ($this->rules as $attribute => $rules) {
    if ($this->shouldBeExcluded($attribute)) {
        $this->removeAttribute($attribute);
    }
}
foreach ($this->after as $after) {
    $after();
}

return $this->messages->isEmpty();
```

`validateAttribute()` (`:686`) fetches the value **once per (attribute, rule)
pair**, not once per attribute:

```php
$value = $this->getValue($attribute);          // :707  → Arr::get($this->data, $attribute)
$validatable = $this->isValidatable($rule, $attribute, $value);
if ($rule instanceof RuleContract) {
    return $validatable ? $this->validateUsingCustomRule($attribute, $value, $rule) : null;
}
```

**Consequence: a mutation performed by rule *n* is observed by rule *n+1***.
Confirmed at runtime (§4).

`isValidatable()` (`:824`) gates on four predicates. The two that matter here:

- `presentOrRuleIsImplicit()` (`:844`):

  ```php
  if (is_string($value) && trim($value) === '') {
      return $this->isImplicit($rule);
  }
  return $this->validatePresent($attribute, $value) || $this->isImplicit($rule);
  ```

  A non-implicit rule **never runs** for `''` or `'   '`.

- `isNotNullIfMarkedAsNullable()` (`:891`):

  ```php
  if ($this->isImplicit($rule) || ! $this->hasRule($attribute, ['Nullable'])) {
      return true;
  }
  return ! is_null(Arr::get($this->data, $attribute, 0));
  ```

  For an **implicit** rule this short-circuits, so `nullable` does *not*
  protect an implicit parser from `null`. The parser must handle null itself.

### 2.3 Validator injection

`validateUsingCustomRule()` (`:922`):

```php
$originalAttribute = $this->replacePlaceholderInString($attribute);
$attribute = match (true) {
    $rule instanceof Rules\Email => $attribute,
    $rule instanceof Rules\File => $attribute,
    $rule instanceof Rules\Password => $attribute,
    default => $originalAttribute,
};
$value = is_array($value) ? $this->replacePlaceholders($value) : $value;
if ($rule instanceof ValidatorAwareRule) {
    $rule->setValidator($this);
}
if ($rule instanceof DataAwareRule) {
    $rule->setData($this->data);
}
if (! $rule->passes($attribute, $value)) {
    // record the failure and its message
}
```

For a wrapped `ValidationRule`, `InvokableValidationRule::passes()`
(`InvokableValidationRule.php:85`) forwards again to the inner object:

```php
if ($this->invokable instanceof DataAwareRule) {
    $this->invokable->setData($this->validator->getData());
}
if ($this->invokable instanceof ValidatorAwareRule) {
    $this->invokable->setValidator($this->validator);
}
```

So the validator is injected **immediately before each invocation**, per
attribute. A rule instance shared across wildcard expansions receives the same
validator repeatedly; it must not cache per-attribute state on `$this`.

Note the placeholder decoding on line `:924`: **the rule receives the decoded
attribute name**, which is why escaped dots break write-back (§6.4).

### 2.4 `after()` callbacks

`after()` (`:447`) appends `fn () => $callback($this)` to `$this->after`.
Callbacks fire at the end of `passes()`, **after every rule and after exclusion
cleanup, but before `messages->isEmpty()` is evaluated**. They therefore run
whether or not validation succeeded, and they can still add errors.

Registering a callback from inside a rule is safe: rules run before the
`foreach ($this->after as $after)` loop begins, and PHP iterates the array by
value, so late appends would not be reached in the same loop anyway.

**Ordering:** callbacks registered before `passes()` (including
`FormRequest::withValidator()` and Precognition's
`Precognition::afterValidationHook()`, registered in
`ValidatesWhenResolvedTrait::validateResolved()`) run **before** a callback
registered from within a rule. Confirmed at runtime: a user `after()` closure
observed `age = "42"` while the parser flush later wrote `42`.

This ordering is **not** merely a documentation note. Because a parsing rule
can never register earlier than a callback the user registered before
`passes()`, `validated()` called inside such a callback returns un-parsed
values, and no reordering trick recovers it. §8.1 works the consequence
through in full; it is the one place the design produces an outright unsound
static type.

### 2.5 Output projection

`validated()` (`:647`):

```php
if (! $this->messages) { $this->passes(); }
if ($this->messages->isNotEmpty()) { throw ...ValidationException; }
foreach ($this->getRules() as $key => $rules) {
    $value = data_get($this->getData(), $key, $missingValue);
    ...
    if ($value !== $missingValue) { Arr::set($results, $key, $value); }
}
return $this->replacePlaceholders($results);
```

Three facts follow, all confirmed at runtime:

1. `validated()` reads **current validator data** (`getData()`), not the
   original input. Mutations are visible.
2. It is driven by `getRules()`. An attribute removed by `removeAttribute()`
   (exclusion) cannot reappear even if its data key is re-added.
3. It runs `passes()` only when `$this->messages` is unset. Repeated
   `validated()` calls do **not** re-validate.

`safe()` (`:633`) is `new ValidatedInput($this->validated())` — identical data.
`validate()` (`:597`) is `fails()` then `validated()`; the extra `passes()` run
inside `fails()` is the only lifecycle difference, and it is idempotent for a
correctly written parser.

`valid()`/`invalid()`/`attributes()` all read `$this->data` and therefore also
see transformed values.

### 2.6 `FormRequest`

`ValidatesWhenResolvedTrait::validateResolved()`:

```php
$this->prepareForValidation();
if (! $this->passesAuthorization()) {
    $this->failedAuthorization();
}
$instance = $this->getValidatorInstance();
if ($this->isPrecognitive()) {
    $instance->after(Precognition::afterValidationHook($this));
}
if ($instance->fails()) {
    $this->failedValidation($instance);
}
$this->passedValidation();
```

`FormRequest::validated()` (`FormRequest.php:362`) is
`data_get($this->validator->validated(), $key, $default)`; `safe()` wraps the
same array. The request's own `ParameterBag` is never touched, so
`$request->all()`, `input()`, `get()`, `post()` keep the **original**
representation while `validated()`/`safe()` are transformed. Confirmed on all
four majors.

### 2.7 Is `setValue()` a sanctioned extension point?

**Intentional public extension API**, with a caveat about age.

`Validator::setValue()` was introduced by
[laravel/framework#46716](https://github.com/laravel/framework/pull/46716)
(commit `b60b19a2`, merged 2023-04-11), first released in **`v10.7.0`**
(bisected by tag: absent in `v10.6.0`, present in `v10.7.0`). The PR's stated
motivation is verbatim the use case under investigation:

> For package development, i would like to make it possible for rules to
> override data to make the validation 'type-safe'. There is now no possible
> way to efficiently update a value inline.

The PR body ships a `BaseTypeSafeRule implements ValidationRule, DataAwareRule,
ValidatorAwareRule` example calling `$this->validator->setValue(...)`. It was
merged by Taylor Otwell as a co-author.

The companion PR
[laravel/framework#46162](https://github.com/laravel/framework/pull/46162),
which proposed a first-class `TransformsResultRule` interface with a
`transform()` hook, was **closed**. Laravel deliberately declined to own the
transformation concept and instead exposed the primitive that lets userland own
it. That is the strongest possible stability signal short of documentation:
the method exists *for this*, and the maintainers rejected the alternative that
would have obsoleted it.

Counterweight: it is not in the official docs, and `getValue()` was only
promoted from `protected` to `public` in `v10.33.0` — the surface around it
has moved within a major.

Verdict: **treat as intentional public extension API with a `>= 10.7.0`
floor**, guard the call, and keep a runtime capability check.

---

## 3. Experimental results

Selected concrete observations, quoted from the captured harness transcripts.
Probe names such as `run2.php` or `after-hole.php` identify which prototype
produced a result; those files were throwaway and are not in the repository.

### 3.1 Baseline

```text
Validator::make(['age' => '42'], ['age' => ['required','integer']])
  passes()     bool(true)
  validated()  ['age' => string('42')]
```

The premise holds on every tested version.

### 3.2 Delayed parser, standalone validator

```text
rules: ['required', 'integer', ParseIntegerDelayed, 'min:18']
  passes()                    bool(true)
    | delayed-parse(age) sees string('42')
    | flush callback fired
    | flush setValue(age, int(42))
  validated()                 ['age' => int(42)]
  safe()->all()               ['age' => int(42)]
  safe(['age'])               ['age' => int(42)]
  original $input untouched   ['age' => string('42')]
  getData() after passes      ['age' => int(42)]
```

### 3.3 `FormRequest`

```text
FR1. delayed parser through FormRequest
  $request->validated()      ['age' => int(42)]
  $request->safe()->all()    ['age' => int(42)]
  $request->validated('age') int(42)
  $request->all()            ['age' => string('42')]
  $request->input('age')     string('42')
  $request->get('age')       string('42')
  $request->post('age')      string('42')
```

Identical on `10-latest`, `11-latest`, `12-latest`, `13-latest`. Nested and
wildcard shapes carry through:

```text
FR3. $request->validated()  ['users' => [0 => ['age' => int(12)], 1 => ['age' => int(34)]]]
     $request->all()        ['users' => [0 => ['age' => string('12')], 1 => ['age' => string('34')]]]
```

### 3.4 Version compatibility

| Laravel | Result |
| --- | --- |
| `v10.0.0` | **Fatal** `BadMethodCallException: Method Illuminate\Validation\Validator::setValue does not exist.` |
| `v10.32.1` | Works. `getValue()` still `protected` (only affects harness code, not the parser). |
| `v10.33.0` → `13.25.0` | Works; output identical modulo object ids and the random placeholder hash. |

Diff of the refined prototype (`run2.php`) and probe suite (`run3.php`) between
`10-latest`, `11-latest`, `12-latest` and `13-latest`: **zero lines**.

---

## 4. Immediate vs delayed mutation

### 4.1 Immediate mutation is order-dependent — confirmed

`validateAttribute()` re-reads the value for every rule, so this is exactly
what happens:

```php
'age' => ['integer', Parse::integer(), 'min:18'],
//         sees "42"   sees "42"          sees 42
```

Observed consequences of immediate mutation:

| Case | Immediate | Delayed |
| --- | --- | --- |
| `['required', Parse, 'string']` | **fails** (`The age must be a string.`) | passes |
| `['required', 'string', Parse]` | passes | passes |
| `['a' => [Parse], 'b' => ['same:a']]` with `a="42" b="42"` | **fails** (`same` compares `int(42)` to `string('42')`) | passes |
| `['start' => [Parse], 'end' => ['gte:start']]` | passes | passes |
| `[Parse, 'max:3']` with `"4000"` | fails (numeric 4000 > 3) | fails (string length 4 > 3) — same verdict, different reason |

The `same:` result is the decisive one. Immediate mutation silently changes
whether an *unrelated field's* cross-field rule passes, and the change depends
on rule declaration order across two different attributes. That is not a
tolerable semantic for a package whose thesis is honest modelling.

It also reproduces, in miniature, the objection `donnysim` raised on
[#46162](https://github.com/laravel/framework/pull/46162): rules that switch
between string and numeric interpretation make order-sensitivity user-visible
in ways nobody predicts.

### 4.2 Delayed transformation — confirmed clean

The delayed model was prototyped as:

```text
raw input
  ├─ ordinary rules observe the raw representation
  ├─ Parse rule checks parseability, records the parsed value
  ├─ all rules finish; exclusion cleanup runs
  ├─ after() callback writes parsed values via setValue()
  └─ validated()/safe()/valid() return transformed values
```

Every ordinary rule — including `string`, `same`, `gte`, `required_if` — sees
exactly what it would have seen without the parser present. Verified for each.

### 4.3 The one real cost of delaying

Laravel decides whether `min`/`max`/`between`/`size` are numeric or string by
`hasRule($attribute, $this->numericRules)` where
`$numericRules = ['Numeric', 'Integer', 'Decimal']` (`:301`). A custom rule
object is wrapped in `InvokableValidationRule` before `hasRule()` ever sees it,
so a parser **cannot** register as numeric:

```text
P1. ValidationRule parser + min:10, input "42"
      passes  false   errors ["min string"]        ← string length 2 < 10
    'integer' + parser + min:10
      passes  true    validated ['age' => int(42)]
    hasRule('age', ['Numeric']) with ValidationRule parser   bool(false)
```

A legacy `Illuminate\Contracts\Validation\Rule` implementation with
`__toString(): 'Numeric'` *does* satisfy `hasRule()` (also verified), because
`prepareRule()` returns `RuleContract` instances unwrapped and
`in_array($object, ['Numeric'])` uses `__toString`. This works but is a
type-punning hack against a deprecated contract, and it would make the rule
lie to every other `hasRule()` consumer. **Do not do this.**

Instead: document that `Parse::integer()` is a parsing rule, not a numeric
predicate, and that numeric sizing still requires `integer` or `numeric` in the
rule list. `['integer', Parse::integer(), 'min:18']` is the idiomatic form and
reads correctly — the predicate says what is accepted, the parser says what
comes out.

### 4.4 Recommendation

**Delayed transformation.** It is the only model under which ordinary Laravel
rules retain ordinary Laravel behaviour, which is a stated project value and
also the only model whose static semantics are expressible without modelling
rule order.

Additionally: **transform unconditionally, not "only if valid."** Both were
prototyped. Conditional flushing was rejected because:

- `validated()` throws when there are errors, so transformed data is
  unobservable on the failure path through the supported API anyway;
- the callback cannot know whether a *later* `after()` callback will add an
  error, so "only if valid" is not actually decidable at flush time (probe P8
  confirms a later user callback can add errors after the flush ran);
- `getData()` staying half-raw on failure is a *worse* leak than staying
  fully transformed, because it varies with error timing.

Unconditional flush + `validated()`'s own error gate is simpler and more
predictable.

---

## 5. Presence, empty-string, and null semantics

### 5.1 A non-implicit parser is unsound — measured

With `implicit = false`:

| rules | input | passes | `validated()` |
| --- | --- | --- | --- |
| `[Parse]` | `[]` | true | `[]` |
| `[Parse]` | `['age' => '']` | true | **`['age' => string('')]`** |
| `[Parse]` | `['age' => '   ']` | true | **`['age' => string('   ')]`** |
| `['sometimes', Parse]` | `['age' => '']` | true | **`['age' => string('')]`** |
| `['nullable', Parse]` | `['age' => '']` | true | **`['age' => string('')]`** |
| `['present', Parse]` | `['age' => '']` | true | **`['age' => string('')]`** |

This is the exact failure mode the brief anticipated: PHPStan would say
`array{age?: int}` while Laravel returns `['age' => '']`. `required`, `filled`,
`accepted`, `declined`, and `missing` happen to block it, which is why the
existing `RuleTreeNode::allowsBlankStringBypass()` already excludes those
five — the same blank-string bypass the analyzer models today.

**A parsing rule must be implicit.**

### 5.2 A naively implicit parser breaks `nullable` and presence

With `implicit = true` and no extra handling:

| rules | input | passes |
| --- | --- | --- |
| `[Parse]` | `[]` | **false** (parser runs on an absent key) |
| `['nullable', Parse]` | `['age' => null]` | **false** (`isNotNullIfMarkedAsNullable` short-circuits for implicit rules) |

So implicitness alone is not enough. The parser must take over presence and
nullability itself.

### 5.3 The refined prototype

The refined prototype (`rules2.php`) is implicit, plus:

```php
// 1. absent → leave absent, let required/present/sometimes decide
if (Arr::get($this->validator->getData(), $attribute, $missing) === $missing) {
    return;
}

// 2. null → allowed only when the attribute carries an explicit `nullable` rule
if ($value === null) {
    // attributeIsNullable() scans getRules()[$attribute] for `nullable`
    if ($this->attributeIsNullable($attribute)) {
        return;
    }
    $fail($this->message());
    return;
}

// 3. otherwise parse or fail
```

Measured result (`run2.php`, identical on Laravel 10-latest → 13-latest):

| rules | `[]` | `''` | `'   '` | `null` | `'42'` | `42` | `'abc'` | `['x']` |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `[Parse]` | absent | fail | fail | fail | `int(42)` | `int(42)` | fail | fail |
| `['required', Parse]` | fail | fail | fail | fail | `int(42)` | `int(42)` | fail | fail |
| `['sometimes', Parse]` | absent | fail | fail | fail | `int(42)` | `int(42)` | fail | fail |
| `['nullable', Parse]` | absent | fail | fail | **`null`** | `int(42)` | `int(42)` | fail | fail |
| `['sometimes','nullable',Parse]` | absent | fail | fail | **`null`** | `int(42)` | `int(42)` | fail | fail |
| `['present', Parse]` | fail | fail | fail | fail | `int(42)` | `int(42)` | fail | fail |
| `['filled', Parse]` | absent | fail | fail | fail | `int(42)` | `int(42)` | fail | fail |
| `['bail','required',Parse]` | fail | fail | fail | fail | `int(42)` | `int(42)` | fail | fail |

This is precisely the target invariant:

```text
absent optional field   → absent
present + parseable     → parsed T
present + unparsable    → validation failure
nullable + null         → null
non-nullable + null     → validation failure
```

Static consequences, all sound:

```php
['age' => ['sometimes', Parse::integer()]]              // array{age?: int}
['age' => ['required',  Parse::integer()]]              // array{age: int}
['age' => ['nullable',  Parse::integer()]]              // array{age?: int|null}
['age' => ['required', 'nullable', Parse::integer()]]   // array{age: int}
```

Note that `filled` and `bail|required` produce different *messages* for the
blank case but the same presence outcome, so shape inference is unaffected.

`required` combined with `nullable` produces `int`, not `int|null`:
`ValidatesAttributes::validateRequired()` rejects `null` outright, so that
combination never yields a null in the validated output. The analyzer's
existing `RuleTreeNode::allowsNull()` is already `nullable && !required` and
needs no change. An earlier revision of this report claimed `int|null` here;
that was wrong, and the measured matrix above never covered the combination.

### 5.4 Interaction notes

- `sometimes` composes correctly: `passesOptionalCheck()` (`:872`) suppresses
  even implicit rules for absent attributes, and the parser's own presence
  check makes the bare case behave the same way.
- `nullable` detection reads `$validator->getRules()[$attribute]` and looks for
  the literal string `nullable`. This works for wildcard-expanded attributes
  because `getRules()` is keyed by concrete attribute after expansion
  (verified: `users.*.age` with `['nullable', Parse]` and a `null` element
  passed through as `null` while its sibling parsed to `int(12)`).
- `present` retains its own semantics: it fails on absent, and the parser then
  fails on `''`. The combination is coherent.

---

## 6. Nested attributes and wildcards

### 6.1 What works

| Case | Result |
| --- | --- |
| `'profile.age' => ['required', Parse]` | `['profile' => ['age' => int(42)]]` |
| Missing nested parent, `'profile.age' => [Parse]` | passes, `validated() === []` |
| `'users.*.age'`, list input | `['users' => [0 => ['age' => 12], 1 => ['age' => 34]]]` |
| `'users.*.age'`, associative input | `['users' => ['a' => ['age' => 12], 'b' => ['age' => 34]]]` |
| `'groups.*.users.*.age'`, depth 2 | fully transformed |
| Wildcard with one absent element (`sometimes`) | only present elements transformed; absent stays absent |
| Wildcard with one `null` element (`nullable`) | `null` preserved, sibling parsed |
| Wildcard with one unparsable element | that element fails; siblings' data still flushed |
| Nested under `'payload' => ['required','array']` | `['payload' => ['age' => int(42)]]` |
| With `excludeUnvalidatedArrayKeys` | `['payload' => ['age' => int(42)]]`, junk keys dropped |

### 6.2 Attribute form and expansion

The parser receives **fully expanded, concrete** attribute names
(`users.0.age`, `groups.1.users.0.age`). Wildcard expansion happens during rule
preparation, before any rule runs. `setValue()` uses `Arr::set()` and handles
these paths correctly, including creating intermediate structure.

### 6.3 Shared mutable state — real, and manageable

Laravel does **not** clone rule objects across wildcard expansions. One
instance serves every expanded attribute, and `setValidator()` is re-invoked
per attribute. Therefore:

- **Parser state must be keyed by concrete attribute, and scoped per
  validator.** The prototype uses an `SplObjectStorage` keyed by validator
  holding `array<string, mixed> $pending`. Verified safe for: one instance
  across two wildcard elements, one instance across two named attributes
  (`['a' => [$shared], 'b' => [$shared]]` → `['a' => 1, 'b' => 2]`), and one
  instance across two independent validators (`v1` → `1`, `v2` → `2`).
- Storing `$attribute` or `$value` on `$this` between `validate()` and the
  flush is a correctness bug waiting to happen. The `after()` closure must
  capture the shared state object, never `$this`-scoped per-attribute fields.
- The flush must be registered **once per (rule instance, validator)**, tracked
  by `spl_object_id`, otherwise a rule appearing on ten wildcard elements
  registers ten identical callbacks.

### 6.4 Escaped dots — the one broken case

```text
input  ['a.b' => '42']
rules  ['a\.b' => ['required', Parse]]

getRules() keys  ['a__dot__eimDoN7A3UPVQddqb']
getData()  keys  ['a__dot__eimDoN7A3UPVQddqb']
rule receives    'a.b'                              ← decoded by validateUsingCustomRule():924
```

With a naive parser, `setValue('a.b', 42)` writes a *new nested* `['a' => ['b'
=> 42]]` branch, leaving the real value untouched:

```text
validated()  ['a.b' => string('42')]                ← unchanged, type unsound
getData()    ['a__dot__…' => string('42'), 'a' => ['b' => int(42)]]   ← corrupted
```

With the presence-guarded refined parser it degrades safely to a silent no-op
(`parse(a.b): ABSENT -> skip`), but `validated()` still returns
`string('42')` where the static type would claim `int`.

Recovery is possible but ugly. `Validator::$placeholderHash` is
`protected static` and `Str::random()` defaults to 16 characters, so the
encoded key can be recovered by scanning `getRules()`:

```php
preg_replace('/__dot__[A-Za-z0-9]{16}/', '.', $key) === $attribute
```

Verified working. It depends on an undocumented placeholder format and a
hard-coded hash length.

**Recommendation:** do not rely on the regex in v1. Instead:

1. Runtime: detect the mismatch (attribute contains `.` but is absent from
   `getData()` while a `__dot__`-bearing rules key decodes to it) and `$fail()`
   with an explicit "parsing rules do not support escaped-dot attributes"
   message. Loud beats silent.
2. Static: treat an escaped-dot path carrying a parsing rule as unresolved and
   fall back to the ordinary predicate type, so the analyzer never claims a
   type the runtime will not deliver.

This is a genuinely rare construct, and the project already has machinery for
escaped-dot paths on the analysis side.

### 6.5 `Rule::forEach`

`Rule::forEach()` compiles through `CompilableRules` on a separate code path in
`prepareRule()`. Parsers nested inside a `forEach` callback were not exercised
beyond confirming the surrounding mechanism still works; treat as out of scope
for v1 and unsupported until tested.

---

## 7. Cross-field and dependent rules

Under delayed transformation, **every cross-field rule sees the original
representation**, because all of them run before the flush. Verified:

| Setup | Delayed | Immediate |
| --- | --- | --- |
| `['start' => [Parse], 'end' => ['gte:start']]`, `"5"`/`"10"` | passes; `['start' => int(5), 'end' => string('10')]` | passes |
| `['a' => [Parse], 'b' => ['same:a']]`, `"42"`/`"42"` | passes | **fails** |
| `['flag' => [Parse], 'other' => ['required_if:flag,1']]` | unchanged | unchanged (`required_if` compares loosely) |

So the preferred model — *ordinary Laravel rules retain ordinary Laravel
behaviour; parsing changes only the representation of the final validated
data* — is technically achievable and is what delayed transformation gives for
free.

### 7.1 Is it intuitive?

Mostly yes, with two documented sharp edges.

**Sharp edge 1 — `gte:start` compares raw values.** With
`['start' => [Parse::integer()], 'end' => ['gte:start']]` and input
`start="9", end="10"`, `gte` uses string comparison (neither attribute has a
numeric rule), so `"10" >= "9"` is length-based and **fails**. Adding
`Parse::integer()` did not change this — it was already Laravel's behaviour —
but users will attribute the surprise to the parser. Mitigation is the same as
§4.3: pair parsers with `integer`/`numeric` when sizing or comparison matters.

**Sharp edge 2 — the parser cannot rescue a comparison it did not cause.** A
user who writes `['start' => [Parse::integer()], 'end' => ['gte:start']]` and
expects integer comparison is asking for immediate mutation. The answer is to
say no clearly in the docs and show the two-rule form.

Neither of these is a *new* Laravel surprise; both are existing Laravel
surprises that a parsing rule makes more visible. That is arguably a feature
for this project, whose documentation already argues exactly this point.

`exclude*`, `prohibited*`, `missing*`, `required_with*`, `confirmed`, and
`different` all behave unchanged, because all of them are evaluated before the
flush.

---

## 8. Failure and repeated-validation behaviour

Every case below was executed (`run.php` §9a–9j, `run3.php` P8–P9,
`formrequest.php` FR4–FR5) and behaves identically on Laravel 10-latest through
13.25.0.

| Scenario | Observed | Assessment |
| --- | --- | --- |
| Parse succeeds, another field fails | flush still runs; `getData()` holds `int(42)`; `validated()` throws `ValidationException` | Safe. Transformed data is unreachable through the supported API. |
| Parse fails immediately | no pending entry, no flush for that attribute; data stays raw | Correct. |
| One field parses, another field's parser fails | the succeeding field is still flushed; the failing one is not | Correct and desirable — partial state is unobservable via `validated()`. |
| Several parser rules on different attributes | one shared `ParseState`, one flush callback per rule instance | Correct. |
| Two parser rules on the *same* attribute | last write wins in `getData()`; the disagreeing rule fails validation | Ambiguous by construction — report statically (§15). |
| `passes()` called twice | second run re-parses the already-parsed value (`sees int(42)`), flush #2 writes the same result | Idempotent **only if** parsers accept their own output. `Parse::integer()` accepts `int`, `Parse::boolean()` accepts `bool`, `Parse::enum()` accepts the case object — all designed for this. A parser that rejects its own output would break on the second run. **This is a hard design constraint on every parser.** |
| `fails()` then `validated()` | `fails()` runs `passes()`; `validated()` sees `$this->messages` set and does not re-run | Correct. |
| `validated()` without a prior `passes()` | `validated()` triggers `passes()` itself (`:649`) | Correct. |
| `validated()` called twice | second call returns the same array, no re-validation | Correct. |
| `Validator::validate()` | `fails()` → `passes()` → flush → `validated()` | Correct; one `passes()` run total. |
| `FormRequest::validated()` twice | identical arrays; `safe(['age'])` matches | Correct. |
| An `after()` callback throws | exception propagates out of `passes()`; callbacks after the throwing one never run | If the user callback was registered first, the flush never runs and data stays raw. Acceptable, but means transformation is not guaranteed when a user callback throws. Document. |
| A user `after()` callback registered *before* validation | runs **before** the flush and observes raw values | **Soundness hole — see §8.1.** `FormRequest::withValidator()` and Precognition land here. |
| A user `after()` callback registered *after* the first `passes()` | runs after the flush on subsequent `passes()` calls, observing parsed values | Ordering follows registration order. Document. |
| A user `after()` callback adds an error after the flush ran | the flush already wrote; `validated()` then throws | The reason "flush only when valid" is undecidable (§4.4). |
| `stopOnFirstFailure()` | parse failure short-circuits the remaining attributes as usual | Unchanged. |
| `setRules()` after `passes()` | data retains the previously flushed value; re-validation uses the new rules against transformed data | Pre-existing Laravel sharp edge, not parser-specific, but worth a test. |
| `setData()` after `passes()` | resets `$this->data` and re-runs `setRules($this->initialRules)`; parsing restarts from the new raw data | Correct. |

Two lifecycle hazards deserve emphasis:

1. **Parser output must be a fixed point of the parser.** `passes()` is
   re-entrant and Laravel calls it more than once on ordinary paths
   (`validate()` → `fails()` → `passes()`). Every parser must satisfy
   `parse(parse(x)) === parse(x)`. Verified for all three proposed v1 parsers
   over three consecutive `passes()` calls (`fixedpoint.php`), on Laravel
   10-latest and 13-latest.
2. **Callback registration must be once per (rule instance, validator).** The
   `after` array persists across `passes()` calls; re-registering on every run
   would accumulate duplicate callbacks. Guard by `spl_object_id`, and clear
   `pending` inside the flush so a stale callback from a previous run is a
   no-op rather than a replay.

### 8.1 `validated()` inside an `after()` callback — a real soundness hole

This deserves more than the passing mention it got in the first draft. It is
the one place where the delayed model produces an outright wrong static type,
and it is **structurally unfixable** within the chosen architecture.

Measured (`after-hole.php`, H1), Laravel 13.25.0, callback registered before
`passes()`, rules `['age' => [Parse::integer()]]`, input `['age' => '42']`:

```text
inside the callback:
  getData()      = ['age' => string('42')]
  validated()    = ['age' => string('42')]     ← PHPStan would say array{age?: int}
  safe()->all()  = ['age' => string('42')]
  valid()        = ['age' => string('42')]
  getValue('age')= string('42')
then:
  flush(age) = int(42)
after passes():
  validated()    = ['age' => int(42)]
```

So this type-checks and is wrong:

```php
$validator = Validator::make(['age' => '42'], ['age' => [Parse::integer()]]);

$validator->after(function (Validator $validator) {
    $data = $validator->validated();
    \PHPStan\dumpType($data['age']);   // extension says int; runtime holds "42"
});
```

Identical on Laravel 10-latest, 11-latest, 12-latest, 13-latest.

#### Why the ordering cannot be repaired

`after()` executes callbacks in registration order. The parser's earliest
possible registration point is during rule execution, which is strictly after
anything the user registered before calling `passes()`. Three escape routes
were tested and all fail:

| Attempted fix | Result |
| --- | --- |
| Register the flush at `setValidator()` time rather than after a successful parse (`after-hole.php` H8) | No change — `setValidator()` still happens inside `passes()`, after pre-registered callbacks. |
| Let the user register a callback that runs after the flush, from inside another callback (H6) | The appended callback **never runs**. `foreach ($this->after as $after)` iterates a copy of the array. |
| Append a synthetic terminal rule so the flush runs before any callback | Impossible — `foreach ($this->rules as ...)` also iterates a copy, so a rule cannot extend the rule loop. |

The only mechanism that would close it is replacing the `Validator` class via
`Factory::resolver()`. That is a documented Laravel extension point, unlike
blind subclassing, but it is a single global slot: last registration wins, and
it collides with every other package that uses it (including
`qtlenh/laravel-strict-validator`). Not worth it for this.

#### Related lifecycle facts, all measured

- **No re-entrancy.** `validated()` inside `after()` does not re-run
  `passes()`, because `$this->messages` is already set at the top of `passes()`
  (`:469`). The callback fires exactly once (H3). No infinite recursion.
- **It can throw from inside the callback.** If errors already exist when a
  callback calls `validated()`, the `ValidationException` propagates out of
  `passes()` (H4). Pre-existing Laravel behaviour, not parser-specific, but it
  compounds the confusion.
- **`after([$object])` array form** behaves identically (H7).

#### `FormRequest`: the workaround is clean and natural

Measured (`after-hole-fr.php`), identical on all four majors:

```text
prepareForValidation():  all()        = ['age' => string('42')]
withValidator() after(): getData()    = ['age' => string('42')]   ← raw
withValidator() after(): validated()  = ['age' => string('42')]   ← raw, UNSOUND
flush(age) = int(42)
passedValidation():      validated()  = ['age' => int(42)]        ← parsed
passedValidation():      safe()       = ['age' => int(42)]        ← parsed
passedValidation():      all()        = ['age' => string('42')]   ← raw, correct
```

`passedValidation()` runs after `fails()` returns, therefore after the flush.
It is the correct hook for anything that reads parsed data, and it is the hook
most people reach for anyway. `withValidator()` remains the correct hook for
*adding validation*, which is what it is for — it just must not read
`validated()`.

#### Recommended handling

1. **Declare `after()` callbacks an unsupported context for reading parsed
   data.** Document that `validated()`, `safe()`, `valid()`, `getData()`, and
   `getValue()` observe pre-parse values there.
2. **Ship a PHPStan rule** that reports a call to `validated()`/`safe()`/
   `valid()`/`getData()`/`getValue()` on a validator inside a closure passed to
   that validator's `after()`, when the validator's rule set contains a parsing
   rule.

   **Scope of the promise — state it plainly in the docs.** The rule
   **detects the common statically tractable forms; it does not close the
   hole.** The underlying Laravel lifecycle limitation remains, and `after()`
   accepts callables the analyzer cannot cheaply follow. Measured: every form
   below is accepted by `after()` and every one exhibits the hole, on Laravel
   10-latest and 13-latest (`after-forms.php`):

   | Form passed to `after()` | Statically tractable? |
   | --- | --- |
   | inline `function (Validator $v) { … }` | **Yes** — detect |
   | inline `fn (Validator $v) => …` | **Yes** — detect |
   | closure assigned to a local variable in the same scope | Usually — detect if cheap |
   | invokable object, `new CheckSomething()` | No — crosses into `__invoke` |
   | `[$object, 'method']` | No — crosses into another method |
   | `'App\Foo::bar'` string callable | No |
   | array-of-objects form, `after([new CheckSomething()])` | No — Laravel calls `->after()` or invokes each element |

   Do **not** hold v1 hostage to arbitrary callable dataflow. Chasing
   `__invoke` bodies would mean whole-program analysis of every callable
   reaching `after()`, for a diagnostic whose purpose is catching the
   ordinary mistake. Catch the inline forms, document the rest as pre-parse
   contexts regardless of whether the diagnostic follows them, and be honest
   in the rule's own error message and in the docs that this is detection,
   not enforcement.

   The tractable half is a straightforward top-down PHPStan rule:

   ```text
   register on Node\Expr\MethodCall
   match ->after(...) where the caller type is the extension's ValidatorType
   if that ValidatorType's rule shape contains no parsing rule: return []
   arg 0 is a Closure or ArrowFunction:
       walk its body for MethodCall nodes named
         validated | safe | valid | getData | getValue
       whose caller is the closure's first parameter, or a use-captured
       variable identical to the validator expression
   report: "Reading parsed validation data inside after() observes values
            from before Parse::* write-back; use passedValidation() or read
            after validation completes."
   ```

   A bottom-up alternative — having `ValidatorValidatedExtension` decline to
   refine whenever the call sits inside an anonymous function — was considered
   and rejected: it would silently degrade every legitimate use of
   `validated()` inside any closure, which is far more common than this bug.
3. **Do not abandon the delayed model over this.** The alternative (immediate
   mutation) removes this hole but reintroduces the `same:` regression and
   rule-order sensitivity across attributes, which is strictly worse and is not
   detectable by any static rule.

Net position: delayed transformation has one unsound context, that context is
named and documented, the common forms of it are reported, and the remaining
forms are a known limitation rather than an unknown one. That is a materially
better place to be than immediate mutation, whose failure modes are silent,
unbounded, and undetectable.

---

## 9. Excluded fields

`removeAttribute()` (`:584`) removes the attribute from **both** `$this->data`
and `$this->rules`. `passes()` performs a second exclusion sweep after the rule
loop and before the `after()` callbacks (added in Laravel 11; Laravel 10
removes inside the inner loop instead — behaviourally equivalent for this
purpose, verified).

Measured, with `['age' => [Parse, 'exclude_if:mode,skip'], 'mode' => ['required']]`,
input `['age' => '42', 'mode' => 'skip']`:

| Parser | `validated()` | `getData()` |
| --- | --- | --- |
| Unguarded delayed | `['mode' => 'skip']` | **`['age' => int(42), 'mode' => 'skip']`** ← reintroduced |
| Guarded delayed | `['mode' => 'skip']` | `['mode' => 'skip']` |

`validated()` is safe either way because it iterates `getRules()` and the rule
was removed. But `getData()`, `valid()`, and `attributes()` are not, and the
resurrected key is a genuine surprise for anyone inspecting the validator.

Same result for bare `exclude`: `validated()` is `[]` either way; only
`getData()` differs.

**The flush must be guarded on two conditions:**

```php
// the rule was removed by exclude_*
if (! array_key_exists($attribute, $validator->getRules())) {
    continue;
}

// the data was removed by exclude_*
if (Arr::get($validator->getData(), $attribute, $missing) === $missing) {
    continue;
}
```

Static side: an attribute carrying both a parsing rule and any `exclude*` rule
must keep the analyzer's existing exclusion modelling. The parsing rule
contributes a *type* for the key, never a claim that the key exists. Exclusion
still wins.

---

## 10. Parser semantics

Two candidate policies were compared against measured behaviour
(`semantics.php`).

### 9.1 What Laravel's predicates actually accept

| input | `integer` | `numeric` | `boolean` | `decimal:0` |
| --- | --- | --- | --- | --- |
| `42` | ✅ | ✅ | ❌ | ✅ |
| `'42'` | ✅ | ✅ | ❌ | ✅ |
| `'+42'` | ✅ | ✅ | ❌ | ✅ |
| `'-42'` | ✅ | ✅ | ❌ | ✅ |
| `'042'` | ❌ | ✅ | ❌ | ✅ |
| `'00'` | ❌ | ✅ | ❌ | ✅ |
| `'42.0'` | ❌ | ✅ | ❌ | ❌ |
| `42.0` (float) | ✅ | ✅ | ❌ | ✅ |
| `' 42'` / `'42 '` | ✅ | ✅ | ❌ | ❌ |
| `'1e3'` | ❌ | ✅ | ❌ | ❌ |
| `'9223372036854775808'` | ❌ | ✅ | ❌ | ✅ |
| `true` | ✅ | ❌ | ✅ | ❌ |
| `false` | ❌ | ❌ | ✅ | ❌ |
| `''` | ✅\* | ✅\* | ✅\* | ✅\* |
| `INF` / `NAN` | ❌ | ✅ | ❌ | ❌ |
| `'1e999'` | ❌ | ✅ | ❌ | ❌ |

\* `''` "passes" only because the non-implicit rule never runs.

`integer` is `filter_var($value, FILTER_VALIDATE_INT) !== false`;
`numeric` is `is_numeric()`; `boolean` is
`in_array($value, [true, false, 0, 1, '0', '1'], true)`. The `:strict`
modifiers (`integer:strict` → `is_int()`, `numeric:strict` rejecting strings,
`boolean:strict` → `[true, false]`) exist on newer versions and are already
modelled by this project.

Notice how bad option (1) — "accept exactly what Laravel's rule accepts" — is
for `integer`: it would make `Parse::integer()` accept `' 42'`, `'42 '`, and
**`true`**, which parses to `1`. Whitespace tolerance and boolean-to-int
promotion are not parser semantics anyone wants to defend.

### 9.2 Recommended narrow semantics

Option (2): **define intentionally narrower lexical semantics**, and make
rejection the default for anything ambiguous.

**`Parse::integer(): int`**

Accept:
- `int` values as-is;
- strings matching `/^-?(0|[1-9][0-9]*)$/` that survive
  `filter_var(..., FILTER_VALIDATE_INT)`.

Reject: `'+42'`, `'042'`, `'00'`, `' 42'`, `'42 '`, `'42.0'`, `'1e3'`,
`'0x1A'`, `'4_2'`, floats (including integral `42.0`), `true`/`false`, `''`,
arrays, values exceeding `PHP_INT_MAX`.

Rationale for the non-obvious calls:

- **Leading `+` and leading zeroes rejected.** They are representation noise,
  not integers, and `'042'` is already rejected by Laravel's own `integer`.
  Accepting `'+42'` while rejecting `'042'` (Laravel's actual combination)
  is arbitrary.
- **Whitespace rejected.** `filter_var` trims; a parser should not.
- **Floats rejected**, including `42.0`. Accepting integral floats invites
  `42.9 → 42` questions and precision arguments. `Parse::integer()` on a JSON
  float is a schema mismatch the user should see.
- **Overflow rejected, not truncated.** `'9223372036854775808'` must fail, not
  become `PHP_INT_MAX`. Platform integer width is therefore observable: a
  32-bit build rejects values a 64-bit build accepts. Document it; do not
  paper over it.
- `-0` parses to `int(0)`. Harmless.

**`Parse::float(): float`**

Accept `int` (widened), finite `float`, and strings matching
`/^-?(0|[1-9][0-9]*)(\.[0-9]+)?$/` whose `(float)` result is finite.

Reject scientific notation (`'1e3'`), `'1e999'` (would become `INF`), `INF`,
`NAN`, `'INF'`, `'NAN'`, `'1,5'` (locale-dependent), leading zeroes, and
whitespace. Precision loss for large integer-valued strings
(`'9223372036854775807'` → `9.2233720368548E+18`) is inherent to `float` and
acceptable; overflow to `INF` is not.

Explicitly: locale never participates. The grammar is fixed ASCII; PHP's
`(float)` cast is locale-independent since PHP 8.0, and the regex gate makes
that moot anyway.

**`Parse::boolean(): bool`**

Accept exactly Laravel's `boolean` acceptance set, mapped:

```text
true, 1, '1'   → true
false, 0, '0'  → false
everything else → failure
```

Reject `'true'`, `'false'`, `'on'`, `'off'`, `'yes'`, `'no'`, `''`. This is the
one case where matching Laravel's predicate exactly is right: the set is
already narrow, already documented, and already what users expect from
`boolean`.

Absolutely not `(bool)`, which yields `(bool) 'false' === true`, and not
`filter_var(..., FILTER_VALIDATE_BOOLEAN)` without `FILTER_NULL_ON_FAILURE`,
which silently maps every unrecognised string to `false` — the exact bug in
`WendellAdriel/laravel-validated-dto`'s `BooleanCast` (§12).

If `'true'`/`'yes'`/`'on'` support is wanted later, it belongs behind an
explicit opt-in (`Parse::boolean()->lenient()`), never in the default.

**`Parse::string(): string`** — *recommended out of v1.*

`Parse::string()` is nearly a no-op: Laravel's `string` rule is `is_string()`,
so `['string']` already gives a sound `string`. The only thing a parser adds
is coercion from `int`/`float`/`Stringable`, which is exactly the surprising
conversion this design is trying to avoid. Ship `Parse::string()` only if a
concrete need appears.

### 9.3 Correctness hazards checklist

| Hazard | Handling |
| --- | --- |
| `(int) '1foo' === 1` | Regex gate before any cast. |
| `(bool) 'false' === true` | Fixed acceptance set; no `(bool)`. |
| `(float) '1e999' === INF` | `is_finite()` check after cast. |
| Integer overflow | `filter_var(FILTER_VALIDATE_INT)` must return `int`. |
| Platform integer width | Documented; 32-bit rejects more. |
| Scientific notation | Rejected by grammar. |
| Leading/trailing whitespace | Rejected by grammar. |
| Leading `+`, leading zeroes | Rejected by grammar. |
| `INF` / `NAN` inputs | Rejected. Note `(string) NAN` emits a PHP warning in 8.5, so never stringify before checking. |
| Locale-dependent syntax | Not reachable; fixed ASCII grammar. |
| Arrays / objects | Rejected (no stringification attempted). |

**Invariant to enforce in code and tests: the runtime parser accepts a value
if and only if the PHPStan model says the parsed type is produced.** The
cleanest way to guarantee this is a single shared table (§21).

---

## 11. Enum parsing

`Parse::enum(Status::class): Status`

Measured behaviour of the prototype versus Laravel's `Rule::enum`:

| enum | input | `Parse::enum` | `Rule::enum` |
| --- | --- | --- | --- |
| string-backed | `'draft'` | `Status::Draft` | passes, returns `string('draft')` |
| string-backed | `'nope'` | fail | fail |
| string-backed | `Status::Live` (case object) | `Status::Live` | passes, returns the case |
| string-backed | `1` | fail | fail |
| int-backed | `1` | `Level::Low` | passes, returns `int(1)` |
| int-backed | `'1'` (string) | **fail** | **passes**, returns `string('1')` |
| int-backed | `9` | fail | fail |
| int-backed | `Level::High` | `Level::High` | passes, returns the case |

Laravel's `Rules\Enum::passes()` calls `$this->type::tryFrom($value)` inside a
`try { } catch (TypeError)`, so an int-backed enum accepts the string `'1'`
through PHP's coercive `tryFrom` — and then leaves `string('1')` in the output.
That is a perfect miniature of this project's whole thesis.

**Recommended semantics:**

- Already an instance of the target enum → pass through unchanged.
- Backed enums: require the value's PHP type to match the backing type
  (`int` backing requires `is_int`, `string` backing requires `is_string`),
  then `tryFrom()`; `null` result → failure. No coercion.
- Pure enums: **not supported in v1.** There is no canonical wire
  representation; matching on `->name` is a convention, not a contract, and
  Laravel's own rule refuses them (`method_exists($this->type, 'tryFrom')`).
  Failing loudly on a pure enum passed to `Parse::enum()` is better than
  inventing name-matching.
- `only()` / `except()` filtering: out of scope for v1. If added, model it
  statically as a union of the surviving cases.

**Is enum worth v1?** Yes, and arguably it is the *most* valuable member of the
set. It is the case where Laravel's predicate/parse gap costs the most (users
overwhelmingly want the case object, and `Rule::enum` gives them a raw scalar),
and it is the case where the inferred type improves most dramatically —
`Status` instead of `string`. It also exercises the generic type-carrying
mechanism (§14) in a way the primitives do not, which is worth proving before
the API ossifies.

PHPStan target: `Status` when required, `Status|null` when nullable,
`array{status?: Status}` when optional.

---

## 12. Prior art

### `joelharkes/laravel-strict-validation` — closest relative

Author of the `setValue()` PR. `BaseRule implements ValidationRule,
DataAwareRule, ValidatorAwareRule`; `modifyValue()` calls `setValue()` with a
fallback to `Arr::set()` + `setData()` on older Laravel.

- Maintenance: effectively dead. 6 total downloads, 0 stars, one `0.0.1`
  release (2023-04-07), last commit 2025-02-17. Requires `illuminate/validation: ^10.1`.
- Timing: **immediate mutation**, inside `validate()`.
- Invalid conversions: `$fail()`, correct.
- `null`: `if (is_null($value)) return;` — **silently accepted**, in every rule.
  So `ValidInteger` alone permits `null` in the output while promising `int`.
- Blank strings: rules are not implicit, so `''` never reaches them and survives
  into `validated()`. Same unsoundness measured in §5.1.
- Missing values: skipped, correct by accident.
- Nested/wildcards: works, because immediate mutation carries no state.
- Enums: `ValidEnum` uses `tryFrom` inside `try/catch (TypeError)` — inherits
  Laravel's coercive `'1'` → `Level::Low` behaviour.
- Input mutation: validator data only; the request is untouched. Correct.
- `validated()` is transformed. Correct.
- Rule ordering: **changes semantics**, exactly as measured in §4.1.
- Static analysis: none.
- The old-Laravel fallback `setData($this->data)` is actively dangerous —
  `setData()` calls `setRules($this->initialRules)`, re-parsing and
  re-expanding every rule **mid-validation**.
- `__toString()` returning `'integer'` is dead code: `prepareRule()` wraps
  `ValidationRule` objects in `InvokableValidationRule` before anything reads
  `__toString`.

*Borrow:* the `setValue()` mechanism and the `ValidatorAwareRule` shape.
*Avoid:* immediate mutation, null pass-through, non-implicit rules, the
`setData()` fallback.

### `qtlenh/laravel-strict-validator`

Adds `integer:cast`, `numeric:cast`, `decimal:cast`, `boolean:cast` (and
`:strict`) by **subclassing `Illuminate\Validation\Validator`**.

- Maintenance: 6,844 downloads, 3 stars, single `1.0.0` release (2024-03-19).
  Its `composer.json` declares **no `require` section at all** — no PHP or
  Laravel constraint whatsoever.
- Mechanism: overrides `validateInteger`/`validateNumeric`/`validateDecimal`/
  `validateBoolean`, and on `cast` does
  `$this->container->request->replace(data_set($this->data, $attribute, ...))`.
- **It mutates the actual `Request` object**, so `$request->input()` changes
  too. Input is not preserved.
- Timing: immediate, inside the predicate.
- Casts with `$value + 0` (numeric) and `(bool) $value` (boolean). The boolean
  cast is safe only because Laravel's `boolean` predicate already restricted
  the value set.
- `null`/blank/missing: inherits Laravel's skipping, with the same unsoundness.
- Nested/wildcards/enums: not addressed.
- Static analysis: none.
- Laravel 12/13 shipped native `integer:strict`/`numeric:strict`/
  `boolean:strict`, obsoleting half the package.

*Avoid:* subclassing `Validator` (breaks composition with every other package
that does the same — the exact concern raised in PR #46716), and mutating the
request.

*Note for §13:* this is the real-world implementation of the `'integer:cast'`
string-parameter API. Its existence is evidence the syntax is thinkable; its
mechanism is evidence it is hard to do safely.

### `vollborn/laravel-request-cast`

`prepareForValidation()` trait that casts before validation.

- Maintenance: dead. **4 total downloads**, 2 stars, `v1.0.0` (2022-08-21),
  `laravel/framework: ^9.0` only.
- Timing: **before validation**. Every rule then sees the cast value, so
  `integer` after an `(int)` cast always passes. This destroys validation.
- Uses `(int)`, `(bool)`, `(string)`, `(array)` — full naive coercion.
  `(bool) 'false' === true`.
- The implementation is also simply broken: `castToInt($value)` is passed the
  *cast-type string* rather than the attribute value, and results are assigned
  to dynamic properties on the `Request` rather than the input bag.
- Nested/wildcards/enums/null: unhandled.

*Avoid:* everything. Kept in this report as a worked example of why
"transform before validation" is not a viable design.

### `WendellAdriel/laravel-validated-dto`

- Maintenance: healthy. 699,615 downloads, 772 stars, `v4.7.0` (2026-05-26),
  supports Laravel 11–13.
- Architecture: a DTO layer. Validation runs first; `passedValidation()` then
  applies `casts()` to the validated array. Casting is **decoupled from rules**,
  so ordering hazards do not exist.
- `IntegerCast`: `if (! is_numeric($value) && $value !== '') throw; return (int) $value;`
  — accepts `''` → `0`, `'1.9'` → `1`, `'1e3'` → `1000`. Silent truncation.
- `BooleanCast`: numeric → `$value > 0`; string → `filter_var(FILTER_VALIDATE_BOOLEAN)`
  **without** `FILTER_NULL_ON_FAILURE`, so `'maybe'` → `false`. Silent.
- `EnumCast`: reasonable — distinguishes backed from pure enums, throws
  `CastException` on mismatch. Uses non-strict `in_array`, so `'1'` matches
  int-backed case `1`.
- Failures raise `CastException`, not validation errors, so a bad cast is a
  500 rather than a 422.
- Static analysis: none for cast output types.

*Borrow:* the decoupling of cast from rule, and per-cast classes.
*Avoid:* permissive numeric coercion, silent boolean fallback, and exceptions
in place of validation failures.

### `spatie/laravel-data`

- Maintenance: very healthy. 38.9M downloads, 1,774 stars, `4.23.0`
  (2026-05-08), Laravel 10–13.
- Architecture: a full data-object framework — property types drive both
  casting and validation-rule inference, with its own pipeline, magic
  creation methods, transformers, lazy properties, and partial serialisation.
- Types come from PHP property declarations, so static analysis is "free" in
  the sense that the DTO class *is* the type. No PHPStan extension needed and
  none provided for validation arrays.
- Requires `phpdocumentor/*` and `spatie/php-structure-discoverer`.

*Relevance:* it is the thing this feature must **not** become. It is also the
honest answer for users who want a full typed boundary: `laravel-data` already
occupies that niche, well. The proposed feature occupies a different one —
staying inside Laravel's rule-array idiom and returning arrays.

### Upstream Laravel

- [#46162](https://github.com/laravel/framework/pull/46162) proposed
  `TransformsResultRule` with a `transform()` hook. **Closed.** Discussion
  raised: rule-order sensitivity, the string-vs-numeric sizing flip, and
  breaking-change risk for date/enum rules.
- [#46716](https://github.com/laravel/framework/pull/46716) added `setValue()`.
  **Merged**, `v10.7.0`.

Reading these together: Laravel will not own transformation, but sanctioned the
primitive. That is precisely the space this feature would occupy — and it means
nobody has yet built a *correct* implementation of it. The two attempts that
exist are dead, and both get presence and null semantics wrong.

---

## 13. API design

**Recommendation: `Parse::*` static factory returning rule objects.** The
brief's instinct is right, for reasons stronger than aesthetics.

### Rejected: `'integer:cast'` string parameters

- Requires either subclassing `Validator` (`qtlenh`'s approach — composition
  hazard, and the reason PR #46716 exists at all) or `Validator::extend()`,
  which cannot express an output type.
- A `:cast` modifier attaches transformation to a *predicate*, entrenching
  exactly the conflation this project exists to criticise.
- String rules cannot carry a class-string (`Parse::enum(Status::class)` has no
  string form that PHPStan can resolve as generically as an object).
- The analyzer would need per-modifier special-casing forever.

### Rejected: `Cast::*`

"Cast" is PHP's word for lossy, unconditional, silent conversion — `(int)
'1foo'`, `(bool) 'false'`. It is also Eloquent's word for attribute casting,
which *is* unconditional. The whole design premise is that this operation can
**fail**. `Cast::integer()` promises the opposite of what the rule does.

### Rejected: bare rule classes only

`new IntegerRule()` at call sites is noisier and gives no namespace-level
signal that a distinct semantic category is in play. Rule classes should exist
(and be public, for custom parsers), but `Parse::` should be the ergonomic
front door — the same relationship Laravel has between `Rule::` and
`Illuminate\Validation\Rules\*`.

### Why `Parse::`

```php
'age' => ['required', 'integer'],           // predicate: constrains, returns "42"
'age' => ['required', Parse::integer()],    // parser: returns 42, or fails
```

The name states the semantic category, matches the established
"parse, don't validate" vocabulary the project's own guide already cites, and
composes readably with predicates:

```php
'age' => ['required', 'integer', Parse::integer(), 'min:18'],
```

### Metadata separation

`#[ValidationRuleType]` and `@laravel-validation-type` currently mean
**"the type of the original value that survives this predicate."** Parsing
rules mean **"the type this rule produces, replacing whatever came in."**
These must not share a channel. §14 proposes `ParsingRule<T>` as the carrier,
with the existing attribute and PHPDoc tag left untouched.

A user's custom rule could legitimately have both: a predicate contract for its
acceptance and, if it also parses, a produced type. Keeping the channels
separate makes that expressible.

---

## 14. Generic parser model

**Recommendation: `ParsingRule<T>` is workable and should be the mechanism.**

### Runtime shape

```php

namespace jbboehr\LaravelValidationParsing;

/**
 * @template-covariant T
 */
interface ParsingRule extends \Illuminate\Contracts\Validation\ValidationRule
{
    /**
     * @return T
     * @throws ParseFailure
     */
    public function parse(mixed $value): mixed;
}
```

with a shared abstract base owning the implicit flag, presence check, null
policy, per-validator state, and delayed flush — so implementers write only
`parse()`:

```php
/** @implements ParsingRule<int> */
final class IntegerRule extends BaseParsingRule implements ParsingRule
{
}

/**
 * @template T of \BackedEnum
 * @implements ParsingRule<T>
 */
final class EnumRule extends BaseParsingRule implements ParsingRule
{
    /** @param class-string<T> $enum */
    public function __construct(private string $enum)
    {
    }
}
```

`Parse::` then carries the generic through:

```php
final class Parse
{
    /** @return ParsingRule<int> */
    public static function integer(): ParsingRule
    {
        return new IntegerRule();
    }

    /**
     * @template T of \BackedEnum
     * @param class-string<T> $enum
     * @return ParsingRule<T>
     */
    public static function enum(string $enum): ParsingRule
    {
        return new EnumRule($enum);
    }
}
```

### Can PHPStan discover `T` generically?

Yes. The analyzer already resolves the call-site expression to a `Type`. For an
object type implementing `ParsingRule`, PHPStan's
`ClassReflection::getActiveTemplateTypeMap()` / `getAncestorWithClassName()`
machinery yields the `T` binding without the extension knowing the concrete
class. The `Parse::enum()` return type is `ParsingRule<Status>` by ordinary
generic inference from the `class-string<T>` argument — no per-class branch.

That gives:

```text
Parse::integer()          → ParsingRule<int>      → int
Parse::enum(Status::class) → ParsingRule<Status>  → Status
new MyOwnMoneyRule()      → ParsingRule<Money>    → Money
```

Third-party parsers work with zero extension changes. This is strictly better
than `if IntegerRule => int; if EnumRule => ...`, and it is worth the small
extra effort in `CustomRuleTypeResolver`.

Fallbacks, in order:

1. `ParsingRule<T>` generic binding (preferred, covers everything);
2. a `#[ParsedType('int')]` attribute / `@laravel-validation-parsed-type` tag
   for parsers whose output type is not expressible as a template argument;
3. neon config `phpstanLaravelValidation.parsingRules.classes`, mirroring the
   existing `customRules.classes`, for third-party rules the user cannot
   annotate;
4. unknown parser → fall back to the predicate path (`mixed`, or the declared
   accepted type). Never guess.

`Parse::using(callable)` is explicitly **not** recommended for v1: a closure's
return type is inferable in the easy cases and hopeless in the rest, and it
invites arbitrary transformation logic in rule arrays — the first step toward
becoming a mapper.

---

## 15. PHPStan design

### The core distinction

```text
predicate rule:  resulting type = input type ∩ accepted type
parsing rule:    resulting type = parser output type          (replaces, not intersects)
```

`TypeResolver::evaluateLeaf()` currently does exactly the first:

```php
$type = Type\TypeCombinator::intersect(...$types);
```

and `Rule` carries a field literally named `$acceptedType`, surfaced as
`getAcceptedType()`. Stretching that field to mean "produced type" would be a
category error and would silently corrupt every intersection it participates
in. **Introduce a separate abstraction.**

### Proposed changes

**`Rule` (`src/Validation/Rule.php`)**

- New constant `RULE_PARSE = '__Parse'`.
- New constructor `Rule::parsing(Type $producedType): self`.
- New accessor `getProducedType(): ?Type`, distinct from `getAcceptedType()`.
- Include the produced type in `getCacheKey()`.

**`RuleTreeNode`**

- `hasParsingRule(): bool` and `getProducedType(): ?Type`.
- `allowsBlankStringBypass()` must return `false` when a parsing rule is
  present. This is the static counterpart of the rule being implicit, and it
  is the single most important soundness change. The existing early-return
  list (`accepted`, `declined`, `filled`, `missing`, `required`) is the right
  place.

**`TypeResolver::evaluateLeaf()`**

```text
if node has a parsing rule and no children:
    type := produced type                     # replaces, does not intersect
    skip blank-string union
    skip refinePositiveMinimum                # min/max constrained the raw value
else:
    unchanged
```

Two or more parsing rules on one attribute: the last one wins at runtime (the
flush map is keyed by attribute, later writes overwrite), but any combination
where they disagree is a user error. Prototype P9 showed
`[Parse::integer(), Parse::boolean()]` on `"42"` fails validation while
`getData()` still holds `int(42)`. Recommended static treatment: **report an
error** ("multiple parsing rules on `age`") rather than silently picking one.

**`TypeResolver::hasExecutableRule()`** — add the parsing rule name. This
report's first revision omitted it, and the omission is a soundness bug on the
feature's happy path. `hasExecutableRule()` gates
`refineSuccessfulDirectInput()`, which `FacadeValidateExtension` and
`FactoryMakeExtension` use to narrow the **caller's own array** after a
successful validation:

```php
$data = ['age' => '42'];
if (Validator::make($data, ['age' => [Parse::integer()]])->passes()) {
    $data['age'];   // narrowed to string ∩ int = never, but still holds '42'
}
```

The parsed value lands in `Validator::$data`, a by-value copy. The caller's
array keeps the original representation, so the produced type says nothing
about it.

**Type discovery** — put it in a new resolver rather than extending
`CustomRuleTypeResolver`. That class's vocabulary is entirely *accepted* type:
its configuration keys, its attribute, its PHPDoc tag, and a class-keyed cache
holding accepted types. A produced type is a different claim and should not
share them. Whichever holds it, the parsing path must be consulted **first**,
because `ParsingRule` extends `ValidationRule` and would otherwise be read as
an unremarkable predicate.

Discovery must decline, not guess, whenever the binding is unavailable. Note
that a bare `ObjectType(ParsingRule)` resolves `T` to the template's default —
an explicit `mixed` — rather than to a `TemplateType`, so a `TemplateType`
guard alone does not catch it. Declining is the conservative direction, since
recognizing a parsing rule also suppresses the blank-string union, and that is
sound only for a rule known to be implicit.

**`RuleParser::parseRule()`** — leave it alone. The runtime cross-check needs
live rule objects turned into rule descriptions, but `RuleParser` is a static
utility with no reflection provider, so it would need either a threaded
service or a class-to-type table — the per-class branching the generic design
exists to avoid. Substitute in test support instead, routing through the
production resolver so the cross-check still proves the produced type is
discoverable.

**New PHPStan rules** (two, both small)

1. `ParsedDataInAfterCallbackRule` — reports reads of parsed data inside an
   `after()` closure. Sketch and scope in §8.1. Two points matter: it must be
   written **top-down** from the `after()` call site rather than bottom-up
   from `validated()`, and it **detects the statically tractable forms rather
   than closing the hole** — invokable objects, `[$obj, 'method']`, string
   callables, and the array-of-objects form are equally unsound and are not
   followed. Word the error message and the docs accordingly.
2. `ParsingRuleLaravelVersionRule` — reports use of a `ParsingRule` when
   `LaravelVersionContext::hasFrameworkVersion()` is true and
   `isAtLeast('10.7.0')` is false. This is the static half of the version
   floor that Composer cannot express (§17, §20). Both the context object and
   its `isAtLeast()` method already exist.

**Unchanged, deliberately**

- Nested/wildcard path handling: parsing rules sit at leaves; the existing
  path machinery already produces the right concrete paths, and the runtime
  writes to those same paths (verified §6).
- `sometimes` / `required` / optional-key inference: presence is orthogonal.
  A parsing rule contributes a type, never a presence claim.
- `exclude*`: exclusion wins over everything, including a parsing rule.
  Unchanged.
- Unions from conditional rule branches: a parsing rule inside one branch
  contributes its produced type to that branch only.
- Rule ordering: irrelevant under delayed transformation, which is a
  significant simplification for the analyzer. Under immediate mutation it
  would have been necessary to model order — another argument for delayed.

### Backward compatibility

`'age' => ['integer']` cannot reach the parsing path: the branch is gated on
the presence of a `RULE_PARSE` entry, which is produced only from a
`ParsingRule` instance or explicit configuration. No string rule name maps to
it, and `resolveType()` for existing rules is untouched. The escape hatch to
verify in review is that neon-configured parsing-rule classes are a **separate
key** from `customRules.classes`, so an existing configuration cannot acquire
parsing behaviour by accident.

---

## 16. Package architecture

### A. Same repository, same Composer package — **recommended**

```text
jbboehr/phpstan-laravel-validation
```

- One version number, one release, one compatibility matrix, one tracker.
- Runtime behaviour and inferred type change atomically. For a feature whose
  entire value proposition is that those two agree, this is not a convenience,
  it is the product.
- `AssertsLaravelValidation` — which already runs Laravel and asserts
  `inferred ⊇ actual` from the same rule array — extends to parsers with
  almost no new machinery. That harness cannot span two packages without a
  circular dev dependency.
- Cost: an additional `require` on `illuminate/validation` for users of the
  parsing API (§17 addresses the PHPStan side).

### B. Same repository, multiple Composer packages

Requires: a monorepo splitter (`symplify/monorepo-builder` or GitHub Actions
subtree splits), per-package `composer.json`, read-only mirror repositories on
Packagist, path repositories for local development, and CI that tests both the
split and unsplit layouts. The payoff is that runtime users skip `phpstan`.

That payoff is entirely obtainable in layout A by moving `phpstan/phpstan` to
`require-dev` (§17). B therefore buys nothing that A cannot have, and costs
permanent release tooling. **Rejected.**

### C. Separate repositories

Two version numbers for one contract. The failure mode is concrete:

```text
parser v0.4  rejects "42.0"
analyzer v0.3 models "42.0" as accepted
```

Composer cannot express "these must agree on parser semantics" except through
hand-maintained pinned ranges, which drift. Every semantic refinement to a
parser becomes a coordinated two-repo release. The runtime component is
expected to be a few hundred lines. **Rejected.**

### Recommendation

**A**, decisively — and note that §17 removes the only advantage C had.

---

## 17. Composer dependency analysis

### Current state

```json
"require": {
    "php": "^8.1",
    "composer-runtime-api": "^2.1",
    "phpstan/phpstan": "^2.1.5",
    "nikic/php-parser": "^4.15 || ^5.1"
}
```

`illuminate/validation` and `laravel/framework` are `require-dev` only, which
is correct today (the analyzer never loads Laravel).

### What production users would pull in, measured

| Package | On-disk | Transitive deps | Runtime cost |
| --- | --- | --- | --- |
| `phpstan/phpstan` | **47 MB** (`phpstan.phar` alone is 27.9 MB) | none (`require: {"php": "^7.4|^8.0"}`) | `autoload.files: ["bootstrap.php"]` → one `require` and one `spl_autoload_register()` on **every request** |
| `nikic/php-parser` | 1.9 MB | none beyond ext-tokenizer/json | classmap only; nothing eager |

The autoload cost is small but not zero, and it is unconditional: PHPStan's
`PharAutoloader::loadClass` sits in the autoloader chain for the life of every
production request, consulted on every class miss. It early-returns unless the
class name starts with `PHPStan\` or `_PHPStan_`, so the CPU cost is
negligible; the 30 MB of deploy artefact and the "why is a static analyser in
my production image?" conversation are the real costs.

### Verdict

**Merely inelegant, and cleanly avoidable at almost no DX cost.**

### The fix: invert the dependency, as Carbon does

`nesbot/carbon` — 725M downloads, one of the most-installed PHP packages in
existence — ships a PHPStan extension *inside a production runtime package*:

```json
"require-dev": { "phpstan/phpstan": "...", "phpstan/extension-installer": "..." },
"extra": { "phpstan": { "includes": ["extension.neon"] } }
```

with the extension classes under `src/Carbon/PHPStan/`. They are never
autoloaded at runtime because nothing references them.

This works because `phpstan/extension-installer` discovers extensions by
**either** `type: phpstan-extension` **or** the presence of `extra.phpstan`
(`Plugin.php:125`):

```php
if ($package->getType() !== 'phpstan-extension' && !isset($package->getExtra()['phpstan'])) { ...skip... }
```

and it reads `require['phpstan/phpstan']` only to emit a version-consistency
warning, not as a discovery requirement. **PHPStan does not require extension
packages to declare PHPStan as a hard dependency.**

### Proposed `composer.json`

```json
"require": {
    "php": "^8.1",
    "composer-runtime-api": "^2.1"
},
"require-dev": {
    "phpstan/phpstan": "^2.1.5",
    "nikic/php-parser": "^4.15 || ^5.1",
    "illuminate/validation": "^10.7 || ^11.0 || ^12.0 || ^13.0",
    ...
},
"conflict": {
    "phpstan/phpstan": "<2.1.5"
},
"suggest": {
    "illuminate/validation": "^10.7 || ^11.0 || ^12.0 || ^13.0 — required only to use the Parse::* runtime parsing rules"
}
```

**`conflict` is the Composer idiom for a peer dependency.** It means "if this
package is present it must satisfy this range; if absent, fine." For
`phpstan/phpstan` that is exactly right:

- a user who installs the extension without PHPStan: no error, extension
  simply inert (an impossible situation in practice);
- a user on PHPStan 1.x: a hard, immediate, actionable resolution error
  instead of a class-not-found at analysis time — **better** than today's
  `require`, which would just install a compatible PHPStan and could silently
  conflict with the user's own pin.

The one genuine regression: a user requiring only this package in a project
with no PHPStan gets no PHPStan installed. That user does not exist — this
package is useless without PHPStan. Document it in the install instructions.

### Why there is no `conflict` on `illuminate/validation`

An earlier draft of this report proposed
`"conflict": {"illuminate/validation": "<10.7"}` to enforce the `Parse::*`
floor. **That is wrong and must not be implemented.**

Composer constraints are package-level and unconditional. There is no way to
express:

> conflict with this version **only if the consumer uses this particular class**

so that entry would make `phpstan-laravel-validation` **uninstallable** on
Laravel 10.0–10.6 for every user, including the overwhelming majority who want
only the static analysis and will never touch `Parse::*`. It would trade real,
current analyzer compatibility for enforcement of an optional feature's floor.
The `conflict` also applies against the root project's own requirements, so a
`composer require --dev` on a Laravel 10.5 application would simply fail to
resolve.

The two constraints genuinely differ in kind:

| Constraint | Conditional on usage? | Enforceable in Composer? |
| --- | --- | --- |
| `phpstan/phpstan >= 2.1.5` | No — the package is inert without it | Yes, `conflict` |
| `illuminate/validation >= 10.7` | **Yes** — only `Parse::*` needs it | **No** |

The options for the second row are:

```text
A. Keep the analyzer's Laravel 10.0 floor.
   Parse::* guards at runtime and is reported statically.
B. Raise the whole project's Laravel floor to 10.7.
C. Split the runtime into its own package so it can carry its own floor.
```

**Recommend A.** B sacrifices analyzer support for Laravel 10.0–10.6 —
a window of six minor releases from the first half of 2023 — purely so that
Composer can police an opt-in feature. C reintroduces every problem §16 and
§20 argue against, for the same reason. Neither is worth it.

Under A the floor is enforced in the two places that can actually be
conditional on usage:

1. **Runtime**, in `setValidator()`:

   ```php
   if (! method_exists($validator, 'setValue')) {
       throw new UnsupportedLaravelVersion(
           'Parsing rules require laravel/framework >= 10.7.0 (Validator::setValue).'
       );
   }
   ```

2. **Statically**, which is the better half and costs almost nothing here:
   `LaravelVersionContext` already exists, already resolves the installed
   Laravel version from `composer.lock`, and already exposes
   `isAtLeast(string $version): bool` and `hasFrameworkVersion(): bool`. A
   small PHPStan rule reports use of a `ParsingRule` when
   `hasFrameworkVersion()` is true and `isAtLeast('10.7.0')` is false.

That combination is strictly better than a Composer constraint would have
been: it is precise about *usage*, it fires at analysis time rather than at
install time, and it names the actual method. This package is a static
analyser; letting the analyser enforce its own feature floors is the natural
answer, not a consolation prize.

`suggest` remains worth adding for discoverability. It has no enforcement and
does not degrade DX; it is advisory text in `composer suggests`.

### Should `nikic/php-parser` move too?

Yes. It is used only by analysis-time code (`FormRequestRuleTypeResolver`,
`FormRequestTypeRegistry`, the `currentPhpVersionSimpleDirectParser` service).
PHPStan itself always provides a compatible `PhpParser\` namespace when it
runs. Moving it to `require-dev` with a matching `conflict` entry is the same
argument. Verify against the minimum-PHPStan consumer smoke test the repo
already runs (`consumer-phpstan-minimum`) before committing to it.

### Answers to the posed questions

| Question | Answer |
| --- | --- |
| What would production users pull in? | ~49 MB and one permanent autoloader, if nothing changes. |
| How problematic? | Inelegant, not material. No transitive deps, negligible CPU. |
| Should deps move? | Yes — `phpstan/phpstan` and `nikic/php-parser` → `require-dev` + `conflict`. |
| Can runtime classes avoid PHPStan? | Yes, trivially; see §19. |
| Can PHPStan integration stay optional at runtime? | Yes — it already is; nothing loads `PHPStan\*` unless PHPStan runs. |
| Would removing `phpstan/phpstan` from `require` break extension install? | No. Carbon proves it; `extension-installer` reads `extra.phpstan`. |
| Is there a peer-dependency pattern? | Yes for `phpstan/phpstan`: `conflict` with a lower bound. **No** for a constraint that applies only when a particular class is used — see the `illuminate/validation` discussion above. |
| Does `suggest` help? | Marginally, for discoverability. Use it with `conflict`, not instead. |
| Does PHPStan require extensions to hard-depend on it? | No. |
| What do well-maintained extensions do? | Pure extensions (`phpstan/phpstan-phpunit`, `phpstan/phpstan-strict-rules`, `larastan/larastan`) use `require`. Runtime packages that *ship* an extension (`nesbot/carbon`) use `require-dev` + `extra.phpstan`. This package becomes the second kind. |

---

## 18. Recommended namespace and layout

The runtime API should not live under a namespace containing `Phpstan`. This
is more than cosmetic: `use jbboehr\PhpstanLaravelValidation\Parsing\Parse;` in
a controller reads as a static-analysis import in production code, and it will
invite exactly the "why is PHPStan in my app?" question that §17 exists to
answer. But it is also not a reason to split the package — a single Composer
package may register any number of PSR-4 roots.

```json
"autoload": {
    "psr-4": {
        "jbboehr\\PhpstanLaravelValidation\\": "src/",
        "jbboehr\\LaravelValidationParsing\\": "runtime/"
    }
}
```

```text
runtime/                                  ← Laravel only, never PHPStan
    Parse.php                             Parse::integer() | float() | boolean() | enum()
    ParsingRule.php                       interface ParsingRule<T> extends ValidationRule
    ParseFailure.php
    Rules/
        BaseParsingRule.php               implicit flag, presence, null policy, flush
        IntegerRule.php                   implements ParsingRule<int>
        FloatRule.php                     implements ParsingRule<float>
        BooleanRule.php                   implements ParsingRule<bool>
        EnumRule.php                      implements ParsingRule<T of BackedEnum>
    Internal/
        ParseState.php                    per-validator SplObjectStorage state
        Lexer.php                         shared narrow grammars

src/                                      ← unchanged root
    Validation/
        ParsingRuleTypeResolver.php       discovers T from ParsingRule<T>
    ...
```

Package name stays `jbboehr/phpstan-laravel-validation`. The README frames the
runtime namespace as the opt-in parsing boundary shipped alongside the
analyzer, which is accurate and reads well.

---

## 19. Runtime dependency hygiene

Required direction:

```text
runtime/  →  illuminate/validation contracts only
src/      →  runtime/ (class-string references only) + PHPStan
```

Enforcement, all mechanical:

1. **No `use PHPStan\*` under `runtime/`.** A PHPStan rule or a grep in CI.
   Prefer the former — this repo already ships custom rules.
2. **`src/` must reference `runtime/` classes by name only**, for
   `instanceof`-style type checks in the resolver. It must never *instantiate*
   a parser during analysis, and must not require `illuminate/validation` to be
   installed to do its job. Since `runtime/ParsingRule` extends
   `Illuminate\Contracts\Validation\ValidationRule`, the analyzer must handle
   the class being unresolvable — PHPStan's `ReflectionProvider` already
   degrades gracefully, but the resolver must not assume the interface exists.
3. **Autoloading is not an issue.** PSR-4 is lazy; nothing under `runtime/` is
   loaded unless application code names it, and nothing under `src/` is loaded
   unless PHPStan runs. No `autoload.files` entries anywhere.
4. A CI job that runs a trivial Laravel app with `composer install --no-dev`
   plus only `illuminate/validation`, exercising `Parse::integer()`, proves the
   runtime is independently loadable. Cheap and worth having.

---

## 20. Version compatibility

The concrete floors established by this investigation:

| Capability | Minimum |
| --- | --- |
| `Validator::setValue()` | **`v10.7.0`** |
| `Validator::getValue()` public | `v10.33.0` (avoidable — use `Arr::get($validator->getData(), …)`) |
| `ValidationRule` contract, `InvokableValidationRule`, `$implicit` | `v10.0.0` |
| `ValidatorAwareRule` forwarding through `InvokableValidationRule` | `v10.0.0` |
| Everything else the design needs | `v10.0.0` |

So: **the analyzer keeps supporting Laravel `^10.0`; the parsing runtime
requires `^10.7`.** This asymmetry cannot be expressed in `composer.json`
(§17): a package-level `conflict` would block installation for analyzer-only
users on Laravel 10.0–10.6. Enforce it in the two places that can be
conditional on actual usage — a PHPStan rule keyed on
`LaravelVersionContext::isAtLeast('10.7.0')`, and a runtime guard:

```php
if (! method_exists($this->validator, 'setValue')) {
    throw new \RuntimeException(
        'Parsing rules require laravel/framework >= 10.7.0 (Validator::setValue).'
    );
}
```

Do **not** implement the `setData()` fallback that
`joelharkes/laravel-strict-validation` uses. `setData()` calls
`setRules($this->initialRules)` mid-validation, re-parsing and re-expanding
every rule. It is a correctness hazard, not a compatibility shim.

### Why colocation matters here specifically

Consider the `'42.0'` question from §10. Suppose v0.3 accepts it and v0.4 does
not. In one package, `Parse::integer()`'s runtime grammar and the analyzer's
`int` claim change in the same commit, are covered by the same fixture, and
ship in the same tag. A user on v0.3 gets v0.3 semantics from both sides,
always.

Split across packages, the mismatch is *silent in the direction that matters*:
analyzer v0.3 (permissive model) with runtime v0.4 (strict) produces code that
type-checks and then throws 422s in production on inputs PHPStan said were
fine. No tooling detects it. Composer cannot express the constraint. This is
the strongest single argument in the whole report for layout A.

Semantic versioning also becomes tractable in one package: a narrowing of
parser acceptance is a breaking change to *both* sides simultaneously, so it is
a single major/minor bump with a single changelog entry, rather than a
cross-package compatibility note nobody reads.

---

## 21. Test matrix

The repository already has the two halves and, in
`AssertsLaravelValidation::assertLaravelValidationCase()`, the bridge between
them:

```php
self::assertSame($expectedPasses, $validator->passes(), $context);
$validated = $validator->validated();
self::assertSame($expectedValidated, $validated, $context);

$inferred = (new TypeResolver())->evaluate(RuleParser::parse($rules));
$actual   = LaravelValueType::fromValue($validated);
self::assertTrue($inferred->isSuperTypeOf($actual)->yes(), ...);
```

**One shared fixture table should drive both sides.** Proposed shape, mirroring
the existing `namedCases()` providers:

```php
// input value        rules                              passes  validated        inferred
['age' => '42'],      ['age' => [Parse::integer()]],     true,   ['age' => 42],   'array{age?: int}'
['age' => 42],        ['age' => [Parse::integer()]],     true,   ['age' => 42],   'array{age?: int}'
['age' => 'foo'],     ['age' => [Parse::integer()]],     false,  null,            'array{age?: int}'
[],                   ['age' => [Parse::integer()]],     true,   [],              'array{age?: int}'
['age' => ''],        ['age' => [Parse::integer()]],     false,  null,            'array{age?: int}'
['age' => null],      ['age' => [Parse::integer()]],     false,  null,            'array{age?: int}'
['age' => null],      ['age' => ['nullable', Parse::…]], true,   ['age' => null], 'array{age?: int|null}'
['age' => '42'],      ['age' => ['required', Parse::…]], true,   ['age' => 42],   'array{age: int}'
[],                   ['age' => ['required', Parse::…]], false,  null,            'array{age: int}'
[],                   ['age' => ['sometimes', Parse::…]],true,   [],              'array{age?: int}'
```

Required additional coverage:

**Parser grammar** (one row per §10 hazard, per parser)

| parser | input | runtime | static |
| --- | --- | --- | --- |
| `integer` | `'042'`, `'+42'`, `' 42'`, `'42.0'`, `42.0`, `'1e3'`, `true`, `'9223372036854775808'` | fail | `int` |
| `float` | `'1e999'`, `INF`, `NAN`, `'1,5'` | fail | `float` |
| `boolean` | `'true'`, `'false'`, `'on'`, `'yes'`, `''`, `2` | fail | `bool` |
| `enum` (int-backed) | `'1'` (string) | fail | `Status` |
| `enum` (string-backed) | `1` (int) | fail | `Status` |
| `enum` | already-a-case | pass through | `Status` |

**Lifecycle**

- delayed flush ordering vs a user `after()` callback registered first;
- `passes()` called twice; `fails()` then `validated()`; `validated()` twice;
- an `after()` callback that throws (data must remain raw);
- `stopOnFirstFailure`;
- two parsers, one failing (the succeeding one's data still flushes).

**Structure**

- `profile.age`; `users.*.age` (list and associative); depth-2 wildcards;
- one rule instance shared across expansions and across validators;
- wildcard element absent / null / unparsable;
- `exclude`, `exclude_if` before and after the parser (`validated()` must omit
  the key and `getData()` must not resurrect it);
- escaped-dot attribute — asserted to **fail loudly**, not silently no-op.

**`after()` lifecycle (§8.1)**

- callback registered before `passes()` observes raw `validated()`/`safe()`/
  `valid()`/`getData()`/`getValue()` — assert the raw values explicitly, so a
  future Laravel ordering change fails the suite loudly rather than silently
  changing the contract;
- `passedValidation()` observes parsed data, `withValidator()`+`after()` does
  not — the pair that documentation depends on;
- a callback appended from inside another callback does not run;
- the `ParsedDataInAfterCallbackRule` fires on the closure form, the arrow-fn
  form, and a `use`-captured validator, and does **not** fire when the rule set
  contains no parsing rule.

**Version floor**

- `ParsingRuleLaravelVersionRule` fires when the resolved Laravel version is
  `< 10.7.0` and stays silent when the version is unknown
  (`hasFrameworkVersion() === false`) or `>= 10.7.0`;
- the runtime guard throws `UnsupportedLaravelVersion` on a validator without
  `setValue()`.

**Cross-field**

- `['start' => [Parse::integer()], 'end' => ['gte:start']]` — raw comparison;
- `['a' => [Parse::integer()], 'b' => ['same:a']]` — must pass (the immediate
  model's regression test).

**Version matrix**

Add the parsing suite to the existing `test:audit:matrix` profiles, with
`10.0.0`–`10.6.x` expected to raise the guarded "requires >= 10.7" error rather
than being skipped silently.

**FormRequest**

`validated()` / `safe()` transformed while `all()` / `input()` / `get()` /
`post()` remain original — the property most likely to regress unnoticed.

---

## 22. Risks and unknowns

| Risk | Severity | Mitigation |
| --- | --- | --- |
| `setValue()` is undocumented | Low | Introduced *for this use case* by a merged PR whose alternative was rejected; guard with `method_exists` |
| `setValue()` absent < 10.7.0 | **High if unhandled** | Runtime guard + PHPStan rule via `LaravelVersionContext`. **Not** a Composer `conflict` — that would break analyzer-only users on 10.0–10.6 (§17). Reproduced as a hard fatal |
| `validated()` inside an `after()` callback returns raw values | **High if unhandled** | Structurally unfixable (§8.1). PHPStan rule catches inline closures/arrow fns only — invokables, `[$obj,'method']`, string callables and the array form stay undetected. Partial mitigation; document `passedValidation()` as the supported hook |
| `after()` ordering changes upstream | Medium | Behaviour identical 10.0→13.25; covered by the version-matrix suite |
| Escaped-dot attributes | **High** | Fail loudly at runtime; refuse the parsed type statically. Recovery via `__dot__` regex exists but depends on undocumented format + 16-char hash |
| Shared rule instance across wildcards | Medium | State keyed by (validator, concrete attribute); never on `$this`. Verified across expansions and across validators |
| Excluded attributes resurrected in `getData()` | Medium | Two-condition flush guard; `validated()` was already safe |
| Blank-string bypass | **High if unhandled** | Rule must be implicit; `allowsBlankStringBypass()` must return `false` for parsing nodes. Both measured |
| Implicit breaks `nullable` | Medium | Parser owns null policy, reads `getRules()` for `nullable`. Measured working incl. wildcards |
| `min`/`max` stay string-semantic | Medium (DX) | Document; recommend `['integer', Parse::integer(), 'min:18']`. Do **not** fake `hasRule()` via `__toString` |
| Users expect `input()` to change | Low (DX) | Document that only `validated()`/`safe()` transform. Arguably the correct behaviour |
| PHPStan in production | Low | Move to `require-dev` + `conflict`, per Carbon |
| `nikic/php-parser` move breaks minimum-PHPStan consumer | Low | Existing `consumer-phpstan-minimum` smoke test covers it |
| Scope creep into a DTO framework | Medium | Hard cap: four parsers, no `Parse::using()`, no `only()`/`except()`, no object mapping, no `Parse::string()` |
| `Rule::forEach` interaction | Unknown | Untested; declare unsupported in v1 |
| Precognition `after()` hook | Low | Registered before the parser's flush; precognitive requests short-circuit before `validated()` |
| Laravel adds native casting | Low | #46162 was closed; if it lands, `Parse::*` becomes a thin adapter |

**Genuine unknowns:** `Rule::forEach` composition; behaviour under
`Validator::extend()`-registered rules that also mutate; interaction with
packages that subclass `Validator` (e.g. `qtlenh/laravel-strict-validator`
installed simultaneously).

---

## 23. Implementation sketch

Enough detail for a later agent to implement safely. **Not implemented now.**

### Runtime

```php

namespace jbboehr\LaravelValidationParsing\Rules;

abstract class BaseParsingRule implements ParsingRule, ValidatorAwareRule
{
    /** Read by InvokableValidationRule::make() — must be public and true. */
    public bool $implicit = true;

    protected Validator $validator;

    public function setValidator($validator)
    {
        if (! method_exists($validator, 'setValue')) {
            throw new UnsupportedLaravelVersion(
                'Parsing rules require laravel/framework >= 10.7.0.'
            );
        }

        $this->validator = $validator;

        return $this;
    }

    final public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $missing = ParseState::MISSING;

        // Escaped-dot attributes: the rule sees the decoded name while the
        // validator keys data by `<name>__dot__<hash>`. Fail loudly.
        if (Arr::get($this->validator->getData(), $attribute, $missing) === $missing) {
            if (str_contains($attribute, '.') && $this->looksLikeEscapedDotPath($attribute)) {
                $fail('Parsing rules do not support escaped-dot attribute names.');
            }

            return;   // genuinely absent: let required/present/sometimes decide
        }

        if ($value === null) {
            if ($this->attributeIsNullable($attribute)) {
                return;
            }

            $fail($this->message());

            return;
        }

        try {
            $parsed = $this->parse($value);
        } catch (ParseFailure) {
            $fail($this->message());

            return;
        }

        $state = ParseState::for($this->validator);
        $state->pending[$attribute] = $parsed;
        $state->registerFlushOnce($this->validator, spl_object_id($this));
    }
}
```

`ParseState::registerFlushOnce()`:

```php
public function registerFlushOnce(Validator $validator, int $ruleId): void
{
    if (isset($this->registered[$ruleId])) {
        return;
    }

    $this->registered[$ruleId] = true;
    $state = $this;

    $validator->after(static function (Validator $validator) use ($state): void {
        $rules = $validator->getRules();

        foreach ($state->pending as $attribute => $value) {
            // exclude_* removed the rule and/or the data. Do not resurrect.
            if (! array_key_exists($attribute, $rules)) { continue; }
            if (Arr::get($validator->getData(), $attribute, self::MISSING) === self::MISSING) { continue; }

            $validator->setValue($attribute, $value);
        }

        $state->pending = [];   // idempotent across repeated passes()
    });
}
```

Notes for the implementer, revised against what building it established:

- **Hold state in a per-instance `\WeakMap<Validator, ParseState>`, not a
  static `SplObjectStorage`.** A static store holds strong references and
  leaks one entry per validation for the life of the process, which is
  invisible in a web request and a real leak under long-lived workers. A
  `WeakMap` also makes `registered` mean "once per (rule instance, validator)"
  structurally, with no separate bookkeeping.
- **Do not key registration by `spl_object_id`.** Ids are reused after an
  object is collected, so a new rule instance can inherit a dead one's id and
  have its write-back silently suppressed.
- **Do not use a string sentinel for absence.** `ParseState::MISSING` as a
  string can collide with real input. `Arr::has()` answers the question
  directly and cannot collide.
- The closure must be `static` and must capture `$state`, never `$this`.
- Take and clear `$state->pending` *before* the write loop, so a failure
  part-way through cannot be replayed against different data on the next run.
  `registered` is *not* cleared — the callback stays in `$validator->after`
  and re-fires correctly, by which time the rules have refilled the map.
- `attributeIsNullable()` scans `$validator->getRules()[$attribute]` for the
  case-insensitive string `nullable`. `hasRule()` is public but does its own
  `ValidationRuleParser::parse()` round-trip; the direct scan is cheaper and
  sufficient.
- **Detect an escaped-dot attribute by asking whether it is a key of
  `getRules()`, not by decoding the placeholder.** Decoding needs
  `/__dot__[A-Za-z0-9]{16}/` — the unanchored form is greedy and yields `a.`
  rather than `a.b` — and the `__dot__` literal does not exist at all in the
  older releases in scope, which used a bare random string. The rules-key test
  is exact and format-independent: Laravel hands rules the decoded name, so a
  rewritten attribute is precisely one that is not a key of its own rule set,
  while an ordinary attribute always is, including a wildcard expansion and
  including one absent from the data.
- Parsers must be stateless apart from constructor arguments, and `parse()`
  must accept its own output — Laravel calls `passes()` more than once.
- Anchor a string grammar with `\z`, not `$`: PCRE's `$` also matches before a
  final newline, so `"42\n"` would otherwise parse.
- **Escaped-dot attributes are addressable after all, on most releases.** The
  placeholder is one fixed random string per validator, marked with `__dot__`,
  so the encoded key can be recovered by decoding the rule-set keys and
  matching the decoded name. The marker arrived during Laravel 10.48;
  before that the dot was replaced by a bare random string with nothing to
  anchor on, and there the attribute has to be reported as unaddressable.
  Anchor the placeholder to `Str::random()`'s 16 characters and accept a
  candidate only when it decodes to exactly the attribute, so an unrecognized
  format fails loudly instead of writing somewhere wrong.
- **Size rules still measure the original representation.** Laravel's
  `getSize()` picks numeric comparison from `hasRule($attribute, ['Numeric',
  'Integer', 'Decimal'])`, and a rule object cannot match that: `prepareRule()`
  wraps a `ValidationRule` in `InvokableValidationRule` before `hasRule()` sees
  it. So `[Parse::integer(), 'min:10']` compares string length. The legacy
  `Rule` contract with `__toString(): 'Numeric'` does satisfy `hasRule()`, but
  it means implementing a deprecated interface and misreporting the rule to
  every other consumer. The better answer is to put the bound on the parser --
  `Parse::integer()->min(18)` -- which sidesteps Laravel's sizing entirely and
  lets the analyzer narrow to `int<18, max>`. Until then, pair the parser with
  `integer` or `numeric`.

### Parsers

`IntegerRule`, `FloatRule`, `BooleanRule`, `EnumRule` implement only
`parse(mixed $value): mixed`, throwing `ParseFailure`. Grammars per §10/§11.
Share the regexes in `Internal/Lexer` so the analyzer-side documentation and
the tests reference one definition.

### Analyzer

1. `Rule::RULE_PARSE`, `Rule::parsing(Type)`, `getProducedType()`, cache key.
2. `ParsingRuleTypeResolver` — resolve `T` from `ParsingRule<T>` via
   `ClassReflection::getAncestorWithClassName(ParsingRule::class)` and its
   active template-type map; fall back to attribute → PHPDoc → neon config;
   return `null` (not `mixed`) when undiscoverable so the caller can fall
   through to the predicate path.
3. `RuleSetResolver` — recognise `ParsingRule` object types at rule positions
   and emit `Rule::parsing(...)`.
4. `RuleParser::parseRule()` — same, for the runtime-values path.
5. `RuleTreeNode::hasParsingRule()` / `getProducedType()`;
   `allowsBlankStringBypass()` returns `false` when a parsing rule is present.
6. `TypeResolver::evaluateLeaf()` — parsing branch replaces the intersection,
   applies `nullable`, skips the blank-string union and `refinePositiveMinimum`.
7. Error rule for two parsing rules on one attribute.
8. `extension.neon`: add `parsingRules: { classes: [] }` under
   `phpstanLaravelValidation`, distinct from `customRules`.

### Order of work

1. `runtime/` + parsers + the full runtime test matrix (§21), version matrix
   included. Prove the semantics before any analyzer change.
2. `Rule::parsing` + `TypeResolver` branch + `allowsBlankStringBypass` fix,
   driven by `AssertsLaravelValidation` so runtime and static agree from the
   first commit.
3. Generic `ParsingRule<T>` discovery.
4. The two new PHPStan rules: `ParsedDataInAfterCallbackRule` (§8.1) and
   `ParsingRuleLaravelVersionRule` (§17). Neither is optional — they are the
   enforcement for the two constraints the type system and Composer
   respectively cannot express.
5. `composer.json` dependency inversion (§17) + the `--no-dev` runtime smoke
   test. **No `conflict` on `illuminate/validation`.**
6. Documentation (§24).

---

## 24. Documentation positioning

The framing holds, and the feature strengthens rather than undermines the
project's thesis. The existing guide already says:

> Laravel validation is not a typed data boundary. […] `phpstan-laravel-validation`
> describes that runtime contract as honestly as possible. It can mitigate the
> problem for existing applications; it cannot turn the underlying design into
> a coherent typed transformation.

and, of the custom-rule metadata:

> Those declarations describe original values preserved after a custom
> predicate succeeds. They do not infer arbitrary mutation, implicitness, or
> output projection.

`Parse::*` is the sanctioned exception to that last sentence, and it should be
introduced as such:

> Laravel's built-in validation rules are predicates. `phpstan-laravel-validation`
> models those semantics faithfully, including the cases where a rule name
> suggests a conversion that never happens.
>
> When you want validated data to *become* a different runtime representation,
> `Parse::*` provides an explicit, opt-in parsing boundary. A parsing rule
> either produces a value of its declared type or fails validation. Ordinary
> rules continue to observe the original representation; only
> `validated()`, `safe()`, and `valid()` return parsed values. The request
> itself is never modified.

Points the documentation must make explicitly, because each was a measured
surprise:

1. `'integer'` and `Parse::integer()` are different operations; showing them
   side by side is the clearest possible statement of the project's thesis.
2. `min`/`max`/`between`/`size` still use Laravel's raw-representation rules.
   Pair `Parse::integer()` with `integer` when sizing matters.
3. `$request->input()` does not change. This is deliberate.
4. Parsing rules are implicit, so `''` and `null` fail unless `nullable`.
5. Escaped-dot attributes are unsupported.
6. Laravel `>= 10.7.0` is required for parsing, while analysis supports
   `>= 10.0`. Composer will not enforce this; the analyzer will.
7. **Do not read `validated()`, `safe()`, `valid()`, or `getData()` inside an
   `after()` callback** — those observe values from before parsing, in *every*
   callable form `after()` accepts, not only the ones the analyzer reports. In
   a `FormRequest`, use `passedValidation()`; `withValidator()` is for adding
   validation, not for reading results. Show both, side by side, with the
   measured output from §8.1, and say explicitly that the PHPStan diagnostic
   is a safety net for the common forms rather than a guarantee. This is the
   single most likely way for a user to get a wrong runtime value out of a
   correct-looking type.

---

## 25. Minimal v1

```php
Parse::integer()            // ParsingRule<int>
Parse::boolean()            // ParsingRule<bool>
Parse::enum(Status::class)  // ParsingRule<Status>
```

plus the public `ParsingRule<T>` interface and `BaseParsingRule` so users can
write their own.

`Parse::float()` — include if `float` semantics land cleanly; it is the least
demanded of the three primitives and carries the most edge cases (`INF`,
precision, scientific notation). Shipping it in v1.1 costs nothing.

`Parse::string()` — **out.** Near-no-op over Laravel's `string` rule; its only
added behaviour is coercion, which the design opposes.

Explicitly out of v1: `Parse::using()`, `only()`/`except()` on enums, pure
enums, date/`Carbon` parsing, collection parsing, nested-object parsing,
`Rule::forEach` support, anything resembling hydration or mapping.

The scope boundary to hold: **a parsing rule maps one scalar-ish input value to
one output value, or fails.** Anything that maps *structure* is out. That line
keeps this a validation feature and not the beginning of a DTO framework — the
niche `spatie/laravel-data` already fills properly.

---

## 26. Final recommendation

```text
RECOMMENDATION: PROTOTYPE FOR PRODUCTION
```

The mechanism is sound, sanctioned, and stable. `Validator::setValue()` exists
specifically because a Laravel maintainer merged it for this exact purpose
after rejecting the alternative that would have made it unnecessary. Delayed
transformation via `after()` gives a clean semantic model in which ordinary
rules are entirely unaffected — verified across every rule family that could
plausibly interact, including the cross-field rules where immediate mutation
demonstrably breaks (`same:` fails on identical input). Runtime output is
byte-identical on Laravel 10-latest through 13.25.0.

The soundness hazards are the interesting part, and they are all enumerable:
the rule must be implicit or blank strings leak; being implicit means the rule
must own null and presence itself; excluded attributes need a write-back guard;
escaped-dot attributes must fail loudly. Each was reproduced, each has a fix,
and each has a test. That is a good position to be in before writing production
code — considerably better than the two existing packages that attempted this
and got presence and null wrong.

Keeping runtime and analyzer in one package is right, and the objection to it
dissolves under inspection: PHPStan does not require extensions to hard-depend
on it, `nesbot/carbon` has shipped an extension from a production package for
years, and `conflict` gives real peer-dependency enforcement. The 47 MB was
never the strongest argument against layout A; the strongest argument *for* it
is that a parser's accepted grammar and its inferred type are one contract, and
splitting them across two version numbers makes silent disagreement
unpreventable.

Three conditions on proceeding:

1. **The Laravel `>= 10.7.0` floor is enforced by a runtime guard and a PHPStan
   rule, not by a Composer `conflict`.** A package-level constraint would make
   the analyzer uninstallable on Laravel 10.0–10.6 for users who never touch
   `Parse::*` — trading real compatibility for enforcement of an optional
   feature. `LaravelVersionContext::isAtLeast()` already exists; use it.
2. **Escaped-dot attributes fail loudly at runtime and are refused statically.**
   A silent no-op there is exactly the class of dishonesty this project exists
   to criticise.
3. **The `after()` hole ships with a PHPStan rule *and* an honest statement of
   its limits.** `validated()` inside a pre-registered `after()` callback
   returns raw values on every supported Laravel and in every callable form,
   and the ordering cannot be repaired. A package whose premise is that
   inferred types match runtime behaviour cannot leave a known counter-example
   undetected — but it also must not claim the diagnostic is complete when it
   only follows inline closures.

Build the runtime and its test matrix first. Only once the runtime semantics
are pinned by tests should the analyzer learn to claim the type.
