<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\ArrayRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus;

use function PHPStan\Testing\assertType;

final class LookalikeArrayRuleFactory
{
    /** @param list<string> $keys */
    public static function array(array $keys): \Stringable
    {
        return Rule::array($keys);
    }
}

final class CustomArrayRule extends ArrayRule
{
}

$keys = ['name', 'email'];
$validator = Validator::make([], [
    'bare' => ['required', Rule::array()],
    'empty' => ['required', Rule::array([])],
    'keyed' => ['required', Rule::array(['name', 'email'])],
    'optional' => [Rule::array(['name', 'email'])],
    'scalar' => ['required', Rule::array('name')],
    'variadic' => ['required', Rule::array('name', 'email')],
    'constant' => ['required', Rule::array($keys)],
    'enum' => ['required', Rule::array([PureValidationStatus::Draft])],
    'backed_enum' => ['required', Rule::array([StringValidationStatus::Draft])],
    'comma' => ['required', Rule::array(['a,b'])],
    'direct_bare' => ['required', new ArrayRule()],
    'direct_empty' => ['required', new ArrayRule([])],
    'direct_keyed' => ['required', new ArrayRule(['name', 'email'])],
    'direct_variadic' => ['required', new ArrayRule('name', 'email')],
    'direct_enum' => ['required', new ArrayRule([StringValidationStatus::Draft])],
]);

assertType(
    'array{bare: array, empty: array, keyed: array{name?: mixed, email?: mixed}, '
        . 'optional?: array{name?: mixed, email?: mixed}|string, scalar: array{name?: mixed}, '
        . 'variadic: array{name?: mixed, email?: mixed}, '
        . 'constant: array{name?: mixed, email?: mixed}, enum: array{Draft?: mixed}, '
        . 'backed_enum: array{draft?: mixed}, comma: array{a?: mixed, b?: mixed}, '
        . 'direct_bare: array, direct_empty: array, '
        . 'direct_keyed: array{name?: mixed, email?: mixed}, '
        . 'direct_variadic: array{name?: mixed, email?: mixed}, '
        . 'direct_enum: array{draft?: mixed}}',
    $validator->validated()
);

$edgeKeys = Validator::make([], [
    'explicit_null' => ['required', Rule::array(null)],
    'false' => ['required', Rule::array(false)],
    'numeric' => ['required', Rule::array([0, '01'])],
    'direct_explicit_null' => ['required', new ArrayRule(null)],
])->validated();
assertType('int<0, 1>', count($edgeKeys['explicit_null']));
assertType('int<0, 1>', count($edgeKeys['false']));
assertType('int<0, 2>', count($edgeKeys['numeric']));
assertType('int<0, 1>', count($edgeKeys['direct_explicit_null']));
assertType('true', array_key_exists('explicit_null', $edgeKeys));
assertType('true', array_key_exists('false', $edgeKeys));
assertType('true', array_key_exists('numeric', $edgeKeys));
assertType('true', array_key_exists('direct_explicit_null', $edgeKeys));
assertType("''|null", array_key_first($edgeKeys['explicit_null']));
assertType("''|null", array_key_first($edgeKeys['false']));
assertType("0|'01'|null", array_key_first($edgeKeys['numeric']));
assertType("''|null", array_key_first($edgeKeys['direct_explicit_null']));

$bareProjection = Validator::make([], [
    'payload' => ['required', Rule::array()],
    'payload.name' => 'required|string',
])->validated();
assertType('array{payload: array{name: string}}', $bareProjection);

$emptyProjection = Validator::make([], [
    'payload' => ['required', Rule::array([])],
    'payload.name' => 'required|string',
])->validated();
assertType('array{payload: array{name: string}}', $emptyProjection);

$directBareProjection = Validator::make([], [
    'payload' => ['required', new ArrayRule()],
    'payload.name' => 'required|string',
])->validated();
assertType('array{payload: array{name: string}}', $directBareProjection);

$keyedMissing = Validator::make([], [
    'payload' => ['required', Rule::array(['name'])],
    'payload.child' => 'missing',
])->validated();
assertType('array{payload: array{name?: mixed}}', $keyedMissing);

$directKeyedMissing = Validator::make([], [
    'payload' => ['required', new ArrayRule(['name'])],
    'payload.child' => 'missing',
])->validated();
assertType('array{payload: array{name?: mixed}}', $directKeyedMissing);

$bareMissing = Validator::make([], [
    'payload' => ['required', Rule::array()],
    'payload.child' => 'missing',
])->validated();
assertType('array{}', $bareMissing);

/** @return list<string> */
function dynamicArrayKeys(): array
{
    return ['name'];
}

function dynamicArrayKey(): string
{
    return 'name';
}

$assigned = Rule::array(['name']);
$assignedDirect = new ArrayRule(['name']);
$factory = Rule::class;
$arrayRuleClass = ArrayRule::class;
$method = 'array';
$opaque = Validator::make([], [
    'dynamic_values' => ['required', $assigned],
    'assigned_direct' => ['required', $assignedDirect],
    'dynamic_expression' => ['required', Rule::array(dynamicArrayKeys())],
    'dynamic_direct_expression' => ['required', new ArrayRule(dynamicArrayKeys())],
    'float_parameter' => ['required', Rule::array([2.5])],
    'direct_float_parameter' => ['required', new ArrayRule([2.5])],
    'dynamic_array_item' => ['required', Rule::array([dynamicArrayKey()])],
    'unpacked_argument' => ['required', Rule::array(...['name'])],
    'unpacked_array_item' => ['required', Rule::array([...['name']])],
    'dynamic_class' => ['required', $factory::array(['name'])],
    'dynamic_direct_class' => ['required', new $arrayRuleClass(['name'])],
    'subclass' => ['required', new CustomArrayRule(['name'])],
    'dynamic_method' => ['required', Rule::$method(['name'])],
    'different_class' => ['required', LookalikeArrayRuleFactory::array(['name'])],
    'different_method' => ['required', Rule::requiredIf(true)],
]);
assertType(
    'array{dynamic_values?: mixed, assigned_direct?: mixed, dynamic_expression?: mixed, '
        . 'dynamic_direct_expression?: mixed, float_parameter?: mixed, '
        . 'direct_float_parameter?: mixed, dynamic_array_item?: mixed, '
        . 'unpacked_argument?: mixed, unpacked_array_item?: mixed, dynamic_class?: mixed, '
        . 'dynamic_direct_class?: mixed, subclass?: mixed, dynamic_method?: mixed, '
        . 'different_class?: mixed, different_method: mixed}',
    $opaque->validated()
);

/** @param array{0?: 'name'} $optionalKeys */
function inspectOptionalArrayKeys(array $optionalKeys): void
{
    $validated = Validator::make([], [
        'value' => ['required', Rule::array($optionalKeys)],
    ])->validated();
    assertType('array{value?: mixed}', $validated);
}

function inspectUnionArrayKey(bool $useName): void
{
    $validated = Validator::make([], [
        'value' => ['required', Rule::array($useName ? 'name' : 'email')],
    ])->validated();
    assertType('array{value?: mixed}', $validated);
}
