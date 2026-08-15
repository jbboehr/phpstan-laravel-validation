<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\NotIn;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;

use function PHPStan\Testing\assertType;

$validator = Validator::make([], [
    'scalar' => ['required', Rule::in(['one'])],
    'enum' => ['required', Rule::in([PureValidationStatus::Draft])],
    'direct_array' => ['required', new In(['one'])],
    'direct_scalar' => ['required', new In('one')],
    'direct_not_in_array' => ['required', 'string', new NotIn(['blocked'])],
    'direct_not_in_scalar' => ['required', 'string', new NotIn('blocked')],
]);

assertType(
    "array{scalar: 'one'|Stringable, enum?: mixed, direct_array: 'one'|Stringable, "
        . 'direct_scalar?: mixed, direct_not_in_array: string, direct_not_in_scalar?: mixed}',
    $validator->validated()
);
