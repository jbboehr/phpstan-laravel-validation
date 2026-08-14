<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Numeric;

use function PHPStan\Testing\assertType;

$strict = random_int(0, 1) === 1;
$strictArguments = [true];
$validated = Validator::make([], [
    'factory' => ['required', Rule::numeric()],
    'direct' => ['required', new Numeric()],
    'between' => ['required', Rule::numeric()->between(1, 10)],
    'decimal' => ['required', Rule::numeric()->decimal(2)],
    'digits' => ['required', Rule::numeric()->digits(2)],
    'integer' => ['required', Rule::numeric()->integer()],
    'strict' => ['required', Rule::numeric()->integer(strict: true)],
    'dynamic_strict' => ['required', Rule::numeric()->integer($strict)],
    'strict_chain' => ['required', Rule::numeric()->integer(true)->max(10)],
    'unpacked_strict' => ['required', Rule::numeric()->integer(...$strictArguments)],
    'optional' => [Rule::numeric()],
])->validated();

assertType(
    'array{factory: float|int|numeric-string, direct: float|int|numeric-string, '
        . 'between: float|int|numeric-string, decimal: float|int|numeric-string, '
        . 'digits: float|int|numeric-string, integer: float|int|numeric-string, '
        . 'strict: int, dynamic_strict: float|int|numeric-string, strict_chain: int, '
        . 'unpacked_strict: float|int|numeric-string, '
        . 'optional?: float|int|string}',
    $validated
);

$assigned = Rule::numeric();
$opaque = Validator::make([], [
    'assigned' => ['required', $assigned],
    'conditional' => ['required', Rule::numeric()->when(
        true,
        static fn (Numeric $rule): Numeric => $rule->integer(true)
    )],
])->validated();

assertType('array{assigned?: mixed, conditional?: mixed}', $opaque);

$callables = Validator::make([], [
    'factory' => ['required', Rule::numeric(...)],
    'method' => ['required', Rule::numeric()->integer(...)],
])->validated();

assertType('array{factory: mixed, method: mixed}', $callables);
