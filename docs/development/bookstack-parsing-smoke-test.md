# BookStack parsing smoke test

This report records a one-off downstream exercise of the opt-in
`jbboehr\Rensei\Parse` parsing rules against BookStack. It is an
investigation snapshot, not a supported-platform promise and not a claim that
the parsing prototype is ready for release.

The prototype and its design are recorded in the
[validation parsing investigation](validation-parsing-investigation.md).
Whole-application compatibility and analysis overhead were established
separately in the
[BookStack compatibility investigation](bookstack-compatibility-investigation.md)
and [BookStack performance benchmark](bookstack-performance-benchmark.md);
this report reuses their installation method and does not repeat their
measurements. The [development report index](README.md) explains how pinned
investigation reports relate to current project documentation.

Investigation date: 2026-08-18.

## Executive summary

The prototype works end to end in a real Laravel 12 application. At
BookStack's own search endpoint, `Parse::integer()` narrowed the validated
type from `float|int|string|Stringable|true` to `int`, and the bootstrapped
application returned `int` at runtime for the same rule set, leaving the
request untouched.

BookStack's complete configured scan produced no errors with the extension
loaded, so nothing in the parsing work regressed ordinary analysis of an
independently developed application.

Two findings are recorded. Neither is new, and neither was introduced by the
parsing work.

1. The documented size-rule hazard reproduces, and it fails in the confusing
   direction. `[Parse::integer(), 'min:5']` rejects `'7'` and reports that 7
   is smaller than 5, because `getSize()` measured the string's length. The
   documented workaround — keeping Laravel's `integer` rule alongside the
   parser — is confirmed to work.
1. A rule set stored in a mutable `array` property loses inference entirely,
   with or without parsing. This is PHPStan widening the property before the
   extension sees it, not an extension defect, and it affects 23 of
   BookStack's 76 validation call sites.

## Scope

The exercise asked three questions:

- Does `Parse::integer()` produce agreeing static and runtime results at a
  real application's validation entry point, rather than only in the
  repository's fixtures?
- Does the runtime component install and autoload in an application that
  takes the extension as an ordinary Composer development dependency?
- Did the parsing work regress whole-application analysis?

It did not attempt to measure analysis overhead, exercise BookStack's test
suite, test more than one Laravel major, propose the change to BookStack, or
establish BookStack as a supported downstream project. BookStack's source was
modified only to place probes, and was restored afterward.

## Revisions and environment

| Component | Version | Commit |
| --- | --- | --- |
| Extension | `parser-prototype` | `ffebe7f` |
| BookStack | `v26.05.3` | `e1cd3229966d` |
| `laravel/framework` | `v12.64.0` | |
| PHPStan | `2.2.8` | |
| Larastan | `v3.10.0` | |
| `nikic/php-parser` | `v5.8.0` | |
| PHP | `8.4.23` | |

The extension was installed into a disposable BookStack checkout through the
same path repository the compatibility investigation used, under
`require-dev`. Because `runtime/` is exported by the extension's own
`autoload.psr-4`, `jbboehr\Rensei\Parse` resolved with no additional wiring:

```php
class_exists(jbboehr\Rensei\Parse::class);   // true
get_class(jbboehr\Rensei\Parse::integer());  // jbboehr\Rensei\Rules\IntegerRule
```

That convenience is also the packaging problem the parsing investigation
deferred. A production use of `Parse::integer()` would require the analyzer
in `require`, not `require-dev`.

## Finding 1: agreeing static and runtime results

`BookStack\Search\SearchApiController::all()` is an ordinary API endpoint
whose query parameters arrive as strings. It validates them as integers and
then converts them by hand:

```php
$this->validate($request, $this->rules['all']);

$options = SearchOptions::fromString($request->input('query') ?? '');
$page = intval($request->input('page', '0')) ?: 1;
$count = min(intval($request->input('count', '0')) ?: 20, 100);
```

The `integer` rule establishes that the value is an integer and leaves a
string behind, so the endpoint reads `$request->input()` and casts. This is
the case the parsing design exists for.

Its rule set was probed in both forms. The rules are written inline here
because the property form is not resolvable — see finding 3.

| rules | inferred type of `validate()` |
| --- | --- |
| `['integer', 'min:1']` | `array{query: mixed, page?: float\|int\|string\|Stringable\|true, count?: float\|int\|string\|Stringable\|true}` |
| `['integer', Parse::integer(), 'min:1']` | `array{query: mixed, page?: int, count?: int}` |

The predicate union is the honest description of Laravel's `integer` rule,
which is `filter_var($value, FILTER_VALIDATE_INT) !== false`.

The same two rule sets were then run through BookStack's bootstrapped
application with `page=3` and `count=25` as query parameters:

```text
predicate    validated(): query=string('cats') page=string('3') count=string('25')
parsing      validated(): query=string('cats') page=int(3)      count=int(25)
request     input('page'): string('3')   all(): {"query":"cats","page":"3","count":"25"}
```

The inferred type and the runtime value agree, and the request is unchanged,
which is the contract `Parse` documents. Failures behave as designed, with
BookStack's own translations serving the non-parser rules:

```text
page=0            ["The page must be at least 1."]
count=101         ["The count may not be greater than 100."]
page=" 3"         ["The page field must be a whole number."]
```

