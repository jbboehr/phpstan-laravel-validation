<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|before_or_equal:2022-12-24',
    'optional_value' => 'before_or_equal:2022-12-24',
    'excluded_value' => 'required|exclude|before_or_equal:2022-12-24',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: DateTimeInterface|non-empty-string, optional_value?: DateTimeInterface|non-empty-string}', $validated);
assertType('DateTimeInterface|non-empty-string', $validated['required_value']);
assertType('DateTimeInterface|non-empty-string', $validated['optional_value']);
