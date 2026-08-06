<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|json',
    'optional_value' => 'json',
    'excluded_value' => 'required|exclude|json',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: float|int|non-empty-string|Stringable|true, '
        . 'optional_value?: float|int|string|Stringable|true}',
    $validated
);
assertType('float|int|non-empty-string|Stringable|true', $validated['required_value']);
assertType('float|int|string|Stringable|true', $validated['optional_value']);
