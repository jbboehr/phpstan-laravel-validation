<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$missingChild = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.id' => 'missing',
])->validated();
assertType('array{items?: array<int|string, mixed>}', $missingChild);

$zeroMatch = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'present|list',
    'items.*.id' => 'required|integer',
])->validated();
assertType(
    'array{items: array<int|string, array{id: float|int|numeric-string|Stringable|true}>|string}',
    $zeroMatch
);
