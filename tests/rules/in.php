<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|in:1,2,3,4,5',
    'optional_value' => 'in:1,2,3,4,5',
    'excluded_value' => 'required|exclude|in:1,2,3,4,5',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: 1|2|3|4|5|float|numeric-string|Stringable|true, '
        . 'optional_value?: 1|2|3|4|5|float|string|Stringable|true}',
    $validated
);
assertType('1|2|3|4|5|float|numeric-string|Stringable|true', $validated['required_value']);
assertType('1|2|3|4|5|float|string|Stringable|true', $validated['optional_value']);

$numericSpellings = \Illuminate\Support\Facades\Validator::make([], [
    'decimal' => 'required|in:1.0',
    'exponent' => 'required|in:1e3',
    'fractional' => 'required|in:1.5',
    'negative_zero' => 'required|in:-0',
    'multiple' => 'required|in:1,2.5,-3e0',
    'non_finite' => 'required|in:INF,-INF,NAN',
    'unsafe_integer_float' => 'required|in:9007199254740992.0',
]);
assertType(
    "array{decimal: 1|float|numeric-string|Stringable|true, "
        . 'exponent: 1000|float|numeric-string|Stringable, '
        . 'fractional: float|numeric-string|Stringable, '
        . 'negative_zero: 0|float|numeric-string|Stringable, '
        . 'multiple: -3|1|float|numeric-string|Stringable|true, '
        . "non_finite: '-INF'|'INF'|'NAN'|float|Stringable, "
        . 'unsafe_integer_float: float|int|numeric-string|Stringable}',
    $numericSpellings->validated()
);
