<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'listed_chain' => ['required', Rule::date()->beforeToday()],
    'listed_format' => ['required', Rule::date()->format('Ymd')],
    'standalone_chain' => Rule::date()->beforeToday(),
    'standalone_format' => Rule::date()->format('Ymd'),
])->validated();

assertType(
    'array{listed_chain: DateTimeInterface|float|int|non-empty-string, '
        . 'listed_format: float|int|non-empty-string, '
        . 'standalone_chain?: DateTimeInterface|float|int|string, '
        . 'standalone_format?: float|int|string}',
    $validated
);
