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
