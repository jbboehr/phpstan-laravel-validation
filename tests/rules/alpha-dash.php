<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|alpha_dash',
    'optional_value' => 'alpha_dash',
    'excluded_value' => 'required|exclude|alpha_dash',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: float|int|non-empty-string, optional_value?: float|int|string}', $validated);
assertType('float|int|non-empty-string', $validated['required_value']);
assertType('float|int|string', $validated['optional_value']);

$asciiValidator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|alpha_dash:ascii',
]);
assertType('float|int|non-empty-string', $asciiValidator->validated()['required_value']);
