# Validation Rule Reference

This page is a lookup table for built-in string rules. It records the
accepted-value type the extension emits after successful validation.

Presence, exclusion, and nested reconstruction are separate from the value
type. See [Presence and Output Projection](presence-and-projection.md).
Fluent objects are in [Rule Builders](rule-builders.md). Shared conservative
fallbacks are in [Static Resolvability](static-resolvability.md). The
inventory and status counts are in
[Validation Rule Coverage](validation-rule-coverage.md).

`int` and `bool` aliases normalize to `integer` and `boolean` before type
resolution, matching Laravel's `ValidationRuleParser::normalizeRule()`.

Before a named rule exists in the detected Laravel version, the same spelling
may be an application alias. Inference then stays `mixed`.

Optional non-implicit rules still admit a blank string unless `required`,
`present`, HTTP-normalization, or another implicit rule prevents that bypass.

## Exact accepted sets

| Rule | Successful native type |
| --- | --- |
| `accepted` | `'yes'\|'on'\|'1'\|1\|'true'\|true` |
| `declined` | `'no'\|'off'\|'0'\|0\|'false'\|false` |
| `boolean` | `bool\|0\|1\|'0'\|'1'` |
| `in:...` | Parameter-aware union of values Laravel can accept and preserve through loose string comparison |

Numeric `in` parameters can narrow representable native integers to
literals. They retain broad `float`, `numeric-string`, and `Stringable`
branches. A float parameter also retains broad `int` because PHP's
`precision` can change the serialized spelling.

## Native strings

| Rule | Successful native type |
| --- | --- |
| `string`, `lowercase`, `uppercase` | `string` |
| `email`, `alpha`, `url`, `uuid`, `ulid`, `ip`, `ipv4`, `ipv6`, `mac_address`, `timezone`, `active_url`, `current_password` | `non-empty-string` |

## Coercive text

These rules admit values Laravel stringifies for the check, then preserve
the original native value.

| Rule | Successful native type |
| --- | --- |
| `alpha_dash` | `float\|int\|non-empty-string` |
| `alpha_num` | `float\|int<0, max>\|non-empty-string` |
| `json` | `float\|int\|non-empty-string\|Stringable\|true` |
| `regex`, `not_regex` | `float\|int\|string` |
| `ascii` | `string` from Laravel 13.4; earlier supported releases preserve a broad weakly coerced union |
| `hex_color` | `non-empty-string` from Laravel 13.4; `non-empty-string\|Stringable` from 10.33 through 13.3; `mixed` before 10.33 |
| `base64` | `non-empty-string` from Laravel 13.21; `mixed` before that |

## Dates

| Rule | Successful native type |
| --- | --- |
| `date`, `date_equals`, `after`, `after_or_equal`, `before`, `before_or_equal` | `DateTimeInterface\|float\|int\|non-empty-string` |
| `date_format` | `float\|int\|non-empty-string` (`date_format` rejects `DateTimeInterface`) |

These rules do not parse input into a canonical date object.

## Numbers

| Rule | Successful native type |
| --- | --- |
| `numeric`, `decimal`, `digits`, `digits_between`, `max_digits`, `min_digits`, `multiple_of` | `float\|int\|numeric-string` |
| `integer` | `float\|int\|numeric-string\|Stringable\|true` |
| `integer:strict` | `int` from Laravel 12.22; earlier releases ignore `strict` and keep the ordinary `integer` union |

PHPStan cannot express “integral floats only.” The non-strict numeric unions
are therefore broader than Laravel's successful subset and still sound.

## Arrays

| Rule | Successful native type |
| --- | --- |
| `array` | `array` |
| `array:name,email` | `array{name?: mixed, email?: mixed}` |
| `array_keys:...` | Optional-key shape from Laravel 13.24; `mixed` before that |
| `list` | `list<mixed>` from Laravel 11.0.3; `mixed` before that |
| `required_array_keys:name` | `array` intersected with a required `name` offset |
| `contains`, `doesnt_contain`, `in_array_keys` | `array` from Laravel 11.8, 12.22, and 12.16 respectively; `mixed` before those releases |

Bare `array` and, from Laravel 11.23, literal `list` also decide nested
reconstruction. See [Presence and Output Projection](presence-and-projection.md).

## Files

| Rule | Successful native type |
| --- | --- |
| `file`, `image`, `mimes`, `mimetypes`, `dimensions` | `Symfony\Component\HttpFoundation\File\File` |
| `extensions` | Same Symfony `File` type from Laravel 10.34; `mixed` before that |
| `encoding` | Broad preserved union from Laravel 12.40; `mixed` before that |

Laravel validates and preserves the original file. It does not construct a
separate dimensions or MIME value.

## Enum

A string `enum` rule is conservative unless the enum class is recovered from
a [fresh `Enum` builder](rule-builders.md#enum). The builder includes
statically visible cases, backing values, and weakly coerced native values
Laravel can accept and preserve.

## Neutral rules

These names are recognized. They contribute no local accepted-value type.
Adjacent value rules remain responsible for the native family.

| Family | Rules |
| --- | --- |
| Size and comparison | `between`, `gt`, `gte`, `lt`, `lte`, `max`, `min`, `size` |
| Cross-field and domain predicates | `accepted_if`, `confirmed`, `declined_if`, `different`, `distinct`, `doesnt_end_with`, `doesnt_start_with`, `ends_with`, `exists`, `filled`, `in_array`, `not_in`, `password`, `same`, `starts_with`, `unique` |
| Flow, presence, and projection | `bail`, `exclude`, `exclude_if`, `exclude_unless`, `exclude_with`, `exclude_without`, `missing`, `missing_if`, `missing_unless`, `nullable`, `present`, `present_if`, `present_unless`, `prohibited`, `prohibited_if`, `prohibited_unless`, `prohibits`, `required`, `required_if`, `required_unless`, `required_with`, `required_with_all`, `required_without`, `required_without_all`, `sometimes` |

Neutral does not mean ignored. `required`, `present`, `missing`, `nullable`,
`sometimes`, and `exclude*` have tree-level handling. `min` can refine an
already known string or collection to its non-empty form when the parameter
is definitely positive.

`not_in` is type-neutral because PHPStan has no useful general complement
for Laravel's loose comparison. A fresh `Rule::notIn()` builder is therefore
not a value-narrowing rule.

## Conservative `mixed` fallbacks

These reserved names have no built-in accepted-value model:

`missing_with`, `missing_with_all`, `present_with`, `present_with_all`,
`required_if_accepted`, `required_if_declined`, `prohibited_if_accepted`,
`prohibited_if_declined`.

They remain optional or `mixed` rather than inventing a correlated union
over another field.

## Adjacent-rule refinement

Adding a native-family rule intersects the unions:

```php
$validated = Validator::make($input, [
    'age' => 'required|integer|string',
])->validated();

\PHPStan\dumpType($validated);
// array{age: numeric-string}
```
