<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Date;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'date_time' => ['required', Rule::dateTime()],
    'date_time_past' => ['required', Rule::dateTime()->past()],
    'past' => ['required', Rule::date()->past()],
    'future' => ['required', Rule::date()->future()],
    'now_or_past' => ['required', Rule::date()->nowOrPast()],
    'now_or_future' => ['required', Rule::date()->nowOrFuture()],
    'direct_future' => ['required', (new Date())->future()],
    'formatted_future' => ['required', Rule::date()->format('Ymd')->future()],
    'optional_date_time' => [Rule::dateTime()],
])->validated();

$date = 'DateTimeInterface|float|int|non-empty-string';
assertType(
    'array{date_time: non-empty-string, date_time_past: non-empty-string, past: ' . $date
        . ', future: ' . $date . ', now_or_past: ' . $date . ', now_or_future: ' . $date
        . ', direct_future: ' . $date . ', formatted_future: float|int|non-empty-string, '
        . 'optional_date_time?: string}',
    $validated
);

$otherBuilders = Validator::make([], [
    'numeric' => ['required', Rule::numeric()->between(1, 10)],
    'file' => ['required', Rule::file()->between('1kb', '2mb')],
])->validated();

assertType(
    'array{numeric: float|int|numeric-string, '
        . 'file: Symfony\Component\HttpFoundation\File\File}',
    $otherBuilders
);
