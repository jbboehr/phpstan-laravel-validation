<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\IntegerValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus;

use function PHPStan\Testing\assertType;

$validator = Validator::make([], [
    'pure' => ['required', Rule::in([
        PureValidationStatus::Draft,
        PureValidationStatus::Published,
    ])],
    'string_backed' => ['required', Rule::in([StringValidationStatus::One])],
    'integer_backed' => ['required', Rule::in([IntegerValidationStatus::Two])],
    'direct_pure' => ['required', new In([PureValidationStatus::Draft])],
    'direct_string_backed' => ['required', new In([StringValidationStatus::One])],
    'direct_scalar' => ['required', new In('one')],
]);

assertType(
    "array{pure: 'Draft'|'Published'|Stringable, "
        . 'string_backed: 1|float|numeric-string|Stringable|true, '
        . 'integer_backed: 2|float|numeric-string|Stringable, '
        . "direct_pure: 'Draft'|Stringable, "
        . 'direct_string_backed: 1|float|numeric-string|Stringable|true, '
        . 'direct_scalar?: mixed}',
    $validator->validated()
);
