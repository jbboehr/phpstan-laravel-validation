<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;

use function PHPStan\Testing\assertType;

$validator = Validator::make([], [
    'nullable' => ["\tnullable\n", 'string'],
    'optional' => ["\rsometimes\t", 'required', 'string'],
    'required' => ["\0required\v", 'string'],
]);
assertType('array{nullable?: string|null, optional?: string, required: string}', $validator->validated());
assertType('array{nullable?: string|null, optional?: string, required: string}', Validator::make([], [
    'nullable' => "\tnullable\n|string",
    'optional' => "\rsometimes\t|required|string",
    'required' => "\0required\v|string",
])->validated());

assertType('array{nullable?: string|null, optional?: string, required: string}', Validator::make([], [
    'nullable' => 'nullable|string',
    'optional' => 'sometimes|required|string',
    'required' => 'required|string',
])->validated());
