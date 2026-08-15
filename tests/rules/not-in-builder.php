<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\NotIn;

use function PHPStan\Testing\assertType;

final class LookalikeNotInRuleFactory
{
    /** @param list<string> $values */
    public static function notIn(array $values): NotIn
    {
        return Rule::notIn($values);
    }
}

final class CustomNotInRule extends NotIn
{
}

/** @return list<string> */
function blockedValues(): array
{
    return ['admin'];
}

$validator = Validator::make([], [
    'required_string' => ['required', 'string', Rule::notIn(['admin'])],
    'optional_string' => ['string', Rule::notIn(['admin'])],
    'required_mixed' => ['required', Rule::notIn(['admin'])],
    'dynamic_values' => ['required', 'string', Rule::notIn(blockedValues())],
    'unpacked_values' => ['required', 'string', Rule::notIn(...blockedValues())],
    'direct_array' => ['required', 'string', new NotIn(['admin'])],
    'direct_scalar' => ['required', 'string', new NotIn('admin')],
    'direct_variadic' => ['required', 'string', new NotIn('admin', 'owner')],
    'direct_dynamic' => ['required', 'string', new NotIn(blockedValues())],
]);

assertType(
    'array{required_string: string, optional_string?: string, required_mixed: mixed, '
        . 'dynamic_values: string, unpacked_values: string, direct_array: string, '
        . 'direct_scalar?: mixed, direct_variadic?: mixed, direct_dynamic?: mixed}',
    $validator->validated()
);

$assigned = Rule::notIn(['admin']);
$assignedDirect = new NotIn(['admin']);
$factory = Rule::class;
$notInClass = NotIn::class;
$method = 'notIn';
$declined = Validator::make([], [
    'assigned' => ['required', 'string', $assigned],
    'assigned_direct' => ['required', 'string', $assignedDirect],
    'different_method' => ['required', 'string', Rule::in(['admin'])],
    'different_class' => ['required', 'string', LookalikeNotInRuleFactory::notIn(['admin'])],
    'dynamic_class' => ['required', 'string', $factory::notIn(['admin'])],
    'dynamic_direct_class' => ['required', 'string', new $notInClass(['admin'])],
    'subclass' => ['required', 'string', new CustomNotInRule(['admin'])],
    'dynamic_method' => ['required', 'string', Rule::$method(['admin'])],
]);

assertType(
    "array{assigned?: mixed, assigned_direct?: mixed, different_method: 'admin', "
        . 'different_class?: mixed, dynamic_class?: mixed, dynamic_direct_class?: mixed, '
        . 'subclass?: mixed, dynamic_method?: mixed}',
    $declined->validated()
);
