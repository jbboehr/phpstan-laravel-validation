<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Date;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'listed_factory' => ['required', Rule::date()],
    'listed_constructor' => ['required', new Date()],
    'listed_chain' => ['required', Rule::date()->beforeToday()],
    'listed_format' => ['required', Rule::date()->format('Ymd')],
    'standalone_factory' => Rule::date(),
    'standalone_chain' => Rule::date()->beforeToday(),
])->validated();

$date = 'DateTimeInterface|float|int|non-empty-string';
assertType(
    'array{listed_factory: ' . $date . ', listed_constructor: ' . $date
        . ', listed_chain?: mixed, listed_format?: mixed, '
        . 'standalone_factory?: DateTimeInterface|float|int|string, standalone_chain?: mixed}',
    $validated
);
