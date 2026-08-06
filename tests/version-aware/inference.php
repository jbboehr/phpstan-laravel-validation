<?php

declare(strict_types=1);

use jbboehr\PhpstanLaravelValidation\Test\Fixtures\TestController;

use function PHPStan\Testing\assertType;

$rules = [
    'strict_integer' => 'required|integer:strict',
    'ascii' => 'required|ascii',
];

$validator = \Illuminate\Support\Facades\Validator::make([], $rules);
assertType('array{strict_integer: int, ascii: string}', $validator->validated());

$validated = \Illuminate\Support\Facades\Validator::validate([], $rules);
assertType('array{strict_integer: int, ascii: string}', $validated);

$requestRules = $rules + ['password' => 'array'];
$request = new \Illuminate\Http\Request();
$validated = $request->validate($requestRules);
assertType('array{password?: array|string, strict_integer: int, ascii: string}', $validated);

$controller = new TestController();
$validated = $controller->validate($request, $requestRules);
assertType('array{password?: array|string, strict_integer: int, ascii: string}', $validated);
