<?php

declare(strict_types=1);

use Illuminate\Validation\Factory;

use function PHPStan\Testing\assertType;

$factory = new Factory(new \Illuminate\Translation\Translator(new \Illuminate\Translation\ArrayLoader(), ''));

$direct = $factory->make([], ['before' => 'required|string']);
$direct->setRules(['after' => 'required|integer']);
assertType('array', $direct->validated());

$chained = $factory
    ->make([], ['before' => 'required|string'])
    ->setRules(['after' => 'required|integer'])
    ->validated();
assertType('array{after: int|numeric-string}', $chained);

$reassigned = $factory->make([], ['before' => 'required|string']);
$reassigned = $reassigned->setRules(['after' => 'required|integer']);
assertType('array{after: int|numeric-string}', $reassigned->validated());

$validateWithDynamicReplacementRules = static function (Factory $factory, array $rules): void {
    $validator = $factory->make([], ['before' => 'required|string']);
    $validator->setRules($rules);
    assertType('array', $validator->validated());

    $validated = $factory
        ->make([], ['before' => 'required|string'])
        ->setRules($rules)
        ->validated();
    assertType('array', $validated);
};
