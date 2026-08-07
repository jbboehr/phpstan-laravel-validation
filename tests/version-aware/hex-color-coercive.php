<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|hex_color',
    'optional_value' => 'hex_color',
    'excluded_value' => 'required|exclude|hex_color',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: non-empty-string|Stringable, optional_value?: string|Stringable}',
    $validated
);
assertType('non-empty-string|Stringable', $validated['required_value']);
assertType('string|Stringable', $validated['optional_value']);
