<?php

declare(strict_types=1);

use jbboehr\PhpstanLaravelValidation\Test\Fixtures\TestController;

use function PHPStan\Testing\assertType;

$controller = new TestController();
$validated = $controller->validate(new \Illuminate\Http\Request(), [
    'amount' => 'required|integer',
]);
assertType("float|int|numeric-string|Stringable|true", $validated['amount']);

$rawOptional = $controller->validate(new \Illuminate\Http\Request(), [
    'value' => 'array',
]);
assertType('array{value?: array|string}', $rawOptional);

$controller = new TestController();
$validator = \Illuminate\Support\Facades\Validator::make([], [
    'amount' => 'required|integer',
]);
$validated = $controller->validateWith($validator, new \Illuminate\Http\Request());
assertType("float|int|numeric-string|Stringable|true", $validated['amount']);
