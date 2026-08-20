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

final class AnotherUnrelatedDataContainer
{
    /** @param array<mixed, mixed> $data */
    public function setData(array $data): void
    {
    }
}

function mutateDynamicAndUnrelatedReceivers(
    Validator $validator,
    UnrelatedDataContainer|AnotherUnrelatedDataContainer $unrelatedUnion,
    string $unknownMethod
): void {
    $unrelatedUnion->setData([]);
    $validator->{$unknownMethod}([]);

    $caseVariant = random_int(0, 1) === 1 ? 'setData' : 'SETDATA';
    $validator->{$caseVariant}([]);
}

function mutateInferredContracts(Factory $factory): void
{
    $factory->make([], ['age' => 'required|string'])->setData([]);
    $factory->make([], ['age' => 'required|string'])->setValue('age', 123);
    $factory->make([], ['age' => 'required|string'])->setRules(['age' => 'required|integer']);
    $factory->make([], ['age' => 'required|string'])->addRules(['name' => 'required|string']);
    $factory->make([], ['age' => 'required|string'])->sometimes(
        'name',
        'required|string',
        static fn (): bool => true
    );

    $validator = $factory->make([], ['age' => 'required|string']);
    $mutationMethod = random_int(0, 1) === 1 ? 'setData' : 'setRules';
    $validator->{$mutationMethod}([]);

    $caseVariant = random_int(0, 1) === 1 ? 'setData' : 'SETDATA';
    $validator->{$caseVariant}([]);
}

function mutateInferredSetValue(Factory $factory): void
{
    $validator = $factory->make([], ['age' => 'required|string']);
    $validator->setValue('age', 123);
}

function mutateInferredSetRules(Factory $factory): void
{
    $validator = $factory->make([], ['age' => 'required|string']);
    $validator->setRules(['age' => 'required|integer']);
}

function mutateInferredAddRules(Factory $factory): void
{
    $validator = $factory->make([], ['age' => 'required|string']);
    $validator->addRules(['name' => 'required|string']);
}

function mutateInferredSometimes(Factory $factory): void
{
    $validator = $factory->make([], ['age' => 'required|string']);
    $validator->sometimes('name', 'required|string', static fn (): bool => true);
}

function mutateInferredInsideClosure(Factory $factory): void
{
    $validator = $factory->make([], ['age' => 'required|string']);
    $callback = static function () use ($validator): void {
        $validator->setData([]);
    };

    $callback();
}
