<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_digits' => 'required|digits',
    'optional_digits' => 'digits',
    'excluded_value' => 'required|exclude|digits',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType("array{required_digits: numeric-string, optional_digits?: numeric-string}", $validated);
assertType("numeric-string", $validated['required_digits']);
assertType("numeric-string", $validated['optional_digits']);
