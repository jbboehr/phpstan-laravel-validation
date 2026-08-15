<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Date;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'factory' => ['required', Rule::date()],
    'direct' => ['required', new Date()],
    'after' => ['required', Rule::date()->after('2024-01-01')],
    'after_equal' => ['required', Rule::date()->afterOrEqual('2024-01-01')],
    'after_today' => ['required', Rule::date()->afterToday()],
    'before' => ['required', Rule::date()->before('2025-01-01')],
    'before_equal' => ['required', Rule::date()->beforeOrEqual('2025-01-01')],
    'before_today' => ['required', Rule::date()->beforeToday()],
    'between' => ['required', Rule::date()->between('2024-01-01', '2025-01-01')],
    'between_equal' => ['required', Rule::date()->betweenOrEqual('2024-01-01', '2025-01-01')],
    'today_or_after' => ['required', Rule::date()->todayOrAfter()],
    'today_or_before' => ['required', Rule::date()->todayOrBefore()],
    'format' => ['required', Rule::date()->format('Ymd')],
    'format_chain' => ['required', Rule::date()->format('Ymd')->after('20230101')],
    'late_format' => ['required', Rule::date()->before('20250101')->format('Ymd')],
    'optional' => [Rule::date()],
    'optional_format' => [Rule::date()->format('Ymd')],
])->validated();

$date = 'DateTimeInterface|float|int|non-empty-string';
assertType(
    'array{factory: ' . $date . ', direct: ' . $date . ', after: ' . $date
        . ', after_equal: ' . $date . ', after_today: ' . $date . ', before: ' . $date
        . ', before_equal: ' . $date . ', before_today: ' . $date . ', between: ' . $date
        . ', between_equal: ' . $date . ', today_or_after: ' . $date
        . ', today_or_before: ' . $date . ', format: float|int|non-empty-string, '
        . 'format_chain: float|int|non-empty-string, late_format: float|int|non-empty-string, '
        . 'optional?: DateTimeInterface|float|int|string, optional_format?: float|int|string}',
    $validated
);

$variants = Validator::make([], [
    'nullable' => ['nullable', Rule::date()],
    'sometimes' => ['sometimes', 'required', Rule::date()->beforeToday()],
    'events.*.starts_at' => ['required', Rule::date()->afterToday()],
])->validated();

assertType(
    'array{nullable?: DateTimeInterface|float|int|string|null, '
        . 'sometimes?: DateTimeInterface|float|int|non-empty-string, '
        . 'events?: array<int|string, array{starts_at: DateTimeInterface|float|int|non-empty-string}>}',
    $variants
);

$assigned = Rule::date();
$opaque = Validator::make([], [
    'assigned' => ['required', $assigned],
    'conditional' => ['required', Rule::date()->when(
        true,
        static fn (Date $rule): Date => $rule->beforeToday()
    )],
    'unless' => ['required', Rule::date()->unless(
        false,
        static fn (Date $rule): Date => $rule->afterToday()
    )],
    'macro' => ['required', Rule::date()->fiscalYear()],
    'factory_callable' => ['required', Rule::date(...)],
    'method_callable' => ['required', Rule::date()->beforeToday(...)],
])->validated();

assertType(
    'array{assigned?: mixed, conditional?: mixed, unless?: mixed, macro?: mixed, '
        . 'factory_callable: mixed, method_callable: mixed}',
    $opaque
);
