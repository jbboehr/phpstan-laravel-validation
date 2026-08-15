<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'date' => ['required', Rule::date()],
    'date_time' => ['required', Rule::dateTime()],
    'past' => ['required', Rule::date()->past()],
    'future' => ['required', Rule::date()->future()],
    'now_or_past' => ['required', Rule::date()->nowOrPast()],
    'now_or_future' => ['required', Rule::date()->nowOrFuture()],
])->validated();

assertType(
    'array{date: DateTimeInterface|float|int|non-empty-string, date_time?: mixed, '
        . 'past?: mixed, future?: mixed, now_or_past?: mixed, now_or_future?: mixed}',
    $validated
);
