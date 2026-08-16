# Limitations

These are current analysis limits and design constraints. Some follow from
Laravel or PHPStan's type system. Others are the extension's current
static-analysis boundaries. Related fallbacks are defined in
[Static Resolvability](static-resolvability.md).

## Value families

Laravel validation generally does not normalize returned values. `numeric`
produces `int|float|numeric-string`. If the input is known to be a string,
`numeric|string` yields `numeric-string`.

PHPStan cannot express some of Laravel's successful subsets, such as
“integral floats only.” The inferred union is then broader than the runtime
set and still sound.

## Custom rules

Custom-rule contracts describe accepted values only. Custom implicitness and
custom output mutation remain conservative. See
[Custom Validation Rules](../guides/custom-rules.md).

## FormRequest lifecycle

Experimental FormRequest inference is opt-in. It models conventional request
validation and falls back for known lifecycle customization. It cannot
globally track an inherited `setValidator()` call that replaces the
validator before `validated()`. See
[FormRequest Inference](../guides/form-requests.md).

## Larastan stubs

Larastan provides its own stub for `Illuminate\Validation\Validator`.
PHPStan does not merge multiple stubs for the same class. When both
extensions are installed, Larastan's stub takes precedence. Use the
`setRules()` return value. See
[Supported Entry Points](entry-points.md#setrules).

## Application execution

The extension does not boot the Laravel application. It does not discover
service-provider factory configuration, registered string-rule aliases, or
macros by executing application code.

## Mixed factory modes

A single `includeUnvalidatedArrayKeys` option cannot model a process that
uses both including and excluding factories. See
[Configuration](configuration.md#includeunvalidatedarraykeys).

## What the test suite does not prove

The suite includes pinned Laravel runtime audits, PHP and Laravel matrices,
Larastan checks, property tests, and mutation testing. That evidence covers
the supported combinations under test. It is not a claim of universal
soundness for arbitrary runtime extensions.
