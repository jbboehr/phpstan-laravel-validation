<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|url',
    'optional_value' => 'url',
    'excluded_value' => 'required|exclude|url',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: non-empty-string, optional_value?: non-empty-string}', $validated);
assertType('non-empty-string', $validated['required_value']);
assertType('non-empty-string', $validated['optional_value']);
