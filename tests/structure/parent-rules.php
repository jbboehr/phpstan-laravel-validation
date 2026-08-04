<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$requiredString = \Illuminate\Support\Facades\Validator::make([], [
    'foo' => 'required|string',
    'foo.bar' => 'sometimes|string',
])->validated();
assertType('array{foo: string}', $requiredString);

$optionalString = \Illuminate\Support\Facades\Validator::make([], [
    'foo' => 'string',
    'foo.bar' => 'sometimes|string',
])->validated();
assertType('array{foo?: string}', $optionalString);

$requiredMixed = \Illuminate\Support\Facades\Validator::make([], [
    'foo' => 'required',
    'foo.bar' => 'sometimes|string',
])->validated();
assertType('array{foo: mixed}', $requiredMixed);

$optionalArrayChild = \Illuminate\Support\Facades\Validator::make([], [
    'foo' => 'required|array',
    'foo.bar' => 'sometimes|string',
])->validated();
assertType('array{foo?: array{bar?: string}}', $optionalArrayChild);

$requiredArrayChild = \Illuminate\Support\Facades\Validator::make([], [
    'foo' => 'required|array',
    'foo.bar' => 'required|string',
])->validated();
assertType('array{foo: array{bar: string}}', $requiredArrayChild);

$requiredWildcardChild = \Illuminate\Support\Facades\Validator::make([], [
    'foo' => 'required|array',
    'foo.*.bar' => 'required|string',
])->validated();
assertType('array{foo: array<int|string, array{bar: string}>}', $requiredWildcardChild);

$nestedOptionalArrayChild = \Illuminate\Support\Facades\Validator::make([], [
    'foo' => 'required|array',
    'foo.bar' => 'required|array',
    'foo.bar.baz' => 'sometimes|string',
])->validated();
assertType('array{foo?: array{bar?: array{baz?: string}}}', $nestedOptionalArrayChild);
