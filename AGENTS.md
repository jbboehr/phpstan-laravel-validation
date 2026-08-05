# Repository guidance

- Before changing inferred Laravel validation behavior or its expected test
  types, verify the behavior against Laravel itself. Prefer runtime reproduction
  across the supported major versions and inspect the corresponding upstream
  implementation or generated fixtures; do not rely on assumptions from rule
  names or documentation alone.
