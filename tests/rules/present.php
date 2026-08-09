<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validated = \Illuminate\Support\Facades\Validator::make([], [
    'value' => 'present',
    'integer' => 'present|integer',
    'nullable' => 'present|nullable|integer',
    'optional' => 'sometimes|present|string',
    'nested.value' => 'present|string',
    'items.*.value' => 'present|string',
])->validated();

assertType(
    'array{value: mixed, integer: float|int|string|Stringable|true, '
    . 'nullable: float|int|string|Stringable|true|null, optional?: string, '
    . 'nested: array{value: string}, items?: array<int|string, array{value: string}>}',
    $validated
);
assertType('mixed', $validated['value']);
assertType('float|int|string|Stringable|true', $validated['integer']);
assertType('float|int|string|Stringable|true|null', $validated['nullable']);

$wildcardArray = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'present|array',
    'items.*.id' => 'required|integer',
])->validated();
assertType(
    'array{items: array<int|string, array{id: float|int|numeric-string|Stringable|true}>|string}',
    $wildcardArray
);

$deepWildcardArray = \Illuminate\Support\Facades\Validator::make([], [
    'payload' => 'present|array',
    'payload.items.*.id' => 'required|integer',
])->validated();
assertType('array{payload: array|string}', $deepWildcardArray);
