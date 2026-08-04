<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|alpha',
    'optional_value' => 'alpha',
    'excluded_value' => 'required|exclude|alpha',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: non-empty-string, optional_value?: string}', $validated);
assertType('non-empty-string', $validated['required_value']);
assertType('string', $validated['optional_value']);
