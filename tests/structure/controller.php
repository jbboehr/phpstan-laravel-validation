<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$controller = new \App\Http\Controllers\Controller();
$validated = $controller->validate(new \Illuminate\Http\Request(), [
    'amount' => 'required|integer',
]);
assertType("int|numeric-string", $validated['amount']);

$controller = new \App\Http\Controllers\Controller();
$validator = \Illuminate\Support\Facades\Validator::make([], [
    'amount' => 'required|integer',
]);
$validated = $controller->validateWith($validator, new \Illuminate\Http\Request());
assertType("int|numeric-string", $validated['amount']);
