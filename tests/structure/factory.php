<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

$factory = new \Illuminate\Validation\Factory(new \Illuminate\Translation\Translator(new \Illuminate\Translation\ArrayLoader(), ''));
$validator = $factory->make([], [
    'person.*.email' => 'required|email|unique:users',
    'person.*.first_name' => 'required|string',
]);
assertType('Illuminate\\Validation\\Validator', $validator);

$validated = $validator->validated();
assertType('array{person?: array<int|string, array{email: non-empty-string, first_name: string}>}', $validated);
if (isset($validated['person'][0])) {
    assertType('non-empty-string', $validated['person'][0]['email']);
    assertType('string', $validated['person'][0]['first_name']);
}

$directValidated = $factory->validate([], [
    'person.*.email' => 'required|email|unique:users',
    'person.*.first_name' => 'required|string',
]);
assertType(
    'array{person?: array<int|string, array{email: non-empty-string, first_name: string}>}',
    $directValidated
);

$namedValidated = $factory->validate(
    rules: ['value' => 'required|boolean'],
    data: []
);
assertType("array{value: 0|1|'0'|'1'|bool}", $namedValidated);

$namedMade = $factory->make(
    rules: ['value' => 'required|string'],
    data: []
)->validated();
assertType('array{value: string}', $namedMade);

$factorySpread = [['value' => 'required|string'], []];
assertType('array', $factory->validate([], ...$factorySpread));
assertType('array', $factory->make([], ...$factorySpread)->validated());

/**
 * @param array<string, mixed> $dynamicRules
 */
function inspectFactoryWithDynamicRules(
    \Illuminate\Validation\Factory $factory,
    array $dynamicRules
): void {
    assertType('array', $factory->validate([], $dynamicRules));
}
