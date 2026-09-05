<?php

declare(strict_types=1);

use jbboehr\PhpstanLaravelValidation\Test\Fixtures\TestController;

use function PHPStan\Testing\assertType;

$digits = \Illuminate\Support\Facades\Validator::make([], [
    'exact' => 'required|digits:1',
    'between' => 'required|digits_between:1,3',
    'minimum' => 'required|min_digits:1',
    'maximum' => 'required|max_digits:3',
])->validated();
assertType('float|int|numeric-string', $digits['exact']);
assertType('bool|float|int|numeric-string|Stringable|null', $digits['between']);
assertType('float|int|numeric-string', $digits['minimum']);
assertType('float|int|numeric-string', $digits['maximum']);

$rules = [
    'strict_integer' => 'required|integer:strict',
    'ascii' => 'required|ascii',
    'hex_color' => 'required|hex_color',
];

$validator = \Illuminate\Support\Facades\Validator::make([], $rules);
assertType('array{strict_integer: int, ascii: string, hex_color: non-empty-string}', $validator->validated());

$validated = \Illuminate\Support\Facades\Validator::validate([], $rules);
assertType('array{strict_integer: int, ascii: string, hex_color: non-empty-string}', $validated);

$requestRules = $rules + ['password' => 'array'];
$request = new \Illuminate\Http\Request();
$validated = $request->validate($requestRules);
assertType(
    'array{password?: array|string, strict_integer: int, ascii: string, hex_color: non-empty-string}',
    $validated
);

$controller = new TestController();
$validated = $controller->validate($request, $requestRules);
assertType(
    'array{password?: array|string, strict_integer: int, ascii: string, hex_color: non-empty-string}',
    $validated
);
