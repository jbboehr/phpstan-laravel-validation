# Koel and Pterodactyl functional validation trial

Investigation date: 2026-09-07.

Both experimental features are enabled and exercised in the pinned application
environments. Parsed values agree with their inferred types, conditional
presence changes the inferred shape, and conservative fallbacks remain broad.
The final checks pass **71 PHPUnit tests with 170 assertions**, including 21
exact type assertions. Whole-application diagnostics are unchanged, and restored
result caches agree with fresh analyses.

This extends the [FormRequest downstream investigation](form-request-downstream-investigation.md)
into a functional trial. The retained patches include application adoption
changes, test fixtures, runtime and inference tests, and enabled/control
configurations. They are local experiments against these revisions.

## Configuration

Both applications use:

```neon
parameters:
    phpstanLaravelValidation:
        experimentalConditionalPresenceInference: true
        formRequests:
            enabled: true
```

These are the two experimental switches in the tested extension.
`assumeHttpInputNormalization` and `includeUnvalidatedArrayKeys` remain false:
they describe runtime assumptions, rather than enabling experimental features.
The tests resolve requests directly before HTTP middleware; Pterodactyl also has
a real trimming skip callback for file routes. Its backup-part test confirms
that unvalidated nested keys are excluded under the existing factory behavior.

The initial parsing pass enabled FormRequest inference alone. Whole-app scans
with both features enabled do not by themselves demonstrate conditional
precision: neither app naturally uses the supported `present_if`,
`present_unless`, `missing_if`, or `missing_unless` rule family. Three
separate fixture requests per app therefore exercise definite presence,
definite absence, and a mixed controller domain. They inherit the apps' abstract
request bases; production requests acquire no artificial conditional rules.

## Revisions

| Component | Koel | Pterodactyl |
| --- | --- | --- |
| Application commit | `dfec91ff290509c622ff7cf392fb5e506841ee2b` | `850f2b9a4ff95b5fee64ffa9da74ca53b3f8eaeb` |
| Laravel | `13.24.0` | `12.64.0` |
| PHPStan | `2.1.55` | `2.2.6` |
| Larastan | `3.9.6` | `3.10.0` |
| PHPUnit | `11.5.55` | `11.5.56` |
| nikic/php-parser | `5.7.0` | `5.8.0` |

The extension source is `e4ac081099dfa4f157bae354ca4f16ec4afb3008`,
tree `6c0bcaa237700f25e3a0d4c41e3a3170de7fcb54`. CLI analysis used PHP
8.5.9; PHPUnit used PHP 8.4.23.

The isolated checkouts reused the prior investigation's Composer manifests and
installed dependencies. The extension is linked to this source tree. Retained
lock metadata still names `dev-rich-parser-invalidation-prototype`; no clean
Composer installation or dependency upgrade was tested.

## Functionality exercised

| Area | Runtime evidence | Static evidence |
| --- | --- | --- |
| Koel rating adoption | Canonical strings become integers; bounds and invalid representations are enforced; song consumer receives parsed output | `validated('rating')` is `int`; safe projection is `array{rating: int}` |
| Pterodactyl deployment adoption | Memory, disk, and wildcard location elements become integers; raw input and unparsed page remain unchanged | Memory/disk safe projection is `array{memory: int, disk: int}` |
| Definite presence | A missing target fails; an empty string succeeds | Target key is required and remains `string` |
| Definite absence | Even null and empty-string targets fail; later siblings survive | Target is omitted; its safe projection is `array{}` |
| Mixed controller domain | Both matching and nonmatching branches follow Laravel's rules | Target remains optional |
| Koel downloads | Enum strings, enum cases, and stringable objects retain their representation; `required_if` rejects missing identifiers | Enum union preserves those representations; conditional identifiers remain optional |
| Pterodactyl backup reports | Successful reports require metadata; numeric strings stay strings; nested extra keys are removed | Boolean representations and optional nullable metadata remain conservative |

Koel adds `Parse::integer()` alongside its existing integer and bounds rules
in `RateRequest`, and its song controller consumes `validated('rating')`.
Pterodactyl adds the parser to `memory`, `disk`, and `location_ids.*` in
`GetDeployableNodesRequest`; its controller already consumes validated output.

Koel shares its request with album, artist, and podcast rating controllers, but
only the song consumer was migrated. A service spy deliberately accepts
`mixed` to observe the argument before PHP can coerce it. A test-only
raw-property controller replacement fails this assertion. The original typed
service already coerces accepted decimal strings; this is evidence of parsed
output consumption, not a reproduced endpoint failure.

Parsing deliberately tightens acceptance: plus-prefixed and padded strings,
native floats, and booleans accepted by Laravel's integer predicate are rejected.
Pterodactyl's optional `location_ids: ''` parent still survives as a string;
parsing its children does not guarantee an array parent.

