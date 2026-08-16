# Laravel Version Behavior

When `laravelVersion` is `auto` or an explicit supported release, inference
follows verified Laravel boundaries. An unavailable, malformed, or
out-of-range version keeps the conservative cross-version type.

The portable audit corpus and focused runtime suites established these
boundaries. Detailed evidence is in the
[Laravel Version Inference Audit](../contributing/laravel-version-inference-audit.md).

## Contract changes in the portable corpus

| Boundary | Effect |
| --- | --- |
| Laravel 12.0 | Top-level literal integer rule keys are preserved instead of reindexed from `0` |
| Laravel 12.22 | `integer:strict` requires a native integer |
| Laravel 13.4 | `ascii` requires a native string |

## Rules and builders introduced in the supported range

| Version | What changes for inference |
| --- | --- |
| 10.21.1 | `In` / `NotIn` serialize enum cases |
| 10.32 | `present_if` / `present_unless` exist; experimental presence refinement may apply |
| 10.33 | `hex_color`; `Rule::unless()` |
| 10.34 | `extensions` |
| 10.36 | `In` / `NotIn` constructors accept scalar, variadic, and `Arrayable` inputs |
| 10.46 | `Enum::only()` / `Enum::except()` |
| 11.0 | `prohibited_if_accepted`, `prohibited_if_declined`; Laravel 10 trims password fields, 11+ does not |
| 11.0.3 | `list`; `required_if_declined` |
| 11.7 | `Rule::array()` |
| 11.8 | `contains` |
| 11.23 | Literal `list` participates in nested reconstruction; `Dimensions` ratio methods |
| 11.40 | Fluent `Date` builder |
| 11.41 | Date chains usable inside rule lists |
| 11.42 | Fluent `Numeric` builder |
| 11.43.2 | Date chains usable as standalone field rules |
| 12.16 | `in_array_keys`; `Rule::contains()` |
| 12.22 | `doesnt_contain`; `Rule::doesntContain()` |
| 12.40 | `encoding` |
| 12.44 | `Rule::dateTime()` and now-relative date predicates |
| 12.55 | `Numeric::integer(strict: true)`; `Rule::string()` |
| 13.4 | `hex_color` rejects compatible `Stringable` objects |
| 13.21 | Native-string-only `base64` |
| 13.24 | `array_keys`; `Rule::arrayKeys()` |

Before a builder or rule exists, a matching name may be an application
macro or a missing validator method. Inference stays conservative there.

## How version is chosen

See [`laravelVersion`](configuration.md#laravelversion).
