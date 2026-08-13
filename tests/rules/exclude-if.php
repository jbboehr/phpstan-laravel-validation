<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|string',
    'excluded_value' => 'required|string|exclude_if:required_field,value',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: string, excluded_value?: string}', $validated);

$rawParent = \Illuminate\Support\Facades\Validator::make([], [
    'user' => 'array',
    'user.profile.name' => 'exclude_if:mode,hidden|string',
    'mode' => 'required|string',
])->validated();
assertType('array{user?: array<int|string, mixed>|string, mode: string}', $rawParent);

$intermediateParent = \Illuminate\Support\Facades\Validator::make([], [
    'user' => 'array',
    'user.profile' => 'array',
    'user.profile.name' => 'exclude_if:mode,hidden|string',
    'mode' => 'required|string',
])->validated();
assertType(
    'array{user?: array{profile?: array<int|string, mixed>|string}, mode: string}',
    $intermediateParent
);
