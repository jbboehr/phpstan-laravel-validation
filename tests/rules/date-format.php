<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|date_format:Y-m-d',
    'optional_value' => 'date_format:Y-m-d',
    'excluded_value' => 'required|exclude|date_format:Y-m-d',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: float|int|non-empty-string, optional_value?: float|int|string}',
    $validated
);
assertType('float|int|non-empty-string', $validated['required_value']);
assertType('float|int|string', $validated['optional_value']);
