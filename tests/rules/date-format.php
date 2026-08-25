<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|date_format:Y-m-d',
    'optional_value' => 'date_format:Y-m-d',
    'numeric_value' => 'required|date_format:Ymd',
    'multiple_formats' => 'required|date_format:Y-m-d,Ymd',
    'excluded_value' => 'required|exclude|date_format:Y-m-d',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: non-empty-string, optional_value?: string, '
        . 'numeric_value: float|int|non-empty-string, multiple_formats: float|int|non-empty-string}',
    $validated
);
assertType('non-empty-string', $validated['required_value']);
assertType('string', $validated['optional_value']);
assertType('float|int|non-empty-string', $validated['numeric_value']);
assertType('float|int|non-empty-string', $validated['multiple_formats']);
