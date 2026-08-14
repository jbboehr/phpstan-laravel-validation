<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\StringRule;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'factory' => ['required', Rule::string()],
    'direct' => ['required', new StringRule()],
    'chain' => ['required', Rule::string()->uppercase()],
])->validated();

assertType('array{factory?: mixed, direct?: mixed, chain?: mixed}', $validated);
