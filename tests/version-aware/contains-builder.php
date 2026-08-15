<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Contains;
use Illuminate\Validation\Rules\DoesntContain;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'contains_factory' => ['required', Rule::contains('needle')],
    'contains_direct' => ['required', new Contains('needle')],
    'optional_contains' => [Rule::contains('needle')],
    'doesnt_contain_factory' => ['required', Rule::doesntContain('blocked')],
    'doesnt_contain_direct' => ['required', new DoesntContain('blocked')],
])->validated();

assertType(
    'array{contains_factory: array, contains_direct: array, optional_contains?: array|string, '
        . 'doesnt_contain_factory?: mixed, doesnt_contain_direct?: mixed}',
    $validated
);
