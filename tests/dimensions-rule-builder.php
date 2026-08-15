<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Dimensions;

use function PHPStan\Testing\assertType;

final class CustomDimensionsRule extends Dimensions
{
}

/** @return array{width: int, height: int} */
function dynamicDimensionConstraints(): array
{
    return ['width' => 1, 'height' => 1];
}

$validated = Validator::make([], [
    'factory' => ['required', Rule::dimensions(['width' => 1])],
    'direct' => ['required', new Dimensions(['height' => 1])],
    'fluent_factory' => ['required', Rule::dimensions()->width(1)->height(1)->ratio(1)],
    'fluent_direct' => ['required', (new Dimensions())->minWidth(1)->maxHeight(2)],
    'dynamic_constraints' => ['required', Rule::dimensions(dynamicDimensionConstraints())],
    'optional' => [Rule::dimensions(['width' => 1])],
])->validated();

assertType(
    'array{factory: Symfony\Component\HttpFoundation\File\File, '
        . 'direct: Symfony\Component\HttpFoundation\File\File, '
        . 'fluent_factory: Symfony\Component\HttpFoundation\File\File, '
        . 'fluent_direct: Symfony\Component\HttpFoundation\File\File, '
        . 'dynamic_constraints: Symfony\Component\HttpFoundation\File\File, '
        . 'optional?: string|Symfony\Component\HttpFoundation\File\File}',
    $validated
);

$assigned = Rule::dimensions(['width' => 1]);
$factory = Rule::class;
$opaque = Validator::make([], [
    'assigned' => ['required', $assigned],
    'conditional' => ['required', Rule::dimensions()->when(
        true,
        static fn (Dimensions $rule): Dimensions => $rule->width(1)
    )],
    'subclass' => ['required', new CustomDimensionsRule(['width' => 1])],
    'dynamic_factory' => ['required', $factory::dimensions(['width' => 1])],
    'first_class_callable' => ['required', Rule::dimensions(...)],
])->validated();

assertType(
    'array{assigned?: mixed, conditional?: mixed, subclass?: mixed, '
        . 'dynamic_factory?: mixed, first_class_callable: mixed}',
    $opaque
);
