<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validated = \Illuminate\Support\Facades\Validator::make([], [
    'required' => 'required|required_array_keys:name,email',
    'optional' => 'required_array_keys:name',
    'present' => 'present|required_array_keys:name',
    'numeric' => 'required|required_array_keys:0',
    'allowed' => 'required|array:name,email|required_array_keys:name',
])->validated();

assertType(
    "array{required: non-empty-array&hasOffset('email')&hasOffset('name'), "
    . "optional?: (non-empty-array&hasOffset('name'))|string, "
    . "present: (non-empty-array&hasOffset('name'))|string, "
    . 'numeric: non-empty-array&hasOffset(0), allowed: array{name: mixed, email?: mixed}}',
    $validated
);

$matchingProjection = \Illuminate\Support\Facades\Validator::make([], [
    'user' => 'required|array|required_array_keys:name',
    'user.name' => 'string',
])->validated();
assertType('array{user: array{name: string}}', $matchingProjection);

$numericProjection = \Illuminate\Support\Facades\Validator::make([], [
    'user' => 'required|array|required_array_keys:0',
    'user.0' => 'string',
])->validated();
assertType('array{user: array{string}}', $numericProjection);

$completeParent = \Illuminate\Support\Facades\Validator::make([], [
    'user' => 'required|required_array_keys:name',
    'user.name' => 'string',
])->validated();
assertType("array{user: non-empty-array&hasOffset('name')}", $completeParent);

$unvalidatedRequiredKey = \Illuminate\Support\Facades\Validator::make([], [
    'user' => 'required|array|required_array_keys:name',
    'user.email' => 'string',
])->validated();
assertType('array{user?: array{email?: string}}', $unvalidatedRequiredKey);

$blankParentCanBypass = \Illuminate\Support\Facades\Validator::make([], [
    'user' => 'present|array|required_array_keys:name',
    'user.name' => 'string',
])->validated();
assertType('array{user?: array{name?: string}}', $blankParentCanBypass);

$requiredKeyWithoutADirectChildRule = \Illuminate\Support\Facades\Validator::make([], [
    'user' => 'required|array|required_array_keys:name',
    'user.name.first' => 'string',
])->validated();
assertType(
    'array{user?: array{name?: array{first?: string}}}',
    $requiredKeyWithoutADirectChildRule
);
