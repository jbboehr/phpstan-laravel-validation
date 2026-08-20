<?php

declare(strict_types=1);

use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\AttributeRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\IntegerRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\UnknownRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\BasicRequest;

use function PHPStan\Testing\assertType;

$factory = new Factory(new \Illuminate\Translation\Translator(new \Illuminate\Translation\ArrayLoader(), ''));

$larastan = $factory->make([], ['before' => 'required|string']);
assertType('Illuminate\Support\ValidatedInput', $larastan->safe());
assertType('array<string, mixed>', $larastan->safe(['before']));
assertType('array', $larastan->safe()->all());

$replacement = $factory->make([], ['before' => 'required|string'])
    ->setRules(['after' => 'required|integer'])
    ->validated();
assertType('array{after: float|int|numeric-string|Stringable|true}', $replacement);

$reassignedReplacement = $factory->make([], ['before' => 'required|string']);
$reassignedReplacement = $reassignedReplacement->setRules(['after' => 'required|string']); // @phpstan-ignore laravelValidation.validatorMutation
assertType('array', $reassignedReplacement->validated());

$directInvalidatedRules = $factory->make([], ['before' => 'required|string']);
$directInvalidatedRules->setRules(['after' => 'required|string']); // @phpstan-ignore laravelValidation.validatorMutation
assertType('array{before: string}', $directInvalidatedRules->validated());

$invalidatedData = $factory->make([], ['before' => 'required|string'])
    ->setData(['before' => 'changed'])
    ->validated();
assertType('array{before: string}', $invalidatedData);

$directInvalidatedData = $factory->make([], ['before' => 'required|string']);
$directInvalidatedData->setData(['before' => 'changed']); // @phpstan-ignore laravelValidation.validatorMutation
assertType('array{before: string}', $directInvalidatedData->validated());

$union = random_int(0, 1) === 1
    ? $factory->make([], ['name' => 'required|string'])
    : $factory->make([], ['age' => 'required|integer']);
assertType('array{age: float|int|numeric-string|Stringable|true}|array{name: string}', $union->validated());

$custom = $factory->make([], ['value' => ['required', new AttributeRule()]])->validated();
assertType('array{value: non-empty-string}', $custom);

$unknownCustom = $factory->make([], [
    'value' => ['required', 'string', new UnknownRule()],
])->validated();
assertType('array{value: string}', $unknownCustom);

$configuredObjectUnion = $factory->make([], [
    'value' => 'required|custom_stringable',
])->validated();
assertType('array{value: non-empty-string|Stringable}', $configuredObjectUnion);

$configuredClassObjectUnion = $factory->make([], [
    'value' => ['required', new IntegerRule()],
])->validated();
assertType('array{value: int|Stringable}', $configuredClassObjectUnion);

$factoryDirect = $factory->validate([], [
    'payload' => 'required|array',
    'payload.name' => 'required|string',
]);
assertType('array{payload: array{name: string}}', $factoryDirect);

/**
 * @param array<string, mixed> $input
 */
function inspectFacadeInputWithLarastan(array $input): void
{
    \Illuminate\Support\Facades\Validator::validate($input, [
        'name' => 'required|string',
    ]);
    assertType('string', $input['name']);
}

/**
 * @param array<string, mixed> $input
 */
function inspectFactoryInputWithLarastan(Factory $factory, array $input): void
{
    $factory->validate($input, [
        'name' => 'required|string',
    ]);
    assertType('string', $input['name']);
}

function inspectFormRequest(BasicRequest $request): void
{
    assertType(
        'array{name: string, age?: float|int|string|Stringable|true}',
        $request->validated()
    );
    assertType('string', $request->validated('name'));
    assertType('float|int|string|Stringable|true|null', $request->validated('age'));
    assertType('array{name: string}', $request->safe()->only(['name']));
}