## Finding 2: the size-rule hazard fails in the confusing direction

`IntegerRule` documents that Laravel decides whether `min`, `max`, `between`,
and `size` compare numerically or by string length from the presence of one
of `integer`, `numeric`, `array`, or `file`, and that a rule object cannot
register as one. The recommended form is therefore
`['integer', Parse::integer(), 'min:10']`.

The hazard reproduces, and the observed behavior is worse than "the
comparison is by length" conveys:

| value | `[Parse::integer(), 'min:5']` | `['integer', Parse::integer(), 'min:5']` |
| --- | --- | --- |
| `'7'` | fails | passes |
| `'123'` | fails | passes |
| `'11111'` | passes | passes |

A user who submits `7` against `min:5` is told the page must be at least 5.
The value satisfies the constraint, the message names the constraint it
satisfies, and nothing in the rule set reads as wrong. The documented
workaround is confirmed to work, but relying on documentation to avoid a
wrong-direction rejection with a self-contradicting message is not adequate.

This raises the priority of the two remedies the parsing investigation
already names: a diagnostic requiring a numeric companion rule beside a
numeric parser, and `Parse::integer()->min(5)` carrying the comparison
itself.

## Finding 3: rule sets in a mutable property lose inference

BookStack stores rule sets on the controller:

```php
protected array $rules = [
    'all' => [
        'query' => ['required'],
        'page'  => ['integer', 'min:1'],
        'count' => ['integer', 'min:1', 'max:100'],
    ],
];

// ...
$this->validate($request, $this->rules['all']);
```

The extension infers plain `array` there. The cause is upstream of the
extension: PHPStan widens a mutable typed property to its declared type, so
the attribute names and rule strings are gone before any rule set can be
resolved.

```text
$this->rules         array<string, array<string, array<string>>>
$this->rules['all']  array<string, array<string>>
```

The deciding factor is whether PHPStan hands the extension a constant array
type. Where the rules are written does not matter; whether their shape
survives does.

| form | resolved |
| --- | --- |
| inline array literal | yes |
| `private const RULES` | yes |
| `protected array $rules` with a `@var` array shape | yes |
| method with a `@return` array shape | yes |
| `protected array $rules` (BookStack today) | **no** |
| `private readonly array $rules` | **no** |
| method returning a bare `array` | **no** |

`readonly` does not help. The property's declared type is still `array`, and
that is what PHPStan reports regardless of the initializer's shape.

23 of BookStack's 76 validation call sites pass `$this->rules[...]`, across
seven controllers. Those sites recover nothing from the extension today, with
or without parsing.

## Limitations

- One application, one Laravel major, one PHP version. The repository's
  own matrix covers Laravel 10 through 13.
- The parsing probes were placed by this investigation. BookStack does not
  use parsing rules, so no independently written parsing code was exercised.
- BookStack has no FormRequest classes in its `app` tree, so this report does
  not exercise parsing under FormRequest resolution.
- The rule sets in finding 1 were written inline to work around finding 3.
  BookStack's own call site remains unresolvable.
- No overhead measurement. The parsing rules add a resolver to the analysis
  and an `after()` callback to each validation run; neither was timed.

## Remaining follow-up

- Decide between the numeric-companion diagnostic and parser-carried size
  constraints for finding 2. Finding 2 is the strongest argument yet for
  doing one of them before more parsers land.
- Consider whether the extension should report finding 3 rather than
  silently inferring `array`. A rule set that resolves to nothing is
  indistinguishable, from the caller's side, from an unsupported rule.
- Finding 3 has no local regression coverage expressing the property form as
  an explicitly unresolvable input. It is upstream behavior rather than an
  extension defect, but it is worth pinning so a future PHPStan release that
  narrows mutable properties is noticed rather than assumed.

## Commands and observed results

Installation followed the compatibility investigation's method, with the
extension required at the branch under test:

```sh
git clone --branch v26.05.3 --depth 1 \
    https://github.com/BookStackApp/BookStack.git bookstack

nix develop /home/sandbox/Code/phpstan-laravel-validation#php84 \
    --command composer update jbboehr/phpstan-laravel-validation \
    --with-dependencies --prefer-dist --no-interaction --no-progress \
    --no-scripts
```

`composer install` cannot be used after adding the path repository by hand,
because the lock file does not yet contain the package.

The combined audit configuration was:

```neon
includes:
    - phpstan.neon.dist
    - vendor/jbboehr/phpstan-laravel-validation/extension.neon

parameters:
    tmpDir: /tmp/phpstan-validation-bookstack-smoke
```

The whole-application scan on unmodified BookStack source reported
`[OK] No errors`.

Inferred types were read with `\PHPStan\dumpType()` at level 4, which reports
at any level. Runtime results were produced by bootstrapping BookStack's
console kernel against a `.env` derived from `.env.example` with a syntactic
`APP_KEY`. No database was required; the rule sets exercised here contain no
`exists` rule.

The probe files and the disposable checkout are not retained. The probes were
a modified copy of `SearchApiController::all()` carrying `dumpType()` calls,
a `SmokeRulesProbe` controller enumerating the storage forms in finding 3,
and two standalone scripts for the runtime and size-rule tables. BookStack's
`app` tree was restored before the investigation closed.
