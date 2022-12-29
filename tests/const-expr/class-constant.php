<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

class FooBar
{
    public const RULES = [
        'value' => 'required|string',
    ];

    public function doStuff(): void
    {
        $validated = \Illuminate\Support\Facades\Validator::make([], self::RULES)->validated();
        assertType('array{value: string}', $validated);
    }
}

// This doesn't work
//$validated = \Illuminate\Support\Facades\Validator::make([], FooBar::RULES)->validated();
//assertType('array{value: string}', $validated);
