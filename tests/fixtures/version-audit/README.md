# Laravel inference audit snapshots

These JSON files are generated runtime-contract snapshots. Do not edit their
case results or provenance by hand.

Regenerate a complete profile from the repository root with:

```sh
composer test:audit:matrix -- --profile=11.22.0 --profile=11.23.0 --update
```

The portable runner installs Laravel in the ignored
`tmp/version-audit/<profile>` directory, executes every deterministic case,
and records the version and source reference reported by Composer. Exact
profiles reuse matching cached installs; `*-latest` profiles refresh before
every run and compare behavior against the last reviewed snapshot without
requiring an identical patch version or source reference.

Review the full JSON diff and the corresponding Laravel runtime/source behavior
before accepting a snapshot update. A changed snapshot is evidence to
investigate, not an instruction to change inference automatically. Partial
updates using `--case` are deliberately rejected.

See [`docs/testing.md`](../../../docs/testing.md) and
[`docs/laravel-version-inference-audit.md`](../../../docs/laravel-version-inference-audit.md)
for the test workflow and audit methodology.
