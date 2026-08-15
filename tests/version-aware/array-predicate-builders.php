<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Contains;
use Illuminate\Validation\Rules\DoesntContain;

use function PHPStan\Testing\assertType;

/** @return list<string> */
function dynamicArrayPredicateValues(): array
{
    return ['needle'];
}

$validated = Validator::make([], [
    'contains_factory' => ['required', Rule::contains('needle')],
    'contains_direct' => ['required', new Contains('needle')],
    'doesnt_contain_factory' => ['required', Rule::doesntContain('blocked')],
    'doesnt_contain_direct' => ['required', new DoesntContain('blocked')],
    'optional_contains' => [Rule::contains('needle')],
    'optional_doesnt_contain' => [Rule::doesntContain('blocked')],
    'dynamic_contains' => ['required', Rule::contains(dynamicArrayPredicateValues())],
    'dynamic_direct' => ['required', new DoesntContain(dynamicArrayPredicateValues())],
])->validated();

assertType(
    'array{contains_factory: array, contains_direct: array, doesnt_contain_factory: array, '
        . 'doesnt_contain_direct: array, optional_contains?: array|string, '
        . 'optional_doesnt_contain?: array|string, dynamic_contains: array, '
        . 'dynamic_direct: array}',
    $validated
);

$assigned = Rule::contains('needle');
$opaque = Validator::make([], [
    'assigned' => ['required', $assigned],
    'first_class_callable' => ['required', Rule::contains(...)],
])->validated();

assertType('array{assigned?: mixed, first_class_callable: mixed}', $opaque);
