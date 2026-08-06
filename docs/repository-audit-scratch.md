# Repository audit — scratch notes

Date: 2026-08-03

Status: Working planning document.

## Executive summary

The repository is compact, readable, and clean under its configured style and
static-analysis tools. The main risk is correctness rather than presentation:
several inferred validation shapes are narrower than values Laravel can return,
which breaks the extension's type-safety guarantee. Some of these cases are
already represented as skipped upstream fixtures or are asserted incorrectly by
the current static inference tests.

The highest-priority work is to establish sound fallback behavior, then correct
wildcard/optional-field semantics and redesign how rule payloads are carried
through PHPStan types.

## Worktree progress

- Optional blank-string bypass is addressed in the current uncommitted
  worktree. The implementation covers empty and whitespace-only strings,
  distinguishes unconditional implicit rules that reject blank values, and
  re-enables ten previously skipped Laravel fixtures.
- The adjacent `array|in` incompatibility is now handled conservatively as an
  array value type rather than an impossible scalar intersection.
- Conditional `accepted_if` and `declined_if` rules are handled conservatively
  in the current uncommitted worktree: they no longer override an explicit
  `required`, and they do not narrow the value when their condition is unknown.
- Scalar `in` inference is addressed in the current uncommitted worktree. It
  now models Laravel's string cast and loose parameter comparison with sound
  unions for numeric strings, integers, floats, booleans, `Stringable` objects,
  null/false for an empty parameter, and matching resource identifiers.
- Integer inference is addressed after the audit. Laravel's filter-based rule
  preserves integral floats, `true`, and compatible `Stringable` objects as
  well as integers and numeric strings, so the resolver now retains the sound
  `float|int|numeric-string|Stringable|true` union. Strict integer validation
  first appears in Laravel 12.22 and is present in Laravel 13; earlier supported
  releases ignore that parameter. The resolver now uses the analyzed Laravel
  version to return `int` at that boundary while retaining the conservative
  union on earlier or unknown versions.
- Current verification: 4,126 tests and 8,401 assertions pass with four known
  skips. PHPStan and PHP_CodeSniffer also pass. Targeted mutation testing kills
  every mutant in the revised `in` resolver; the file-wide run remains below
  the configured threshold because of pre-existing survivors elsewhere.
- The reverse-direction precision audit now exercises 101 preservation-only
  witnesses across all twelve pinned profiles. It confirms that the only
  rule-level version-dependent branches in the portable corpus are
  `integer:strict` on Laravel 12.22+ and `ascii` on Laravel 13.4+. The existing
  Laravel 10 versus 11+ password-field behavior is a separate
  HTTP-normalization boundary. A shared analyzed-project version context now
  narrows all three cases at their verified releases and remains conservative
  when the version or full-framework middleware context is unavailable. The
  audit's version-independent findings are also applied:
  `required|nullable` respects unconditional requiredness regardless of rule
  order, and `regex` and `not_regex` no longer include booleans.

## Prioritized findings

### High priority: unsound inference

1. Optional empty strings bypass non-implicit rules.

   Laravel permits a present empty string to bypass optional rules such as
   `integer`, `array`, and `email`, and `validated()` retains the empty string.
   `TypeResolver` reports only the rule's narrowed type. The Laravel fixture test
   currently skips the upstream cases that expose this behavior.

   Relevant code:

   - `src/Validation/TypeResolver.php`
   - `tests/LaravelInferenceTest.php` (`KNOWN_QUIRKS`)

2. Required wildcard descendants incorrectly require their parent.

   Rules such as `person.*.email => required|email` match no attributes when
   `person` is absent or empty. Validation succeeds and returns an empty array,
   but `RuleTreeNode::resolveOptional()` propagates requiredness through the
   wildcard and reports a required `person` offset.

   Relevant code:

   - `src/Validation/RuleTreeNode.php`
   - `README.md` and `tests/structure/readme.php` currently demonstrate the
     unsafe required-parent shape.

3. `confirmed` invents a validated output key.

   The confirmation field is a validation dependency. Laravel does not include
   `*_confirmation` in `validated()` unless it has its own rule. The resolver
   currently synthesizes this key, and `tests/rules/confirmed.php` asserts the
   incorrect shape.

   Relevant code: `src/Validation/TypeResolver.php::evaluateMap()`.

4. A node's own rules are ignored when the node has children.

   `TypeResolver::evaluate()` chooses map/wildcard evaluation instead of leaf
   evaluation whenever a node has children. For example, `foo => required|string`
   combined with `foo.bar => sometimes|string` can return `foo` as a string at
   runtime while the extension reports an array shape.

   Relevant code: `src/Validation/TypeResolver.php::evaluate()`.

5. Validator rule payloads are not flow-safe.

   `ValidatorType` subclasses `ObjectType` and stores a `RuleTreeNode` payload,
   but that payload is not safely represented across PHPStan unions or mutations.

   Reproduced failures:

   - A conditional choosing validators with different rules collapses to one
     rule shape instead of returning a union.
   - Calling `setRules()` leaves the original rules attached, so a subsequent
     `validated()` call reports the stale shape.

   Relevant code:

   - `src/Type/ValidatorType.php`
   - `src/Extension/ValidatorValidatedExtension.php`

