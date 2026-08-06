<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|date_equals:2022-12-24',
    'optional_value' => 'date_equals:2022-12-24',
    'excluded_value' => 'required|exclude|date_equals:2022-12-24',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: DateTimeInterface|float|int|non-empty-string, '
        . 'optional_value?: DateTimeInterface|float|int|string}',
    $validated
);
assertType('DateTimeInterface|float|int|non-empty-string', $validated['required_value']);
assertType('DateTimeInterface|float|int|string', $validated['optional_value']);
