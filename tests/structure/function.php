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
assertType("float|int|numeric-string|Stringable|true", $validated['amount']);

$namedValidator = validator(
    rules: ['value' => 'required|string'],
    data: []
);
assertType('array{value: string}', $namedValidator->validated());

$helperSpread = [['value' => 'required|string'], []];
assertType('array', validator([], ...$helperSpread)->validated());
