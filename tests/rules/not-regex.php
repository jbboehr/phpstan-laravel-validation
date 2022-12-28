<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|not_regex:/^.+$/i|string',
    'optional_value' => 'not_regex:/^.+$/i|string',
    'excluded_value' => 'required|exclude|not_regex:/^.+$/i|string',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: string, optional_value?: string}', $validated);
assertType('string', $validated['required_value']);
assertType('string', $validated['optional_value']);
