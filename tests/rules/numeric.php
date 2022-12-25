<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|numeric',
    'optional_value' => 'numeric',
    'excluded_value' => 'required|exclude|numeric',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType("array{required_value: numeric-string, optional_value?: numeric-string}", $validated);
assertType("numeric-string", $validated['required_value']);
assertType("numeric-string", $validated['optional_value']);
