<?php

declare(strict_types=1);

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\OtherMutationTarget;

use function PHPStan\Testing\assertType;

$factory = new Factory(new Translator(new ArrayLoader(), ''));

$directReplacement = $factory->make([], ['before' => 'required|string']);
$directReplacement->setRules(['after' => 'required|integer']); // @phpstan-ignore laravelValidation.validatorMutation
assertType('array{before: string}', $directReplacement->validated());

$chainedReplacement = $factory->make([], ['before' => 'required|string'])
    ->setRules(['after' => 'required|integer'])
    ->validated();
assertType('array{after: float|int|numeric-string|Stringable|true}', $chainedReplacement);

$reassignedReplacement = $factory->make([], ['before' => 'required|string']);
$reassignedReplacement = $reassignedReplacement->setRules(['after' => 'required|string']); // @phpstan-ignore laravelValidation.validatorMutation
assertType('array', $reassignedReplacement->validated());

/** @param array<mixed, mixed> $rules */
$replaceWithDynamicRules = static function (Factory $factory, array $rules): void {
    $validator = $factory->make([], ['before' => 'required|string']);
    $validator = $validator->setRules($rules); // @phpstan-ignore laravelValidation.validatorMutation
    assertType('array', $validator->validated());
};

$directData = $factory->make([], ['before' => 'required|string']);
$directData->setData(['before' => 'changed']); // @phpstan-ignore laravelValidation.validatorMutation
assertType('array{before: string}', $directData->validated());

$chainedData = $factory->make([], ['before' => 'required|string'])
    ->setData(['before' => 'changed'])
    ->validated();
assertType('array{before: string}', $chainedData);

$facadeReplacement = ValidatorFacade::make([], ['before' => 'required|string'])
    ->setRules(['after' => 'required|string'])
    ->validated();
assertType('array{after: string}', $facadeReplacement);

$facadeData = ValidatorFacade::make([], ['before' => 'required|string'])
    ->setData(['before' => 'changed'])
    ->validated();
assertType('array{before: string}', $facadeData);

$helperData = validator([], ['before' => 'required|string'])
    ->setData(['before' => 'changed'])
    ->validated();
assertType('array{before: string}', $helperData);

$helperReplacement = validator([], ['before' => 'required|string'])
    ->setRules(rules: ['after' => 'required|string'])
    ->validated();
assertType('array{after: string}', $helperReplacement);

$directAdditionalRules = $factory->make([], ['before' => 'required|string']);
$directAdditionalRules->addRules(['after' => 'required|string']); // @phpstan-ignore laravelValidation.validatorMutation
assertType('array{before: string}', $directAdditionalRules->validated());

$directSometimes = $factory->make([], ['before' => 'required|string']);
$directSometimes->sometimes('after', 'required|string', static fn (): bool => true); // @phpstan-ignore laravelValidation.validatorMutation
assertType('array{before: string}', $directSometimes->validated());

$chainedSometimes = $factory->make([], ['before' => 'required|string'])
    ->sometimes('after', 'required|string', static fn (): bool => true) // @phpstan-ignore laravelValidation.validatorMutation
    ->validated();
assertType('array', $chainedSometimes);

$setDataCallable = $factory->make([], ['before' => 'required|string'])->setData(...);
assertType('Closure(array): Illuminate\\Validation\\Validator', $setDataCallable);

/** @param array<mixed, mixed> $rules */
$inspectDynamicMutation = static function (Factory $factory, array $rules): void {
    $freshData = $factory->make([], $rules)
        ->setData([])
        ->validated();
    assertType('array', $freshData);

    $freshReplacement = $factory->make([], ['before' => 'required|string'])
        ->setRules($rules)
        ->validated();
    assertType('array', $freshReplacement);

    $replacementAfterDynamicOriginal = $factory->make([], $rules)
        ->setRules(['after' => 'required|string'])
        ->validated();
    assertType('array{after: string}', $replacementAfterDynamicOriginal);
};

$union = random_int(0, 1) === 1
    ? $factory->make([], ['name' => 'required|string'])
    : $factory->make([], ['age' => 'required|integer']);
$union = $union->setData([]); // @phpstan-ignore laravelValidation.validatorMutation
assertType('array', $union->validated());

$mixedMutationTarget = random_int(0, 1) === 1
    ? $factory->make([], ['name' => 'required|string'])
    : new OtherMutationTarget();
assertType(
    'Illuminate\\Validation\\Validator|int',
    $mixedMutationTarget->setRules([]) // @phpstan-ignore laravelValidation.validatorMutation
);
