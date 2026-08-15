<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\ExcludeIf;
use Illuminate\Validation\Rules\ProhibitedIf;
use Illuminate\Validation\Rules\RequiredIf;

use function PHPStan\Testing\assertType;

final class LookalikeConditionalRuleFactory
{
    public static function requiredIf(bool $condition): RequiredIf
    {
        return new RequiredIf($condition);
    }
}

final class CustomRequiredIf extends RequiredIf
{
}

function dynamicConditionalRuleCondition(): bool
{
    return random_int(0, 1) === 1;
}

const LITERAL_CONDITIONAL_TRUE = true;
const LITERAL_CONDITIONAL_FALSE = false;

$literal = Validator::make([], [
    'factory_required_true' => ['string', Rule::requiredIf(true)],
    'factory_required_false' => ['string', Rule::requiredIf(false)],
    'direct_required_true' => ['string', new RequiredIf(true)],
    'direct_required_false' => ['string', new RequiredIf(false)],
    'factory_exclude_true' => ['required', 'string', Rule::excludeIf(true)],
    'factory_exclude_false' => ['required', 'string', Rule::excludeIf(false)],
    'direct_exclude_true' => ['required', 'string', new ExcludeIf(true)],
    'direct_exclude_false' => ['required', 'string', new ExcludeIf(false)],
    'factory_prohibited_true' => Rule::prohibitedIf(true),
    'factory_prohibited_false' => ['required', 'string', Rule::prohibitedIf(false)],
    'direct_prohibited_true' => new ProhibitedIf(true),
    'direct_prohibited_false' => ['required', 'string', new ProhibitedIf(false)],
    'standalone_required_false' => Rule::requiredIf(false),
    'standalone_exclude_false' => Rule::excludeIf(false),
    'standalone_prohibited_false' => Rule::prohibitedIf(false),
])->validated();
assertType(
    'array{factory_required_true: string, factory_required_false?: string, '
        . 'direct_required_true: string, direct_required_false?: string, '
        . 'factory_exclude_false: string, direct_exclude_false: string, '
        . 'factory_prohibited_true?: mixed, factory_prohibited_false: string, '
        . 'direct_prohibited_true?: mixed, direct_prohibited_false: string, '
        . 'standalone_required_false?: mixed, standalone_exclude_false?: mixed, '
        . 'standalone_prohibited_false?: mixed}',
    $literal
);

$nested = Validator::make([], [
    'factory_required' => Rule::requiredIf(false),
    'factory_required.name' => 'required|string',
    'direct_required' => new RequiredIf(false),
    'direct_required.name' => 'required|string',
    'factory_exclude' => Rule::excludeIf(false),
    'factory_exclude.name' => 'required|string',
    'direct_exclude' => new ExcludeIf(false),
    'direct_exclude.name' => 'required|string',
    'factory_prohibited' => Rule::prohibitedIf(false),
    'factory_prohibited.name' => 'required|string',
    'direct_prohibited' => new ProhibitedIf(false),
    'direct_prohibited.name' => 'required|string',
])->validated();
assertType(
    'array{factory_required: mixed, direct_required: mixed, factory_exclude: mixed, '
        . 'direct_exclude: mixed, factory_prohibited: mixed, direct_prohibited: mixed}',
    $nested
);

$nestedInteractions = Validator::make([], [
    'wildcard' => Rule::requiredIf(false),
    'wildcard.*.name' => 'required|string',
    'excluded_descendant' => Rule::requiredIf(false),
    'excluded_descendant.secret' => 'exclude',
    'missing_descendant' => Rule::requiredIf(false),
    'missing_descendant.forbidden' => 'missing',
    'bare_array' => [Rule::requiredIf(false), 'array'],
    'bare_array.name' => 'required|string',
    'excluded_parent' => Rule::excludeIf(true),
    'excluded_parent.name' => 'required|string',
])->validated();
assertType(
    'array{wildcard?: mixed, excluded_descendant?: mixed, missing_descendant?: mixed, '
        . 'bare_array: array{name: string}}',
    $nestedInteractions
);

$literalTrue = true;
$literalFalse = false;
$constantForms = Validator::make([], [
    'local_true' => ['string', Rule::requiredIf($literalTrue)],
    'local_false' => ['string', Rule::requiredIf($literalFalse)],
    'constant_true' => ['string', Rule::requiredIf(LITERAL_CONDITIONAL_TRUE)],
    'constant_false' => ['string', Rule::requiredIf(LITERAL_CONDITIONAL_FALSE)],
    'named_factory_true' => ['string', Rule::requiredIf(callback: true)],
    'named_factory_false' => ['string', Rule::requiredIf(callback: false)],
    'named_direct_true' => ['string', new RequiredIf(condition: true)],
    'named_direct_false' => ['string', new RequiredIf(condition: false)],
])->validated();
assertType(
    'array{local_true: string, local_false?: string, constant_true: string, '
        . 'constant_false?: string, named_factory_true: string, named_factory_false?: string, '
        . 'named_direct_true: string, named_direct_false?: string}',
    $constantForms
);

$assigned = Rule::requiredIf(true);
$assignedDirect = new RequiredIf(true);
$factoryClass = Rule::class;
$directClass = RequiredIf::class;
$method = 'requiredIf';
$opaque = Validator::make([], [
    'dynamic_factory' => ['required', 'string', Rule::requiredIf(dynamicConditionalRuleCondition())],
    'dynamic_direct' => ['required', 'string', new RequiredIf(dynamicConditionalRuleCondition())],
    'callback_factory' => ['required', 'string', Rule::requiredIf(static fn (): bool => true)],
    'callback_direct' => ['required', 'string', new RequiredIf(static fn (): bool => true)],
    'assigned_factory' => ['required', 'string', $assigned],
    'assigned_direct' => ['required', 'string', $assignedDirect],
    'lookalike_factory' => ['required', 'string', LookalikeConditionalRuleFactory::requiredIf(true)],
    'subclass' => ['required', 'string', new CustomRequiredIf(true)],
    'dynamic_factory_class' => ['required', 'string', $factoryClass::requiredIf(true)],
    'dynamic_direct_class' => ['required', 'string', new $directClass(true)],
    'dynamic_method' => ['required', 'string', Rule::$method(true)],
    'first_class_callable' => ['required', 'string', Rule::requiredIf(...)],
    'unpacked_factory' => ['required', 'string', Rule::requiredIf(...[true])],
    'unpacked_direct' => ['required', 'string', new RequiredIf(...[true])],
]);
assertType(
    'array{dynamic_factory?: mixed, dynamic_direct?: mixed, callback_factory?: mixed, '
        . 'callback_direct?: mixed, assigned_factory?: mixed, assigned_direct?: mixed, '
        . 'lookalike_factory?: mixed, subclass?: mixed, dynamic_factory_class?: mixed, '
        . 'dynamic_direct_class?: mixed, dynamic_method?: mixed, first_class_callable: string, '
        . 'unpacked_factory?: mixed, unpacked_direct?: mixed}',
    $opaque->validated()
);
