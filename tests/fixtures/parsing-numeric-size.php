<?php

declare(strict_types=1);

namespace ParsingNumericSizeFixture;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\GenericParsingRule;
use jbboehr\Rensei\Parse;

function factoryCalls(Factory $factory): void
{
    $factory->make([], [
        'minimum' => [Parse::integer(), 'min:5'],
        'maximum' => [Parse::integer(), 'max:50'],
        'range' => [Parse::integer(), 'between:5,10'],
        'exact' => [Parse::integer(), 'size:7'],
        'several' => [Parse::integer(), 'min:5', 'max:10'],
        'profile.age' => [Parse::integer(), 'min:5'],
        'users.*.age' => [Parse::integer(), 'min:5'],
        'field\.name' => [Parse::integer(), 'min:5'],
        'integer' => ['integer', Parse::integer(), 'min:5'],
        'numeric' => ['numeric', Parse::integer(), 'max:50'],
        'decimal' => ['decimal:0', Parse::integer(), 'between:5,10'],
        'relative' => [Parse::integer(), 'gte:other'],
        'boolean' => [Parse::boolean(), 'min:1'],
        'float' => [Parse::float(), 'min:1'],
        'float_numeric' => ['numeric', Parse::float(), 'min:1'],
    ]);

    $factory->validate(
        data: [],
        rules: ['named' => [Parse::integer(), 'max:3']]
    );

    $custom = new GenericParsingRule(static fn (mixed $value): int => (int) $value);
    $factory->make([], ['custom' => [$custom, ['size', 2]]]);

    $rules = ['variable' => [Parse::integer(), 'min:2']];
    $factory->make([], $rules);

    $notUsedAsValidationRules = ['unrelated' => [Parse::integer(), 'min:2']];
}

function otherEntryPoints(Request $request): void
{
    $request->validate([
        'request' => [Parse::integer(), 'min:2'],
    ]);

    ValidatorFacade::make([], [
        'facade_make' => [Parse::integer(), 'min:2'],
    ]);

    ValidatorFacade::validate([], [
        'facade_validate' => [Parse::integer(), 'min:2'],
    ]);

    \validator([], [
        'helper' => [Parse::integer(), 'min:2'],
    ]);

    \CustomValidatorHelper\validator(
        [],
        ['custom_helper' => [Parse::integer(), 'min:2']]
    );
}
