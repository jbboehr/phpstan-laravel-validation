<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;

use function PHPStan\Testing\assertType;

$validator = Validator::make([], [
    'scalar' => ['required', Rule::in(['one'])],
    'enum' => ['required', Rule::in([PureValidationStatus::Draft])],
]);

assertType("array{scalar: 'one'|Stringable, enum?: mixed}", $validator->validated());
