<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$validator = \Illuminate\Support\Facades\Validator::make([], [
    'optional_email' => 'email',
    'required_email' => 'required|email',
    'sometimes_email' => 'sometimes|email',
    'filled_email' => 'filled|email',
    'accepted_value' => 'accepted',
    'optional_array' => 'array',
    'optional_array_in' => 'array|in:foo,bar',
    'required_array_in' => 'required|array|in:foo,bar',
]);

$validated = $validator->validated();

assertType('string', $validated['optional_email']);
assertType('non-empty-string', $validated['required_email']);
assertType('string', $validated['sometimes_email']);
assertType('non-empty-string', $validated['filled_email']);
assertType("1|'1'|'on'|'true'|'yes'|true", $validated['accepted_value']);
assertType('array|string', $validated['optional_array']);
assertType('array|string', $validated['optional_array_in']);
assertType('array', $validated['required_array_in']);
