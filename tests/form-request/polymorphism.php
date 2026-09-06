<?php

declare(strict_types=1);

use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest as Requests;

use function PHPStan\Testing\assertSuperType;
use function PHPStan\Testing\assertType;

function inspectPolymorphicRequest(Requests\PolymorphicRequest $request): void
{
    assertType('array{value: array}|array{value: string}', $request->validated());
    assertType('array|string', $request->validated('value'));
    assertType('array|string', $request->validated(key: 'value', default: 'fallback'));
    assertType('array{value: array}|array{value: string}', $request->safe(['value']));
    assertType('array{value: array}|array{value: string}', $request->safe()->all());
    assertType('array{value: array}|array{value: string}', $request->safe()->only(['value']));
    assertType('array{}', $request->safe()->except(['value']));
    assertType(
        'array{value: array|string, extra: true}',
        $request->safe()->merge(['extra' => true])->all()
    );
}

function inspectFinalRules(Requests\PolymorphicFinalRulesRequest $request): void
{
    assertType('mixed', $request->validated());
    assertType('array', $request->safe()->all());
}

function inspectDocumentedFinal(Requests\PolymorphicDocumentedFinalRequest $request): void
{
    assertType('array{value: string}', $request->validated());
}

function inspectAbstractReceiver(Requests\PolymorphicAbstractRequest $request): void
{
    assertType('array{value: array}', $request->validated());
    assertType('array{value: array}', $request->safe()->all());
}

function inspectSafeOverride(Requests\PolymorphicSafeRequest $request): void
{
    assertType('array{value: string}', $request->validated());
    // The installed Laravel declaration may use a conditional return type.
    assertSuperType('array|Illuminate\Support\ValidatedInput', $request->safe(['value']));
}

function inspectAnonymousDescendant(Requests\PolymorphicAnonymousRequest $request): void
{
    assertType('mixed', $request->validated());
    assertType('array', $request->safe()->all());
}

function inspectFinalChildren(
    Requests\PolymorphicChildRequest $changed,
    Requests\PolymorphicInheritedRequest $inherited,
    Requests\PolymorphicHookRequest $hook
): void {
    assertType('array{value: array}', $changed->validated());
    assertType('array{value: array}', $changed->safe(['value']));
    assertType('array{value: string}', $inherited->validated());
    assertType('array{value: string}', $inherited->safe()->all());
    assertType('mixed', $hook->validated());
    assertType('array', $hook->safe()->all());
}

function inspectMixedReceivers(
    Requests\PolymorphicFinalRulesRequest|Requests\PolymorphicInheritedRequest $open,
    Requests\PolymorphicChildRequest|Requests\PolymorphicInheritedRequest $closed
): void {
    assertType('mixed', $open->validated());
    assertType('array', $open->safe()->all());
    assertType('array{value: array}|array{value: string}', $closed->validated());
    assertType('array{value: array}|array{value: string}', $closed->safe()->all());
}

function inspectTrustedFinal(Requests\PolymorphicTrustedFinalRequest $request): void
{
    assertType('array{value: string}', $request->validated());
    assertType('array{value: string}', $request->safe()->all());
}

function inspectNarrowedFinalChild(Requests\PolymorphicRequest $request): void
{
    if ($request instanceof Requests\PolymorphicChildRequest) {
        assertType('array{value: array}', $request->validated());
        assertType('array{value: array}', $request->safe()->all());
    }
}
