<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

use function PHPStan\Testing\assertType;

final class LookalikeInRuleFactory
{
    /** @param list<string> $values */
    public static function in(array $values): In
    {
        return Rule::in($values);
    }
}

final class CustomInRule extends In
{
}

/** @return list<string> */
function dynamicAllowedValues(): array
{
    return ['one'];
}

$allowed = ['one', 'two'];
$floatAllowed = [2.5];
$validator = Validator::make([], [
    'strings' => ['required', Rule::in(['one', 'a,b', 'a"b'])],
    'numeric' => ['required', Rule::in([1])],
    'numeric_multiple' => ['required', Rule::in([1, 2.5, -3.0])],
    'float_constant_array' => ['required', Rule::in($floatAllowed)],
    'optional' => [Rule::in(['one'])],
    'scalar' => ['required', Rule::in('one')],
    'constant_array' => ['required', Rule::in($allowed)],
    'empty' => ['required', Rule::in([])],
    'direct_strings' => ['required', new In(['one', 'two'])],
    'direct_scalar' => ['required', new In('one')],
    'direct_variadic' => ['required', new In('one', 'two')],
    'direct_empty' => ['required', new In([])],
    'direct_float' => ['required', new In([2.5])],
]);

assertType(
    "array{strings: 'a\"b'|'a,b'|'one'|Stringable, "
        . 'numeric: 1|float|numeric-string|Stringable|true, '
        . 'numeric_multiple: float|int|numeric-string|Stringable|true, '
        . 'float_constant_array: float|int|numeric-string|Stringable, '
        . "optional?: string|Stringable, scalar: 'one'|Stringable, "
        . "constant_array: 'one'|'two'|Stringable, empty: ''|Stringable|false|null, "
        . "direct_strings: 'one'|'two'|Stringable, direct_scalar?: mixed, "
        . 'direct_variadic?: mixed, '
        . "direct_empty: ''|Stringable|false|null, "
        . 'direct_float: float|int|numeric-string|Stringable}',
    $validator->validated()
);

$assigned = Rule::in(['one']);
$assignedDirect = new In(['one']);
$dynamic = Validator::make([], [
    'value' => ['required', $assigned],
    'direct' => ['required', $assignedDirect],
]);
assertType('array{value?: mixed, direct?: mixed}', $dynamic->validated());

$factory = Rule::class;
$inClass = In::class;
$method = 'in';
$declined = Validator::make([], [
    'different_method' => ['required', Rule::notIn(['one'])],
    'different_class' => ['required', LookalikeInRuleFactory::in(['one'])],
    'dynamic_class' => ['required', $factory::in(['one'])],
    'dynamic_direct_class' => ['required', new $inClass(['one'])],
    'dynamic_direct_value' => ['required', new In(dynamicAllowedValues())],
    'subclass' => ['required', new CustomInRule(['one'])],
    'dynamic_method' => ['required', Rule::$method(['one'])],
    'unpacked' => ['required', Rule::in(...['one'])],
    'direct_unpacked' => ['required', new In(...['one'])],
]);
assertType(
    'array{different_method: mixed, different_class?: mixed, dynamic_class?: mixed, '
        . 'dynamic_direct_class?: mixed, dynamic_direct_value?: mixed, subclass?: mixed, '
        . 'dynamic_method?: mixed, unpacked?: mixed, direct_unpacked?: mixed}',
    $declined->validated()
);
