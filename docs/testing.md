# Testing and runtime verification

This project models Laravel's behavior rather than the behavior suggested by a
rule name. A change to inference therefore needs two independent pieces of
evidence: Laravel must actually produce the values being modeled, and PHPStan
must emit a type that contains those values.

Nix is convenient for switching PHP versions, but it is not required. The
canonical test and audit commands use PHP and Composer directly.

## Choose the smallest useful test

| Question | Test layer | Typical location |
| --- | --- | --- |
| Does the parser or resolver build the intended type? | Fast unit test | `tests/RuleTreeNodeTest.php`, `tests/TypeResolverTest.php` |
| What does Laravel accept and return? | Focused runtime test with named cases | `tests/*LaravelRuntimeTest.php` |
| What type does PHPStan show at a real call site? | Explicit `assertType()` fixture | `tests/rules`, `tests/structure`, `tests/version-aware` |
| Is behavior stable across supported Laravel releases? | Deterministic inference audit | `tests/Support/InferenceAuditCases.php`, `tests/fixtures/version-audit` |
| Do many bounded combinations remain sound? | Eris property suite | `tests/Property/InferenceSoundnessPropertyTest.php` |
| Does the whole extension still work? | Full PHPUnit and PHPStan suites | `composer exec phpunit`, `composer exec phpstan analyse` |

Start with the narrowest layer that reproduces the behavior, but do not use a
resolver-only assertion as evidence of Laravel's runtime contract. Changes to
inferred behavior normally need a focused Laravel runtime case and a static
inference assertion as well as the resolver unit test.

## Adding or changing inference

1. Give the runtime scenario a descriptive case name. The focused runtime
   suites use named providers and include the case name, rules, and input in a
   failure.
1. Reproduce the behavior against every supported Laravel major. If a patch
   boundary is suspected, test both sides of it.
1. Add or update the parser/resolver unit assertion.
1. Add an explicit PHPStan fixture assertion at the relevant entry point.
1. Add a deterministic audit case when the scenario is adversarial,
   version-sensitive, or useful as a long-term runtime witness.
1. Regenerate complete audit baselines only after reviewing the runtime diff
   and upstream source reference.

Never narrow an expected type merely because a rule name appears to promise
that type. Preserve conservative inference when Laravel behavior or a custom
runtime contract cannot be established.

## Focused runtime cases

[`AssertsLaravelValidation`](../tests/Support/AssertsLaravelValidation.php)
runs a named case through Laravel, checks the exact `validated()` output, and
checks that the inferred PHPStan type contains it. For example, presence and
projection cases live in
[`PresenceLaravelRuntimeTest`](../tests/PresenceLaravelRuntimeTest.php) rather
than in the large historical export test.

Keep providers grouped by one behavior and name cases for the distinction they
prove, such as `present array blank bypass with zero wildcard matches`. A
failure should be understandable without converting an opaque numeric index
back into several generator tables.

Run a focused file or case with ordinary PHPUnit options:

```sh
vendor/bin/phpunit tests/PresenceLaravelRuntimeTest.php
vendor/bin/phpunit --filter 'present array blank bypass'
```

## Static inference fixtures

PHPStan fixtures remain deliberately explicit. A contributor should be able to
read the rules and the expected type next to each other:

```php
assertType('array{value?: string}', Validator::make($input, [
    'value' => 'string',
])->validated());
```

Do not generate these assertions from the resolver. They are an independent
check that the extension is wired into PHPStan correctly. Generated upstream
and audit fixtures are identified separately in `.gitattributes` and their
directories contain regeneration instructions.

## Named property catalogs

[`InferencePropertyCases`](../tests/Support/InferencePropertyCases.php) builds
three finite, named catalogs for scalar, structural, and conditional behavior.
Eris samples those catalogs with replacement. Failures print a stable semantic
ID such as `boolean.filled.numeric-string-zero.rule-first`, along with the
rules, input, Laravel version, inferred type, and actual output type.

The default seed is fixed by `phpunit.xml.dist` so CI is reproducible:

