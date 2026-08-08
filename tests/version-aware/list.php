<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|list',
    'optional_value' => 'list',
    'nullable_value' => 'nullable|list',
    'excluded_value' => 'required|exclude|list',
]);
assertType('Illuminate\Validation\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: list, optional_value?: list|string, nullable_value?: list|string|null}',
    $validated
);
assertType('list', $validated['required_value']);
assertType('list|string', $validated['optional_value']);
assertType('list|string|null', $validated['nullable_value']);
