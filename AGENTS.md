# Repository guidance

- Before changing inferred Laravel validation behavior or its expected test
  types, verify the behavior against Laravel itself. Prefer runtime reproduction
  across the supported major versions and inspect the corresponding upstream
  implementation or generated fixtures; do not rely on assumptions from rule
  names or documentation alone.
- Treat `docs/laravel-validation-and-type-safety.md` as evidence-backed
  documentation. Before changing concrete Laravel behavior, inferred types,
  supported-version claims, fixture metadata, or test mappings, verify the
  claim against every supported Laravel major and update the corresponding
  runtime tests, static inference tests, and fixtures where applicable.
- Preserve that document's primary purpose: a technically rigorous criticism
  of Laravel validation's runtime semantics. Keep
  `phpstan-laravel-validation` framed as a compatibility and mitigation layer,
  and do not imply that array shapes are inherently unsafe or that DTOs are
  required for static type safety.
