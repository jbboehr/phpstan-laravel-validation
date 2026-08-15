<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'rules_branch' => Rule::unless(false, ['required', 'string']),
    'default_branch' => Rule::unless(true, ['required', 'integer'], ['required', 'string']),
    'empty_default' => Rule::unless(true, ['required', 'string']),
])->validated();

assertType(
    'array{rules_branch: string, default_branch: string, empty_default?: mixed}',
    $validated
);
