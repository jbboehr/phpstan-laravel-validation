<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|timezone',
    'optional_value' => 'timezone',
    'excluded_value' => 'required|exclude|timezone',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: non-empty-string, optional_value?: string}', $validated);
assertType('non-empty-string', $validated['required_value']);
assertType('string', $validated['optional_value']);
