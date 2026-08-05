<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|in:1,2,3,4,5',
    'optional_value' => 'in:1,2,3,4,5',
    'excluded_value' => 'required|exclude|in:1,2,3,4,5',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: float|int|numeric-string|Stringable|true, '
        . 'optional_value?: float|int|string|Stringable|true}',
    $validated
);
assertType('float|int|numeric-string|Stringable|true', $validated['required_value']);
assertType('float|int|string|Stringable|true', $validated['optional_value']);
