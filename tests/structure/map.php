<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'photos.profile' => 'required|image',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{photos: array{profile: Symfony\\Component\\HttpFoundation\\File\\File}}', $validated);
assertType('array{profile: Symfony\\Component\\HttpFoundation\\File\\File}', $validated['photos']);
assertType('Symfony\\Component\\HttpFoundation\\File\\File', $validated['photos']['profile']);
