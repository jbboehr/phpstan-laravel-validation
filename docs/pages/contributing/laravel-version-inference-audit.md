# Laravel-version inference audit

## Result

No successful output in the portable audit corpus falls outside the inferred
type.

This audit checks whether Laravel's validation behavior changes across the
releases supported by `phpstan-laravel-validation`, and whether the
extension's version-aware inferred types contain every successful output
observed at those releases.

This is an audit result, not a proof of universal soundness. It covers the
portable rule families and interactions listed below. Files, databases, DNS,
password services, image metadata, and application-defined validation
extensions remain outside the deterministic corpus.

## What was audited

The audit pins the first release of every supported major, the current
latest release, and both sides of every semantic transition used by
version-aware inference. Profiles and recorded commits are in
[Audited releases](#audited-releases).

User-facing inference boundaries are summarized in
[Laravel Version Behavior](../reference/laravel-versions.md).

## Important version boundaries

| Boundary | Source | Effect |
| --- | --- | --- |
| Laravel 12.0 | Portable corpus | Top-level literal integer rule keys are preserved instead of reindexed from `0` |
| Laravel 12.22 | Portable corpus | `integer:strict` requires a native integer |
| Laravel 13.4 | Portable corpus | `ascii` requires a native string |
| 10.21.1 | Builder fixtures | `In` / `NotIn` serialize enum cases |
| 10.32 | Runtime suite | `present_if` / `present_unless` exist |
| 10.33 | Runtime suite | `hex_color`; `Rule::unless()` |
| 10.34 | Runtime suite | `extensions` |
| 10.36 | Builder fixtures | `In` / `NotIn` constructors accept scalar, variadic, and `Arrayable` inputs |
| 10.46 | Builder fixtures | `Enum::only()` / `Enum::except()` |
| 11.0 | Runtime suite | Laravel 10 trims password fields; 11+ does not |
| 11.0.3 | Runtime suite | `list`; `required_if_declined` |
| 11.7 | Builder fixtures | `Rule::array()` |
| 11.23 | Runtime suite | Literal `list` joins nested reconstruction; `Dimensions` ratio methods |
| 11.40–11.43.2 | Builder fixtures | Fluent `Date` builder, then list and standalone expansion |
| 11.42 | Builder fixtures | Fluent `Numeric` builder |
| 12.16 | Runtime suite | `in_array_keys`; `Rule::contains()` |
| 12.22 | Runtime suite | `doesnt_contain`; `Rule::doesntContain()` |
| 12.40 | Runtime suite | `encoding` |
| 12.44 | Builder fixtures | `Rule::dateTime()` and now-relative date predicates |
| 12.55 | Builder fixtures | `Numeric::integer(strict: true)`; `Rule::string()` |
| 13.4 | Runtime suite | `hex_color` rejects compatible `Stringable` objects |
| 13.21 | Runtime suite | Native-string-only `base64` |
| 13.24 | Runtime suite | `array_keys`; `Rule::arrayKeys()` |

## Where uncertainty remains

The portable corpus does not execute environment-dependent rules. Builder
introduction boundaries outside that corpus are pinned by upstream commits,
focused fixtures, and cross-profile PHPUnit. Floating `*-latest` profiles
fail only when observed case results change.

## Builder-boundary evidence

Laravel 11.7 adds the `Rule::array()` builder via
[`8c684a222143`](https://github.com/laravel/framework/commit/8c684a222143fee9f9eff53b544c1f54a27b9e9e).
Laravel 11.40 adds the fluent `Date` builder via
[`1049c0370b24`](https://github.com/laravel/framework/commit/1049c0370b24a0d08ba5f3f2c8019b3249a31851),
but Laravel's validation parser did not expand its pipe-delimited chains
inside rule lists until 11.41 via
[`b7fca4b8fe48`](https://github.com/laravel/framework/commit/b7fca4b8fe48ad6502accb974fdc1dde5993fb91),
or as standalone field rules until 11.43.2 via
[`1f5e3833ae2b`](https://github.com/laravel/framework/commit/1f5e3833ae2b6cb933a451493ab5c64635663a87).
Laravel 12.44 adds `Rule::dateTime()` plus the builder's now-relative
predicates via
[`00ed6626514a`](https://github.com/laravel/framework/commit/00ed6626514a482dff22b0c7b77ab7b3226b1178).
Laravel 12.3 had already changed `Date::format()` from a `date|date_format`
intersection to a single `date_format` constraint via
[`726434c6d8b3`](https://github.com/laravel/framework/commit/726434c6d8b3a34a66a73c7fe2322f6f632a2339);
both forms require the same sound native output family.
Laravel 11.42 adds the fluent `Numeric` builder via
[`75b6392fd7c8`](https://github.com/laravel/framework/commit/75b6392fd7c8bee0ed7f1e490ba47241de7d0d31),
and Laravel 12.55 adds its strict integer option via
[`73b393274b25`](https://github.com/laravel/framework/commit/73b393274b2531995116ef30a83d8091e6934af8).
Laravel 12.55 also adds the fluent `StringRule` builder via
[`36c2a3a7d317`](https://github.com/laravel/framework/commit/36c2a3a7d31715cf2490489b3e05e7906b6541eb).
Laravel 12.16 adds `Rule::contains()` and `Contains` via
[`3a9fa0214fc3`](https://github.com/laravel/framework/commit/3a9fa0214fc3d8f63149e8d9a1bec7e4647101ba),
while Laravel 12.22 adds `Rule::doesntContain()` and `DoesntContain` with
the underlying rule via
[`ad138584ef0b`](https://github.com/laravel/framework/commit/ad138584ef0bceaf6ccd8e3e27d66bb339438562).
Laravel's `Enum` rule adds literal `only`/`except` filters in 10.46 via
[`8d47be393e43`](https://github.com/laravel/framework/commit/8d47be393e43ffeacd49556471110454f868da5f).
Laravel 10.21.1 also teaches the `In` and `NotIn` builders to serialize
enum cases via
[`4989e6de0766`](https://github.com/laravel/framework/commit/4989e6de076688ade265e2f1970ab6f0c1b60fcb).
Laravel 10.36 expands their concrete constructors from array-only inputs to
the factory's scalar, variadic, and `Arrayable` forms via
[`aeb284959f15`](https://github.com/laravel/framework/commit/aeb284959f15f8a5eb79eef5b29734bfd7c1ccbc).

The extension obtains one analyzed-project Laravel version from the
matching Composer installed-package dataset, falling back to
`composer.lock` when runtime package data for that project root is
unavailable. It passes that version through every inference entry point,
retains the broad historical behavior before each boundary, and narrows
the type after it. Missing, malformed, and unsupported versions remain
conservative rather than silently inheriting a version from an unrelated
Composer root loaded into PHPStan.

## Audited releases

| Profile | Constraint | PHP floor | Recorded release | Upstream commit |
| --- | --- | --- | --- | --- |
| `10.0.0` | `10.0.0` | 8.1 | 10.0.0 | [`be2ddb5c31b0`](https://github.com/laravel/framework/commit/be2ddb5c31b0b9ebc2738d9f37a9d4c960aa3199) |
| `10.32.1` | `10.32.1` | 8.1 | 10.32.1 | [`b30e44f20d24`](https://github.com/laravel/framework/commit/b30e44f20d244f7ba125283e14a8bbac167f4e5b) |
| `10.33.0` | `10.33.0` | 8.1 | 10.33.0 | [`4536872e3e5b`](https://github.com/laravel/framework/commit/4536872e3e5b6be51b1f655dafd12c9a4fa0cfe8) |
| `10.34.0` | `10.34.0` | 8.1 | 10.34.0 | [`92b78fdd1f38`](https://github.com/laravel/framework/commit/92b78fdd1f386425a88f443a728efd176c666244) |
| `10-latest` | `^10.0` | 8.1 | 10.50.3 | [`74e222cee687`](https://github.com/laravel/framework/commit/74e222cee687f957d95aaadddae69270e3205cf7) |
| `11.0.0` | `11.0.0` | 8.2 | 11.0.0 | [`6089f679d6d2`](https://github.com/laravel/framework/commit/6089f679d6d29e6071a6448ed5e96de02e57fedb) |
| `11.22.0` | `11.22.0` | 8.2 | 11.22.0 | [`868c75beacc4`](https://github.com/laravel/framework/commit/868c75beacc47d0f361b919bbc155c0b619bf3d5) |
| `11.23.0` | `11.23.0` | 8.2 | 11.23.0 | [`576f6f5d63f6`](https://github.com/laravel/framework/commit/576f6f5d63f68afb36dc062e728e717ddeb1a4aa) |
| `11-latest` | `^11.0` | 8.2 | 11.55.1 | [`8d786e25c5fb`](https://github.com/laravel/framework/commit/8d786e25c5fb41eb472e86b465b328b494a0da89) |
| `12.0.0` | `12.0.0` | 8.2 | 12.0.0 | [`bd8aeb64d3f9`](https://github.com/laravel/framework/commit/bd8aeb64d3f9fa4b11690d702bdf289f5f32ae97) |
| `12.21.0` | `12.21.0` | 8.2 | 12.21.0 | [`ac8c4e73bf1b`](https://github.com/laravel/framework/commit/ac8c4e73bf1b5387b709f7736d41427e6af1c93b) |
| `12.22.0` | `12.22.0` | 8.2 | 12.22.0 | [`6ab00c913ef6`](https://github.com/laravel/framework/commit/6ab00c913ef6ec6fad0bd506f7452c0bb9e792c3) |
| `12.39.0` | `12.39.0` | 8.2 | 12.39.0 | [`1a6176129ef2`](https://github.com/laravel/framework/commit/1a6176129ef28eaf42b6b4a6250025120c3d8dac) |
| `12.40.0` | `12.40.0` | 8.2 | 12.40.0 | [`3159215d904a`](https://github.com/laravel/framework/commit/3159215d904a2b04c5b903bce0328d54f1688d0f) |
| `12-latest` | `^12.0` | 8.2 | 12.66.0 | [`82a53323c701`](https://github.com/laravel/framework/commit/82a53323c701a668f9054cbeb1d6b6cdbb6a5e10) |
| `13.0.0` | `13.0.0` | 8.3 | 13.0.0 | [`3e33f431a053`](https://github.com/laravel/framework/commit/3e33f431a05365d008742ff8001b92641086d5f8) |
| `13.3.0` | `13.3.0` | 8.3 | 13.3.0 | [`118b7063c44a`](https://github.com/laravel/framework/commit/118b7063c44a2f3421d1646f5ddf08defcfd1db3) |
| `13.4.0` | `13.4.0` | 8.3 | 13.4.0 | [`912de244f88a`](https://github.com/laravel/framework/commit/912de244f88a69742b76e8a2807f6765947776da) |
| `13.20.0` | `13.20.0` | 8.3 | 13.20.0 | [`b9d1bccad5fb`](https://github.com/laravel/framework/commit/b9d1bccad5fbc32578dca22566bb11e7c0e545d7) |
| `13.21.0` | `13.21.0` | 8.3 | 13.21.0 | [`d1e02ce7b7e2`](https://github.com/laravel/framework/commit/d1e02ce7b7e25146177a1a0137c37bccb32d26d3) |
| `13.23.0` | `13.23.0` | 8.3 | 13.23.0 | [`92a707229148`](https://github.com/laravel/framework/commit/92a707229148e57f08a249211c8a5a194159c619) |
| `13.24.0` | `13.24.0` | 8.3 | 13.24.0 | [`6d481710375d`](https://github.com/laravel/framework/commit/6d481710375d2aa67656922ef760cdd2b18bcfe0) |
| `13-latest` | `^13.0` | 8.3 | 13.25.0 | [`ed36fe882bd4`](https://github.com/laravel/framework/commit/ed36fe882bd4eed4e6ff75343cbad8dbda03fdba) |

The `*-latest` constraints intentionally float in CI. Their committed
baselines record the releases above. A later patch release that changes any
probed contract fails the baseline test and requires an explicit review rather
than silently inheriting the old inference assumption.

The runner identifies the installed release through Composer package metadata,
not `Application::VERSION`. Laravel's `v12.22.0` package still contains the
stale application constant `12.21.0`; using that constant would mislabel the
exact release on the strict-integer boundary.

## Method

[`InferenceAuditCases`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/tests/Support/InferenceAuditCases.php) defines one
deterministic input and rule set for each adversarial probe. For every case,
[`InferenceAudit`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/tests/Support/InferenceAudit.php):

1. runs the rule through Laravel's own `Validation\Factory`;
2. records whether validation failed, threw, or returned validated output;
3. converts successful output into a PHPStan type;
4. resolves the same rule with this extension and the exact installed Laravel
   version; and
5. records whether the inferred type is a supertype of Laravel's actual
   output.

The audit uses PHPStan's `isSuperTypeOf()` relation for this containment check.
Its `accepts()` relation also models PHP parameter coercions, so it can report
that `float` accepts an `int` even though an inferred `float` does not literally
describe an integer runtime value. That distinction matters in both directions
of this audit.

The committed JSON files under
[`tests/fixtures/version-audit`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/tests/fixtures/version-audit) are runtime
contract snapshots, not hand-authored expected types. The
[`inference-audit.php`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/scripts/inference-audit.php) runner can load an
isolated Composer installation of Laravel before the project's own autoloader,
which lets the same extension build be checked against exact framework
releases.

The runner deliberately normalizes objects, resources, non-finite floats, and
the array-to-string warning into stable data. Unrelated PHP engine and
dependency deprecations are not part of the Laravel validation contract and
are omitted from the snapshot.

An additional property suite exhaustively visits three finite named catalogs:
1,620 scalar-presence and native-representation cases, 180 nested-projection
and wildcard cases, and 280 cross-field presence and exclusion cases. Each
catalog requires at least 30 percent of its cases to produce successful Laravel
output so a mostly rejected domain cannot pass vacuously. Every successful
case runs the same runtime-to-static containment check without creating a
snapshot. Exhausting these bounded catalogs strengthens the observed evidence;
it does not prove universal soundness outside them.

## Inventory

| Area | Representative probes | Result |
| --- | --- | --- |
| Accepted and declined values | `accepted.true`, `accepted_if.inactive`, `declined.false`, `declined_if.inactive` | No observed release difference |
| Boolean and numeric predicates | `boolean.*`, `integer.*`, `numeric.*`, `digits*`, `decimal`, `multiple_of`, `max_digits`, `min_digits`, and fresh fluent numeric builders | `integer:strict` begins at 12.22; the exact 11.42 and 12.55 builder cutovers are pinned by the linked upstream commits, tag history, and focused static fixtures, while cross-profile PHPUnit confirms representative behavior before and after them |
| Text predicates | `alpha*`, `ascii.*`, `string`, `lowercase`, `uppercase`, `regex`, `not_regex`, and fresh fluent string builders | `ascii` boundary at 13.4; the exact 12.55 `StringRule` cutover is pinned by the linked upstream commit, tag history, and focused static fixtures, while cross-profile PHPUnit confirms representative behavior before and after it |
| Hex colors | valid strings, compatible `Stringable`, optional blank input, and unsupported-rule behavior | Rule introduction at 10.33; native-string boundary at 13.4, covered by the cross-profile PHPUnit suite |
| File extensions | valid and failed uploads, a compatible Symfony file subclass, invalid native values, optional blank input, and unsupported-rule behavior | `extensions` begins at Laravel 10.34, covered by the cross-profile PHPUnit suite rather than the portable audit corpus |
| Character encoding | strings, arrays, scalars, `Stringable`, `null`, valid and invalid file contents, invalid uploads and parameters, and unsupported-rule behavior | `encoding` begins at Laravel 12.40, covered by the cross-profile PHPUnit suite rather than the portable audit corpus |
| JSON, dates, and membership | `json.*`, `date*`, comparisons, fresh fluent date builders, scalar `in`, and fresh `Rule::in()` / `Rule::notIn()` builders and exact constructors | Scalar behavior is stable; the date builder begins in 11.40, chains become usable in rule lists at 11.41 and standalone at 11.43.2, and `dateTime` plus now-relative predicates arrive in 12.44; enum-valued membership builders begin in 10.21.1, while scalar and variadic direct constructors begin in 10.36. Builder boundaries are pinned by upstream commits, tag history, focused static fixtures, manual cross-profile runtime probes, and cross-profile PHPUnit |
| Network and identifiers | `email`, `ip`, `ipv4`, `ipv6`, `mac_address`, `timezone`, `url`, `uuid`, `ulid` | No observed release difference |
| Arrays and projection | bare and keyed arrays, parameterized-parent preservation, required array offsets, numeric rule keys, nested child projection, wildcards, parent-plus-child rules, and fresh `Rule::array()` builders | Numeric rule-key boundary at Laravel 12; `Rule::array()` begins at Laravel 11.7 and `list` reconstruction changes at Laravel 11.23, covered by the cross-profile PHPUnit suite |
| Array-only predicates | required and optional values, non-array rejection, preserved associative and nested arrays | `contains`, `in_array_keys`, and `doesnt_contain` begin at Laravel 11.8, 12.16, and 12.22, covered by the cross-profile PHPUnit suite rather than the portable audit corpus |
| Allowed array keys | permitted subsets, extra-key rejection, numeric keys, empty parameters, blank bypass, nested rules, and the fluent builder | `array_keys` begins at Laravel 13.24, covered by the cross-profile PHPUnit suite rather than the portable audit corpus |
| Enum objects | pure, string-backed, and integer-backed cases; weakly coerced preserved values; optional blanks; and literal filters | Base behavior is stable across Laravel 10–13; `only` and `except` begin in 10.46, covered by the cross-profile PHPUnit suite rather than the portable audit corpus |
| Image dimensions | a real one-pixel image file, incorrect dimensions, native path strings, optional blanks, nullable input, and fresh `Dimensions` builders | No difference observed for the native value family; the extended ratio builder methods begin at Laravel 11.23 and are covered by exact-version static fixtures and the cross-profile PHPUnit suite |
| Presence and conditions | optional blanks, nullable, present, missing, zero-match wildcard parent preservation, confirmed, `required_if`, `exclude_if`, and literal-boolean `RequiredIf` / `ExcludeIf` / `ProhibitedIf` builders | No observed release difference; the builders' true rules and false empty-rule projection markers are covered by cross-profile PHPUnit |
| Default HTTP middleware | password-path trimming before validation | Laravel 10 versus 11+ boundary covered by the cross-profile PHPUnit suite |
| Static entry points | facade, factory, request, controller, helper, and validator unions | Covered by the existing PHPStan fixture suite |
| Environment-dependent behavior | other file and image metadata, database, DNS, password-rule service checks, custom rules | Catalogued but not executed by this portable audit |

The inventory focuses on rules for which the extension currently narrows a
type, plus representative non-narrowing and structural rules that can change
presence or projection. It is intentionally adversarial: values such as
integral floats, booleans, `Stringable` objects, resources, blank strings,
missing wildcard parents, and undeclared nested keys are included because
ordinary happy-path strings do not reveal Laravel's native output contract.

## Findings

### Laravel 12 preserves top-level numeric rule keys

Laravel 10 and 11 pass parsed rules through `array_merge_recursive()` when
adding them to the validator. PHP reindexes numeric keys during that merge, so
this apparently literal rule path:

```php
[
    3 => 'required|string',
]
```

actually validates and returns key `0`. Multiple sparse keys such as `3` and
`5` become `0` and `1` in encounter order. Negative integer keys are reindexed
the same way. Laravel 12 replaced that merge with per-key assignment in
[`83e28d065b7b`](https://github.com/laravel/framework/commit/83e28d065b7bc53f3e53a5c188806844eee30161),
so Laravel 12 and 13 preserve the original integer keys.

The corresponding sound types are therefore version-dependent:

```php
// Laravel 10 and 11
array{string}

// Laravel 12 and 13
array{3: string}
```

This applies only to literal integer keys in the top-level rule map. Numeric
segments in string paths such as `items.3.name` remain literal path segments
on every supported release. When the Laravel version is unavailable or
unsupported, the extension uses a general array shape rather than guessing
which output key Laravel will produce.

### Laravel 12.22 changes `integer:strict`

Laravel 12.21 accepts and preserves both `'1'` and `1.0` for this rule:

```php
['value' => 'required|integer:strict']
```

Before Laravel 12.22, the parameter is ignored and validation has the same
coercive behavior as the ordinary `integer` rule. Laravel 12.22 adds strict
mode and rejects both non-integer values. Native `int` values continue to pass
and are preserved.

For Laravel 10 through 12.21, the inferred value type remains:

```php
float|int|numeric-string|Stringable|true
```

That union is required for Laravel 10, 11, and 12.0 through 12.21. From Laravel
12.22 through the supported 13.x releases, the extension now infers `int`. If
the analyzed version is unavailable or outside the supported range, it keeps
the union.

### Laravel 13.4 changes `ascii`

Laravel 13.3 retains the coercive behavior inherited from Laravel 10 through
12. The `ascii` predicate casts values to strings and `validated()` preserves
the originals. The audit reproduces successful integer, boolean, `null`,
`Stringable`, resource, and warning-tolerant array outputs.

Laravel 13.4 adds a native `is_string()` guard and rejects every one of those
non-string inputs. The behavior remains string-only through the pinned Laravel
13.23 release.

For Laravel 10 through 13.3, the inferred value type remains:

```php
array|bool|float|int|resource|string|Stringable|null
```

For Laravel 13.4 through the supported 13.x releases, the extension now infers
`string` before applying presence and blank-value behavior. The broad union is
not an invented analyzer edge case on older versions; it is the set of native
categories Laravel can successfully return. It remains the safe fallback when
version context is unavailable.

### `hex_color` has two release boundaries

Laravel 10.32.1 has no `validateHexColor()` method. A non-blank value reaches
Laravel's missing validator method and throws `BadMethodCallException`, while
an optional blank string can bypass the unknown non-implicit rule and remain
in `validated()`. Applications on those releases may also register their own
rule under the same name. The extension therefore retains `mixed` before
Laravel 10.33 rather than inventing a contract for an absent built-in rule.

Laravel 10.33 adds this implementation:

```php
return preg_match('/^#(?:(?:[0-9a-f]{3}){1,2}|(?:[0-9a-f]{4}){1,2})$/i', $value) === 1;
```

The weak internal string conversion accepts a compatible `Stringable` object,
and `validated()` preserves that object instead of returning its string form.
Laravel retains this behavior through 13.3, so a required field needs the
following structural type:

```php
non-empty-string|Stringable
```

Laravel 13.4 adds an `is_string()` guard. From that release onward, the sound
required value type narrows to `non-empty-string`. Optional raw validator input
still includes blank strings because Laravel skips this non-implicit rule for
blank values; HTTP normalization can remove that branch when enabled in the
extension.

### `extensions` begins in Laravel 10.34

Laravel 10.33 and earlier have no `validateExtensions()` method. A non-blank
value therefore reaches the missing validator method unless the application
registers a custom rule with that name. Inference remains `mixed` before
Laravel 10.34 rather than assigning the later built-in contract to an
open-ended extension point.

Laravel 10.34 adds a file predicate built around this sequence:

```php
if (! $this->isValidFileInstance($value)) {
    return false;
}

return in_array(strtolower($value->getClientOriginalExtension()), $parameters);
```

The method also has a family-wide PHP-upload block for `php`, `php3` through
`php8`, `phtml`, and `phar`. That block is disabled only when the literal `php`
parameter appears anywhere in the rule; the ordinary extension allow-list is
then still applied. Consequently, `extensions:phtml` rejects an upload named
`evil.phtml`, while `extensions:phtml,php` accepts it. For an `UploadedFile`,
the block inspects the client-supplied extension. For another Symfony `File`,
it inspects the physical path extension instead.

Successful validation preserves the original object. Laravel's initial guard
establishes only that it is a Symfony `File`; it does not establish that the
value is specifically an `UploadedFile`. A compatible `File` subclass that
supplies `getClientOriginalExtension()` can pass, while a plain `File` with a
non-PHP path reaches an undefined-method error. The sound useful type is
therefore Symfony `File`, not the narrower `UploadedFile`.

Focused probes cover a valid upload, a compatible file subclass, the
plain-file error path, the PHP-family block and its `php` escape hatch, failed
uploads, mismatched and case-sensitive parameters, absent parameters, invalid
native values, optional blank bypass, and preservation in `validated()`.
Before 10.34 and when version context is unavailable, the type remains
`mixed`. The rule was introduced by
[`4ae1ef68e4e4`](https://github.com/laravel/framework/commit/4ae1ef68e4e443499dceaf6bb3605863df8e8b4e).

### `encoding` begins in Laravel 12.40

Laravel 12.39 and every earlier supported release have no
`validateEncoding()` method. Non-blank use therefore reaches Laravel's missing
validator method unless an application has registered its own rule under that
name. The extension retains `mixed` before Laravel 12.40 rather than assigning
the later built-in contract to that open extension point.

Laravel 12.40 adds a rule that first verifies the requested encoding name and
then delegates to PHP:

```php
return mb_check_encoding(
    $value instanceof File ? $value->getContent() : $value,
    $parameters[0],
);
```

The apparent text predicate consequently has a much broader native output
contract. PHP's weak parameter coercion accepts booleans, integers, floats,
and compatible `Stringable` objects in addition to strings. Arrays are passed
through directly, while Symfony `File` objects are checked through their
contents. Laravel preserves the original input after success rather than the
coerced string, array elements, or file content that PHP actually inspected.

An explicit `null` also succeeds and remains in `validated()`, although current
supported PHP releases emit the deprecation associated with
`mb_check_encoding(null, ...)`. Resources and arbitrary non-stringable objects
cannot be passed to the native function. Array validity depends recursively on
its contents, so the useful sound array branch remains `array<mixed>` rather
than claiming a narrower element type.

The resulting structural value type is:

```php
array<mixed>|bool|float|int|string|Stringable|null
```

Symfony `File` is contained by the `Stringable` branch, even though Laravel
checks file contents rather than the object's string path. Laravel also marks
`encoding` as a file rule, so a failed `UploadedFile` is rejected before its
contents are inspected. Missing parameters and unknown encoding names throw
`InvalidArgumentException`; optional blank strings can still bypass this
non-implicit rule.

Focused probes cover the successful native categories, valid and invalid file
contents, failed uploads, invalid arrays, excluded object categories,
parameter errors, blank bypass, and preservation in `validated()`. The exact
12.39 and 12.40 profiles lock the introduction boundary, and the implementation
is unchanged through the pinned Laravel 13 release. The rule was introduced by
[`660c653024d0`](https://github.com/laravel/framework/commit/660c653024d01b4d36691730e110c987f61aee18).

### `base64` begins in Laravel 13.21

Laravel 13.20 and every earlier supported release have no
`validateBase64()` method. Non-blank input therefore reaches Laravel's missing
validator method and throws `BadMethodCallException`, although optional blank
strings and nullable `null` values bypass the unknown non-implicit rule and can
still remain in `validated()`. Because applications may register a custom rule
under the absent built-in name, the extension retains `mixed` through 13.20.

Laravel 13.21 adds an explicitly native-string implementation:

```php
if (! is_string($value) || $value === '') {
    return false;
}

$decoded = base64_decode($value, true);

return $decoded !== false && base64_encode($decoded) === $value;
```

Runtime probes against Laravel 10.0, 11.0, 12.0, 13.0, 13.20, and 13.21
confirm the boundary. From 13.21 onward, successful non-blank values are
preserved native strings, while integers, floats, booleans, arrays, and
compatible `Stringable` objects fail. The sound required value type is
therefore `non-empty-string`; optional raw validator input still includes the
blank-string bypass.

### The `Rule::array()` builder begins in Laravel 11.7

Laravel's underlying `array` string rule predates every supported release, but
the `Rule::array()` factory and its `ArrayRule` object do not. They were added
in Laravel 11.7 by
[`8c684a222143`](https://github.com/laravel/framework/commit/8c684a222143fee9f9eff53b544c1f54a27b9e9e).
Before that release, an application could still provide a macro under the same
method name, so analysis cannot assume Laravel's later builder contract.

The builder preserves a distinction that matters to `validated()` projection.
Both `Rule::array()` and `Rule::array([])` serialize to the bare `array` rule,
which lets nested child rules reconstruct the returned parent. A non-empty key
list serializes to a parameterized rule such as `array:name,email`; Laravel
then preserves the complete permitted parent rather than rebuilding it solely
from validated descendants. The builder therefore affects output shape, not
only the accepted value family.

Omitting the argument is also observably different from passing `null`.
Laravel's factory forwards the actual argument list through `func_get_args()`:
no argument produces bare `array`, while explicit `null` produces `array:` and
permits only the empty-string key. Focused runtime coverage checks these forms,
scalar and enum keys, extra-key rejection, and nested projection across the CI
Laravel profiles.

The serialization is lossy for some key strings. `ArrayRule` joins keys with
unquoted commas, and Laravel then parses the resulting rule parameters as CSV.
For example, `Rule::array(['a,b'])` becomes `array:a,b` and permits `a` and `b`,
not a literal `a,b` key. The expression resolver reproduces that round trip
rather than assigning the builder's pre-serialization key list a prettier but
false meaning. Fresh constant factory calls and exact `ArrayRule` construction
are recovered from 11.7 onward; assigned objects, subclasses, dynamic
construction, dynamic arguments, and earlier versions stay broad. Float keys
also stay broad because PHP's configurable runtime `precision` can change the
serialized key after analysis.

### `array_keys` begins in Laravel 13.24

Laravel 13.23 and every earlier supported release have no
`validateArrayKeys()` method. As with other absent built-in names, non-blank
use either reaches Laravel's missing validator method or an application-defined
rule registered under that name. Inference therefore remains `mixed` before
13.24.

Laravel 13.24 adds a predicate that requires a native array and rejects keys
outside the rule parameters. It does not require any listed key to exist, so
this rule:

```php
['value' => 'required|array_keys:name,email']
```

accepts the empty array and either permitted subset. Laravel preserves the
original array, including its values, so the corresponding structural type is:

```php
array{name?: mixed, email?: mixed}
```

The focused runtime test also confirms that canonical numeric key parameters
such as `0` use integer offsets, while a non-canonical numeric-looking key such
as `01` remains a string offset. Optional blank strings bypass the
non-implicit rule as usual. Nested child rules do not turn `array_keys` into a
parent-reconstruction rule: the complete permitted parent remains in
`validated()`.

Combining an allowed-key rule with `list` produces another non-obvious
intersection. A list can use only consecutive integer keys beginning at zero.
`array_keys:name|list` therefore accepts only the empty array, while
`array_keys:0,2|list` accepts the empty array and a one-element list at key
zero. The resolver models the longest permitted consecutive prefix directly;
otherwise PHPStan can collapse the real empty-array overlap between an
optional-key shape and `list` to `never`. The same repair applies to the
pre-existing `array:name|list` form.

The two empty-looking spellings are observably different. Bare `array_keys`
throws `InvalidArgumentException` when it is evaluated because the rule
requires a parameter. `array_keys:` supplies one empty parameter and permits
only the empty-string array key. The extension models both contracts from
13.24 and remains broad when the Laravel version is unavailable or unsupported.

The rule was introduced by
[`91eee4b8a7c4`](https://github.com/laravel/framework/commit/91eee4b8a7c4f4301700fa359de92898528bb917).
`Rule::arrayKeys()` and direct `ArrayKeys` construction serialize to the same
string contract at runtime, including the empty-key-list case. Fresh exact
expressions with statically visible scalar or enum keys recover that contract
directly. Assigned objects, subclasses, dynamic construction, and dynamic or
`Arrayable` arguments still lose the builder's key state and remain opaque.
Float keys likewise stay opaque because runtime `precision` can change their
serialized spelling.

### Three array predicates have mid-major introductions

Laravel adds `contains` in 11.8, `in_array_keys` in 12.16, and
`doesnt_contain` in 12.22. The corresponding changes are
[`4815757851f0`](https://github.com/laravel/framework/commit/4815757851f0d32f4659cc2b4ed8561c513d64cc),
[`8b9f434868d1`](https://github.com/laravel/framework/commit/8b9f434868d18feb764b9dbbfa7fba12c09e185d),
and
[`ad138584ef0b`](https://github.com/laravel/framework/commit/ad138584ef0bceaf6ccd8e3e27d66bb339438562).
Before each release, non-blank use reaches Laravel's missing validator method
unless the application has registered a custom rule with the same name.
Inference therefore remains `mixed` before the built-in contract exists.

All three built-in methods first require `is_array($value)`. They differ in
which members or keys make that array pass, but successful validation retains
the original array and its arbitrary keys and values. A required field can
therefore narrow to `array<mixed>` after the appropriate boundary. Optional
raw input still includes Laravel's blank-string bypass. Focused runtime tests
also reject scalars, `Stringable` objects, and `ArrayObject`, and confirm that
associative arrays and nested values are preserved unchanged.

Laravel 12.16 also adds the fresh `Rule::contains()` factory and `Contains`
object. Laravel 12.22 adds the corresponding `Rule::doesntContain()` factory
and `DoesntContain` object with the rule itself. Exact inline factories and
direct construction recover the same array-only output contract. Assigned
objects and dynamic expressions remain opaque because their serialized state
is no longer available at the rule expression.

### HTTP normalization also has a known major boundary

Laravel's default `TrimStrings` middleware excludes password-related paths in
Laravel 11 and later. Laravel 10 trims them. In optional HTTP-normalization
mode, the extension now removes the blank-string branch for those paths on
Laravel 10 and preserves it on Laravel 11 through 13. If a supported
full-framework version is unavailable, it preserves the branch
conservatively; an `illuminate/validation` component version alone does not
establish the application's middleware behavior.

This is already covered by
`LaravelInferenceTest::testDefaultPasswordTrimExceptionVariesByLaravelMajor`
and the normalized request PHPStan fixtures. The shared version context refines
this behavior alongside rule inference rather than treating it as an unrelated
special case.

### Laravel 11.23 changes `list` output projection

Laravel added the `list` value predicate in 11.0.3, but it initially remained
different from `array` during `validated()` projection. Through Laravel 11.22,
a literal `list` parent with nested rules is copied in full. Laravel 11.23 adds
`list` to the parent-reconstruction condition:

```php
(in_array('array', $rules) || in_array('list', $rules))
```

Consequently, this successful validation changes output at the patch boundary:

```php
$input = ['items' => [['name' => 'Ada']]];
$rules = [
    'items' => 'required|list',
    'items.*.id' => 'missing',
];

// Laravel 11.22: $input
// Laravel 11.23+: []
```

The change was introduced by
[`d8aabd9697e2`](https://github.com/laravel/framework/commit/d8aabd9697e240df69c2cca26c05308db4b06020).
Inference therefore treats a bare `list` as a reconstruction rule only from
Laravel 11.23. Unknown or unsupported versions retain both the preserved-parent
and reconstructed-output possibilities. Zero wildcard matches remain a
separate branch: without any concrete descendant rule, Laravel keeps the raw
parent even after the reconstruction change.

### Parameterized `array` rules preserve the parent value

Laravel's nested-output reconstruction distinguishes the literal `array` rule
from allowed-key forms such as `array:name`. With a literal `array`, Laravel
can omit the raw parent value and rebuild validated output from matching child
rules. With `array:name`, the parameterized rule rejects undeclared keys but
does not trigger that reconstruction path, so Laravel preserves the complete
permitted parent value.

For example, every supported profile preserves `name` here even though the
only child rule requires `child` to be missing:

```php
Validator::make(
    ['payload' => ['name' => 'Ada']],
    [
        'payload' => 'required|array:name',
        'payload.child' => 'missing',
    ],
)->validated();

// ['payload' => ['name' => 'Ada']]
```

Laravel 11.23 and later also recognize a literal `list` rule when deciding
whether to reconstruct nested output; that does not make parameterized
`array` rules equivalent to bare `array`. The extension therefore preserves
the allowed-key parent shape around nested rules instead of projecting it
away. The deterministic audit and the structural property catalog both cover
this distinction.

### No additional portable boundary was observed

The portable case snapshot is identical at Laravel 10.0, 10.32, and 10.33,
across Laravel 10 and 11, from Laravel 11 to Laravel 12.0, and from Laravel
12.0 to 12.21. After accounting for the numeric-key, strict-integer, and ASCII
boundaries above, later snapshots are also identical within their covered
ranges. The focused `hex_color`, array-predicate, and `list` witnesses are
intentionally separate: invoking a rule before its introduction throws, while
the portable corpus cannot exercise `list` projection on releases where the
rule does not exist.

Across 2,412 case executions on the eighteen profiles, Laravel returns 1,682
successful outputs. Every one is contained in the extension's inferred type.
There are no `observed-unsound`, `inference-error`, or `runtime-exception`
classifications. Failed inputs are recorded as `no-successful-output`; only the
preservation-only subset described below is also used as reverse precision
evidence.

This result supports the current conservative unions. It does not establish
that unprobed rule interactions, application extensions, or future Laravel
patches are sound.

## Reverse-direction precision audit

Sound inference requires Laravel's successful output set to be contained in the
inferred type. Exact inference would additionally require the inferred type to
contain nothing Laravel can never return. The second relation fails often, so
the audit now measures it separately rather than treating imprecision as a
conformance failure.

Of the 134 portable cases, 103 are marked as preservation-only precision
probes.
For those cases, the supplied data has the same shape and native values that
`validated()` would return if validation succeeded. The audit verifies that
the candidate is literally contained in the inferred type and then classifies
Laravel's behavior:

- `observed-realizable`: Laravel returned that inferred inhabitant unchanged;
- `observed-imprecision`: the inferred type contains the candidate, but Laravel
  rejected it;
- `candidate-outside-inference`: inference already excludes the rejected
  candidate; or
- `candidate-indeterminate`: PHPStan could not establish either relation.

Projection, exclusion, wildcard, and conditional cases are not reverse probes
unless raw input is a defensible candidate output. Treating every rejected
input as an impossible output would otherwise confuse input filtering with
output projection.

The aggregate precision results are:

| Laravel profiles | Realizable | Observed imprecision | Outside inference | Not reverse-probed |
| --- | ---: | ---: | ---: | ---: |
| 10.0 through 12.21 | 71 | 22 | 10 | 31 |
| 12.22 through 13.3 | 67 | 22 | 14 | 31 |
| 13.4 and later | 59 | 22 | 22 | 31 |

Only twelve witnesses change classification by Laravel release:

| Rule | Witnesses realized on older releases | Releases where they become removable |
| --- | --- | --- |
| `integer:strict` | numeric string, integral float, `true`, compatible `Stringable` | Laravel 12.22+ |
| `ascii` | integer, float, `true`, `false`, `null`, `Stringable`, resource, array | Laravel 13.4+ |

No other reverse probe changed classification across the pinned profiles. The
four strict-integer witnesses and eight ASCII witnesses now move from
`observed-imprecision` to `candidate-outside-inference` at their verified
boundaries. This confirms that version-aware narrowing removed exactly the
release-dependent branches identified by the runtime differential audit; it
did not reveal another major or minor boundary in the portable corpus.

The reverse audit exposed two version-independent branches that could be
removed immediately:

- `required|nullable|string` no longer includes `null`, and the output key is
  required regardless of rule order. Every pinned profile rejects both missing
  and null values under an unconditional `required` rule.
- `regex` and `not_regex` no longer include booleans. Every pinned profile
  across the supported releases requires a string or numeric value before
  applying the expression.

The remaining invariant imprecision witnesses have less direct causes:

- Rules such as `email`, `date`, `multiple_of`, digit limits, regular
  expressions, and scalar `in` necessarily accept fewer values than their
  native PHP supertypes can describe. Numeric string-rule `in` parameters now
  remove the broad integer branch when their native integer equivalence class
  is safely representable, so `in.other_integer` is classified as
  `candidate-outside-inference`; its float, numeric-string, and `Stringable`
  equivalence classes remain broader than PHPStan can express. Float-bearing
  `Rule::in()` builders additionally retain broad `int` because runtime PHP
  precision can change their serialized parameter. Other rules may support
  similar parameter-aware refinements or require predicates PHPStan cannot
  express.
- Optional blank-string bypass currently contributes all `string` values even
  though only blank strings bypass the remaining predicates. PHPStan has no
  ordinary native type for Laravel's complete blank-string set.
- Broad float and `Stringable` branches remain necessary when some inhabitants
  pass and others fail, such as integral versus non-integral floats or objects
  whose string representation differs.

An `observed-imprecision` classification is therefore a review input, not an
automatic instruction to narrow. A branch is safely removable only when no
successful output in the supported context needs it.

## CI enforcement

The exhaustive Nix matrix runs every audit profile once on the PHP floor for
its Laravel major. Each profile has a committed Composer lock and an offline
Nix dependency closure, and each appears as an independent GitHub Actions job.
The deterministic audit compares the recorded contract, checks containment of
successful output, and records the reverse precision classification.

Four focused Nix checks separately run
[`date-rule-parser-audit.php`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/scripts/date-rule-parser-audit.php) against
exact Laravel 11.40.0, 11.41.0, 11.43.1, and 11.43.2 dependency closures. They
verify the otherwise easy-to-miss distinction between a Date chain nested in a
rule list and the same builder used as a standalone field rule.

A separate PHPUnit matrix runs the complete suite on every supported project
PHP version, 8.1 through 8.5. Additional complete-suite jobs install the latest
locked Laravel 11, 12, and 13 closures; the root lock supplies Laravel 10.
Separating framework-boundary evidence from PHP compatibility retains both
dimensions without multiplying them into a 70-job Cartesian matrix.

The exact boundary releases are not substitutes for the floating latest
profiles. The former preserve known historical contracts; the latter record
the newest release present when their Nix locks were refreshed. Run the
portable Composer matrix when checking for a newer patch release, then review
and refresh the corresponding lock and baseline deliberately.

## Reproducing the audit

The contributor workflow, including focused runtime cases and the relationship
between test layers, is documented in the
[testing and runtime verification guide](testing.md).

Run the installed Laravel release and print its audit result:

```sh
php scripts/inference-audit.php
```

Compare the installed release with a committed profile:

```sh
php scripts/inference-audit.php --baseline=10-latest
```

List semantic case IDs and run only the cases relevant to an investigation:

```sh
php scripts/inference-audit.php \
    --list-cases
php scripts/inference-audit.php \
    --baseline=10-latest \
    --case=present.value \
    --case=missing.absent
```

Run one or more isolated profiles with ordinary PHP and Composer. Exact
profiles are cached; floating latest profiles are refreshed:

```sh
composer test:audit:matrix -- --profile=12.21.0 --profile=12.22.0
```

Regenerate a complete baseline only after reviewing Laravel's behavior and
upstream provenance:

```sh
composer test:audit:matrix -- --profile=12.22.0 --update
```

The matrix uses disposable installations below `tmp/version-audit` and does
not modify the root Composer project. Its Nix wrapper is optional; it only
selects a compatible PHP shell before invoking the same portable runner.

Run the exhaustive bounded property catalogs with:

```sh
composer test:property
```

Every failure must be reproduced against the supported Laravel majors
and promoted into the deterministic audit or a focused runtime regression
before inference changes.

## Cross-version catalog execution

CI runs the complete finite catalogs throughout the supported Laravel/PHP
matrix. Version-specific failures must still become focused runtime or audit
cases: the catalogs establish containment over their named combinations, while
the deterministic audit preserves exact boundary evidence and upstream
provenance.

## Possible future fuzzing

A manual coverage-guided “probator” may eventually complement these bounded
properties, but only with Laravel itself as an independent differential
oracle. A useful target would compare Laravel's and this project's handling of
rule names, parameters, quoting, escaping, regular expressions, dotted paths,
and malformed rules under an explicit Laravel profile. Crash-only fuzzing of
the current small parser would provide little evidence about inference
soundness.

Such a target should remain outside mandatory CI, keep its evolving corpus and
crash artifacts in ignored scratch storage, and promote every genuine finding
into a deterministic cross-version regression. This is future work; the
project does not currently depend on a fuzzing framework.

## Version-aware implementation

`LaravelVersionContext` first reads Composer's installed-package dataset whose
root installation path matches PHPStan's working directory. This follows the
Laravel implementation actually installed for analysis and avoids trusting a
stale lockfile. When no matching dataset contains Laravel, it falls back to the
analyzed project's `composer.lock`. Both sources prefer `laravel/framework`
and fall back to `illuminate/validation` for rule-level behavior. An explicit
`phpstanLaravelValidation.laravelVersion` setting remains authoritative for
monorepos and other layouts where the working directory is not the relevant
Composer project root.

One shared context is injected into the rule parser and resolver used by
validator, facade, request, and controller inference. The parser normalizes
numeric rule keys, while the resolver specializes `integer:strict`, `ascii`,
`base64`, `encoding`, `extensions`, `hex_color`, `array_keys`, `contains`,
`in_array_keys`, `doesnt_contain`, `list` value types, `list` parent
reconstruction, fresh `Rule::array()` and `Rule::arrayKeys()` builder
extraction, fresh date-, numeric-, and string-builder extraction, strict
integer mode, and default HTTP normalization only at the verified boundaries
above.
It ignores installed-package datasets belonging to unrelated project roots,
so a globally installed tool or another registered autoloader cannot silently
select the Laravel contract. The same context contributes its effective
version and framework/component source to PHPStan's result-cache metadata,
forcing cached file results to be recomputed whenever that inference input
changes.

Auto-detection remains deliberately conservative when both installed-package
data and the lockfile are unavailable, when the authoritative installed
package has a development version without a stable numeric contract, or when
the detected Laravel major is outside the supported 10–13 range. It does not
fall back to a potentially stale lockfile after finding an installed Laravel
package whose version is unstable. A standalone `illuminate/validation`
version can select rule semantics but cannot prove that full-framework HTTP
middleware defaults apply.

The version-independent `required|nullable`, `regex`, and `not_regex`
opportunities have already been applied and remain covered by the pinned
runtime profiles. Unconditional `present` and `missing` now also refine output
presence without conflating key existence with non-blank requiredness; focused
runtime tests cover their named, nested, blank-value, and wildcard behavior
through the pinned profile audits and supported-major PHPUnit jobs.

Environment-dependent rules should remain conservative unless their runtime
services can be replaced with deterministic test doubles and their static
contract can be stated without booting arbitrary application behavior.

Last reviewed: 2026-08-14.
