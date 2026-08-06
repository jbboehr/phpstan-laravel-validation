<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|not_regex:/^.+$/i|string',
    'optional_value' => 'not_regex:/^.+$/i|string',
    'native_required_value' => 'required|not_regex:/^.+$/i',
    'native_optional_value' => 'not_regex:/^.+$/i',
    'excluded_value' => 'required|exclude|not_regex:/^.+$/i|string',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: string, optional_value?: string, native_required_value: float|int|string, '
        . 'native_optional_value?: float|int|string}',
    $validated
);
assertType('string', $validated['required_value']);
assertType('string', $validated['optional_value']);
assertType('float|int|string', $validated['native_required_value']);
assertType('float|int|string', $validated['native_optional_value']);
