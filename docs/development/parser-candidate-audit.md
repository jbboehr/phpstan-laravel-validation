# Parser candidate audit

## Decision

Laravel's 114 built-in rule names do not justify 114 parsing rules. Most rules
describe predicates, presence, projection, relationships, or constraints on an
already useful native type. Giving each one a `Parse::*` counterpart would
reproduce Laravel's overloaded rule catalog instead of establishing canonical
representations.

The strongest additional first-party parser candidates were:

1. Laravel-compatible or strict formatted date/time input to
   `DateTimeImmutable`;
2. timezone identifiers to `DateTimeZone`;
3. an explicit accepted/declined token grammar to `bool`;
4. top-level-specific JSON decoding, after a separate structural investigation;
5. canonical Base64 decoding, if semantic normalization without a narrower PHP
   type proves useful.

Date/time, timezone, and accepted/declined token parsing are now implemented.
JSON should not enter the runtime API until its output families and
nested-projection behavior have a written contract.

Do not add a core runtime dependency merely to supply UUID, ULID, URI, email,
IP-address, color, or decimal value objects. Applications can implement those
choices now through `BaseParsingRule<T>`. A future adapter package can support a
specific value-object library without making that library part of the core
contract.

## Scope and evidence

This audit covers the 114 names reserved by `TypeResolver` at extension revision
`e2c2fe7`. That inventory corresponds to Laravel 13.25 and is documented in the
[validation rule coverage survey](../pages/reference/validation-rule-coverage.md).

Candidate source behavior was inspected at the pinned Laravel fixture commits:

| Laravel | Version | Commit |
| --- | --- | --- |
| 10 | 10.50.2 | `3ff39b7a9b83e633383ec9b019827ed54b6d38bc` |
| 11 | 11.55.0 | `dc7ec34ae95bacf4a63b96ec81482b4f3e702289` |
| 12 | 12.64.0 | `727a8ea2949c23ca8b5316b86a00984b6017b7a0` |
| 13 | 13.24.0 | `6d481710375d2aa67656922ef760cdd2b18bcfe0` |

A direct `Validator::make()` probe then ran against the current floating Nix
profiles:

| Laravel | Commit |
| --- | --- |
| 10.50.3 | `74e222cee687f957d95aaadddae69270e3205cf7` |
| 11.55.1 | `8d786e25c5fb41eb472e86b465b328b494a0da89` |
| 12.66.0 | `82a53323c701a668f9054cbeb1d6b6cdbb6a5e10` |
| 13.25.0 | `ed36fe882bd4eed4e6ff75343cbad8dbda03fdba` |

Each supported major produced the same successful output:

| Rule and input | Value returned by `validated()` |
| --- | --- |
| `accepted`, `'yes'` | `string('yes')` |
| `declined`, `'off'` | `string('off')` |
| `date_format:Y-m-d`, `'2026-08-20'` | `string('2026-08-20')` |
| `date`, a `DateTimeImmutable` | The same object |
| `timezone`, `'America/Los_Angeles'` | `string('America/Los_Angeles')` |
| `json`, `'{"answer":42}'` | The original JSON string |
| `json`, `42` | `int(42)` |

Laravel 13.25 also returned the original `'aGk='` from `base64`; that rule did
not exist before Laravel 13.21. These probes confirm output preservation. They
do not define the proposed parsers' grammars. A parser should use a narrower,
deliberate contract where Laravel's predicate is coercive or environment
dependent.

## Selection criteria

A built-in parser should satisfy all of these conditions:

1. It produces one canonical representation or fails.
2. The representation has a useful declared PHP type.
3. Its grammar is deterministic and independent of services, mutable
   application state, locale, and process defaults.
4. Its behavior can be tested as one runtime-to-static contract.
5. Its dependency cost is proportionate to its general usefulness.
6. It remains one input value to one output value. It does not become object
   hydration or a schema language.

A parser can still be useful when its input and output are both strings, as
with decoded Base64 bytes. That case ranks below a parser that also gives
PHPStan a materially stronger type.

## Complete inventory

Every reserved Laravel rule has one primary disposition in this audit:

