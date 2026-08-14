<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus;

use function PHPStan\Testing\assertSuperType;
use function PHPStan\Testing\assertType;

final class LookalikeArrayKeysRuleFactory
{
    /** @param list<string> $keys */
    public static function arrayKeys(array $keys): \Stringable
    {
        return Rule::arrayKeys($keys);
    }
}

$keys = ['name', 'email'];
$validated = Validator::make([], [
    'keyed' => ['required', Rule::arrayKeys(['name', 'email'])],
    'optional' => [Rule::arrayKeys(['name', 'email'])],
    'scalar' => ['required', Rule::arrayKeys('name')],
    'variadic' => ['required', Rule::arrayKeys('name', 'email')],
    'constant' => ['required', Rule::arrayKeys($keys)],
    'enum' => ['required', Rule::arrayKeys([PureValidationStatus::Draft])],
    'backed_enum' => ['required', Rule::arrayKeys([StringValidationStatus::Draft])],
    'comma' => ['required', Rule::arrayKeys(['a,b'])],
])->validated();

assertType(
    'array{keyed: array{name?: mixed, email?: mixed}, '
        . 'optional?: array{name?: mixed, email?: mixed}|string, scalar: array{name?: mixed}, '
        . 'variadic: array{name?: mixed, email?: mixed}, '
        . 'constant: array{name?: mixed, email?: mixed}, enum: array{Draft?: mixed}, '
        . 'backed_enum: array{draft?: mixed}, comma: array{a?: mixed, b?: mixed}}',
    $validated
);

$edgeKeys = Validator::make([], [
    'empty' => ['required', Rule::arrayKeys([])],
    'blank' => ['required', Rule::arrayKeys('')],
    'explicit_null' => ['required', Rule::arrayKeys(null)],
    'false' => ['required', Rule::arrayKeys(false)],
    'numeric' => ['required', Rule::arrayKeys([0, '01'])],
])->validated();
assertSuperType('array', $edgeKeys['empty']);
assertSuperType('array', $edgeKeys['blank']);
assertSuperType('array', $edgeKeys['explicit_null']);
assertSuperType('array', $edgeKeys['false']);
assertSuperType('array', $edgeKeys['numeric']);
assertType('mixed', $edgeKeys['empty']['']);
assertType('mixed', $edgeKeys['blank']['']);
assertType('mixed', $edgeKeys['explicit_null']['']);
assertType('mixed', $edgeKeys['false']['']);
assertType('mixed', $edgeKeys['numeric'][0]);
assertType('mixed', $edgeKeys['numeric']['01']);

$nested = Validator::make([], [
    'payload' => ['required', Rule::arrayKeys(['name', 'email'])],
    'payload.name' => 'required|string',
])->validated();
assertType('array{payload: array{name?: mixed, email?: mixed}}', $nested);

$listIntersection = Validator::make([], [
    'value' => ['required', Rule::arrayKeys([0, 2]), 'list'],
])->validated();
assertType('array{value: array{0?: mixed}}', $listIntersection);

/** @return list<string> */
function dynamicAllowedArrayKeys(): array
{
    return ['name'];
}

function dynamicAllowedArrayKey(): string
{
    return 'name';
}

$assigned = Rule::arrayKeys(['name']);
$factory = Rule::class;
$method = 'arrayKeys';
$opaque = Validator::make([], [
    'assigned' => ['required', $assigned],
    'dynamic_expression' => ['required', Rule::arrayKeys(dynamicAllowedArrayKeys())],
    'dynamic_array_item' => ['required', Rule::arrayKeys([dynamicAllowedArrayKey()])],
    'arrayable' => ['required', Rule::arrayKeys(collect(['name']))],
    'unpacked_argument' => ['required', Rule::arrayKeys(...['name'])],
    'first_class_callable' => ['required', Rule::arrayKeys(...)],
    'unpacked_array_item' => ['required', Rule::arrayKeys([...['name']])],
    'dynamic_class' => ['required', $factory::arrayKeys(['name'])],
    'dynamic_method' => ['required', Rule::$method(['name'])],
    'different_class' => ['required', LookalikeArrayKeysRuleFactory::arrayKeys(['name'])],
    'different_method' => ['required', Rule::array(['name'])],
])->validated();
assertType(
    'array{assigned?: mixed, dynamic_expression?: mixed, dynamic_array_item?: mixed, '
        . 'arrayable?: mixed, unpacked_argument?: mixed, first_class_callable: mixed, '
        . 'unpacked_array_item?: mixed, '
        . 'dynamic_class?: mixed, dynamic_method?: mixed, different_class?: mixed, '
        . 'different_method: array{name?: mixed}}',
    $opaque
);

/** @param array{0?: 'name'} $optionalKeys */
function inspectOptionalAllowedArrayKeys(array $optionalKeys): void
{
    $validated = Validator::make([], [
        'value' => ['required', Rule::arrayKeys($optionalKeys)],
    ])->validated();
    assertType('array{value?: mixed}', $validated);
}

function inspectUnionAllowedArrayKey(bool $useName): void
{
    $validated = Validator::make([], [
        'value' => ['required', Rule::arrayKeys($useName ? 'name' : 'email')],
    ])->validated();
    assertType('array{value?: mixed}', $validated);
}
