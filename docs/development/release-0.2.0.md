# v0.2.0 release preparation

This release keeps runtime parsing and static analysis in the same Composer
package with the existing dependency graph. Runtime users install the package
under `require`; analysis-only users may use `require-dev`. Moving analyzer
dependencies out of production installations remains deferred.

The [changelog](../../CHANGELOG.md) contains the release notes, including the
experimental API boundaries, stricter parsing grammars, new diagnostics, and
inference corrections. The [installation guide](../pages/guides/parsing-validated-output.md)
explains the production dependency cost.

## Production installation check

Run from the repository root with PHP and Composer available:

```sh
bash scripts/check-runtime-installation.bash HEAD 13.25.0
```

The first argument selects a committed revision; the second selects an
`illuminate/validation` constraint. The defaults are `HEAD` and `^13.0`.
The check needs Composer repository access and retains its temporary directory
for inspection. It does not modify the repository's installed dependencies.

The runner exports the selected commit as a Git ZIP, copies its actual Composer
manifest into a package repository entry, and adds only temporary version and
distribution metadata. Fresh consumers resolve their own lockfiles and run
`composer install --no-dev --classmap-authoritative`. No checkout symlink,
copied vendor directory, or repository development autoloader supplies classes.
The temporary `dev-runtime-installation` version is not a release tag.

The production probe checks that the installed parser converts `'42'` to `42`,
preserves an unparsed field and caller input, returns the same parsed safe
output, and rejects `'042'` without relying on Laravel's native integer rule.
It verifies the installed class location, disabled development dependencies,
and authoritative classmap. The paired `require-dev` consumer must fail with
the exact missing-production-dependency diagnostic.

The 2026-09-07 preparation exercised standalone Illuminate validation versions
10.49.0, 11.51.0, 12.66.0, and 13.25.0 on PHP 8.5.9. All four production
consumers passed, and the development-only control failed as expected. Their
resolved production dependencies included PHPStan 2.2.13 and nikic/php-parser
5.8.0. This is installation evidence; the ordinary repository matrix remains
the broader PHP and Laravel conformance check.

## Final release steps

1. Run the installation check on the release candidate and the normal
   `nix flake check --keep-going -L` verification.
1. Require green CI, including aggregate mutation thresholds, on the candidate
   submitted for release.
1. Review the versioned changelog and merge the release preparation.
1. Tag the verified release revision as `v0.2.0`, then publish its release notes.

Preparing this document does not create a tag or publish a release.
