<?php

declare(strict_types=1);

use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\TestController;

use function PHPStan\Testing\assertType;

$factory = new Factory(new \Illuminate\Translation\Translator(new \Illuminate\Translation\ArrayLoader(), ''));
$condition = random_int(0, 1) === 1;

$nameValidator = $factory->make([], ['name' => 'required|string']);
$ageValidator = $factory->make([], ['age' => 'required|integer']);

$validator = $condition ? $nameValidator : $ageValidator;
assertType('array{age: int|numeric-string}|array{name: string}', $validator->validated());

$validatorInReverseOrder = $condition ? $ageValidator : $nameValidator;
assertType('array{age: int|numeric-string}|array{name: string}', $validatorInReverseOrder->validated());

$threeBranchValidator = match (random_int(0, 2)) {
    0 => $nameValidator,
    1 => $ageValidator,
    default => $factory->make([], ['email' => 'required|email']),
};
assertType(
    'array{age: int|numeric-string}|array{email: non-empty-string}|array{name: string}',
    $threeBranchValidator->validated()
);

$sameValidator = $condition
    ? $factory->make([], ['name' => 'required|string'])
    : $factory->make([], ['name' => 'required|string']);
assertType('array{name: string}', $sameValidator->validated());

$controller = new TestController();
assertType(
    'array{age: int|numeric-string}|array{name: string}',
    $controller->validateWith($validator, new \Illuminate\Http\Request())
);

$stringValidator = $factory->make([], ['before' => 'required|string']);
$stringValidator = $stringValidator->setRules(['email' => 'required|email']);
$integerValidator = $factory->make([], ['before' => 'required|string']);
$integerValidator = $integerValidator->setRules(['count' => 'required|integer']);
$replacementValidator = $condition ? $stringValidator : $integerValidator;
assertType(
    'array{count: int|numeric-string}|array{email: non-empty-string}',
    $replacementValidator->validated()
);

$validateWithDynamicRules = static function (Factory $factory, array $rules, bool $condition): void {
    $trackedValidator = $factory->make([], ['name' => 'required|string']);
    $untrackedValidator = $factory->make([], $rules);
    $validator = $condition ? $trackedValidator : $untrackedValidator;

    assertType('array', $validator->validated());
};
