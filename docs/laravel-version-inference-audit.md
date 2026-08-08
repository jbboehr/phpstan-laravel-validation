# Laravel-version inference audit

This audit checks whether Laravel's validation behavior changes across the
releases supported by `phpstan-laravel-validation`, and whether the extension's
version-aware inferred types continue to contain every successful output
observed at those releases.

No successful output in the portable audit corpus falls outside the inferred
type. Three release boundaries change contracts exercised by that corpus:

- top-level numeric rule keys are reindexed by Laravel 10 and 11 but preserved
  from Laravel 12;
- `integer:strict` begins enforcing native integers in Laravel 12.22; and
- `ascii` begins requiring a native string in Laravel 13.4.

The cross-profile runtime suite records three additional rule boundaries
outside that portable corpus: Laravel adds `hex_color` in 10.33, Laravel 13.4
stops `hex_color` from accepting compatible `Stringable` objects, and Laravel
adds the native-string-only `base64` rule in 13.21.

The extension now obtains one analyzed-project Laravel version from the
matching Composer installed-package dataset, falling back to `composer.lock`
when runtime package data for that project root is unavailable. It passes that
version through every inference entry point, retains the broad historical
behavior before each boundary, and narrows the type after it. Missing,
malformed, and unsupported versions remain conservative rather than silently
inheriting a version from an unrelated Composer root loaded into PHPStan.

This is an audit result, not a proof of universal soundness. It covers the
portable rule families and rule interactions listed below. Laravel behavior
that depends on files, databases, DNS, password services, image metadata, or
application-defined validation extensions remains outside the deterministic
corpus.

## Audited releases

The audit pins the first release of every supported major, the current latest
release, and both sides of every semantic transition used by version-aware
inference.

