<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'value' => ['required', Rule::file()->extensions(['txt'])],
])->validated();

assertType('array{value: mixed}', $validated);