6. Several Laravel rules preserve more native input types than modeled.

   Examples reproduced against Laravel 10.50.2:

   - Addressed in the current uncommitted worktree: `accepted_if` and
     `declined_if` were treated as unconditional type restrictions. When their
     dependent condition is false, Laravel accepts any value permitted by the
     other rules. An explicit `required` also still guarantees input presence,
     so these rules now preserve requiredness and contribute no standalone type
     restriction when their condition is unknown.
   - Addressed after the audit: `alpha_dash` and `alpha_num` accept and
     preserve numeric values. Their inferred types now include native floats,
     all integers for `alpha_dash`, and non-negative integers for `alpha_num`,
     with runtime coverage for Unicode and ASCII modes.
   - Addressed in the current uncommitted worktree: `in:1` accepts and
     preserves numeric-equivalent strings, integers, floats, `true`, and
     `Stringable` objects. Laravel's empty parameter and resource-string cases
     are also modeled and covered by runtime tests.
   - Addressed after the audit: `integer` accepts and preserves integral floats,
     `true`, and compatible `Stringable` objects. The inferred type now includes
     their expressible supertypes.
   - Date comparison and date-format rules can accept numeric values.
   - `json` accepts scalars and stringable objects, not only strings.
   - `ascii` accepts values PHP can coerce to strings.
   - Combining `array` and `in` can resolve to `never`; the implementation
     already marks array `in` rules as incorrect.

   Relevant code: `src/Validation/RuleTreeNode.php::push()`,
   `src/Validation/TypeResolver.php::resolveType()`, and `resolveTypeIn()`.

### High priority: analyzer reliability

7. Valid mixed wildcard rules crash PHPStan.

   A rule set containing both `items.*` and `items.named` throws
   `ShouldNotHappenException`, aborting analysis with an incomplete result. Even
   if precise support is deferred, the extension should return a conservative
   fallback type instead of producing an internal error.

   Relevant code: `src/Validation/TypeResolver.php::evaluateWildcard()`.

### Medium priority

8. PHPUnit succeeds but exits nonzero.

   All 4,464 tests and 8,664 assertions pass, but PHPUnit 10 reports the PHPUnit
   9 `<coverage><include>` element as invalid. Because `failOnWarning="true"`,
   the process exits with status 1 and the CI test jobs fail.

   Relevant code: `phpunit.xml.dist`.

9. Requiredness remains incomplete.

   - Resolved: `nullable` no longer forces a field optional or adds `null` when
     combined with unconditional `required`; both rule orders are covered by
     static tests and pinned Laravel runtime probes.
   - `accepted` and `declined` imply requiredness without an explicit
     `required` rule, but the extension treats them as optional.
   - `present` and `required_array_keys` remain unsupported.

   Relevant code: `src/Validation/RuleTreeNode.php::push()`.

10. Common validation entry points are not covered.

    Missing or incomplete inference includes:

    - `Validator::validate()` and `safe()`
    - `Factory::validate()`
    - Request/controller `validateWithBag()`
    - `FormRequest::validated()`
    - The one-argument form of controller `validateWith()`
    - The rules-array form of controller `validateWith()`

    `ControllerValidateWithExtension` currently requires two arguments even
    though Laravel's request argument is optional.

11. The test strategy permits large precision gaps.

    The generated Laravel test verifies only that an inferred type accepts one
    observed validated value. An unsupported rule resolving to `mixed` therefore
    passes. Several known unsound cases are skipped, while some static tests
    assert the current incorrect behavior.

    Latest generated coverage summary:

    - Lines: 55.5% (222/400)
    - Methods: 33.3% (24/72)

### Lower priority and maintenance notes

- `composer.json` advertises PHP 8.0, while CI starts at PHP 8.1.
- The resolver references Laravel 9's validation implementation despite the
  declared Laravel 9–13 compatibility range.
- README wildcard guidance says indexes must be integers, while Laravel and the
  inferred type support string keys.
- Extension classes duplicate constant evaluation, parsing, type resolution,
  and exception wrapping; a shared service would reduce drift.
- `composer audit` reports three advisory records for the locked development-only
  Laravel 10.50.2 dependency, including a high-severity email validation issue.
  This has limited production exposure because Laravel is used here as a test
  dependency, but CI should make the policy explicit.

## Checks performed

Passed:

- `composer validate --strict`
- PHP_CodeSniffer
- PHPStan at maximum level
- Actionlint
- Bash syntax validation
- `nix flake check -L`

Completed with an issue:

- PHPUnit: 4,464 tests, 8,664 assertions, 16 skipped; exit status 1 because of
  the XML schema warning.
- `composer audit --locked`: three Laravel advisory records.

## Suggested remediation order

1. Add adversarial integration tests for every reproduced unsound case before
   changing the resolver.
2. Make unsupported or ambiguous cases return a conservative type rather than
   throwing or narrowing unsafely.
3. Correct empty-string, wildcard requiredness, confirmation, parent-node, and
   implicit-rule semantics.
4. Redesign the validator rule payload so unions preserve every branch and rule
   mutations invalidate or replace the payload.
5. Continue expanding the soundness and reverse-precision audit across every
   supported Laravel 10–13 rule family.
6. Repair the dual PHPUnit configuration and ensure the CI command exits zero.
7. Expand support for the missing Laravel validation entry points.
8. Update README caveats and compatibility claims to match verified behavior.

## Proposed definition of done for soundness work

- Every supported inferred type accepts all values Laravel can return for the
  same rules.
- Unsupported rules and structures fall back without aborting analysis.
- Optional offsets account for empty-string bypass and zero-match wildcards.
- Validator unions and mutations cannot silently retain an unrelated rule tree.
- The complete local and CI test commands exit zero on every supported PHP and
  Laravel version.