```sh
composer test:property
ERIS_SEED=123456 composer test:property
```

The catalog integrity test locks the intended sizes and requires unique,
descriptive IDs. A generated counterexample is discovery evidence, not the
permanent regression: promote it into a named focused runtime test or the
deterministic audit.

## Deterministic audit cases

List the available semantic case IDs and profiles without booting PHPStan or
running Laravel:

```sh
php scripts/inference-audit.php --list-cases
php scripts/inference-audit.php --list-profiles
```

Run one or more cases against the installed Laravel release and a committed
baseline:

```sh
php scripts/inference-audit.php \
    --baseline=10-latest \
    --case=present.value \
    --case=missing.absent
```

Case filters are repeatable and exact. They are intentionally incompatible
with `--update`: a snapshot update must always regenerate the complete case
map, so a focused command cannot silently erase or leave stale evidence.

## Portable cross-version matrix

The matrix runner creates isolated Composer projects under
`tmp/version-audit/<profile>`. It never changes the root manifest, lockfile, or
installed dependencies. Run every profile with:

```sh
composer test:audit:matrix
```

The current PHP binary must satisfy every selected profile; running all
profiles currently requires PHP 8.3 or newer. Select one or more profiles when
using an older PHP or investigating a boundary:

```sh
composer test:audit:matrix -- --profile=11.22.0 --profile=11.23.0
```

Exact profiles reuse a matching cached install. Floating `*-latest` profiles
always run `composer update`. Their committed version and source reference
record the last reviewed snapshot, while ordinary checks compare the current
release's case results rather than failing solely because a behaviorally
identical patch was published. Exact profiles continue to require matching
version and source provenance. Use `--reinstall` to discard selected caches,
`--composer=/path/to/composer` to select a Composer executable, and `--update`
to regenerate the selected complete baselines:

```sh
composer test:audit:matrix -- --profile=12.22.0 --reinstall
composer test:audit:matrix -- --profile=12.22.0 --update
```

Some deliberately pinned historical Laravel releases have known security
advisories. The runner disables dependency-policy blocking solely inside these
disposable audit projects and disables Composer plugins and scripts. Do not use
the generated projects as application dependencies.

Each snapshot records the installed Laravel version and the actual 40-character
source reference reported by Composer. Exact-profile checks verify both before
comparing runtime cases. Floating profiles retain that provenance as the last
reviewed reference but fail only when the observed case results change, so a
new patch release remains visible without making every unchanged release a CI
failure.

### Optional Nix convenience

If Nix is available, the wrapper selects the minimum compatible project shell
for each profile and delegates to the same portable matrix runner:

```sh
composer test:audit:matrix:nix
composer test:audit:matrix:nix -- --profile=10.0.0 --profile=13-latest
```

The Nix wrapper contains no audit or snapshot logic. Contributors using local
PHP binaries, containers, `phpenv`, or another version manager exercise the
same canonical PHP implementation.

## Before submitting an inference change

Run the focused tests while developing, then run:

```sh
composer exec phpunit
composer exec phpstan analyse
composer cs
composer validate --strict
```

Run the relevant audit profiles whenever a claim depends on a Laravel version.
Mutation testing is valuable for deterministic inference branches but is not a
replacement for runtime evidence. Its separate setup and subprocess exclusions
are described in the README development notes. The Infection configuration
also narrowly ignores mutations that make `RuleTreeNode::resolvePath()` recurse
without consuming input: those mutants can exhaust PHP's native stack before
Infection's timeout can stop the process.

CI divides the source tree into the shards declared in
`.github/infection-shards.json`. Individual shard jobs disable Infection's MSI
and covered-MSI minimums because differently sized shards cannot meaningfully
enforce whole-project percentages. The final `Mutation testing | PHP 8.5` job
combines their counts and enforces the project-wide MSI, covered-MSI, timeout,
and expected-ignore thresholds. A local full-repository `composer infection`
run continues to enforce the values in `infection.json5.dist` directly.
