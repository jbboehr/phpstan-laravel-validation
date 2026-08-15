<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\ArrayRule;

use function PHPStan\Testing\assertType;

$validator = Validator::make([], [
    'bare' => ['required', Rule::array()],
    'keyed' => ['required', Rule::array(['name'])],
    'direct' => ['required', new ArrayRule(['name'])],
]);

assertType('array{bare?: mixed, keyed?: mixed, direct?: mixed}', $validator->validated());
