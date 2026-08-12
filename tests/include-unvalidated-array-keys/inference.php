<?php

declare(strict_types=1);

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\IncludedArrayKeysRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\TestController;

use function PHPStan\Testing\assertType;

$rules = [
    'payload' => 'required|array',
    'payload.name' => 'required|string',
];

$factory = new Factory(new Translator(new ArrayLoader(), 'en'));
assertType('array{payload: array}', $factory->make([], $rules)->validated());
assertType('array{payload: array}', $factory->validate([], $rules));

$listRules = [
    'items' => 'required|list',
    'items.*.id' => 'required|integer',
];
assertType(
    'array{items: list}',
    $factory->validate([], $listRules)
);

$wildcardRules = [
    'items' => 'required|array',
    'items.*.id' => 'required|integer',
];
assertType('array{items: array}', $factory->validate([], $wildcardRules));

$childOnlyRules = [
    'payload.name' => 'required|string',
];
assertType(
    'array{payload: array{name: string}}',
    $factory->validate([], $childOnlyRules)
);

$parameterizedRules = [
    'payload' => 'required|array:name',
    'payload.name' => 'required|string',
];
assertType(
    'array{payload: array{name?: mixed}}',
    $factory->validate([], $parameterizedRules)
);

$excludedListItemRules = [
    'items' => 'required|list',
    'items.0' => 'exclude',
];
assertType(
    'array{items: array<int, mixed>}',
    $factory->validate([], $excludedListItemRules)
);

$conditionallyExcludedListItemRules = [
    'items' => 'required|list',
    'items.0' => 'exclude_if:mode,hidden',
    'mode' => 'required|string',
];
assertType(
    'array{items: array<int, mixed>, mode: string}',
    $factory->validate([], $conditionallyExcludedListItemRules)
);

$excludedRequiredKeyRules = [
    'payload' => 'required|array|required_array_keys:name',
    'payload.name' => 'exclude',
];
assertType(
    'array{payload: array}',
    $factory->validate([], $excludedRequiredKeyRules)
);

assertType(
    'array{payload: array}',
    \Illuminate\Support\Facades\Validator::make([], $rules)->validated()
);
assertType(
    'array{payload: array}',
    \Illuminate\Support\Facades\Validator::validate([], $rules)
);
assertType('array{payload: array}', validator([], $rules)->validated());

$request = new \Illuminate\Http\Request();
assertType('array{payload: array}', $request->validate($rules));

$controller = new TestController();
assertType('array{payload: array}', $controller->validate($request, $rules));
assertType(
    'array{payload: array}',
    $controller->validateWith($factory->make([], $rules), $request)
);

function inspectIncludedArrayKeysRequest(IncludedArrayKeysRequest $request): void
{
    assertType('array{payload: array}', $request->validated());
}

$directValidator = new Validator(
    new Translator(new ArrayLoader(), 'en'),
    [],
    $rules
);
assertType('array', $directValidator->validated());
