<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|mimes:jpg,bmp,png',
    'optional_value' => 'mimes:jpg,bmp,png',
    'excluded_value' => 'required|exclude|mimes:jpg,bmp,png',
    'deduplicated_value' => 'required|file|mimes:jpg,bmp,png',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: Symfony\\Component\\HttpFoundation\\File\\File, optional_value?: Symfony\\Component\\HttpFoundation\\File\\File, deduplicated_value: Symfony\\Component\\HttpFoundation\\File\\File}', $validated);
assertType('Symfony\\Component\\HttpFoundation\\File\\File', $validated['required_value']);
assertType('Symfony\\Component\\HttpFoundation\\File\\File', $validated['optional_value']);
assertType('Symfony\\Component\\HttpFoundation\\File\\File', $validated['deduplicated_value']);
