<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|array:name,username',
    'optional_value' => 'array:name,username',
    'excluded_value' => 'required|exclude|array:name,username',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: array{name: mixed, username: mixed}, optional_value?: array{name: mixed, username: mixed}}', $validated);
assertType('array{name: mixed, username: mixed}', $validated['required_value']);
assertType('array{name: mixed, username: mixed}', $validated['optional_value']);