| Disposition | Rule names | Count |
| --- | --- | ---: |
| Already served by current parsers or adjacent numeric constraints | `Boolean`, `Decimal`, `Digits`, `DigitsBetween`, `Enum`, `Integer`, `MaxDigits`, `MinDigits`, `MultipleOf`, `Numeric` | 10 |
| Credible dependency-free parser track | `Accepted`, `Base64`, `Date`, `DateFormat`, `Declined`, `Json`, `Timezone` | 7 |
| Useful only with a chosen value-object dependency | `ActiveUrl`, `Email`, `HexColor`, `Ip`, `Ipv4`, `Ipv6`, `MacAddress`, `Ulid`, `Url`, `Uuid` | 10 |
| Same-native-type normalization with little static gain | `Alpha`, `AlphaDash`, `AlphaNum`, `Ascii`, `DoesntEndWith`, `DoesntStartWith`, `Encoding`, `EndsWith`, `Lowercase`, `StartsWith`, `String`, `Uppercase` | 12 |
| No distinct parser responsibility | All rules in the grouped table below | 75 |
| **Total** |  | **114** |

The first row does not claim that one parser replaces every listed predicate.
`Parse::integer()`, `Parse::float()`, and the numeric Laravel rules have
different jobs. Digit counts, decimal places, multiples, and ranges remain
constraints that can accompany a parser. They do not each define a canonical
output representation. In particular, parsing a digit string as an integer can
destroy meaningful leading zeroes.

The 75 rules with no distinct parser responsibility divide as follows:

| Reason | Rules | Count |
| --- | --- | ---: |
| Validation flow, presence, or projection | `Bail`, `Exclude`, `ExcludeIf`, `ExcludeUnless`, `ExcludeWith`, `ExcludeWithout`, `Filled`, `Missing`, `MissingIf`, `MissingUnless`, `MissingWith`, `MissingWithAll`, `Nullable`, `Present`, `PresentIf`, `PresentUnless`, `PresentWith`, `PresentWithAll`, `Prohibited`, `ProhibitedIf`, `ProhibitedIfAccepted`, `ProhibitedIfDeclined`, `ProhibitedUnless`, `Prohibits`, `Required`, `RequiredIf`, `RequiredIfAccepted`, `RequiredIfDeclined`, `RequiredUnless`, `RequiredWith`, `RequiredWithAll`, `RequiredWithout`, `RequiredWithoutAll`, `Sometimes` | 34 |
| Already typed structural or file predicates | `Array`, `ArrayKeys`, `Contains`, `DoesntContain`, `Dimensions`, `Extensions`, `File`, `Image`, `InArrayKeys`, `List`, `Mimes`, `Mimetypes`, `RequiredArrayKeys` | 13 |
| Comparison or size predicates | `After`, `AfterOrEqual`, `Before`, `BeforeOrEqual`, `Between`, `DateEquals`, `Gt`, `Gte`, `Lt`, `Lte`, `Max`, `Min`, `Size` | 13 |
| Cross-field, membership, database, or environment predicates | `AcceptedIf`, `Confirmed`, `CurrentPassword`, `DeclinedIf`, `Different`, `Distinct`, `Exists`, `In`, `InArray`, `NotIn`, `Password`, `Same`, `Unique` | 13 |
| Application-defined regular-expression predicates | `NotRegex`, `Regex` | 2 |

Presence and projection rules determine whether parsed values reach output;
they are not conversions. Comparison and membership rules constrain a parsed
domain but do not choose its representation. Database, password, DNS, callback,
and regular-expression behavior may have no stable local contract at all.

## First-party candidates

### Formatted date and time

Laravel's `date` rule accepts `DateTimeInterface`, strings, and numeric values
through broad `strtotime()` behavior. `date_format` checks an explicit format.
Neither rule constructs a date object from successful scalar input.

A parser can provide a substantially stronger boundary:

```php
Parse::dateTime()
Parse::dateTime(format: 'Y-m-d', timezone: 'UTC')
// ParsingRule<DateTimeImmutable>
```

