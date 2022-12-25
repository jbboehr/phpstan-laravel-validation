<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_boolean' => 'required|boolean',
    'optional_boolean' => 'boolean',
    'excluded_value' => 'required|exclude|boolean',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType("array{required_boolean: 0|1|'0'|'1'|bool, optional_boolean?: 0|1|'0'|'1'|bool}", $validated);
assertType("0|1|'0'|'1'|bool", $validated['required_boolean']);
assertType("0|1|'0'|'1'|bool", $validated['optional_boolean']);
