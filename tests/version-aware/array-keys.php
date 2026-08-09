<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;
use function PHPStan\Testing\assertSuperType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|array_keys:name,email',
    'optional_value' => 'array_keys:name,email',
    'nullable_value' => 'nullable|array_keys:name,email',
    'missing_parameter' => 'required|array_keys',
    'excluded_value' => 'required|exclude|array_keys:name,email',
]);
assertType('Illuminate\Validation\Validator', $validator);

$validated = $validator->validated();
assertType(
    "array{required_value: array{name?: mixed, email?: mixed}, "
    . "optional_value?: array{name?: mixed, email?: mixed}|string, "
    . "nullable_value?: array{name?: mixed, email?: mixed}|string|null, "
    . 'missing_parameter: *NEVER*}',
    $validated
);
assertType('array{name?: mixed, email?: mixed}', $validated['required_value']);
assertType('array{name?: mixed, email?: mixed}|string', $validated['optional_value']);
assertType('array{name?: mixed, email?: mixed}|string|null', $validated['nullable_value']);

// PHPStan 2.1 and 2.2 render quoted numeric-string and empty-string shape
// keys differently. Assert their semantics without coupling the fixture to
// that presentation detail.
$numericKeys = \Illuminate\Support\Facades\Validator::make([], [
    'value' => 'required|array_keys:0,01',
])->validated();
assertSuperType('array', $numericKeys['value']);
assertType('mixed', $numericKeys['value'][0]);
assertType('mixed', $numericKeys['value']['01']);

$emptyParameter = \Illuminate\Support\Facades\Validator::make([], [
    'value' => 'required|array_keys:',
])->validated();
assertSuperType('array', $emptyParameter['value']);
assertType('mixed', $emptyParameter['value']['']);

$listIntersections = \Illuminate\Support\Facades\Validator::make([], [
    'string_keys' => 'array_keys:name,email|list',
    'sparse_numeric_keys' => 'required|array_keys:0,2|list',
    'numeric_prefix' => 'required|array_keys:0,1,3|list',
])->validated();
assertType(
    'array{string_keys?: array{}|string, sparse_numeric_keys: array{0?: mixed}, '
    . 'numeric_prefix: list{0?: mixed, 1?: mixed}}',
    $listIntersections
);

$nested = \Illuminate\Support\Facades\Validator::make([], [
    'user' => 'required|array_keys:name,email',
    'user.name' => 'string',
])->validated();
assertType('array{user: array{name?: mixed, email?: mixed}}', $nested);
