<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validated = \Illuminate\Support\Facades\Validator::validate([], [
    'person.*.email' => 'required|email|unique:users',
    'person.*.first_name' => 'required|string',
]);

assertType('array{person?: array<int|string, array{email: non-empty-string, first_name: string}>}', $validated);
if (isset($validated['person'][0])) {
    assertType('non-empty-string', $validated['person'][0]['email']);
    assertType('string', $validated['person'][0]['first_name']);
}

$namedValidated = \Illuminate\Support\Facades\Validator::validate(
    rules: ['value' => 'required|string'],
    data: []
);
assertType('array{value: string}', $namedValidated);

$namedMade = \Illuminate\Support\Facades\Validator::make(
    rules: ['value' => 'required|string'],
    data: []
)->validated();
assertType('array{value: string}', $namedMade);

$facadeSpread = [['value' => 'required|string'], []];
assertType(
    'array',
    \Illuminate\Support\Facades\Validator::validate([], ...$facadeSpread)
);
assertType(
    'array',
    \Illuminate\Support\Facades\Validator::make([], ...$facadeSpread)->validated()
);

/**
 * @param array<string, mixed> $input
 */
function inspectSuccessfulFacadeInput(array $input): void
{
    \Illuminate\Support\Facades\Validator::validate($input, [
        'name' => 'required|string',
        'flag' => 'boolean',
        'excluded' => 'exclude|required|string',
        'conditionally_excluded' => 'exclude_if:mode,draft|required|string',
        'must_be_missing' => 'missing|string',
    ]);

    assertType('string', $input['name']);
}

/**
 * @param array<string, mixed> $input
 */
function inspectNamedSuccessfulFacadeInput(array $input): void
{
    $validated = \Illuminate\Support\Facades\Validator::validate(
        rules: ['value' => 'required|integer'],
        data: $input
    );

    assertType('float|int|numeric-string|Stringable|true', $input['value']);
    assertType('array{value: float|int|numeric-string|Stringable|true}', $validated);
}

/**
 * @param array{extra: int} $input
 */
function inspectSuccessfulFacadeInputPreservesKnownKeys(array $input): void
{
    \Illuminate\Support\Facades\Validator::validate($input, [
        'name' => 'required|string',
        'optional' => 'string',
    ]);

    assertType('array{extra: int, name: string}', $input);
}

/**
 * @param array<string, mixed> $input
 * @param array<array-key, mixed> $dynamicRules
 */
function inspectUnresolvedSuccessfulFacadeInput(array $input, array $dynamicRules): void
{
    \Illuminate\Support\Facades\Validator::validate($input, $dynamicRules);
    assertType('array<string, mixed>', $input);

    \Illuminate\Support\Facades\Validator::validate($input, [
        'nested.value' => 'required|string',
        'items.*' => 'required|string',
    ]);
    assertType('array<string, mixed>', $input);
}

/**
 * @param array<string, mixed> $input
 */
function inspectExecutableRuleDoesNotRefineFacadeInput(array $input): void
{
    \Illuminate\Support\Facades\Validator::validate($input, [
        'name' => 'required|string',
        'other' => [
            static function (string $attribute, mixed $value, \Closure $fail) use (&$input): void {
                $input = ['name' => 123];
            },
        ],
    ]);

    assertType('array<string, mixed>', $input);
}

function inspectArgumentWritesDoNotRefineFacadeInput(): void
{
    $positional = ['name' => 'Ada'];
    \Illuminate\Support\Facades\Validator::validate(
        $positional,
        ['name' => 'required|string'],
        $positional = ['name' => 123]
    );
    assertType('array{name: 123}', $positional);

    $named = ['name' => 'Ada'];
    \Illuminate\Support\Facades\Validator::validate(
        rules: ['name' => 'required|string'],
        data: $named,
        messages: $named = ['name' => 123]
    );
    assertType('array{name: 123}', $named);
}
