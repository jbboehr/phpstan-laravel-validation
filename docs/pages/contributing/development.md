# Development

Enter the reproducible development environment and install ordinary mutable
Composer dependencies for interactive work:

```sh
nix develop
composer install
```

Before submitting a change, run the complete normal validation suite:

```sh
nix flake check --keep-going -L
```

That command runs the supported-PHP PHPUnit matrix, PHPStan, php-cs-fixer,
Composer and PHP linting, documentation formatting, the mdBook build and
link check, Larastan and minimum-PHPStan consumer checks, and the pinned
Laravel runtime audits. It does not run mutation testing.

Focused Composer commands remain useful while developing:

```sh
composer exec phpunit
composer exec phpstan analyse
composer cs
composer validate --strict
```

Choose a test layer with
[Testing and Runtime Verification](testing.md).

## Documentation

Build and check the mdBook:

```sh
composer docs
composer docs:check
composer docs:serve
```

`composer docs:check` builds the book and fails on broken same-site
relative links in the generated HTML.

The sidebar keeps every page's h2/h3 outline open. mdBook only injects
headings for the active page, so `docs/theme/phpstan-laravel-validation.js`
adds the remaining outlines from `headingsByChapter`. Update that map when
public headings change.

The optional Heliogenesis control is mounted from
`docs/theme/phpstan-laravel-validation.js`. The unmodified Doctrine
runtime lives under `docs/pages/assets/heliogenesis/`. The theme marks the
reading plane so the event can light the article and run document
tomography.

The Document Looks Back integration is mounted separately from
`docs/pages/assets/document-looks-back/`. After it mounts,
`window.documentLooksBack` is the Doctrine controller, so
`window.documentLooksBack.summon()` requests one immediate eye. A mount
or renderer failure of either integration leaves the documentation usable.

The copied-runtime tests live in the `documentation` PHPUnit group. They
compare those assets to the root Composer Doctrine pin. Laravel-matrix
and minimum-PHPStan Nix jobs exclude the group because those lockfiles
do not install that pin.

Akashi formats inline PHP fences in the README, changelog, and `docs/`:

```sh
composer docs:format
composer docs:format:fix
```

`composer cs:fix` formats PHP source and those documentation fences.

Akashi checks formatting. It does not execute illustrative fragments as
standalone programs.

The published site is
[https://jbboehr.github.io/phpstan-laravel-validation/](https://jbboehr.github.io/phpstan-laravel-validation/).
[`.github/workflows/pages.yml`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/.github/workflows/pages.yml)
builds the book on `develop` and `master`, and deploys only from `master`.

## Mutation testing

Mutation testing uses an isolated toolchain because Infection requires
PHP 8.3 or newer while this package supports PHP 8.1. It is excluded from
`nix flake check`. Run it explicitly:

```sh
nix build -L .#mutation
```

The package supplies PHP 8.5 with PCOV and preserves the thresholds,
timeouts, test exclusions, and worker count from `infection.json5.dist`.
It divides the source into four cached shard derivations, each using four
Infection workers, then aggregates the project-wide thresholds. GitHub
builds those shards serially so a four-core runner is not oversubscribed.

PHPStan type-inference fixtures that cover extension code must run their
first `gatherAssertTypes()` analysis inside the test body. Data providers
run before PHPUnit starts coverage and can warm PHPStan's process-level
caches. The test-only `AssertsFixtureUnderCoverage` trait implements this
pattern. Infection runs only the individual test cases that cover each
mutant.

Tests in the `subprocess` group remain in the normal suite but are
excluded from mutation testing because child processes cannot observe the
active in-process mutant. The `property` group is also excluded: rerunning
hundreds of generated cases for each mutant would be disproportionate.
Promoted deterministic regressions remain available to Infection.

The Infection configuration also ignores mutations that make
`RuleTreeNode::resolvePath()` recurse without consuming input. Those
mutants can exhaust PHP's native stack before Infection's timeout can
stop the process.

The `php85` Nix development shell includes PCOV for focused manual
investigation.

## Nix dependency hashes

Nix builds offline Composer repositories from committed lockfiles. When
Composer dependencies change, update the hashes in
[`nix/vendor-hashes.nix`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/nix/vendor-hashes.nix) as described in
[CONTRIBUTING.md](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/CONTRIBUTING.md).

## CI

[`.github/workflows/ci.yml`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/.github/workflows/ci.yml) has two
surfaces:

- a conventional PHP baseline job on PHP 8.5 (Composer, PHPUnit, PHPStan,
  php-cs-fixer);
- an exhaustive Nix matrix generated from flake checks, plus mutation.

A documentation failure is a flake-check failure. It is not silent.

## Downstream investigations

Pinned application investigations live under
[`docs/development/`](https://github.com/jbboehr/phpstan-laravel-validation/blob/master/docs/development/README.md). They are evidence for
specific experiments, not user-facing support promises.
