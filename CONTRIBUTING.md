# Contributing

Enter the reproducible development environment, then install the ordinary
mutable Composer dependencies used for interactive work:

```sh
nix develop
composer install
```

Focused Composer commands remain useful while developing. Before submitting a
change, run the normal authoritative validation suite:

```sh
nix flake check --keep-going -L
```

This runs the supported-PHP PHPUnit matrix, PHPStan, php-cs-fixer, Composer and
PHP linting, documentation formatting, Larastan and minimum-PHPStan consumer
checks, and the pinned Laravel runtime audits. It does not run mutation
testing. Run that expensive target explicitly with:

```sh
nix build -L .#mutation
```

## Updating Composer dependencies for Nix

Nix creates offline Composer repositories from the committed lockfiles. Their
fixed-output hashes live in [`nix/vendor-hashes.nix`](nix/vendor-hashes.nix).
The main `composer.lock` uses the `root` entry; the remaining named entries
belong to their corresponding lockfile under `nix/composer-locks/`, the
Larastan consumer, or the isolated Infection toolchain.

When Composer dependencies change:

1. Update `composer.json` and `composer.lock` normally. Refresh any affected
   specialized lockfiles as well.
1. Set the matching value in `nix/vendor-hashes.nix` to `""`.
1. Build an affected check with `-L`. Nix will intentionally fail with a
   fixed-output hash mismatch.
1. Copy the reported `got: sha256-...` value into the matching hash entry.
1. Run `nix flake check --keep-going -L` again.

The closure name includes fingerprints of both its manifest and lockfile, so a
stale non-empty hash also forces a rebuild and reports the replacement value.
If Nix is unavailable locally, push the dependency change: the failed Nix
GitHub Actions job repeats the replacement hash near the end of its log and in
the job summary. Update `nix/vendor-hashes.nix` with that value and push again.

Do not copy a hash from an unrelated derivation. The `got:` value reported by
the failed Composer-repository derivation is authoritative.
