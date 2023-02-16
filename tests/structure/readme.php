<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$request = new \Illuminate\Http\Request();
$data = \Illuminate\Support\Facades\Validator::make($request->all(), [
    'person.*.email' => 'required|email|unique:users',
    'person.*.first_name' => 'required|string',
    'person.*.age' => 'required|integer|string',
])->validated();

assertType('array{person: array<int|string, array{email: non-empty-string, first_name: string, age: numeric-string}>}', $data);

$data = $request->validate([
    'person.*.email' => 'required|email|unique:users',
    'person.*.first_name' => 'required|string',
    'person.*.age' => 'required|integer|string',
]);

assertType('array{person: array<int|string, array{email: non-empty-string, first_name: string, age: numeric-string}>}', $data);
