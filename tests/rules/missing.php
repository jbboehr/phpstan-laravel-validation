<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$direct = \Illuminate\Support\Facades\Validator::make([], [
    'missing' => 'missing',
    'kept' => 'present|string',
])->validated();
assertType('array{kept: string}', $direct);

$nested = \Illuminate\Support\Facades\Validator::make([], [
    'payload.value' => 'missing',
])->validated();
assertType('array{}', $nested);

$wildcard = \Illuminate\Support\Facades\Validator::make([], [
    'items.*.value' => 'missing',
])->validated();
assertType('array{}', $wildcard);

$arrayParent = \Illuminate\Support\Facades\Validator::make([], [
    'payload' => 'required|array',
    'payload.value' => 'missing',
])->validated();
assertType('array{}', $arrayParent);

$scalarParent = \Illuminate\Support\Facades\Validator::make([], [
    'payload' => 'string',
    'payload.value' => 'missing',
])->validated();
assertType('array{payload?: string}', $scalarParent);

$parameterizedArrayParent = \Illuminate\Support\Facades\Validator::make([], [
    'payload' => 'required|array:name',
    'payload.child' => 'missing',
])->validated();
assertType('array{payload: array{name?: mixed}}', $parameterizedArrayParent);

$wildcardArrayParent = \Illuminate\Support\Facades\Validator::make([], [
    'items' => 'present|array',
    'items.*.id' => 'missing',
])->validated();
assertType('array{items?: array<int|string, mixed>|string}', $wildcardArrayParent);
