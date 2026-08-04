<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$wildcardOnly = \Illuminate\Support\Facades\Validator::make([], [
    'names.*.first' => 'required|string',
])->validated();
assertType('array{names?: array<int|string, array{first: string}>}', $wildcardOnly);

$optionalArray = \Illuminate\Support\Facades\Validator::make([], [
    'names' => 'array',
    'names.*.first' => 'required|string',
])->validated();
assertType('array{names?: array<int|string, array{first: string}>}', $optionalArray);

$requiredArray = \Illuminate\Support\Facades\Validator::make([], [
    'names' => 'required|array',
    'names.*.first' => 'required|string',
])->validated();
assertType('array{names: array<int|string, array{first: string}>}', $requiredArray);

$namedDescendant = \Illuminate\Support\Facades\Validator::make([], [
    'names.named.first' => 'required|string',
])->validated();
assertType('array{names: array{named: array{first: string}}}', $namedDescendant);

$nestedWildcards = \Illuminate\Support\Facades\Validator::make([], [
    'people.*.cars.*.model' => 'required|string',
])->validated();
assertType(
    'array{people?: array<int|string, array{cars?: array<int|string, array{model: string}>}>}',
    $nestedWildcards
);
