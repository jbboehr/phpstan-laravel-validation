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
assertType("array{required_value?: 1|'1'|'on'|'true'|'yes'|true, optional_value?: 1|'1'|'on'|'true'|'yes'|true}", $validated);
assertType("1|'1'|'on'|'true'|'yes'|true", $validated['required_value']);
assertType("1|'1'|'on'|'true'|'yes'|true", $validated['optional_value']);
