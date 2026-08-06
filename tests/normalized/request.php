<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\TestController;

$rules = [
    'array_value' => 'array',
    'nullable_array' => 'nullable|array',
    'email' => 'email',
    'password' => 'array',
];

$request = new \Illuminate\Http\Request();
$validated = $request->validate($rules);
assertType(
    'array{array_value?: array, nullable_array?: array|null, email?: non-empty-string, password?: array}',
    $validated
);

$controller = new TestController();
$validated = $controller->validate($request, $rules);
assertType(
    'array{array_value?: array, nullable_array?: array|null, email?: non-empty-string, password?: array}',
    $validated
);

$wildcard = $request->validate([
    'people.*.email' => 'email',
]);
assertType('array{people?: array<int|string, array{email?: non-empty-string}>}', $wildcard);

$directValidator = \Illuminate\Support\Facades\Validator::make([], [
    'array_value' => 'array',
]);
assertType('array{array_value?: array|string}', $directValidator->validated());
