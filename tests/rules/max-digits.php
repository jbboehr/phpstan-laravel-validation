<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|max_digits:3',
    'optional_value' => 'max_digits:3',
    'excluded_value' => 'required|exclude|max_digits:3',
    'deduplicated_value' => 'required|digits|max_digits:3',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: numeric-string, optional_value?: numeric-string, deduplicated_value: numeric-string}', $validated);
assertType('numeric-string', $validated['required_value']);
assertType('numeric-string', $validated['optional_value']);
assertType('numeric-string', $validated['deduplicated_value']);
