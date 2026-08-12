<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$request = new \Illuminate\Http\Request();
$validated = $request->validate([
    'person.*.email' => 'required|email|unique:users',
    'person.*.first_name' => 'required|string',
]);

assertType('array{person?: array<int|string, array{email: non-empty-string, first_name: string}>}', $validated);
if (isset($validated['person'][0])) {
    assertType('non-empty-string', $validated['person'][0]['email']);
    assertType('string', $validated['person'][0]['first_name']);
}

$rawOptional = $request->validate([
    'value' => 'array',
]);
assertType('array{value?: array|string}', $rawOptional);

$namedValidated = $request->validate(
    rules: ['value' => 'required|string']
);
assertType('array{value: string}', $namedValidated);

$requestSpread = [[
    'value' => 'required|string',
]];
assertType('array', $request->validate(...$requestSpread));
