<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function PHPStan\Testing\assertType;

$validator = Validator::make([], [
    'bare' => ['required', Rule::array()],
    'keyed' => ['required', Rule::array(['name'])],
]);

assertType('array{bare?: mixed, keyed?: mixed}', $validator->validated());
