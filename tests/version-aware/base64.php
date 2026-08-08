<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|base64',
    'optional_value' => 'base64',
    'nullable_value' => 'nullable|base64',
    'excluded_value' => 'required|exclude|base64',
]);
assertType('Illuminate\Validation\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: non-empty-string, optional_value?: string, nullable_value?: string|null}',
    $validated
);
assertType('non-empty-string', $validated['required_value']);
assertType('string', $validated['optional_value']);
assertType('string|null', $validated['nullable_value']);
