<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

const RULES = [
    'value' => 'required|string',
];

$validated = \Illuminate\Support\Facades\Validator::make([], RULES)->validated();

assertType('array{value: string}', $validated);
