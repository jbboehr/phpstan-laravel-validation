<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'unfiltered' => ['required', Rule::enum(PureValidationStatus::class)],
    'filtered' => ['required', Rule::enum(PureValidationStatus::class)->only(PureValidationStatus::Draft)],
])->validated();

assertType(
    'array{unfiltered: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft'
        . '|jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Published, filtered: mixed}',
    $validated
);
