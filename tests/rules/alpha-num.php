<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|alpha_num',
    'optional_value' => 'alpha_num',
    'excluded_value' => 'required|exclude|alpha_num',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: float|int<0, max>|non-empty-string, optional_value?: float|int<0, max>|string}',
    $validated
);
assertType('float|int<0, max>|non-empty-string', $validated['required_value']);
assertType('float|int<0, max>|string', $validated['optional_value']);

$asciiValidator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|alpha_num:ascii',
]);
assertType('float|int<0, max>|non-empty-string', $asciiValidator->validated()['required_value']);
