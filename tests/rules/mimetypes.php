<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'required_value' => 'required|mimetypes:video/avi,video/mpeg,video/quicktime',
    'optional_value' => 'mimetypes:video/avi,video/mpeg,video/quicktime',
    'excluded_value' => 'required|exclude|mimetypes:video/avi,video/mpeg,video/quicktime',
    'deduplicated_value' => 'required|file|mimetypes:video/avi,video/mpeg,video/quicktime',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{required_value: Symfony\\Component\\HttpFoundation\\File\\File, optional_value?: Symfony\\Component\\HttpFoundation\\File\\File, deduplicated_value: Symfony\\Component\\HttpFoundation\\File\\File}', $validated);
assertType('Symfony\\Component\\HttpFoundation\\File\\File', $validated['required_value']);
assertType('Symfony\\Component\\HttpFoundation\\File\\File', $validated['optional_value']);
assertType('Symfony\\Component\\HttpFoundation\\File\\File', $validated['deduplicated_value']);
