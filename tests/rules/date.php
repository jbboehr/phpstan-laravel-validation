<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|date',
    'optional_value' => 'date',
    'excluded_value' => 'required|exclude|date',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: DateTimeInterface|non-empty-string, optional_value?: DateTimeInterface|non-empty-string}', $validated);
assertType('DateTimeInterface|non-empty-string', $validated['required_value']);
assertType('DateTimeInterface|non-empty-string', $validated['optional_value']);
