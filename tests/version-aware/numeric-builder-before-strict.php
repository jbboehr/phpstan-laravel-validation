<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'positional_true' => ['required', Rule::numeric()->integer(true)],
])->validated();

assertType('array{positional_true: float|int|numeric-string}', $validated);
