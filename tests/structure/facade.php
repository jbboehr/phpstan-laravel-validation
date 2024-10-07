<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validated = \Illuminate\Support\Facades\Validator::validate([], [
    'person.*.email' => 'required|email|unique:users',
    'person.*.first_name' => 'required|string',
]);

assertType('array{person: array<int|string, array{email: non-empty-string, first_name: string}>}', $validated);
assertType('non-empty-string', $validated['person'][0]['email']);
assertType('string', $validated['person'][0]['first_name']);
