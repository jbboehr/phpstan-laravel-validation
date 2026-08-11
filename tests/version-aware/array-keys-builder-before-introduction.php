<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'keyed' => ['required', Rule::arrayKeys(['name'])],
    'empty' => ['required', Rule::arrayKeys([])],
])->validated();

assertType('array{keyed?: mixed, empty?: mixed}', $validated);
