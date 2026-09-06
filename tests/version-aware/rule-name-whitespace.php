<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;

use function PHPStan\Testing\assertType;

$validator = Validator::make([], [
    'value' => ["\u{00a0}nullable\u{00a0}", 'string'],
    'optional' => "\fsometimes\f|required|string",
    'conditional' => ["required\twith:other", 'string'],
    'custom' => ['required', "custom\tvalue"],
    'required' => ["\u{00a0}required\u{00a0}", 'string'],
]);
assertType('array{value?: string|null, optional?: string, conditional?: string, custom: int, required: string}', $validator->validated());
