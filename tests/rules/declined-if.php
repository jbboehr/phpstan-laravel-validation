<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_declined' => 'required|declined_if:foo,bar',
    'optional_declined' => 'declined_if:foo,bar',
    'excluded_value' => 'required|exclude|declined_if:foo,bar',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_declined: mixed, optional_declined?: mixed}', $validated);
assertType('mixed', $validated['required_declined']);
assertType('mixed', $validated['optional_declined']);
