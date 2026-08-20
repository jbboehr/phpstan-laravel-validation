<?php

declare(strict_types=1);

use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Numeric;
use jbboehr\Rensei\Parse;

function numericParsingSizeBuilders(Factory $factory): void
{
    $factory->make([], [
        'hazard' => [Parse::integer(), 'min:5'],
        'factory' => [Rule::numeric(), Parse::integer(), 'min:5'],
        'direct' => [new Numeric(), Parse::integer(), 'min:5'],
        'fluent' => [Rule::numeric()->max(10), Parse::integer()],
    ]);
}
