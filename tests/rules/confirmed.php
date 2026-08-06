<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|string|confirmed',
    'optional_value' => 'string|confirmed',
    'excluded_value' => 'required|exclude|confirmed',
    'explicit_value' => 'required|string|confirmed',
    'explicit_value_confirmation' => 'required|string',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: string, optional_value?: string, explicit_value: string, explicit_value_confirmation: string}', $validated);
assertType('string', $validated['required_value']);
assertType('string', $validated['optional_value']);
assertType('string', $validated['explicit_value']);
assertType('string', $validated['explicit_value_confirmation']);
