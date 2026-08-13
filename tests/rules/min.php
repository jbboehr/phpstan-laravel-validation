<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validated = \Illuminate\Support\Facades\Validator::make([], [
    'required_string' => 'required|string|min:1',
    'optional_string' => 'string|min:1',
    'required_array' => 'required|array|min:1',
    'optional_array' => 'array|min:1',
    'nullable_array' => 'nullable|array|min:1',
    'zero_array' => 'required|array|min:0',
    'numeric' => 'required|numeric|min:1',
    'untyped' => 'required|min:1',
])->validated();

assertType(
    'array{required_string: non-empty-string, optional_string?: string, '
        . 'required_array: non-empty-array, optional_array?: non-empty-array|string, '
        . 'nullable_array?: non-empty-array|string|null, zero_array: array, '
        . 'numeric: float|int|numeric-string, untyped: mixed}',
    $validated
);

$excluded = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|array:name|min:1',
    'items.name' => 'exclude',
])->validated();

assertType('array{items: array{name?: mixed}}', $excluded);
