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
]);

assertType(
    'array{required_string: string, optional_string?: string, required_mixed: mixed, '
        . 'dynamic_values: string, unpacked_values: string}',
    $validator->validated()
);

$assigned = Rule::notIn(['admin']);
$factory = Rule::class;
$method = 'notIn';
$declined = Validator::make([], [
    'assigned' => ['required', 'string', $assigned],
    'different_method' => ['required', 'string', Rule::in(['admin'])],
    'different_class' => ['required', 'string', LookalikeNotInRuleFactory::notIn(['admin'])],
    'dynamic_class' => ['required', 'string', $factory::notIn(['admin'])],
    'dynamic_method' => ['required', 'string', Rule::$method(['admin'])],
]);

assertType(
    "array{assigned?: mixed, different_method: 'admin', different_class?: mixed, "
        . 'dynamic_class?: mixed, dynamic_method?: mixed}',
    $declined->validated()
);
