<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|dimensions:width=1,height=1,ratio=1',
    'optional_value' => 'dimensions:min_width=1',
    'nullable_value' => 'nullable|dimensions:max_width=10',
    'excluded_value' => 'required|exclude|dimensions:width=1',
    'combined_image_value' => 'required|image|dimensions:width=1',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType(
    'array{required_value: Symfony\\Component\\HttpFoundation\\File\\File, '
        . 'optional_value?: string|Symfony\\Component\\HttpFoundation\\File\\File, '
        . 'nullable_value?: string|Symfony\\Component\\HttpFoundation\\File\\File|null, '
        . 'combined_image_value: Symfony\\Component\\HttpFoundation\\File\\File}',
    $validated
);
assertType('Symfony\\Component\\HttpFoundation\\File\\File', $validated['required_value']);
assertType(
    'string|Symfony\\Component\\HttpFoundation\\File\\File',
    $validated['optional_value']
);
assertType(
    'string|Symfony\\Component\\HttpFoundation\\File\\File|null',
    $validated['nullable_value']
);
assertType(
    'Symfony\\Component\\HttpFoundation\\File\\File',
    $validated['combined_image_value']
);
