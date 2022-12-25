<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|file',
    'optional_value' => 'file',
    'excluded_value' => 'required|exclude|file',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: Symfony\\Component\\HttpFoundation\\File\\File, optional_value?: Symfony\\Component\\HttpFoundation\\File\\File}', $validated);
assertType('Symfony\\Component\\HttpFoundation\\File\\File', $validated['required_value']);
assertType('Symfony\\Component\\HttpFoundation\\File\\File', $validated['optional_value']);
