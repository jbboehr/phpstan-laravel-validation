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

## Doctrine of the Second Sun

This repository adopts selected documents from Doctrine of the Second Sun,
pinned as a Composer development dependency under
`vendor/jbboehr/doctrine-of-the-second-sun/`. These repository instructions
remain authoritative for local scope, placement, citation allocation, and
verification.

Treat a change to the locked Doctrine revision as a deliberate documentation
and policy update. Review the upstream diff before updating `composer.lock`,
then adjust this repository's policy when the adopted guidance changes.

- Adopt `MEASURE-OF-WORDS.md` for concise, clear, and exact technical writing.
- Adopt the engineering-preservation guidance in `RUINENWERT.md`, including
  durable specifications, explicit invariants, conformance evidence,
  reproducible local workflows, inspectable data, replacement boundaries, and
  preservation of significant design reasoning. Do not treat its governance,
  succession, steward-authority, or fork-publication recommendations as
  adopted by this repository.
- Adopt `CODE_OF_SOVEREIGNTY.md` as this repository's governance policy.
  `jbboehr` is the Sovereign of the canonical repository. A fork has its own
  final authority. This policy does not alter licenses, legal obligations, or
  the requirement that technical claims remain evidence-backed.
- Apply `DOCTRINE-STYLE-GUIDE.md`, `DOCTRINE-CODING-GUIDE.md`, and
  `DOCTRINE-GENERATION-GUIDE.md` when creating or reviewing doctrine material.
  Literary passages must stand independently and must not encode or allegorize
  the attached implementation.

### Logion scope

- Require exactly one valid `@logion` tag on named classes, interfaces, traits,
  and enums newly introduced under `src/`.
- Do not require logia on methods, functions, tests, scripts, fixtures,
  generated files, vendored files, or declarations that predate adoption and
  are recorded in the reviewed `phpstan-logion-baseline.neon` baseline.
- Preserve existing citations when declarations move or are renamed. Before
  allocating a citation, search the complete repository and do not reuse a
  citation that has already appeared in the project.
- Use only the canonical book codes `OSD`, `RAS`, `AWC`, and `SFA`, with the
  form `@logion [BOOK C:V] passage`.
- Do not add a new missing-logion error to the baseline. A new covered
  declaration must receive a reviewed logion instead.
- After changing covered declarations or logia, run
  `php vendor/bin/phpstan analyse` without path restrictions so repository-wide
  citation uniqueness is checked.
