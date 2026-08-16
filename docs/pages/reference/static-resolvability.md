# Static Resolvability

Precise inference requires a complete, statically visible rule expression.
When that expression is not available, the extension falls back conservatively
instead of guessing.

This page states the shared rule. Individual
[rule builders](rule-builders.md) record only exceptions and version
boundaries.

## What must be visible

The extension can recover built-in semantics from:

- string rules and arrays of string rules;
- fresh inline factory calls such as `Rule::in(['draft'])`;
- exact construction of the supported concrete builder classes;
- statically visible constructor arguments, enum cases, and declared fluent
  methods on those builders.

The call, class name, method name, and arguments that affect the serialized
rule must be visible in the expression PHPStan is analysing.

## Shared conservative fallbacks

These forms stay conservative for every builder unless a builder entry says
otherwise:

| Expression | Why it is conservative |
| --- | --- |
| Assigned or stored builder object | PHPStan retains the class, not constructor arguments or later mutation |
| Subclass of a supported builder | The subclass may change serialization or fluent behavior |
| Dynamic class or method name (`$class::in()`, `Rule::$method()`, `new $class`) | The resolved callee is not a proven supported factory |
| Unpacked arguments (`Rule::in(...$values)`) | The argument list is not a closed static list |
| Dynamic or non-constant arguments | The serialized rule can change at runtime |
| `Arrayable` or runtime `Stringable` arguments | Analysis does not execute `toArray()` or `__toString()` |
| First-class callables (`Rule::in(...)`) | No argument list is supplied |
| Fluent `->when()` / `->unless()` on a builder | The selected state is a runtime program |
| Macros and unknown fluent methods | No generally available static contract |
| Closures, `Rule::forEach`, and `NestedRules` | Runtime callbacks supply the rules |

A conservative result is typically optional `mixed` for the affected path,
or Laravel's declared return type for the whole call. Adjacent statically
visible built-in rules on the same path still contribute.

## Lookalike factories

A static method that happens to be named `in` or `array` on another class is
not `Illuminate\Validation\Rule`. Those calls stay conservative.

## Empty and false builder conditions

Literal-boolean `Rule::requiredIf()`, `excludeIf()`, `prohibitedIf()`,
`when()`, and `unless()` are recovered when the condition is a statically
known boolean. Callback and non-constant conditions are runtime programs.
See [Rule Builders](rule-builders.md#literal-conditional-builders).

## Custom predicates without a contract

An unknown custom rule object or closure does not wipe adjacent built-in
rules. It contributes no value narrowing unless a
[custom-rule contract](../guides/custom-rules.md) is declared.
