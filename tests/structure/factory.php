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

/**
 * @param array<string, mixed> $input
 */
function inspectSuccessfulFactoryInput(
    \Illuminate\Validation\Factory $factory,
    array $input
): void {
    $factory->validate($input, [
        'name' => 'required|string',
        'flag' => 'boolean',
        'excluded' => 'exclude|required|string',
        'conditionally_excluded' => 'exclude_if:mode,draft|required|string',
        'must_be_missing' => 'missing|string',
    ]);

    assertType('string', $input['name']);
}

/**
 * @param array<string, mixed> $input
 */
function inspectNamedSuccessfulFactoryInput(
    \Illuminate\Validation\Factory $factory,
    array $input
): void {
    $validated = $factory->validate(
        rules: ['value' => 'required|integer'],
        data: $input
    );

    assertType('float|int|numeric-string|Stringable|true', $input['value']);
    assertType('array{value: float|int|numeric-string|Stringable|true}', $validated);
}

/**
 * @param array{extra: int} $input
 */
function inspectSuccessfulFactoryInputPreservesKnownKeys(
    \Illuminate\Validation\Factory $factory,
    array $input
): void {
    $factory->validate($input, [
        'name' => 'required|string',
        'optional' => 'string',
    ]);

    assertType('array{extra: int, name: string}', $input);
}

/**
 * @param array<string, mixed> $input
 * @param array<array-key, mixed> $dynamicRules
 */
function inspectUnresolvedSuccessfulFactoryInput(
    \Illuminate\Validation\Factory $factory,
    array $input,
    array $dynamicRules
): void {
    $factory->validate($input, $dynamicRules);
    assertType('array<string, mixed>', $input);

    $factory->validate($input, [
        'nested.value' => 'required|string',
        'items.*' => 'required|string',
    ]);
    assertType('array<string, mixed>', $input);
}

/**
 * @param array<string, mixed> $input
 */
function inspectFactoryMakeDoesNotRefineInput(
    \Illuminate\Validation\Factory $factory,
    array $input
): void {
    $factory->make($input, ['name' => 'required|string']);
    assertType('array<string, mixed>', $input);
}

/**
 * @param array<string, mixed> $input
 */
function inspectExecutableRuleDoesNotRefineFactoryInput(
    \Illuminate\Validation\Factory $factory,
    array $input
): void {
    $factory->validate($input, [
        'name' => 'required|string',
        'other' => [
            static function (string $attribute, mixed $value, \Closure $fail) use (&$input): void {
                $input = ['name' => 123];
            },
        ],
    ]);

    assertType('array<string, mixed>', $input);
}

function inspectArgumentWritesDoNotRefineFactoryInput(
    \Illuminate\Validation\Factory $factory
): void {
    $positional = ['name' => 'Ada'];
    $factory->validate(
        $positional,
        ['name' => 'required|string'],
        $positional = ['name' => 123]
    );
    assertType('array{name: 123}', $positional);

    $named = ['name' => 'Ada'];
    $factory->validate(
        rules: ['name' => 'required|string'],
        data: $named,
        messages: $named = ['name' => 123]
    );
    assertType('array{name: 123}', $named);
}

/**
 * @param array<string, mixed> $input
 * @param iterable<int, array<array-key, string>> $arguments
 */
function inspectIndirectExecutionDoesNotRefineFactoryInput(
    \Illuminate\Validation\Factory $factory,
    array $input,
    \Stringable $mutator,
    iterable $arguments
): void {
    $factory->validate(
        $input,
        ['name' => 'required|string'],
        [(string) $mutator]
    );
    assertType('array<string, mixed>', $input);

    $factory->validate(
        $input,
        ['name' => 'required|string'],
        ...$arguments
    );
    assertType('array<string, mixed>', $input);
}

final class FactoryValidationConsumer
{
    public function __construct(
        private \Illuminate\Validation\Factory $factory
    ) {
    }

    /**
     * @param array<string, mixed> $input
     */
    public function inspect(array $input): void
    {
        $this->factory->validate($input, ['name' => 'required|string']);
        assertType('string', $input['name']);
    }
}
