<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|min_digits:3|string',
    'optional_value' => 'min_digits:3|string',
    'excluded_value' => 'required|exclude|min_digits:3|string',
    'deduplicated_value' => 'required|digits|min_digits:3|string',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: numeric-string, optional_value?: numeric-string, deduplicated_value: numeric-string}', $validated);
assertType('numeric-string', $validated['required_value']);
assertType('numeric-string', $validated['optional_value']);
assertType('numeric-string', $validated['deduplicated_value']);
