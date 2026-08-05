<?php

declare(strict_types=1);

use Illuminate\Validation\Factory;

use function PHPStan\Testing\assertType;

$factory = new Factory(new \Illuminate\Translation\Translator(new \Illuminate\Translation\ArrayLoader(), ''));

$larastan = $factory->make([], ['before' => 'required|string']);
assertType('Illuminate\Support\ValidatedInput', $larastan->safe());
assertType('array<string, mixed>', $larastan->safe(['before']));

$direct = $factory->make([], ['before' => 'required|string']);
$direct->setRules(['after' => 'required|integer']);
assertType('array{before: string}', $direct->validated());

$chained = $factory
    ->make([], ['before' => 'required|string'])
    ->setRules(['after' => 'required|integer'])
    ->validated();
assertType('array{after: int|numeric-string}', $chained);

$reassigned = $factory->make([], ['before' => 'required|string']);
$reassigned = $reassigned->setRules(['after' => 'required|integer']);
assertType('array{after: int|numeric-string}', $reassigned->validated());

$union = random_int(0, 1) === 1
    ? $factory->make([], ['name' => 'required|string'])
    : $factory->make([], ['age' => 'required|integer']);
assertType('array{age: int|numeric-string}|array{name: string}', $union->validated());
