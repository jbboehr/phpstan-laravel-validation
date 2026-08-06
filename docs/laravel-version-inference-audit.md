# Laravel-version inference audit

This audit checks whether Laravel's validation behavior changes across the
releases supported by `phpstan-laravel-validation`, and whether the extension's
version-independent inferred types continue to contain every successful output
observed at those releases.

The result is reassuring for soundness but costly for precision. No successful
output in the portable audit corpus falls outside the inferred type. Two
release boundaries do change the native values accepted by Laravel:

- `integer:strict` begins enforcing native integers in Laravel 12.22; and
- `ascii` begins requiring a native string in Laravel 13.4.

The extension currently has no Laravel-version input to its type resolver. It
therefore retains the broader behavior of every supported release. That is
sound for the supported range, but projects on the newer side of either
boundary receive a less precise type than their installed Laravel release
would permit.

This is an audit result, not a proof of universal soundness. It covers the
portable rule families and rule interactions listed below. Laravel behavior
that depends on files, databases, DNS, password services, image metadata, or
application-defined validation extensions remains outside the deterministic
corpus.

## Audited releases

The audit pins the first release of every supported major, the current latest
release, and both sides of every semantic transition found by the audit.

| Profile | Constraint | PHP floor | Recorded release | Upstream commit |
| --- | --- | --- | --- | --- |
| `10.0.0` | `10.0.0` | 8.1 | 10.0.0 | [`be2ddb5c31b0`](https://github.com/laravel/framework/commit/be2ddb5c31b0b9ebc2738d9f37a9d4c960aa3199) |
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
4. resolves the same rule with this extension; and
5. records whether the inferred type accepts Laravel's actual output.

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

## Inventory

| Area | Representative probes | Result |
| --- | --- | --- |
| Accepted and declined values | `accepted.true`, `accepted_if.inactive`, `declined.false`, `declined_if.inactive` | No observed release difference |
| Boolean and numeric predicates | `boolean.*`, `integer.*`, `numeric.*`, `digits*`, `decimal`, `multiple_of`, `max_digits`, `min_digits` | `integer:strict` boundary at 12.22 |
| Text predicates | `alpha*`, `ascii.*`, `string`, `lowercase`, `uppercase`, `regex`, `not_regex` | `ascii` boundary at 13.4 |
| JSON, dates, and membership | `json.*`, `date*`, comparisons, scalar `in` | No observed release difference |
| Network and identifiers | `email`, `ip`, `ipv4`, `ipv6`, `mac_address`, `timezone`, `url`, `uuid`, `ulid` | No observed release difference |
| Arrays and projection | bare and keyed arrays, nested child projection, required keys, wildcards, parent-plus-child rules | No observed release difference |
| Presence and conditions | optional blanks, nullable, present, confirmed, `required_if`, `exclude_if` | No observed release difference |
| Static entry points | facade, factory, request, controller, helper, validator unions, constant `setRules()` | Covered by the existing PHPStan fixture suite |
| Environment-dependent behavior | files, images, dimensions, database, DNS, password checks, custom rules | Catalogued but not executed by this portable audit |

The inventory focuses on rules for which the extension currently narrows a
type, plus representative non-narrowing and structural rules that can change
presence or projection. It is intentionally adversarial: values such as
integral floats, booleans, `Stringable` objects, resources, blank strings,
missing wildcard parents, and undeclared nested keys are included because
ordinary happy-path strings do not reveal Laravel's native output contract.

## Findings

### Laravel 12.22 changes `integer:strict`

Laravel 12.21 accepts and preserves both `'1'` and `1.0` for this rule:

```php
['value' => 'required|integer:strict']
```

Before Laravel 12.22, the parameter is ignored and validation has the same
coercive behavior as the ordinary `integer` rule. Laravel 12.22 adds strict
mode and rejects both non-integer values. Native `int` values continue to pass
and are preserved.

The current inferred value type remains:

```php
float|int|numeric-string|Stringable|true
```

That union is required for Laravel 10, 11, and 12.0 through 12.21. It is broader
than necessary for Laravel 12.22 and later. Refining it safely requires the
resolver to know which Laravel release PHPStan is analyzing.

### Laravel 13.4 changes `ascii`

Laravel 13.3 retains the coercive behavior inherited from Laravel 10 through
12. The `ascii` predicate casts values to strings and `validated()` preserves
the originals. The audit reproduces successful integer, boolean, `null`,
`Stringable`, resource, and warning-tolerant array outputs.

Laravel 13.4 adds a native `is_string()` guard and rejects every one of those
non-string inputs. The behavior remains string-only through the pinned Laravel
13.23 release.

The current cross-version inferred value type remains:

```php
array|bool|float|int|resource|string|Stringable|null
```

For Laravel 13.4 and later, `string` would be sufficient before applying
presence and blank-value behavior. The broad union is not an invented analyzer
edge case on older versions; it is the set of native categories Laravel can
successfully return. On newer versions it is a compatibility cost caused by
version-independent resolution.

### HTTP normalization also has a known major boundary

Laravel's default `TrimStrings` middleware excludes password-related paths in
Laravel 11 and later. Laravel 10 trims them. The optional HTTP-normalization
mode conservatively retains those exceptions for every supported major, so it
does not become unsound when the analyzed Laravel version is unavailable. It
can be less precise for Laravel 10.

This is already covered by
`LaravelInferenceTest::testDefaultPasswordTrimExceptionVariesByLaravelMajor`
and the normalized request PHPStan fixtures. It is listed here because any
future version context should refine this behavior alongside rule inference,
not as an unrelated special case.

### No additional portable boundary was observed

The complete case snapshot is identical from Laravel 10.0 to 11.0, across
Laravel 10 and 11, from Laravel 11 to Laravel 12.0, and from Laravel 12.0 to
12.21. After accounting for the two boundaries above, later snapshots are also
identical within their covered ranges.

Across all twelve profiles, every successful output in the corpus is accepted
by the extension's inferred type. There are no `observed-unsound`,
`inference-error`, or `runtime-exception` classifications. Invalid adversarial
inputs are recorded as `no-successful-output` and do not establish an inferred
type guarantee.

This result supports the current conservative unions. It does not establish
that unprobed rule interactions, application extensions, or future Laravel
patches are sound.

## CI enforcement

The main CI test matrix now runs every audit profile across every project PHP
version at or above that Laravel major's supported PHP floor:

- Laravel 10 profiles on PHP 8.1 through 8.5;
- Laravel 11 and 12 profiles on PHP 8.2 through 8.5; and
- Laravel 13 profiles on PHP 8.3 through 8.5.

This produces 46 Laravel/PHP combinations. Each job installs the profile's
exact or floating Composer constraint, exposes its profile name through
`LARAVEL_AUDIT_BASELINE`, runs Laravel's runtime probes, compares the recorded
contract, and checks that every successful output remains accepted by current
inference.

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

## Recommended follow-up

No production inference change is justified merely by this audit. The current
types accept every successful portable output across the supported range.

The actionable precision work is to design one reliable source of analyzed
Laravel-version context and pass it through every inference entry point. Only
then should `integer:strict`, `ascii`, and default HTTP normalization be
specialized. Adding isolated version checks directly to individual rules would
make behavior depend on whichever Laravel happens to execute PHPStan, which is
not necessarily the dependency version represented by the analyzed code.

Environment-dependent rules should remain conservative unless their runtime
services can be replaced with deterministic test doubles and their static
contract can be stated without booting arbitrary application behavior.

Last reviewed: 2026-08-05.
