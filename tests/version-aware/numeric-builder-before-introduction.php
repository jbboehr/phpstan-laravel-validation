<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Numeric;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'factory' => ['required', Rule::numeric()],
    'direct' => ['required', new Numeric()],
])->validated();

assertType('array{factory?: mixed, direct?: mixed}', $validated);
