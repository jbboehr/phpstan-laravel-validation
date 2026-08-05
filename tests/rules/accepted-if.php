<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|accepted_if:foo,bar',
    'optional_value' => 'accepted_if:foo,bar',
    'excluded_value' => 'required|exclude|accepted_if:foo,bar',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: mixed, optional_value?: mixed}', $validated);
assertType('mixed', $validated['required_value']);
assertType('mixed', $validated['optional_value']);
