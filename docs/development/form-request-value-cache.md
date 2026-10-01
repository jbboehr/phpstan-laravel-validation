# FormRequest value-dependency migration

Plan to adopt PHPStan's value-dependency API when PHPStan 2.3 is released.
The `phpstan-2.3-value-dependencies` branch implements that migration and removes
the rich-parser visitor. It preserves
the existing Laravel inference behavior.

This report records the 2026-09-30 development build. Composer temporarily
requires `^2.3@dev`; the lockfiles pin distribution commit
`f7c762bf2ffc48ce213051ae3282838206811842`, reporting
`2.3.x-dev@223a01f`. The Doctrine revision is unchanged.

## Implementation

[PHPStan PR #6638](https://github.com/phpstan/phpstan-src/pull/6638) added
`DependencyEmitter::valueDependency()` and `ResultCacheValueExtension` on the
`2.3.x` branch. Each FormRequest payload lookup now records a dependency keyed
by method and receiver class: `validated:ClassName` or `safe:ClassName`.
Its value hashes the resolved type's `VerbosityLevel::cache()` description,
or records an unresolved sentinel. Unresolved lookups must remain dependencies
so a cached broad fallback can recover when a lifecycle hook or missing
descendant becomes resolvable.

The existing discovery, hierarchy, eligibility, and inference code computes
these values. A local manifest persists them behind verified source,
configuration, Composer metadata, and external-dependency fingerprints.
Unchanged warm runs can therefore compare values without rebuilding payload
types. Invalid or outdated manifests are rebuilt. When analysis itself emits
a dependency, the registry initializes first so PHPStan records the value of
the actual inferred result.

`safe()` lookups carry the calling scope through direct projections and
`all()` or `merge()` chains. Class-name keys need no checkout-path conversion.
Global cache metadata now tracks only the extension implementation. Request
changes use individual value dependencies. The old visitor, synthetic marker
attribute, reserved-attribute rule, and exported/global classification are
removed.

File dependencies alone would react to irrelevant method edits and could not
observe a newly added descendant. Value dependencies expose the payload
contract, including descendant changes and unresolved-to-resolved transitions.

## Selectivity and cost

The [benchmark](../../scripts/benchmark-form-request-value-cache.py) creates
100 literal requests, 100 payload consumers, and 100 files that reference the
request but only call `method()`. Both checkouts use the pinned PHPStan build,
PHP 8.5.9, Laravel 10.50.2, and otherwise unchanged locked dependencies.
The visitor baseline is project revision
`7baef7a2c82032f8a25860cb7d18a9d253025758`.

| Measurement | Existing visitor | Value dependencies |
| --- | ---: | ---: |
| Cold analysis, seconds | 2.161 | 1.708 |
| Warm analysis median, seconds | 0.451 | 0.454 |
| Reanalysed files after `max:10` becomes `max:20` | 3 | 1 |
| Reanalysed files after `string` becomes `array` | 3 | 2 |

Warm medians use five runs. Both edits took about one second with either
implementation. These are small synthetic measurements on a shared host;
the cold timings do not establish a general speedup. Full samples are in
[measurements.json](form-request-value-cache/measurements.json).

The selectivity improvement is observable: an equivalent payload retains its
consumer's cached result, and changing the payload leaves ordinary references
to the same request cached. An initial prototype without persisted values
took 0.848 seconds warm versus 0.433 for the visitor. The retained manifest
shortcut removes that regression.

## Verification and release follow-up

Removing the visitor without adding value dependencies reproduced a stale
caller after a rules body changed from `string` to `array`. The implemented
dependency fixes that case. The additional subprocess suite covers selective
invalidation, independent receiver and method keys, descendant creation and
removal, lifecycle recovery, external constants, trait consumers, and
dependencies emitted during private-property inference. Manifest tests cover
corruption recovery and warm reuse without parsing request sources. Further
adversarial cases verify configuration changes and recovery when an external
rule provider or receiver class disappears and returns.

Current local results:

- `php vendor/bin/phpunit --exclude-group subprocess`: 5,651 tests,
  18,633 assertions, two skips for the absent optional Larastan integration.
- `php vendor/bin/phpunit tests/FormRequestValueCacheTest.php`: 20 tests,
  322 assertions pass.
- `php vendor/bin/phpstan analyse`: passes without path restrictions.
- `composer cs`, `composer validate --strict`, and `composer docs:check`: pass.
- The legacy cache suite retains its diagnostic and cached-versus-fresh
  assertions, with global-invalidation expectations changed to selective
  value dependencies. Its pinned-development-build run has 21 failures among
  57 cases. These failures remain visible for verification against the
  stable release.
- The Nix cache job reproduces those same 21 failures across 74 tests,
  including the first 17 new cases before the final hardening pass. PHP
  8.1–8.5, Laravel 11–13, Larastan compatibility, static analysis, and the
  runtime audit jobs pass.

The current PHPStan development build fails to discover some classes exposed
by Composer loaders registered in fixture bootstrap files. The unchanged
visitor implementation also fails on that build; a representative external
package case passes with PHPStan 2.2.7. Keeping the visitor would not solve
this compatibility problem. Before adoption, rerun these cases against the
stable release and investigate any remaining failures rather than weakening
their assertions.

Reliability verdict: **PASS_WITH_RESIDUAL_RISK**. Independent review found no
additional defect in the migration. The focused evidence supports adoption;
the pinned development build's compatibility failures remain a release
follow-up. The full Nix matrix was stopped after the relevant test jobs
reported their results; its minimum-version consumer job was still running.

For release, remove the temporary `@dev` allowance from both Composer
manifests, update PHPStan in all dependency locks and regenerate the matching
Nix vendor hashes. Run `composer test:form-request-cache`, unrestricted static
analysis, and `nix flake check --keep-going -L` against the stable release.
Investigate any remaining compatibility failures before publishing support
for that release.

## Reproduction

From this branch, create a visitor baseline with the same dependency locks:

```sh
git worktree add --detach tmp/value-cache-baseline 7baef7a2c82032f8a25860cb7d18a9d253025758
cp composer.json composer.lock tmp/value-cache-baseline/
composer install --working-dir=tmp/value-cache-baseline --no-scripts --no-plugins
python3 scripts/benchmark-form-request-value-cache.py tmp/value-cache-baseline .
php vendor/bin/phpunit tests/FormRequestValueCacheTest.php --no-coverage
composer test:form-request-cache
php vendor/bin/phpstan analyse
```

The benchmark preserves its temporary projects and prints their paths.
