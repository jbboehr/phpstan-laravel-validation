<?php

declare(strict_types=1);

namespace UnvalidatedArrayKeysConfigurationFixture;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\OtherUnvalidatedArrayKeysFactory;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\UnvalidatedArrayKeysFactory;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\UnvalidatedArrayKeysValidatorFacade;

function configureFactories(
    Factory $factory,
    ?Factory $nullableFactory,
    UnvalidatedArrayKeysFactory $factorySubclass,
    OtherUnvalidatedArrayKeysFactory $otherFactory,
    Factory|OtherUnvalidatedArrayKeysFactory $uncertainFactory
): void {
    $factory->includeUnvalidatedArrayKeys();
    $factory->excludeUnvalidatedArrayKeys();
    $nullableFactory?->includeUnvalidatedArrayKeys();
    $factorySubclass->includeUnvalidatedArrayKeys();

    $otherFactory->includeUnvalidatedArrayKeys();
    $uncertainFactory->includeUnvalidatedArrayKeys();
    $callback = $factory->includeUnvalidatedArrayKeys(...);

    ValidatorFacade::includeUnvalidatedArrayKeys();
    ValidatorFacade::excludeUnvalidatedArrayKeys();
    UnvalidatedArrayKeysValidatorFacade::includeUnvalidatedArrayKeys();

    $facadeClass = ValidatorFacade::class;
    $facadeClass::includeUnvalidatedArrayKeys();
}
