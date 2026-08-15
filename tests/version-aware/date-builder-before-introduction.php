<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Date;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'factory' => ['required', Rule::date()],
    'direct' => ['required', new Date()],
    'chain' => ['required', Rule::date()->beforeToday()],
])->validated();

assertType('array{factory?: mixed, direct?: mixed, chain?: mixed}', $validated);
