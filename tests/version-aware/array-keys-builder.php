<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\ArrayKeys;
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

final class CustomArrayKeysRule extends ArrayKeys
{
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
    'direct_keyed' => ['required', new ArrayKeys(['name', 'email'])],
    'direct_variadic' => ['required', new ArrayKeys('name', 'email')],
    'direct_enum' => ['required', new ArrayKeys([StringValidationStatus::Draft])],
])->validated();

assertType(
    'array{keyed: array{name?: mixed, email?: mixed}, '
        . 'optional?: array{name?: mixed, email?: mixed}|string, scalar: array{name?: mixed}, '
        . 'variadic: array{name?: mixed, email?: mixed}, '
        . 'constant: array{name?: mixed, email?: mixed}, enum: array{Draft?: mixed}, '
        . 'backed_enum: array{draft?: mixed}, comma: array{a?: mixed, b?: mixed}, '
        . 'direct_keyed: array{name?: mixed, email?: mixed}, '
        . 'direct_variadic: array{name?: mixed, email?: mixed}, '
        . 'direct_enum: array{draft?: mixed}}',
    $validated
);

$edgeKeys = Validator::make([], [
    'empty' => ['required', Rule::arrayKeys([])],
    'blank' => ['required', Rule::arrayKeys('')],
    'explicit_null' => ['required', Rule::arrayKeys(null)],
    'false' => ['required', Rule::arrayKeys(false)],
    'numeric' => ['required', Rule::arrayKeys([0, '01'])],
    'direct_empty' => ['required', new ArrayKeys([])],
])->validated();
assertSuperType('array', $edgeKeys['empty']);
assertSuperType('array', $edgeKeys['blank']);
assertSuperType('array', $edgeKeys['explicit_null']);
assertSuperType('array', $edgeKeys['false']);
assertSuperType('array', $edgeKeys['numeric']);
assertSuperType('array', $edgeKeys['direct_empty']);
assertType('mixed', $edgeKeys['empty']['']);
assertType('mixed', $edgeKeys['blank']['']);
assertType('mixed', $edgeKeys['explicit_null']['']);
assertType('mixed', $edgeKeys['false']['']);
assertType('mixed', $edgeKeys['numeric'][0]);
assertType('mixed', $edgeKeys['numeric']['01']);
assertType('mixed', $edgeKeys['direct_empty']['']);

$nested = Validator::make([], [
    'payload' => ['required', Rule::arrayKeys(['name', 'email'])],
    'payload.name' => 'required|string',
])->validated();
assertType('array{payload: array{name?: mixed, email?: mixed}}', $nested);

$directNested = Validator::make([], [
    'payload' => ['required', new ArrayKeys(['name', 'email'])],
    'payload.name' => 'required|string',
])->validated();
assertType('array{payload: array{name?: mixed, email?: mixed}}', $directNested);

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
$assignedDirect = new ArrayKeys(['name']);
$factory = Rule::class;
$arrayKeysClass = ArrayKeys::class;
$method = 'arrayKeys';
$opaque = Validator::make([], [
    'assigned' => ['required', $assigned],
    'assigned_direct' => ['required', $assignedDirect],
    'dynamic_expression' => ['required', Rule::arrayKeys(dynamicAllowedArrayKeys())],
    'dynamic_direct_expression' => ['required', new ArrayKeys(dynamicAllowedArrayKeys())],
    'float_parameter' => ['required', Rule::arrayKeys([2.5])],
    'direct_float_parameter' => ['required', new ArrayKeys([2.5])],
    'dynamic_array_item' => ['required', Rule::arrayKeys([dynamicAllowedArrayKey()])],
    'arrayable' => ['required', Rule::arrayKeys(collect(['name']))],
    'unpacked_argument' => ['required', Rule::arrayKeys(...['name'])],
    'first_class_callable' => ['required', Rule::arrayKeys(...)],
    'unpacked_array_item' => ['required', Rule::arrayKeys([...['name']])],
    'dynamic_class' => ['required', $factory::arrayKeys(['name'])],
    'dynamic_direct_class' => ['required', new $arrayKeysClass(['name'])],
    'subclass' => ['required', new CustomArrayKeysRule(['name'])],
    'dynamic_method' => ['required', Rule::$method(['name'])],
    'different_class' => ['required', LookalikeArrayKeysRuleFactory::arrayKeys(['name'])],
    'different_method' => ['required', Rule::array(['name'])],
])->validated();
assertType(
    'array{assigned?: mixed, assigned_direct?: mixed, dynamic_expression?: mixed, '
        . 'dynamic_direct_expression?: mixed, float_parameter?: mixed, '
        . 'direct_float_parameter?: mixed, dynamic_array_item?: mixed, arrayable?: mixed, '
        . 'unpacked_argument?: mixed, first_class_callable: mixed, unpacked_array_item?: mixed, '
        . 'dynamic_class?: mixed, dynamic_direct_class?: mixed, subclass?: mixed, '
        . 'dynamic_method?: mixed, different_class?: mixed, different_method: array{name?: mixed}}',
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
