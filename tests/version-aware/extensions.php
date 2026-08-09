<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|extensions:txt',
    'optional_value' => 'extensions:txt',
    'nullable_value' => 'nullable|extensions:txt',
    'excluded_value' => 'required|exclude|extensions:txt',
]);
assertType('Illuminate\Validation\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: Symfony\Component\HttpFoundation\File\File, '
    . 'optional_value?: string|Symfony\Component\HttpFoundation\File\File, '
    . 'nullable_value?: string|Symfony\Component\HttpFoundation\File\File|null}',
    $validated
);
assertType('Symfony\Component\HttpFoundation\File\File', $validated['required_value']);
assertType(
    'string|Symfony\Component\HttpFoundation\File\File',
    $validated['optional_value']
);
assertType(
    'string|Symfony\Component\HttpFoundation\File\File|null',
    $validated['nullable_value']
);