## Results and negative controls

| Application | Runtime tests | Inference tests | Total assertions |
| --- | --- | --- | --- |
| Koel | 34 | 1, containing 10 exact type assertions | 84 |
| Pterodactyl | 35 | 1, containing 11 exact type assertions | 86 |

The runtime tests boot each application's console kernel and call the actual
request's `validateResolved()`. Pterodactyl's application requests receive an
unsaved user and account API token through public interfaces. Request lifecycle
methods are not overridden. Exact output, safe projections, and preserved raw
input are checked.

The same inference tests fail with only conditional inference disabled:
`array{mode: 'create', value: string}` becomes
`array{mode: 'create', value?: string}`. Each control stops at its first
failed assertion; this does not establish independent flag-off checks for every
later assertion. The earlier parser controls produced seven expected failures
per app before the parsing rules were added.

PHPStan's `TypeInferenceTestCase` creates its container at PHPStan's own
package directory. Automatic Laravel detection is consequently unknown in that
harness, which correctly prevents version-dependent presence refinement.
`tests/Type/phpstan.neon` pins each app's version for PHPUnit, and an assertion
checks it against the installed framework. Laravel's handler cleanup prevents
bootstrap state from leaking between tests.

Separate CLI probes use ordinary automatic version detection. All 21 inferred
types match the PHPUnit expectations, including the conditional refinements.
No extension fix was needed.

## Whole-application analysis and caches

| Application | Native Larastan | FormRequests only | Both experimental features |
| --- | --- | --- | --- |
| Koel | 0 diagnostics | 0 | 0 |
| Pterodactyl | 29 existing diagnostics | Same 29 | Same 29 |

Pterodactyl has 28 file diagnostics and one unmatched ignore pattern. Normalized
diagnostic payloads, including messages and locations, match the original
baselines. There were no analysis crashes.

With both features enabled, whole-app scans and type probes each report
`Result cache restored. 0 files will be reanalysed.` Their diagnostic payloads
match separate fresh-cache runs exactly. The initial parsing pass also verified
recomputation after editing previously cached request rules; that pass used
FormRequest inference alone and conservative metadata invalidation.

CLI probes intentionally emit `dumpType()` diagnostics and exit nonzero.
Pterodactyl's narrowed probe selection additionally leaves two ignore patterns
unmatched. These instrumented runs are compared by their exact type payloads.

## Artifacts and reproduction

- [Koel patch](patches/koel-functional-validation.patch)
- [Pterodactyl patch](patches/pterodactyl-functional-validation.patch)

Both patches apply to the pinned application revisions and reproduce the final
source, fixtures, tests, and configuration. They exclude dependency manifests.
The complete local evidence is retained under `/tmp/plv-downstream.zZ01pE`,
including checkouts, runners, controls, CLI probes, logs, and the manifest.

The recorded test environment uses a syntactic application key, array
cache/session drivers, a synchronous queue, and in-memory SQLite. In that
environment, with the app's dependencies available:

```sh
# Koel, using PHP 8.4:
php -d memory_limit=2G vendor/bin/phpunit \
    --bootstrap vendor/autoload.php --no-coverage --do-not-cache-result \
    tests/Feature/ParsingAdoptionTest.php tests/Feature/ExperimentalInferenceTest.php

# For Pterodactyl, use tests/Integration/ for both files.
# PLV_CONDITIONAL_CONTROL=off selects the expected-failure inference control.

php vendor/bin/phpstan analyse -c phpstan-experimental.neon --memory-limit=2G
```

Overriding Pterodactyl's ordinary PHPUnit bootstrap avoids its database refresh
and seed step. These cases require no database. Koel's unavailable `ffmpeg`
lookup produces incidental bootstrap stderr. The inference harness needs more
than Pterodactyl's default 128 MB memory limit.

## Review and limits

Independent correctness and adversarial review covered the initial adoption;
the expanded tests received a read-only strategy and test-value review. The
retained tests distinguish missing parsing, raw-output consumption, accidental
parsing of an unchanged field, and incorrect conditional precision. No
production defect was demonstrated. Verdict: `PASS_WITH_RESIDUAL_RISK` for
this functional trial.

Changed Koel files pass Mago formatting and lint. Changed Pterodactyl files pass
PHP CS Fixer using its supported PHP 8.3 runtime. Documentation formatting and
link checks pass.

This trial covers selected functionality, including both experimental switches.
Full application suites, HTTP middleware, persistence, media operations, and
exhaustive parser/conditional-rule coverage remain outside it. The parser is
installed under `require-dev`; production adoption still needs its runtime
classes available in a `--no-dev` installation, as discussed in the
[parsing investigation](validation-parsing-investigation.md).
