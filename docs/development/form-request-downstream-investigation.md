# FormRequest downstream investigation

This report tests the experimental FormRequest integration against
[Koel](https://github.com/koel/koel) and
[Pterodactyl](https://github.com/pterodactyl/panel). It asks two questions:

1. Does enabling the integration change or break whole-application PHPStan
   analysis?
1. What time and memory does FormRequest discovery and rule resolution add?

This is a pinned development investigation, not a compatibility or performance
promise for later revisions.

Initial investigation date: 2026-08-10. Follow-up implementation and rerun:
2026-08-11. Cold discovery phase profile: 2026-08-25.

## Result

Koel demonstrates that the integration can recover useful structural types
across a large conventional FormRequest corpus. Pterodactyl demonstrates the
cost of the conservative lifecycle gate: a harmless-looking hook on a shared
base class can deliberately leave most requests broad.

Initial implementation:

| App | Requests | Eligible | Shapes | Broad | Native errors | Enabled errors |
| --- | ---: | ---: | ---: | ---: | ---: | ---: |
| Koel | 97 | 94 | 85 | 9 | 0 | 0 |
| Pterodactyl | 113 | 27 | 19 | 8 | 29 | 29 |

All native, extension-loaded-but-disabled, and FormRequest-enabled scans
produced the same diagnostic hash within each application. Pterodactyl's 29
diagnostics were present before this extension was loaded. No new crash,
soundness regression, or downstream diagnostic was found.

After recognizing provably empty `withValidator()` bodies and selected
database builders:

| App | Requests | Eligible | Shapes | Broad | Native errors | Enabled errors |
| --- | ---: | ---: | ---: | ---: | ---: | ---: |
| Koel | 97 | 94 | 85 | 9 | 0 | 0 |
| Pterodactyl | 113 | 98 | 48 | 50 | 29 | 29 |

Pterodactyl's remaining lifecycle exclusions are 11 custom `validated()`
implementations, one `getValidatorInstance()` override, and three non-empty
`withValidator()` hooks. Ten otherwise eligible requests have unresolved rules
and 40 resolve to conservative `mixed`; eligibility is not itself a promise of
a useful shape.

The integration has a measurable startup cost when enabled. The cleanest cold
serial comparison added 2.36 seconds and 6.2 MiB in Koel, and 1.24 seconds and
3.2 MiB in Pterodactyl. Merely registering the extension while leaving
FormRequests disabled was within run-to-run noise. The follow-up manifest
reduces the warm incremental cost to approximately 0.05 seconds in Koel and
0.03 seconds in Pterodactyl.

## What the applications exercise

### Koel

Koel has 103 PHP files under `app/Http/Requests`, 97 concrete FormRequests, 88
`rules()` implementations, and two application calls to `validated()`. Its
request hierarchy is mostly conventional: 94 concrete classes pass the
extension's lifecycle-safety checks.

An inventory probe called no-argument `validated()` on every eligible concrete
request. The extension inferred array shapes for 85 of 94 classes. The other
nine retained `mixed` or Laravel's broad array type because their rules were
not statically resolvable.

A focused probe on `PlaylistFolderUpdateRequest` illustrated both the benefit
and an honest precision limit:

| Configuration | Inferred type |
| --- | --- |
| Native Larastan | `array<string, mixed>` |
| FormRequests enabled | `array{name?: string, parent_id?: mixed}` |
| Follow-up | `array{name?: string, parent_id?: string&#124;null}` |

The extension recovers the output keys and the `name` predicate. Initially,
the fluent `Rule::exists(...)->where(...)` object made `parent_id` opaque. The
follow-up recognizes the database rule as a type-neutral predicate, allowing
the adjacent `nullable|uuid` rules to establish `string|null` without claiming
that the database check transforms the value.

Three Koel requests override `passedValidation()` and therefore fail the
lifecycle gate. This is expected: the hook runs after validation and can mutate
state observed after validation. No trust overrides were used in the audit.

### Pterodactyl

Pterodactyl has 117 PHP files under `app/Http/Requests`, 113 concrete
FormRequests, 77 `rules()` implementations, and 24 calls to `validated()` in 15
application files.

Initially, only 27 concrete classes passed the lifecycle gate:

| Eligibility result | Classes |
| --- | ---: |
| Eligible | 27 |
| `withValidator()` present | 74 |
| `validated()` overridden | 11 |
| `getValidatorInstance()` overridden | 1 |

The principal cause was `ApplicationApiRequest`. It declares a no-op
`withValidator()` method, and 83 concrete requests descend from it. The
initial extension did not attempt to prove arbitrary hook bodies harmless, so
those requests remained broad. Eleven descendants were classified earlier
because they also override `validated()`.

The follow-up accepts only a parsed `withValidator()` body with no executable
statements. That admits the inherited no-op without generalizing to arbitrary
hooks, increasing eligibility to 98 requests and structural shapes to 48.
Non-empty overrides remain excluded.

The inventory probe produced shapes for 19 of the 27 eligible classes. Eight
eligible requests retained broad types because their `rules()` expressions
were not statically resolvable. Representative probes were:

- `Admin\BaseFormRequest` changes from `array<string, mixed>` to
  `array{company: mixed}`. Its literal rules are resolvable, but `between`
  alone does not establish a narrower native value type.
- `Admin\Egg\EggFormRequest` remains `array` because the class declares a
  validator lifecycle hook.
- `Api\Application\Locations\StoreLocationRequest` remains
  `array<string, mixed>` after the follow-up because its rules are assembled
  from `Location::getRules()` through a runtime collection pipeline. The
  inherited no-op hook is no longer the blocker.

## Performance result

The benchmark separates three configurations:

- **native**: the application's normal PHPStan and Larastan configuration;
- **extension-disabled**: this extension is registered, with FormRequests
  explicitly disabled; and
- **form-requests**: the same extension configuration with FormRequests
  enabled.

The incremental FormRequest cost is therefore `form-requests` compared with
`extension-disabled`, not merely enabled compared with native.

### Koel performance

| Cache | Mode | Native | Off | On | Wall vs off | RSS vs off |
| --- | --- | ---: | ---: | ---: | ---: | ---: |
| Cold | One | 24.66 s | 24.49 s | 26.85 s | +9.6% | +6.2 MiB |
| Cold | Default | 6.64 s | 6.65 s | 9.02 s | +35.6% | n/a |
| Warm | Default | 0.34 s | 0.35 s | 1.20 s | +242.9% | n/a |

The total cold serial impact relative to native was 2.19 seconds, 8.9%, and
7.1 MiB. The difference between native and extension-disabled was negative
0.7%, which is measurement noise rather than evidence that registration makes
analysis faster.

### Pterodactyl performance

| Cache | Mode | Native | Off | On | Wall vs off | RSS vs off |
| --- | --- | ---: | ---: | ---: | ---: | ---: |
| Cold | One | 8.02 s | 8.02 s | 9.26 s | +15.5% | +3.2 MiB |
| Cold | Default | 3.42 s | 3.32 s | 4.76 s | +43.4% | n/a |
| Warm | Default | 0.28 s | 0.29 s | 0.84 s | +189.7% | n/a |

The total cold serial impact relative to native was 1.24 seconds, 15.5%, and
4.4 MiB. The native and extension-disabled cold serial medians were identical.

### Follow-up performance

The cache manifest avoids parsing and resolving every request merely to answer
PHPStan's result-cache metadata query when the discovered source contents and
Composer metadata are unchanged. Five-sample medians after the implementation
were:

| App | Cache | Mode | Off | On | Increment |
| --- | --- | --- | ---: | ---: | ---: |
| Koel | Cold | One | 24.58 s | 26.83 s | +2.25 s |
| Koel | Cold | Default | 6.77 s | 8.69 s | +1.92 s |
| Koel | Warm | Default | 0.35 s | 0.40 s | +0.05 s |
| Pterodactyl | Cold | One | 7.92 s | 9.33 s | +1.41 s |
| Pterodactyl | Cold | Default | 3.32 s | 4.93 s | +1.61 s |
| Pterodactyl | Warm | Default | 0.29 s | 0.32 s | +0.03 s |

All follow-up samples retained the same application-specific diagnostic hash.
Cold analysis still builds the registry, and Pterodactyl now resolves many
more eligible requests, so this optimization primarily addresses repeated
cached invocations rather than eliminating cold discovery.

### Cold discovery phase profile

A later profile isolated the registry's cold work from whole-application
analysis. A temporary instrumented copy of `FormRequestTypeRegistry` recorded
wall-clock time around source fingerprinting, AST class discovery, PHPStan
class reflection, lifecycle eligibility, and rule resolution. The
instrumentation was not retained in production code.

Each application was analyzed three times with a fresh PHPStan `tmpDir`. The
analysis target was one ordinary service-provider file so unrelated rule
analysis did not dominate the sample. FormRequest discovery still traversed
the same project and Composer source roots as the full scan. The table reports
medians from the three runs:

| Phase | Koel | Pterodactyl |
| --- | ---: | ---: |
| Complete registry `getHash()` | 3.580 s | 2.528 s |
| Source-path enumeration and content fingerprint | 0.011 s | 0.006 s |
| Parse source files and collect class names | 0.934 s | 0.477 s |
| Reflect classes and test FormRequest ancestry | 1.616 s | 1.038 s |
| Reflect the FormRequest base class | 0.028 s | 0.028 s |
| Lifecycle eligibility checks | 0.027 s | 0.079 s |
| Resolve eligible `rules()` methods | 0.439 s | 0.597 s |
| Other registry work, including type descriptions | 0.526 s | 0.304 s |

The corpus behind those timings was:

| App | PHP source files | Declared classes | FormRequests | Eligible | Inferred |
| --- | ---: | ---: | ---: | ---: | ---: |
| Koel | 1,363 | 1,221 | 97 | 94 | 90 |
| Pterodactyl | 681 | 635 | 113 | 98 | 88 |

The result rejects the simplest optimization hypothesis. Filesystem traversal
and content hashing are negligible on this warm operating-system cache, while
AST discovery is material and PHPStan reflection is the largest measured
phase. Rule resolution is also significant, especially in Pterodactyl, but it
is not the principal Koel cost.

A disposable experiment skipped class declarations with no `extends` clause;
such a class cannot inherit `FormRequest`. It preserved every FormRequest,
eligibility, and inferred-type count in both applications. The prefilter
reduced reflected candidates from 1,221 to 918 in Koel and from 635 to 525 in
Pterodactyl. Median registry time fell from 3.580 to 3.275 seconds in Koel
(-8.5%) and from 2.528 to 2.445 seconds in Pterodactyl (-3.3%). This is a safe,
modest optimization candidate, not evidence that more aggressive syntactic
ancestry reconstruction would be sound. Indirect and external base classes
still require PHPStan reflection.

### Result-cache correctness and invalidation boundary

The registry manifest and PHPStan's result cache have different invalidation
boundaries. Any content change among the discovered PHP sources or Composer
metadata invalidates the manifest and rebuilds the registry. If that rebuild
produces the same semantic descriptor hash, PHPStan can still reuse its result
cache. A changed descriptor hash invalidates the complete PHPStan result cache
through `ResultCacheMetaExtension`.

The global hash is currently necessary for soundness. A controlled experiment
removed the registry's result-cache metadata tag. PHPStan then reused a stale
caller result after either a FormRequest `rules()` body changed or an external
constant used by its rules changed. The focused
`FormRequestResultCacheTest::testChangingOnlyRulesMethodBodyInvalidatesCachedCaller()`
and
`FormRequestResultCacheTest::testChangingExternalRuleConstantInvalidatesCachedCaller()`
cases preserve these regressions. PHPStan's ordinary dependency graph does not
express this method-body-derived semantic dependency.

PHPStan does not currently expose a supported per-file semantic dependency
extension point. Draft
[phpstan/phpstan-src#5364](https://github.com/phpstan/phpstan-src/pull/5364)
proposes per-file dependencies on external paths, with a companion Symfony use
case in
[phpstan/phpstan-symfony#478](https://github.com/phpstan/phpstan-symfony/pull/478).
The proposal remains unmerged. Its maintainer discussion identifies a more
precise target: record that an analyzed file consumes a particular semantic
key, then ask the owning extension for that key's current hash. This would let
a FormRequest contract change invalidate its actual consumers without making
the dependency a raw file path or discarding the complete cache.

Until PHPStan provides such an extension point, the global semantic descriptor
hash plus the manifest is the safest available compromise. FormRequest
inference remains disabled by default so projects that do not opt in pay none
of this discovery or invalidation cost. Disabling invalidation for enabled
projects would knowingly permit stale inferred types.

### Raw wall-time samples

These are the five wall-time samples, in seconds, behind each median. "Off"
and "on" mean that the extension was registered with FormRequests disabled or
enabled, respectively.

#### Koel samples

- Cold, one worker, native:
  `25.18, 24.66, 24.97, 24.57, 24.57`.
- Cold, one worker, off:
  `24.87, 24.46, 24.88, 24.49, 24.47`.
- Cold, one worker, on:
  `26.85, 26.84, 26.86, 26.84, 27.06`.
- Cold, default workers, native:
  `6.74, 6.64, 6.83, 6.37, 6.64`.
- Cold, default workers, off:
  `6.65, 6.45, 6.75, 6.47, 6.75`.
- Cold, default workers, on:
  `8.73, 8.51, 9.07, 9.04, 9.02`.
- Warm, native:
  `0.38, 0.35, 0.34, 0.34, 0.34`.
- Warm, off:
  `0.39, 0.35, 0.35, 0.35, 0.34`.
- Warm, on:
  `1.23, 1.19, 1.20, 1.19, 1.20`.

#### Pterodactyl samples

- Cold, one worker, native:
  `8.02, 8.02, 8.03, 7.93, 7.92`.
- Cold, one worker, off:
  `8.03, 8.02, 8.02, 7.93, 8.03`.
- Cold, one worker, on:
  `9.16, 9.26, 9.27, 9.15, 9.39`.
- Cold, default workers, native:
  `3.22, 3.42, 3.42, 3.52, 3.52`.
- Cold, default workers, off:
  `3.32, 3.33, 3.32, 3.52, 3.22`.
- Cold, default workers, on:
  `4.76, 4.57, 4.85, 4.96, 4.75`.
- Warm, native:
  `0.31, 0.29, 0.28, 0.28, 0.28`.
- Warm, off:
  `0.32, 0.28, 0.29, 0.29, 0.29`.
- Warm, on:
  `0.87, 0.83, 0.83, 0.84, 0.84`.

### Interpretation

Cold serial memory is the reliable memory comparison. GNU `time` observes the
PHPStan coordinator in default-worker mode, not aggregate process-tree memory,
so the parallel RSS figures are retained in the machine-readable results but
are not interpreted here.

In the initial run, the warm percentages looked dramatic because they compared
a fixed discovery and resolution cost with a very small cached scan. PHPStan
asks the registry for result-cache metadata in each fresh process. The
extension must rediscover the relevant source classes and describe their
inferred types before PHPStan can decide whether its previous result cache is
valid. On these applications that adds approximately 0.55 seconds for
Pterodactyl and 0.85 seconds for Koel.

The manifest follow-up removes most of that fixed warm cost while preserving
content-based invalidation. The integration is disabled by default, so
ordinary extension users do not pay its cold discovery cost. Projects opting
into FormRequests still pay for initial analysis and registry reconstruction
whenever any discovered PHP source or Composer metadata changes. Only a
changed semantic descriptor hash invalidates PHPStan's complete result cache.

Pterodactyl initially exhausted its project's effective 128 MiB limit when the
audit first enabled FormRequests. A controlled rerun used a 2 GiB limit and
showed that all cold serial variants actually need about 410 MiB on this
revision; FormRequests add only 3.2 MiB relative to the extension-disabled
variant. The initial failure exposed an unsuitable limit for this full scan,
not a 280 MiB registry regression.

## Target and environment

### Koel target

- Application: `dfec91ff290509c622ff7cf392fb5e506841ee2b`.
- Size under `app`: 904 PHP files and 38,269 lines.
- Laravel: v13.24.0 at `6d481710375d`.
- PHPStan: 2.1.55 at `9eaac3826ed5`.
- Larastan: v3.9.6 at `9ad17e83e96b`.
- Native scan: level 5 over `app`, `database`, `routes`, and `tests`.
- Native diagnostics: zero.

### Pterodactyl target

- Application: `850f2b9a4ff95b5fee64ffa9da74ca53b3f8eaeb`.
- Size under `app`: 565 PHP files and 31,977 lines.
- Laravel: v12.64.0 at `727a8ea2949c`.
- PHPStan: 2.2.6 at `a6e9b5a9420f`.
- Larastan: v3.10.0 at `2970f8339815`.
- Native scan: level 4 over `app`.
- Native diagnostics: 29.

The initial targets used `phpstan-laravel-validation` from `develop` at
`e8ec818eb51b`. The follow-up used the local working tree based on
`0bf439b46f17`; Composer's path-dependency lock reference remained at the
older commit, which is why the harness now records the resolved extension
source revision separately from package lock metadata.

Both benchmarks used PHP 8.5.9 on NixOS, Linux 7.1.5, on an AMD Ryzen 9
9950X3D with 16 cores and 32 logical CPUs. The PHPStan memory limit was fixed
at 2 GiB for every variant.

The application and locked dependency revisions were identical among each
application's three configurations. The extension was installed as a Composer
path development dependency, then PHPStan was returned to the application's
locked version where Composer otherwise selected a newer compatible release.
Neither target installed `phpstan/extension-installer`, and neither native
configuration included this extension, so the native variants did not register
its services.

## Method

[`scripts/benchmark-form-requests.php`](../../scripts/benchmark-form-requests.php)
ran each application with five rotated samples of:

1. fresh result cache and one PHPStan worker;
1. fresh result cache and PHPStan's default workers; and
1. a separately primed result cache and PHPStan's default workers.

Each cache and worker mode tested all three configurations. Every cold sample
received a unique `tmpDir`. Warm samples reused one independently primed cache
per configuration. The harness recorded wall, user, system, exit status,
maximum RSS, diagnostic count, and a hash of the full diagnostic set. Medians
are the primary comparison.

"Cold" excludes PHPStan result-cache reuse; it does not clear the operating
system's filesystem cache. Variant order rotates to reduce first-run and
scheduler bias, but five samples cannot resolve small sub-percent differences.

The type inventories were temporary `PHPStan\dumpType()` probes over every
concrete request that passed the lifecycle gate. They were removed after the
results were recorded and are not application modifications.

## Reproduction

Install the extension as a development path dependency in the target
application without changing its PHPStan, Larastan, or Laravel versions. Then
run from this repository:

```sh
php scripts/benchmark-form-requests.php /path/to/koel phpstan.neon.dist 5
php scripts/benchmark-form-requests.php /path/to/pterodactyl phpstan.neon 5
```

The script uses the calling PHP binary and does not require Nix. It requires
the target application's installed dependencies, this extension at
`vendor/jbboehr/phpstan-laravel-validation`, and GNU `time`. Set
`FORM_REQUEST_BENCHMARK_TIME` if GNU `time` is not in a conventional location,
and `FORM_REQUEST_BENCHMARK_PHP` to select another PHP executable.

Pterodactyl also required a valid application key and audit-safe local service
settings while Larastan booted the application:

```sh
APP_KEY='base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=' \
APP_ENV=testing CACHE_STORE=array SESSION_DRIVER=array \
QUEUE_CONNECTION=sync DB_CONNECTION=sqlite DB_DATABASE=:memory: \
php scripts/benchmark-form-requests.php /path/to/pterodactyl phpstan.neon 5
```

Use a disposable audit key, not a production secret. Set
`FORM_REQUEST_BENCHMARK_JSON=/path/to/results.json` to retain every sample in
machine-readable form. The default 2 GiB PHPStan memory limit can be changed
with `FORM_REQUEST_BENCHMARK_MEMORY_LIMIT`.

The harness detects a locked `phpstan/extension-installer` package and avoids
including this extension a second time. Set
`FORM_REQUEST_BENCHMARK_EXTENSION_LOADED=1` when the native configuration loads
the extension by another mechanism, or `0` when a locked extension installer
is disabled and does not load it. The chosen posture is recorded in benchmark
metadata.

## Stored `ValidatedInput` wrappers remain broad

The direct-chain implementation intentionally does not attach a persistent
payload type to `Illuminate\Support\ValidatedInput` instances. A generic
internal wrapper type would make this refactor appear harmless:

```php
$safe = $request->safe();
$validated = $safe->all();
```

That provenance would not be stable. `ValidatedInput` exposes property and
array-offset writes and unsets, and aliases share the same object:

```php
$safe = $request->safe();
$alias = $safe;
$alias['name'] = 42;

$safe->all(); // the alias changed this payload
```

Passing the wrapper to application or vendor code creates the same problem.
PHPStan does not provide this extension with a general object-identity or
typestate hook that could invalidate every alias after every possible escape.
A custom generic type would therefore preserve a stale shape rather than make
the existing inference composable.

The supported pattern is to store the terminal array from `safe()->all()`,
`safe()->toArray()`, `safe([...])`, `safe()->only(...)`, or
`safe()->except(...)`. Direct bounded `merge()` chains may participate before
the terminal accessor. Stored wrappers remain broad unless Laravel exposes an
immutable wrapper or PHPStan gains an alias-safe invalidation mechanism.

## Remaining candidates

1. Pursue a supported PHPStan API for per-file, extension-defined semantic
   dependencies. A consumer should record a stable dependency key, such as a
   FormRequest class, and the extension should provide that key's current
   contract hash.
1. Consider an optional exact FormRequest class list, separate from
   `trustedClasses`, for projects that prefer explicit discovery. Unlisted
   requests must retain Laravel's broad declared type. This would reduce
   registry discovery work without weakening lifecycle checks or cache
   invalidation.
1. Consider the measured `extends` prefilter if its modest cold-start benefit
   justifies a production change and dedicated discovery regression tests.
1. Extend selected rule-object support only where Laravel runtime evidence
   establishes a useful static contract.
1. Consider additional direct `ValidatedInput` access patterns beyond the
   implemented terminal accessors. Numeric merge shapes remain broad unless
   their insertion order is explicit. Before Laravel 13.24, multi-selector
   `except()` inference also remains broad when Laravel's retained nested
   reference makes later selectors stateful. Persistent stored-wrapper
   provenance is not a candidate without an alias-safe invalidation mechanism.
