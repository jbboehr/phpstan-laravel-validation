# Development investigations

This directory contains pinned downstream investigations and performance
measurements. These reports preserve the evidence, revisions, environment, and
commands for a specific experiment. They are not user-facing support promises
and should not be silently rewritten to describe a newer dependency release.

Current configuration and feature documentation belongs in the
[documentation book](../pages/SUMMARY.md) and the project
[`README`](../../README.md). Laravel-behavior evidence and contributor testing
guidance belong in the
[version inference audit](../pages/contributing/laravel-version-inference-audit.md)
and [testing guide](../pages/contributing/testing.md).

## Reports

### [BookStack compatibility investigation](bookstack-compatibility-investigation.md)

This records whole-application Larastan coexistence, the diagnostic
differential, and runtime reproduction. The discovered null-rule crash is
fixed; the application-level optional-upload finding remains external.

### [BookStack performance benchmark](bookstack-performance-benchmark.md)

This provides a reproducible baseline/extension comparison for wall time,
memory, result caches, and parallel scaling. Its recorded results are a pinned
2026-08-10 snapshot.

The BookStack reports complement each other. The compatibility investigation
asks whether the extension works and whether its types reveal useful findings.
The benchmark asks what loading it costs on the same application. BookStack has
77 ordinary validation entry points but no FormRequest classes, so neither
report exercises the opt-in FormRequest registry.

### [FormRequest downstream investigation](form-request-downstream-investigation.md)

This records compatibility, inferred-type coverage, conservative lifecycle
fallbacks, and a three-configuration performance benchmark against pinned Koel
and Pterodactyl revisions. It shows useful structural recovery in Koel and the
cost of a shared lifecycle hook in Pterodactyl.

### [Work-project differential follow-up](work-project-differential-follow-up.md)

This records six completed implementation slices derived from sanitized
differential-testing findings in a large Laravel 11 application. It
distinguishes confirmed precision and entrypoint gaps from a
configuration-dependent soundness risk, and preserves the verification behind
each change.

### [Validation parsing investigation](validation-parsing-investigation.md)

This records whether an opt-in `Parse::*` rule could turn selected validated
values into a declared runtime type without disturbing ordinary Laravel
validation semantics. It traces the validator lifecycle across every supported
major, measures a delayed write-back prototype, and documents the presence,
null, wildcard, exclusion, version-floor, and `after()`-ordering hazards it
found. Experimental implementations of `Parse::integer()` and
`Parse::boolean()` have since been built on that design; the report's Status
section records the corrections and remaining constraints exposed while
building them.

### [BookStack parsing smoke test](bookstack-parsing-smoke-test.md)

This records a one-off downstream exercise of the opt-in `Parse::*` rules
against BookStack, confirming that a parsed attribute's inferred type and its
runtime value agree at a real endpoint and that the parsing work regressed
nothing in a whole-application scan. It also records the size-rule hazard
failing in a confusing direction, and measures how often a rule set stored in
a mutable property costs all inference.

### [Validator mutation inference](validator-mutation-inference.md)

This records Laravel's stale validation state, the hybrid invalidation and
diagnostic policy used for inferred validators, and the alias-safe lifecycle
model that would be required for broader precision. It also records downstream
smoke tests against BookStack, Koel, and Pterodactyl, including the adoption
cost of the earlier strict diagnostic prototype.

## Maintenance rules

- Keep exact application, framework, PHPStan, Larastan, PHP, and extension
  revisions with recorded results.
- Distinguish an original failure, an audit-only workaround, and a verified
  production fix.
- Put repeatable tooling under `scripts/`; do not make an external application
  a normal CI dependency merely because it was useful during an investigation.
- Add a new dated result or follow-up section when rerunning an experiment
  against newer dependencies. Do not make old measurements appear current.
- Promote every extension bug found downstream into focused local regression
  coverage before treating the investigation as complete.
