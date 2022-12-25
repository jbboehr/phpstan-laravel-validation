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
assertType("array{required_declined?: 0|'0'|'false'|'no'|'off'|false, optional_declined?: 0|'0'|'false'|'no'|'off'|false}", $validated);
assertType("0|'0'|'false'|'no'|'off'|false", $validated['required_declined']);
assertType("0|'0'|'false'|'no'|'off'|false", $validated['optional_declined']);
