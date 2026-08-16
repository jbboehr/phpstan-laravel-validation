<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;

use function PHPStan\Testing\assertType;

/** @return array<string, mixed> */
function conditionalPresenceInput(): array
{
    return [];
}

$presentIf = Validator::make(conditionalPresenceInput(), [
    'mode' => 'required|string|in:create',
    'value' => 'present_if:mode,create|string',
])->validated();
assertType("array{mode: 'create', value: string}", $presentIf);

$presentUnless = Validator::make(conditionalPresenceInput(), [
    'mode' => 'required|string|in:update',
    'value' => 'present_unless:mode,create|string',
])->validated();
assertType("array{mode: 'update', value: string}", $presentUnless);

$missingIf = Validator::make(conditionalPresenceInput(), [
    'mode' => 'required|string|in:create',
    'value' => 'missing_if:mode,create|string',
])->validated();
assertType("array{mode: 'create'}", $missingIf);

$missingUnless = Validator::make(conditionalPresenceInput(), [
    'mode' => 'required|string|in:update',
    'value' => 'missing_unless:mode,create|string',
])->validated();
assertType("array{mode: 'update'}", $missingUnless);
