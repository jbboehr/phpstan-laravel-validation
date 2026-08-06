<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|ascii',
    'optional_value' => 'ascii',
    'excluded_value' => 'required|exclude|ascii',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: array|bool|float|int|resource|string|Stringable|null, '
        . 'optional_value?: array|bool|float|int|resource|string|Stringable|null}',
    $validated
);
assertType('array|bool|float|int|resource|string|Stringable|null', $validated['required_value']);
assertType('array|bool|float|int|resource|string|Stringable|null', $validated['optional_value']);
