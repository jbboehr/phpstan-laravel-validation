<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|encoding:UTF-8',
    'optional_value' => 'encoding:UTF-8',
    'nullable_value' => 'nullable|encoding:UTF-8',
    'excluded_value' => 'required|exclude|encoding:UTF-8',
]);
assertType('Illuminate\Validation\Validator', $validator);

$validated = $validator->validated();
$encodingType = 'array|bool|float|int|string|Stringable|null';
assertType(
    'array{required_value: ' . $encodingType
    . ', optional_value?: ' . $encodingType
    . ', nullable_value?: ' . $encodingType . '}',
    $validated
);
assertType($encodingType, $validated['required_value']);
assertType($encodingType, $validated['optional_value']);
assertType($encodingType, $validated['nullable_value']);
