<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_contains' => 'required|contains:needle',
    'required_doesnt_contain' => 'required|doesnt_contain:blocked',
    'required_in_array_keys' => 'required|in_array_keys:name',
    'optional_contains' => 'contains:needle',
    'nullable_in_array_keys' => 'nullable|in_array_keys:name',
    'excluded_value' => 'required|exclude|contains:needle',
]);
assertType('Illuminate\Validation\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_contains: array, required_doesnt_contain: array, '
    . 'required_in_array_keys: array, optional_contains?: array|string, '
    . 'nullable_in_array_keys?: array|string|null}',
    $validated
);
assertType('array', $validated['required_contains']);
assertType('array', $validated['required_doesnt_contain']);
assertType('array', $validated['required_in_array_keys']);
assertType('array|string', $validated['optional_contains']);
assertType('array|string|null', $validated['nullable_in_array_keys']);
