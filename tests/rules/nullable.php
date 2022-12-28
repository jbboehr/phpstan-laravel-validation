<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|string|nullable',
    'optional_value' => 'string|nullable',
    'excluded_value' => 'required|exclude|string|nullable',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value?: string|null, optional_value?: string|null}', $validated);
assertType('string|null', $validated['required_value']);
assertType('string|null', $validated['optional_value']);
