<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|current_password',
    'optional_value' => 'current_password',
    'excluded_value' => 'required|exclude|current_password',
    'string_value' => 'required|string|current_password',
    'nullable_string_value' => 'nullable|string|current_password',
    'named_guard_value' => 'required|current_password:fixture',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: mixed, optional_value?: mixed, string_value: string, nullable_string_value?: string|null, named_guard_value: mixed}', $validated);
assertType('mixed', $validated['required_value']);
assertType('mixed', $validated['optional_value']);
assertType('string', $validated['string_value']);
assertType('string|null', $validated['nullable_string_value']);
assertType('mixed', $validated['named_guard_value']);
