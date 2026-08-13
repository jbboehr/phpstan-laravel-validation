<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$missingChild = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.id' => 'missing',
])->validated();
assertType('array{items?: array<int|string, mixed>}', $missingChild);

$directRequired = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*' => 'required|string',
])->validated();
assertType('array{items: list<string>}', $directRequired);

$optionalDirect = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'list',
    'items.*' => 'required|string',
])->validated();
assertType('array{items?: list<string>|string}', $optionalDirect);

$presentDirect = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'present|list',
    'items.*' => 'required|string',
])->validated();
assertType('array{items: list<string>|string}', $presentDirect);

$constrainedDirect = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list|array:0,1|required_array_keys:0',
    'items.*' => 'required|string',
])->validated();
assertType('array{items: list{0: string, 1?: string}}', $constrainedDirect);

$nestedRequired = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.id' => 'required|string',
])->validated();
assertType('array{items: list<array{id: string}>}', $nestedRequired);

$optionalNested = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.id' => 'sometimes|string',
])->validated();
assertType('array{items?: array<int|string, array{id?: string}>}', $optionalNested);

$deeperWildcard = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.*.id' => 'required|string',
])->validated();
assertType(
    'array{items: array<int|string, array<int|string, array{id: string}>>}',
    $deeperWildcard
);

$excludedElement = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.0' => 'exclude',
])->validated();
assertType('array{items?: array<int, mixed>}', $excludedElement);

$conditionalExclusion = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.id' => 'required|string|exclude_if:items.*.drop,true',
])->validated();
assertType('array{items?: array<int|string, mixed>}', $conditionalExclusion);

$reorderedProjection = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.id' => 'sometimes|string',
    'items.*.name' => 'required|string',
])->validated();
assertType(
    'array{items: array<int|string, array{id?: string, name: string}>}',
    $reorderedProjection
);

$orderedNestedExclusion = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.id' => 'required|string',
    'items.*.tmp' => 'exclude_if:mode,hidden|string',
])->validated();
assertType('array{items: list<array{id: string, tmp?: string}>}', $orderedNestedExclusion);

$zeroMatch = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'present|list',
    'items.*.id' => 'required|integer',
])->validated();
assertType(
    'array{items: list<array{id: float|int|numeric-string|Stringable|true}>|string}',
    $zeroMatch
);
