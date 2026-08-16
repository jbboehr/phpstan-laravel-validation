# Presence and Output Projection

Presence rules decide whether a key must exist. Projection rules decide
whether a successful path appears in `validated()` and whether a parent
array is rebuilt or preserved.

## Presence

| Rule | Successful output |
| --- | --- |
| `required` | Key is present. Blank strings fail. |
| `present` | Key is present. Blank strings may bypass adjacent non-implicit rules. |
| `filled` | If the key is present, it is non-blank. |
| `nullable` | `null` is allowed. Does not make a missing key required. |
| `sometimes` | The key remains optional. |
| `missing` | The path is omitted from successful output. |
| `exclude` | The path is omitted from successful output. |

`required` and `present` are not interchangeable. `present|integer` can
still yield a blank string because `integer` is non-implicit.

## Nested reconstruction

| Parent rule | Nested children |
| --- | --- |
| Bare `array` or, from Laravel 11.23, literal `list` | Parent is rebuilt from validated descendants |
| `array:name,email` or `array_keys:...` | Complete permitted parent is preserved |
| `includeUnvalidatedArrayKeys: true` | Affected bare parents widen; see [Configuration](configuration.md#includeunvalidatedarraykeys) |

An empty explicit rule on a nested parent, including a false
`Rule::requiredIf(false)` or an empty `Rule::when()` branch, still marks
that path for projection and can preserve unvalidated sibling keys.

## Wildcards

Wildcard collections may have integer or string keys. When wildcard and
named rules share a parent, inference unions their possible projected value
types because it cannot preserve every key correlation.

A required wildcard descendant does not require any match to exist. Zero
matches can leave an array parent present with no projected children.

## Conditional presence

Dependent rules such as `required_if`, `present_if`, and `exclude_if`
normally leave the affected key optional because the outcome depends on
another runtime value.

The experimental
[`experimentalConditionalPresenceInference`](configuration.md#experimentalconditionalpresenceinference)
option can resolve definite `present_if`, `present_unless`, `missing_if`,
and `missing_unless` outcomes for one top-level field whose controller is a
required sibling with a finite scalar-literal domain.

`present_if` and `present_unless` require Laravel 10.32 or later.

## Numeric rule keys

Laravel 10 and 11 reindex top-level literal integer rule keys from `0`.
Laravel 12 and later preserve them. The extension follows the detected or
configured Laravel version and falls back to a conservative array type when
that version is unavailable.

## Literal integer keys in output

Canonical numeric key parameters such as `0` use integer offsets. A
non-canonical numeric-looking key such as `01` remains a string offset.
