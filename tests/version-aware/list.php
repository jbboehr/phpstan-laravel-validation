<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|list',
    'optional_value' => 'list',
    'nullable_value' => 'nullable|list',
    'excluded_value' => 'required|exclude|list',
]);
assertType('Illuminate\Validation\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: list, optional_value?: list|string, nullable_value?: list|string|null}',
    $validated
);
assertType('list', $validated['required_value']);
assertType('list|string', $validated['optional_value']);
assertType('list|string|null', $validated['nullable_value']);

$minimum = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|list|min:1',
    'optional_value' => 'list|min:1',
])->validated();
assertType(
    'array{required_value: non-empty-list, optional_value?: non-empty-list|string}',
    $minimum
);

$allowedKeyList = \Illuminate\Support\Facades\Validator::make([], [
    'value' => 'array:name|list',
])->validated();
assertType('array{value?: array{}|string}', $allowedKeyList);

$preservedParent = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.id' => 'missing',
])->validated();
assertType('array{items: array<int|string, mixed>}', $preservedParent);

$directProjection = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*' => 'required|string',
])->validated();
assertType('array{items: list<string>}', $directProjection);

$optionalDirectProjection = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'list',
    'items.*' => 'required|string',
])->validated();
assertType('array{items?: list<string>|string}', $optionalDirectProjection);

$presentDirectProjection = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'present|list',
    'items.*' => 'required|string',
])->validated();
assertType('array{items: list<string>|string}', $presentDirectProjection);

$constrainedDirectProjection = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list|array:0,1|required_array_keys:0',
    'items.*' => 'required|string',
])->validated();
assertType('array{items: list{0: string, 1?: string}}', $constrainedDirectProjection);

$nestedProjection = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.id' => 'required|string',
])->validated();
assertType('array{items: list}', $nestedProjection);

$excludedElement = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.0' => 'exclude',
])->validated();
assertType('array{items: array<int, mixed>}', $excludedElement);

$conditionalExclusion = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.id' => 'required|string|exclude_if:items.*.drop,true',
])->validated();
assertType('array{items: array<int|string, mixed>}', $conditionalExclusion);

$orderedNestedExclusion = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'required|list',
    'items.*.id' => 'required|string',
    'items.*.tmp' => 'exclude_if:mode,hidden|string',
])->validated();
assertType('array{items: list}', $orderedNestedExclusion);
