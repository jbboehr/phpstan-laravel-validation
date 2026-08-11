# FormRequest downstream investigation

This report tests the experimental FormRequest integration against
[Koel](https://github.com/koel/koel) and
[Pterodactyl](https://github.com/pterodactyl/panel). It asks two questions:

1. Does enabling the integration change or break whole-application PHPStan
   analysis?
1. What time and memory does FormRequest discovery and rule resolution add?

This is a pinned development investigation, not a compatibility or performance
promise for later revisions.

Investigation date: 2026-08-10.

## Result

Koel demonstrates that the integration can recover useful structural types
across a large conventional FormRequest corpus. Pterodactyl demonstrates the
cost of the conservative lifecycle gate: a harmless-looking hook on a shared
base class can deliberately leave most requests broad.

| App | Requests | Eligible | Shapes | Broad | Native errors | Enabled errors |
| --- | ---: | ---: | ---: | ---: | ---: | ---: |
| Koel | 97 | 94 | 85 | 9 | 0 | 0 |
| Pterodactyl | 113 | 27 | 19 | 8 | 29 | 29 |

All native, extension-loaded-but-disabled, and FormRequest-enabled scans
produced the same diagnostic hash within each application. Pterodactyl's 29
diagnostics were present before this extension was loaded. No new crash,
soundness regression, or downstream diagnostic was found.

The integration has a measurable startup cost when enabled. The cleanest cold
serial comparison added 2.36 seconds and 6.2 MiB in Koel, and 1.24 seconds and
3.2 MiB in Pterodactyl. Merely registering the extension while leaving
FormRequests disabled was within run-to-run noise.

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

The extension recovers the output keys and the `name` predicate. `parent_id`
remains `mixed` because its fluent `Rule::exists(...)->where(...)` object has no
supported static output contract. Widening that leaf is preferable to claiming
a native type Laravel does not establish.

Three Koel requests override `passedValidation()` and therefore fail the
lifecycle gate. This is expected: the hook runs after validation and can mutate
state observed after validation. No trust overrides were used in the audit.

### Pterodactyl

Pterodactyl has 117 PHP files under `app/Http/Requests`, 113 concrete
FormRequests, 77 `rules()` implementations, and 24 calls to `validated()` in 15
application files.

Only 27 concrete classes pass the lifecycle gate:

| Eligibility result | Classes |
| --- | ---: |
| Eligible | 27 |
| `withValidator()` present | 74 |
| `validated()` overridden | 11 |
| `getValidatorInstance()` overridden | 1 |

The principal cause is `ApplicationApiRequest`. It declares a no-op
`withValidator()` method, and 83 concrete requests descend from it. The
extension does not attempt to prove arbitrary hook bodies harmless, so those
requests remain broad. Eleven descendants are classified earlier because they
also override `validated()`.

That conservatism is justified for soundness but costly for this codebase:
discovery still examines the requests even though most cannot be narrowed. A
future slice could recognize a narrowly defined, statically empty lifecycle
hook, provided inherited and overridden behavior remain conservative.

The inventory probe produced shapes for 19 of the 27 eligible classes. Eight
eligible requests retained broad types because their `rules()` expressions
were not statically resolvable. Representative probes were:

- `Admin\BaseFormRequest` changes from `array<string, mixed>` to
  `array{company: mixed}`. Its literal rules are resolvable, but `between`
  alone does not establish a narrower native value type.
- `Admin\Egg\EggFormRequest` remains `array` because the class declares a
  validator lifecycle hook.
- `Api\Application\Locations\StoreLocationRequest` remains
  `array<string, mixed>` because of the inherited no-op `withValidator()`.

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

The warm percentages look dramatic because they compare a fixed discovery and
resolution cost with a very small cached scan. PHPStan asks the registry for
result-cache metadata in each fresh process. The extension must rediscover the
relevant source classes and describe their inferred types before PHPStan can
decide whether its previous result cache is valid. On these applications that
adds approximately 0.55 seconds for Pterodactyl and 0.85 seconds for Koel.

This is the main actionable performance finding. The integration is disabled
by default, so ordinary extension users do not pay it. Projects opting into
FormRequests do pay it on incremental command invocations as well as cold
analysis. Any optimization must preserve cache invalidation when request
classes, inherited rules, custom contracts, trusted-class configuration, or
Laravel versions change.

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

Both targets used `phpstan-laravel-validation` from `develop` at
`e8ec818eb51b`.

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

## Follow-up candidates

1. Profile and reduce the registry's fresh-process discovery and cache-hash
   cost, using these two applications as before-and-after workloads.
1. Investigate narrowly recognizing inherited lifecycle hooks whose bodies are
   provably empty. Do not generalize this to arbitrary hooks, and keep override
   behavior conservative.
1. Add static contracts for selected common fluent rule objects, such as the
   `Rule::exists(...)->where(...)` case seen in Koel, as separate rule-resolver
   slices with Laravel runtime verification.

The first item has the broadest impact. The second would materially improve
Pterodactyl but needs a careful soundness design. The third improves precision
independently of FormRequest discovery.