The initial recommendation was to require an explicit format and avoid
Laravel's relative `strtotime()` grammar. The implemented API instead makes
Laravel-compatible `date` acceptance the default while requiring successful
`DateTimeImmutable` construction. Supplying a format, or an ordered non-empty
list of formats, opts into exact round-trip validation that rejects warnings,
normalization, and trailing data. The configured timezone makes output stable
when input carries no zone. The parser additionally rejects embedded NUL bytes
instead of reproducing PHP date parsing's hidden-byte ambiguity.

Laravel changed `date_format` to construct its comparison date in UTC during
the supported version range. A parser-owned timezone contract avoids making
its produced value depend on that framework detail.

**Original recommendation:** highest-value next parser; complete the grammar
and timezone decision before implementation.

**Follow-up:** `Parse::dateTime()` now implements both contracts. UTC is its
stable output fallback; existing `DateTimeImmutable` values pass through and
other `DateTimeInterface` values are copied to immutable values.

### Timezone identifiers

Laravel's `timezone` rule checks membership in `timezone_identifiers_list()`
and preserves the identifier string. A parser can instead return:

```php
Parse::timezone()
// ParsingRule<DateTimeZone>
```

This requires no third-party dependency. The implemented parser mirrors the
default identifier set. Group and country filtering can remain an adjacent
ordinary Laravel rule from Laravel 10.12 onward; Laravel 10.7 through 10.11
silently ignores those parameters, so those releases need an explicit
membership rule. The parser also accepts an existing `DateTimeZone` as a fixed
point. It does not rely only on the `DateTimeZone` constructor, which accepts
offsets, abbreviations, backward-compatible aliases, and other representations
outside Laravel's default identifier list.

**Follow-up:** `Parse::timezone()` now implements this contract and produces
`DateTimeZone` for both direct factory use and static inference.

### Accepted and declined tokens

Laravel accepts these exact sets across all supported majors:

```text
accepted: 'yes', 'on', '1', 1, true, 'true'
declined: 'no', 'off', '0', 0, false, 'false'
```

It preserves the selected token. Mapping the first set to `true` and the second
to `false` is deterministic and useful for form and checkbox input.

Do not silently widen `Parse::boolean()`. Its current six-value grammar is
deliberately exact. Possible explicit APIs include separate
`Parse::accepted()` and `Parse::declined()` rules or a clearly named combined
boolean grammar. Separate rules could expose literal `true` and `false` output
types; a combined grammar would expose `bool`.

Conditional `accepted_if` and `declined_if` are not separate parser candidates.
They control whether a predicate is active and can leave the original value
untouched when inactive.

**Follow-up:** `Parse::accepted()` and `Parse::declined()` now implement the
separate literal contracts. They produce `true` and `false`, respectively,
without widening `Parse::boolean()`. Like every parser, they leave presence to
an adjacent rule; `required` restores the built-in rules' implicit-presence
behavior. No conditional parser was added.

### JSON

Laravel's `json` rule accepts any JSON top-level value and preserves the input.
Because Laravel calls JSON functions from weakly typed framework code, even an
integer such as `42` can validate and remain an integer.

A general `Parse::json()` returning `mixed` adds little static information.
Useful APIs would distinguish the expected top-level representation:

```php
Parse::jsonList()    // ParsingRule<list<mixed>>
Parse::jsonObject()  // ParsingRule<array<array-key, mixed>> or stdClass
Parse::jsonScalar()  // ParsingRule<bool|float|int|string|null>
```

The [JSON parser investigation](json-parser-investigation.md) resolved these
questions far enough to reject a general parser for now. Associative decoding
requires `array-key` rather than string keys, large integers need an explicit
precision policy, duplicate members silently overwrite, and a valid exponent
can decode to `INF`.

More importantly, delayed parser write-back means Laravel child rules inspect
the encoded string, not the decoded value. An explicit child fails as missing,
while a wildcard descendant can match nothing and let arbitrary decoded
elements through. A sound inferred type can retain the broad parser-produced
branch, but the rule set still misleadingly resembles structural validation.

