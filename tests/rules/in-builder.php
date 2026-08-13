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
]);

assertType(
    "array{strings: 'a\"b'|'a,b'|'one'|Stringable, "
        . 'numeric: 1|float|numeric-string|Stringable|true, '
        . 'numeric_multiple: float|int|numeric-string|Stringable|true, '
        . 'float_constant_array: float|int|numeric-string|Stringable, '
        . "optional?: string|Stringable, scalar: 'one'|Stringable, "
        . "constant_array: 'one'|'two'|Stringable, empty: ''|Stringable|false|null}",
    $validator->validated()
);

$assigned = Rule::in(['one']);
$dynamic = Validator::make([], [
    'value' => ['required', $assigned],
]);
assertType('array{value?: mixed}', $dynamic->validated());

$factory = Rule::class;
$method = 'in';
$declined = Validator::make([], [
    'different_method' => ['required', Rule::notIn(['one'])],
    'different_class' => ['required', LookalikeInRuleFactory::in(['one'])],
    'dynamic_class' => ['required', $factory::in(['one'])],
    'dynamic_method' => ['required', Rule::$method(['one'])],
    'unpacked' => ['required', Rule::in(...['one'])],
]);
assertType(
    'array{different_method: mixed, different_class?: mixed, dynamic_class?: mixed, '
        . 'dynamic_method?: mixed, unpacked?: mixed}',
    $declined->validated()
);