| Profile | Constraint | PHP floor | Recorded release | Upstream commit |
| --- | --- | --- | --- | --- |
| `10.0.0` | `10.0.0` | 8.1 | 10.0.0 | [`be2ddb5c31b0`](https://github.com/laravel/framework/commit/be2ddb5c31b0b9ebc2738d9f37a9d4c960aa3199) |
| `10.32.1` | `10.32.1` | 8.1 | 10.32.1 | [`b30e44f20d24`](https://github.com/laravel/framework/commit/b30e44f20d244f7ba125283e14a8bbac167f4e5b) |
| `10.33.0` | `10.33.0` | 8.1 | 10.33.0 | [`4536872e3e5b`](https://github.com/laravel/framework/commit/4536872e3e5b6be51b1f655dafd12c9a4fa0cfe8) |
| `10-latest` | `^10.0` | 8.1 | 10.50.2 | [`3ff39b7a9b83`](https://github.com/laravel/framework/commit/3ff39b7a9b83e633383ec9b019827ed54b6d38bc) |
| `11.0.0` | `11.0.0` | 8.2 | 11.0.0 | [`6089f679d6d2`](https://github.com/laravel/framework/commit/6089f679d6d29e6071a6448ed5e96de02e57fedb) |
| `11-latest` | `^11.0` | 8.2 | 11.55.0 | [`dc7ec34ae95b`](https://github.com/laravel/framework/commit/dc7ec34ae95bacf4a63b96ec81482b4f3e702289) |
| `12.0.0` | `12.0.0` | 8.2 | 12.0.0 | [`bd8aeb64d3f9`](https://github.com/laravel/framework/commit/bd8aeb64d3f9fa4b11690d702bdf289f5f32ae97) |
| `12.21.0` | `12.21.0` | 8.2 | 12.21.0 | [`ac8c4e73bf1b`](https://github.com/laravel/framework/commit/ac8c4e73bf1b5387b709f7736d41427e6af1c93b) |
| `12.22.0` | `12.22.0` | 8.2 | 12.22.0 | [`6ab00c913ef6`](https://github.com/laravel/framework/commit/6ab00c913ef6ec6fad0bd506f7452c0bb9e792c3) |
| `12-latest` | `^12.0` | 8.2 | 12.64.0 | [`727a8ea2949c`](https://github.com/laravel/framework/commit/727a8ea2949c23ca8b5316b86a00984b6017b7a0) |
| `13.0.0` | `13.0.0` | 8.3 | 13.0.0 | [`3e33f431a053`](https://github.com/laravel/framework/commit/3e33f431a05365d008742ff8001b92641086d5f8) |
| `13.3.0` | `13.3.0` | 8.3 | 13.3.0 | [`118b7063c44a`](https://github.com/laravel/framework/commit/118b7063c44a2f3421d1646f5ddf08defcfd1db3) |
| `13.4.0` | `13.4.0` | 8.3 | 13.4.0 | [`912de244f88a`](https://github.com/laravel/framework/commit/912de244f88a69742b76e8a2807f6765947776da) |
| `13.20.0` | `13.20.0` | 8.3 | 13.20.0 | [`b9d1bccad5fb`](https://github.com/laravel/framework/commit/b9d1bccad5fbc32578dca22566bb11e7c0e545d7) |
| `13.21.0` | `13.21.0` | 8.3 | 13.21.0 | [`d1e02ce7b7e2`](https://github.com/laravel/framework/commit/d1e02ce7b7e25146177a1a0137c37bccb32d26d3) |
| `13-latest` | `^13.0` | 8.3 | 13.23.0 | [`92a707229148`](https://github.com/laravel/framework/commit/92a707229148e57f08a249211c8a5a194159c619) |

The `*-latest` constraints intentionally float in CI. Their committed
baselines record the releases above. A later patch release that changes any
probed contract fails the baseline test and requires an explicit review rather
than silently inheriting the old inference assumption.

The runner identifies the installed release through Composer package metadata,
not `Application::VERSION`. Laravel's `v12.22.0` package still contains the
stale application constant `12.21.0`; using that constant would mislabel the
exact release on the strict-integer boundary.

## Method

[`InferenceAuditCases`](../tests/Support/InferenceAuditCases.php) defines one
deterministic input and rule set for each adversarial probe. For every case,
[`InferenceAudit`](../tests/Support/InferenceAudit.php):

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
[`tests/fixtures/version-audit`](../tests/fixtures/version-audit) are runtime
contract snapshots, not hand-authored expected types. The
[`inference-audit.php`](../scripts/inference-audit.php) runner can load an
isolated Composer installation of Laravel before the project's own autoloader,
which lets the same extension build be checked against exact framework
releases.

The runner deliberately normalizes objects, resources, non-finite floats, and
the array-to-string warning into stable data. Unrelated PHP engine and
dependency deprecations are not part of the Laravel validation contract and
are omitted from the snapshot.

An additional Eris property suite takes 250 seed-dependent draws in each of
three bounded domains: scalar presence and native representations, nested
projection and wildcards, and cross-field presence and exclusion. Their finite
catalogs contain 1,260, 48, and 280 possible combinations respectively; draws
are made with replacement and are not claims of exhaustive coverage. Each
property requires at least 30 percent of its trials to produce successful
Laravel output so a mostly rejected sample cannot pass vacuously. It then runs
the same runtime-to-static containment check without creating snapshots.

The fixed default seed makes CI reproducible, while an explicit `ERIS_SEED`
explores or replays another input sequence. Property testing broadens the
observed evidence; it does not prove universal soundness.

## Inventory

| Area | Representative probes | Result |
| --- | --- | --- |
| Accepted and declined values | `accepted.true`, `accepted_if.inactive`, `declined.false`, `declined_if.inactive` | No observed release difference |
| Boolean and numeric predicates | `boolean.*`, `integer.*`, `numeric.*`, `digits*`, `decimal`, `multiple_of`, `max_digits`, `min_digits` | `integer:strict` boundary at 12.22 |
| Text predicates | `alpha*`, `ascii.*`, `string`, `lowercase`, `uppercase`, `regex`, `not_regex` | `ascii` boundary at 13.4 |
| Hex colors | valid strings, compatible `Stringable`, optional blank input, and unsupported-rule behavior | Rule introduction at 10.33; native-string boundary at 13.4, covered by the cross-profile PHPUnit suite |
| JSON, dates, and membership | `json.*`, `date*`, comparisons, scalar `in` | No observed release difference |
| Network and identifiers | `email`, `ip`, `ipv4`, `ipv6`, `mac_address`, `timezone`, `url`, `uuid`, `ulid` | No observed release difference |
| Arrays and projection | bare and keyed arrays, numeric rule keys, nested child projection, required keys, wildcards, parent-plus-child rules | Numeric rule-key boundary at Laravel 12 |
| Presence and conditions | optional blanks, nullable, present, confirmed, `required_if`, `exclude_if` | No observed release difference |
| Default HTTP middleware | password-path trimming before validation | Laravel 10 versus 11+ boundary covered by the cross-profile PHPUnit suite |
| Static entry points | facade, factory, request, controller, helper, validator unions, constant `setRules()` | Covered by the existing PHPStan fixture suite |
| Environment-dependent behavior | files, images, dimensions, database, DNS, password-rule service checks, custom rules | Catalogued but not executed by this portable audit |

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

### No additional portable boundary was observed

The portable case snapshot is identical at Laravel 10.0, 10.32, and 10.33,
across Laravel 10 and 11, from Laravel 11 to Laravel 12.0, and from Laravel
12.0 to 12.21. After accounting for the numeric-key, strict-integer, and ASCII
boundaries above, later snapshots are also identical within their covered
ranges. The focused `hex_color` witness is intentionally separate because
invoking that rule before its introduction throws rather than producing an
ordinary validation result.

Across 1,968 case executions on the sixteen profiles, Laravel returns 1,328
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

Of the 123 portable cases, 101 are marked as preservation-only precision
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
| 10.0 through 12.21 | 69 | 22 | 10 | 18 |
| 12.22 through 13.3 | 65 | 22 | 14 | 18 |
| 13.4 and later | 57 | 22 | 22 | 18 |

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
  native PHP supertypes can describe. Some may support parameter-aware
  refinements; others require predicates PHPStan cannot express.
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

The main CI test matrix now runs every audit profile across every project PHP
version at or above that Laravel major's supported PHP floor:

- Laravel 10 profiles on PHP 8.1 through 8.5;
- Laravel 11 and 12 profiles on PHP 8.2 through 8.5; and
- Laravel 13 profiles on PHP 8.3 through 8.5.

This produces 56 Laravel/PHP combinations. Each job installs the profile's
exact or floating Composer constraint and exposes its profile name through
`LARAVEL_AUDIT_BASELINE`. The deterministic audit compares the recorded
contract, checks containment of successful output, and records the reverse
precision classification. The same job separately runs the bounded property
suite and requires containment for every successful generated output.

The exact boundary releases are not substitutes for the floating latest
profiles. The former preserve known historical contracts; the latter detect
new patch-release behavior.

## Reproducing the audit

Run the installed Laravel release and print its audit result:

```sh
php scripts/inference-audit.php
```

Compare the installed release with a committed profile:

```sh
php scripts/inference-audit.php --baseline=10-latest
```

Run against an isolated exact Laravel installation:

```sh
php scripts/inference-audit.php \
    --laravel-autoload=/path/to/laravel/vendor/autoload.php \
    --baseline=12.22.0
```

Regenerate a baseline only after reviewing Laravel's behavior and upstream
provenance:

```sh
php scripts/inference-audit.php \
    --laravel-autoload=/path/to/laravel/vendor/autoload.php \
    --baseline=12.22.0 \
    --update
```

Run only the bounded property suite with its default seed, or select another
seed to explore and replay a different sequence:

```sh
composer test:property
ERIS_SEED=123456 composer test:property
```

Every counterexample must be reproduced against the supported Laravel majors
and promoted into the deterministic audit or a focused runtime regression
before inference changes.

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
`base64`, `hex_color`, `list`, and default HTTP normalization only at the
verified boundaries above.
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
runtime profiles.

Environment-dependent rules should remain conservative unless their runtime
services can be replaced with deterministic test doubles and their static
contract can be stated without booting arbitrary application behavior.

Last reviewed: 2026-08-08.
