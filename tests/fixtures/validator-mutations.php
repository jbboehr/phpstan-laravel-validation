<?php

declare(strict_types=1);

namespace ValidatorMutationFixture;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\UnrelatedDataContainer;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\ValidatorSubclass;
use jbboehr\Rensei\Rules\BaseParsingRule;

function mutateValidators(
    Validator $validator,
    ?Validator $nullableValidator,
    ValidatorSubclass $subclass,
    Validator|UnrelatedDataContainer $union,
    UnrelatedDataContainer $unrelated,
    Factory $factory,
    ValidatorContract $contract
): void {
    $validator->setData([]);
    $nullableValidator?->setData([]);
    $subclass->setData([]);
    $union->setData([]);
    $validator->setValue('age', 123);
    $validator->setRules(['age' => 'integer']);
    $validator->addRules(['age' => 'string']);
    $validator->sometimes('age', 'integer', static fn (): bool => true);
    $contract->sometimes('age', 'integer', static fn (): bool => true);
    $method = 'addRules';
    $validator->{$method}(['age' => 'required']);
    $mutationMethod = random_int(0, 1) === 1 ? 'setData' : 'setRules';
    $validator->{$mutationMethod}([]);
    $validator->setData(data: []);
    $factory->make([], [])->setData([]);

    /** @var null $definitelyNull */
    $definitelyNull = null;
    $definitelyNull?->setData([]);

    $maybeMutationMethod = random_int(0, 1) === 1 ? 'setData' : 'passes';
    $validator->{$maybeMutationMethod}([]);

    $unrelated->setData([]);
    $unrelated->setValue('age', 123);
    $unrelated->setRules([]);
    $unrelated->addRules([]);
    $unrelated->sometimes('age', 'integer', static fn (): bool => true);
    $callable = $validator->setData(...);
}

/** @extends BaseParsingRule<mixed> */
final class ExternalParsingRule extends BaseParsingRule
{
    public function parse(mixed $value): mixed
    {
        return $value;
    }

    public function mutateValidator(Validator $validator): void
    {
        $validator->setValue('age', 123);
    }

    protected function message(): string
    {
        return 'Invalid value.';
    }
}
