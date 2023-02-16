<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$factory = validator();
assertType('Illuminate\\Contracts\\Validation\\Factory', $factory);

$validator = validator([], [
    'amount' => 'required|integer',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType("int|numeric-string", $validated['amount']);
