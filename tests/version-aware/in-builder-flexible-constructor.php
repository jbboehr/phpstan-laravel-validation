<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\NotIn;

use function PHPStan\Testing\assertType;

/** @return list<string> */
function dynamicMembershipValues(): array
{
    return ['one'];
}

$validated = Validator::make([], [
    'in_scalar' => ['required', new In('one')],
    'in_variadic' => ['required', new In('one', 'two')],
    'in_dynamic' => ['required', new In(dynamicMembershipValues())],
    'not_in_scalar' => ['required', 'string', new NotIn('blocked')],
    'not_in_variadic' => ['required', 'string', new NotIn('blocked', 'admin')],
    'not_in_dynamic' => ['required', 'string', new NotIn(dynamicMembershipValues())],
])->validated();

assertType(
    "array{in_scalar: 'one'|Stringable, in_variadic: 'one'|'two'|Stringable, "
        . 'in_dynamic?: mixed, not_in_scalar: string, not_in_variadic: string, '
        . 'not_in_dynamic: string}',
    $validated
);
