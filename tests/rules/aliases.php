<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validated = \Illuminate\Support\Facades\Validator::make([], [
    'required_integer' => 'required|int',
    'optional_integer' => 'int',
    'required_boolean' => 'required|bool',
    'optional_boolean' => 'bool',
])->validated();

assertType(
    "array{required_integer: float|int|numeric-string|Stringable|true, "
        . "optional_integer?: float|int|string|Stringable|true, "
        . "required_boolean: 0|1|'0'|'1'|bool, optional_boolean?: 0|1|bool|string}",
    $validated
);