**Recommendation:** no general parser. If a concrete consumer justifies a
first-party `Parse::jsonList()` or `Parse::jsonObject()`, make it a terminal,
string-only decoder with explicit numeric and object policies, and diagnose
descendant Laravel rules on the same field. Prefer an application-defined
typed parser when decoded values need a declared nested shape.

### Base64

Laravel 13.21 added a strict canonical Base64 predicate and preserves the
encoded string. A parser could return the decoded bytes:

```php
Parse::base64() // ParsingRule<string>
```

The runtime result is useful, but the PHP type remains `string`. The contract
must decide empty payloads and URL-safe Base64; neither follows automatically
from the rule name.

**Recommendation:** low priority unless a concrete consumer needs decoded
bytes. A byte-string value object would provide more static distinction but
would reintroduce the dependency question.

## Candidates requiring value-object policy

Laravel's `Uuid`, `Ulid`, `Url`, `Email`, `Ip`, `Ipv4`, `Ipv6`, `MacAddress`,
and `HexColor` rules already establish a useful string type. Parsing becomes
materially stronger only when the result is a declared value object.
`ActiveUrl` adds a DNS predicate; network liveness is not a representation and
should remain separate from parsing.

Possible representations include Symfony UID objects, a URI implementation,
an email-address class, an IP-address class, and a color class. PHP has no
single standard representation for most of these. Normalization is also
domain-sensitive: URL equivalence, IDNA handling, email local-part case, IPv6
compression, UUID versions, and color shorthand all need explicit policy.

The development dependency graph currently contains `symfony/uid`,
`brick/math`, and `egulias/email-validator` through Laravel and its test
closure. They are not core runtime dependencies. Public APIs must not rely on
those transitive packages.

`Decimal` has the same issue. `Parse::float()` is appropriate when binary
floating point is the desired representation. Exact decimal arithmetic would
require a canonical decimal string contract or a declared decimal value object
such as `BigDecimal`; it should not masquerade as another float grammar.

**Recommendation:** keep these as application-defined `BaseParsingRule<T>`
implementations until the project deliberately adopts an adapter dependency or
a separate integration package.

## Same-type normalization is not enough

The `Alpha`, `AlphaDash`, `AlphaNum`, `Ascii`, `DoesntEndWith`,
`DoesntStartWith`, `Encoding`, `EndsWith`, `Lowercase`, `StartsWith`, `String`,
and `Uppercase` rules produce strings or constrain existing strings. A parser
that merely returns the same string adds no type information. A parser that
lowercases, uppercases, strips characters, or transcodes input changes invalid
input into valid input according to an application-specific normalization
policy.

Such transformations may be useful, but they are not general counterparts to
the Laravel predicates. `Encoding` is especially ambiguous: validation answers
whether bytes already have an encoding, while transcoding needs both a source
policy and a target representation.

**Recommendation:** no first-party parsers without a concrete normalization
contract and downstream demand.

## Remaining sequence

Date/time, timezone, and accepted/declined parsing completed the first three
recommendations. The remaining candidates are:

1. Add no JSON parser until a concrete terminal-decoding use case justifies
   the contract recorded in the JSON parser investigation.
2. Add no value-object dependencies to core. Collect real consumer demand and
   prefer optional adapters.
3. Add Base64 decoding only for a demonstrated normalization use case.

Each implementation slice must include grammar tests, Laravel runtime and
static-inference conformance, optional and nullable paths, nested and wildcard
paths, exclusions, lifecycle failure modes, every supported PHP/Laravel
profile, mutation-shard ownership, and user documentation. A parser's accepted
grammar and its emitted PHPStan type remain one contract.

## Reproduction

The audit used the repository's Nix Composer closures, not the mutable local
`vendor/`, for the four-major runtime probe. For each `*-latest` audit profile,
it loaded the profile's `vendor/autoload.php`, constructed
`Illuminate\Validation\Factory` with an array-backed translator, validated the
candidate values above, and recorded `get_debug_type()` plus value identity.

The source audit used
`git show <commit>:src/Illuminate/Validation/Concerns/ValidatesAttributes.php`
against the four pinned Laravel commits. The complete rule count was checked by
partitioning `TypeResolver::BUILT_IN_RULE_NAMES`; every name appears exactly
once in the disposition table.
