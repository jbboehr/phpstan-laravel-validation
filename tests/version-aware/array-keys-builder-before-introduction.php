<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\ArrayKeys;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'keyed' => ['required', Rule::arrayKeys(['name'])],
    'empty' => ['required', Rule::arrayKeys([])],
    'direct' => ['required', new ArrayKeys(['name'])],
])->validated();

assertType('array{keyed?: mixed, empty?: mixed, direct?: mixed}', $validated);
