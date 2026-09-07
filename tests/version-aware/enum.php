<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FractionValidationValue;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\IntegerValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus;
use jbboehr\Rensei\Parse;

use function PHPStan\Testing\assertType;

$pure = Validator::make([], [
    'factory' => ['required', Rule::enum(PureValidationStatus::class)],
    'constructor' => ['required', new Enum(PureValidationStatus::class)],
    'optional' => [Rule::enum(PureValidationStatus::class)],
    'nullable' => ['nullable', Rule::enum(PureValidationStatus::class)],
])->validated();
assertType(
    'array{factory: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft'
        . '|jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Published, '
        . 'constructor: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft'
        . '|jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Published, '
        . 'optional?: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft'
        . '|jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Published|string, '
        . 'nullable?: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft'
        . '|jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Published|string|null}',
    $pure
);

$backed = Validator::make([], [
    'string' => ['required', Rule::enum(StringValidationStatus::class)],
    'integer' => ['required', Rule::enum(IntegerValidationStatus::class)],
])->validated();
assertType(
    "array{string: 0|1|'0'|'01'|'1'|'draft'|'published'|bool|float|"
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus::Draft|'
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus::LeadingZero|'
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus::One|'
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus::Published|'
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus::Zero|Stringable, '
        . 'integer: 0|1|2|bool|float|'
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\IntegerValidationStatus::One|'
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\IntegerValidationStatus::Two|'
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\IntegerValidationStatus::Zero|numeric-string}',
    $backed
);

$filtered = Validator::make([], [
    'only' => ['required', Rule::enum(PureValidationStatus::class)->only(PureValidationStatus::Draft)],
    'constructor_only' => ['required', (new Enum(PureValidationStatus::class))
        ->only(PureValidationStatus::Draft)],
    'only_array' => ['required', Rule::enum(PureValidationStatus::class)->only([
        PureValidationStatus::Draft,
        PureValidationStatus::Published,
    ])],
    'except' => ['required', Rule::enum(PureValidationStatus::class)->except(PureValidationStatus::Draft)],
    'last_only_wins' => ['required', Rule::enum(PureValidationStatus::class)
        ->only(PureValidationStatus::Draft)
        ->only(PureValidationStatus::Published)],
    'only_precedes_except' => ['required', Rule::enum(PureValidationStatus::class)
        ->only(PureValidationStatus::Draft)
        ->except(PureValidationStatus::Draft)],
    'empty_only_uses_except' => ['required', Rule::enum(PureValidationStatus::class)
        ->only([])
        ->except(PureValidationStatus::Draft)],
])->validated();
assertType(
    'array{only: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft, '
        . 'constructor_only: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft, '
        . 'only_array: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft'
        . '|jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Published, '
        . 'except: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Published, '
        . 'last_only_wins: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Published, '
        . 'only_precedes_except: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft, '
        . 'empty_only_uses_except: '
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Published}',
    $filtered
);

$onlyArraySubset = Validator::make([], [
    'value' => ['required', Rule::enum(PureValidationStatus::class)->only([PureValidationStatus::Draft])],
])->validated();
assertType(
    'array{value: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft}',
    $onlyArraySubset
);

$exceptArraySubset = Validator::make([], [
    'value' => ['required', Rule::enum(PureValidationStatus::class)->except([PureValidationStatus::Draft])],
])->validated();
assertType(
    'array{value: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Published}',
    $exceptArraySubset
);

$case = PureValidationStatus::Draft;
$knownCaseVariable = Validator::make([], [
    'value' => ['required', Rule::enum(PureValidationStatus::class)->only($case)],
])->validated();
assertType(
    'array{value: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft}',
    $knownCaseVariable
);

$enumClass = PureValidationStatus::class;
$knownClassVariable = Validator::make([], [
    'value' => ['required', Rule::enum($enumClass)],
])->validated();
assertType(
    'array{value: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft'
        . '|jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Published}',
    $knownClassVariable
);

$coerciveFilters = Validator::make([], [
    'string_one' => ['required', Rule::enum(StringValidationStatus::class)->only(StringValidationStatus::One)],
    'string_draft' => ['required', Rule::enum(StringValidationStatus::class)->only(StringValidationStatus::Draft)],
    'string_leading_zero' => ['required', Rule::enum(StringValidationStatus::class)
        ->only(StringValidationStatus::LeadingZero)],
    'integer_one' => ['required', Rule::enum(IntegerValidationStatus::class)->only(IntegerValidationStatus::One)],
    'integer_two' => ['required', Rule::enum(IntegerValidationStatus::class)->only(IntegerValidationStatus::Two)],
])->validated();
assertType(
    "array{string_one: 1|'1'|float|"
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus::One|Stringable|true, '
        . "string_draft: 'draft'|"
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus::Draft|Stringable, '
        . "string_leading_zero: '01'|"
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus::LeadingZero|Stringable, '
        . 'integer_one: 1|float|'
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\IntegerValidationStatus::One|numeric-string|true, '
        . 'integer_two: 2|float|'
        . 'jbboehr\PhpstanLaravelValidation\Test\Fixtures\IntegerValidationStatus::Two|numeric-string}',
    $coerciveFilters
);

$parsedFiltered = Validator::make([], [
    'age' => ['required', Parse::integer()],
    'only' => ['required', Rule::enum(PureValidationStatus::class)->only(PureValidationStatus::Draft)],
    'except' => ['required', (new Enum(PureValidationStatus::class))->except(PureValidationStatus::Published)],
    'same_field' => [
        'required',
        Rule::enum(FractionValidationValue::class)->only(FractionValidationValue::Half),
        Parse::float(),
    ],
])->validated();
assertType(
    'array{age: int, only: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft, '
        . 'except: jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus::Draft, same_field: float}',
    $parsedFiltered
);

/** @param array{status: mixed} $enumInput */
function preserveEnumCallerInput(array $enumInput): void
{
    if (Validator::make($enumInput, [
        'status' => ['required', Rule::enum(PureValidationStatus::class)],
    ])->passes()) {
        assertType('array{status: mixed}', $enumInput);
    }
}

$ruleVariable = Rule::enum(PureValidationStatus::class);
$mutable = Validator::make([], [
    'variable' => ['required', $ruleVariable],
    'conditionable' => ['required', Rule::enum(PureValidationStatus::class)->when(
        true,
        static fn (Enum $rule): Enum => $rule->only(PureValidationStatus::Draft)
    )],
])->validated();
assertType('array{variable: mixed, conditionable: mixed}', $mutable);

/** @param class-string<UnitEnum> $enum */
function inferredDynamicEnum(string $enum): void
{
    $validated = Validator::make([], [
        'value' => ['required', Rule::enum($enum)],
    ])->validated();
    assertType('array{value: mixed}', $validated);
}
