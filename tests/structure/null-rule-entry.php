<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

use function PHPStan\Testing\assertType;

$request = new Request();

$literalNullEntry = $request->validate([
    'value' => ['required', null, 'string'],
]);
assertType('array{value: string}', $literalNullEntry);

$runtimeConfigured = $request->validate([
    'password' => ['required', 'confirmed', Password::defaults()],
]);
assertType('array{password: mixed}', $runtimeConfigured);

$constrainedByStaticRules = $request->validate([
    'password' => ['required', 'string', Password::defaults()],
]);
assertType('array{password: string}', $constrainedByStaticRules);
