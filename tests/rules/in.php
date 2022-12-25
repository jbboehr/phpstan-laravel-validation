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
assertType("array{required_value: '1'|'2'|'3'|'4'|'5', optional_value?: '1'|'2'|'3'|'4'|'5'}", $validated);
assertType("'1'|'2'|'3'|'4'|'5'", $validated['required_value']);
assertType("'1'|'2'|'3'|'4'|'5'", $validated['optional_value']);
