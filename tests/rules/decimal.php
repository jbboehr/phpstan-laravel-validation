<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_digits' => 'required|decimal:2,4',
    'optional_digits' => 'decimal:2,4',
    'excluded_value' => 'required|exclude|decimal:2,4',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType("array{required_digits: numeric-string, optional_digits?: numeric-string}", $validated);
assertType("numeric-string", $validated['required_digits']);
assertType("numeric-string", $validated['optional_digits']);
