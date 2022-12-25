<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|digits_between:1,99',
    'optional_value' => 'digits_between:10,20',
    'excluded_value' => 'required|exclude|digits_between:10,20',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType("array{required_value: numeric-string, optional_value?: numeric-string}", $validated);
assertType("numeric-string", $validated['required_value']);
assertType("numeric-string", $validated['optional_value']);
