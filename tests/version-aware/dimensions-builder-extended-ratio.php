<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Dimensions;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'factory_minimum' => ['required', Rule::dimensions()->minRatio(1)],
    'factory_maximum' => ['required', Rule::dimensions()->maxRatio(1)],
    'direct_range' => ['required', (new Dimensions())->ratioBetween(1, 2)],
])->validated();

assertType(
    'array{factory_minimum: Symfony\Component\HttpFoundation\File\File, '
        . 'factory_maximum: Symfony\Component\HttpFoundation\File\File, '
        . 'direct_range: Symfony\Component\HttpFoundation\File\File}',
    $validated
);
