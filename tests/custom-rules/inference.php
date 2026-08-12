<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\CustomRules;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\TestController;

use function PHPStan\Testing\assertType;

require_once __DIR__ . '/../CustomRules/Rules.php';

$unknown = Validator::make([], [
    'value' => new UnknownRule(),
])->validated();
assertType('array{value?: mixed}', $unknown);

$unknownWithBuiltIns = Validator::make([], [
    'value' => ['required', 'string', new UnknownRule()],
])->validated();
assertType('array{value: string}', $unknownWithBuiltIns);

$closure = Validator::make([], [
    'value' => ['required', 'string', static function (): void {
    }],
])->validated();
assertType('array{value: string}', $closure);

$configured = Validator::make([], [
    'value' => ['required', new IntegerRule()],
])->validated();
assertType('array{value: int}', $configured);

$configuredOptional = Validator::make([], [
    'value' => new IntegerRule(),
])->validated();
assertType('array{value?: int|string}', $configuredOptional);

$attribute = Validator::make([], [
    'value' => ['required', new AttributeRule()],
])->validated();
assertType('array{value: non-empty-string}', $attribute);

$phpDoc = Validator::make([], [
    'value' => ['required', new PhpDocRule()],
])->validated();
assertType('array{value: int<1, max>}', $phpDoc);

$precedence = Validator::make([], [
    'value' => ['required', new PrecedenceRule()],
])->validated();
assertType('array{value: bool}', $precedence);

$named = Validator::make([], [
    'value' => 'required|custom_integer',
])->validated();
assertType('array{value: int}', $named);

$normalizedName = Validator::make([], [
    'value' => 'required|custom-integer',
])->validated();
assertType('array{value: int}', $normalizedName);

$opaque = Validator::make([], [
    'value' => ['required', 'string', new StringableRuleBuilder()],
])->validated();
assertType('array{value?: mixed}', $opaque);

$rule = rand(0, 1) === 1 ? new AttributeRule() : new PhpDocRule();
$alternative = Validator::make([], [
    'value' => ['required', $rule],
])->validated();
assertType('array{value: int<1, max>}|array{value: non-empty-string}', $alternative);

$maybeUnknownRule = rand(0, 1) === 1 ? new AttributeRule() : new UnknownRule();
$partlyUnknownAlternative = Validator::make([], [
    'value' => ['required', $maybeUnknownRule],
])->validated();
assertType('array{value: mixed}', $partlyUnknownAlternative);

$nested = Validator::make([], [
    'items.*.code' => ['required', 'string', new UnknownRule()],
])->validated();
assertType('array{items?: array<int|string, array{code: string}>}', $nested);

$contractedNested = Validator::make([], [
    'items.*.code' => ['required', new AttributeRule()],
])->validated();
assertType('array{items?: array<int|string, array{code: non-empty-string}>}', $contractedNested);

$nullable = Validator::make([], [
    'value' => ['nullable', new IntegerRule()],
])->validated();
assertType('array{value?: int|string|null}', $nullable);

$implicit = Validator::make([], [
    'value' => new ImplicitStringRule(),
])->validated();
assertType('array{value?: string}', $implicit);

$facadeValidated = Validator::validate([], [
    'value' => ['required', 'string', new UnknownRule()],
]);
assertType('array{value: string}', $facadeValidated);

$factory = new Factory(new Translator(new ArrayLoader(), 'en'));
$factoryValidated = $factory->make([], [
    'value' => ['required', 'string', new UnknownRule()],
])->validated();
assertType('array{value: string}', $factoryValidated);

$factoryDirectValidated = $factory->validate([], [
    'value' => ['required', new AttributeRule()],
]);
assertType('array{value: non-empty-string}', $factoryDirectValidated);

$helperValidated = validator([], [
    'value' => ['required', 'string', new UnknownRule()],
])->validated();
assertType('array{value: string}', $helperValidated);

$requestValidated = (new Request())->validate([
    'value' => ['required', 'string', new UnknownRule()],
]);
assertType('array{value: string}', $requestValidated);

$controllerValidated = (new TestController())->validate(new Request(), [
    'value' => ['required', 'string', new UnknownRule()],
]);
assertType('array{value: string}', $controllerValidated);

$setRulesValidated = $factory
    ->make([], ['old' => 'required|string'])
    ->setRules(['value' => ['required', 'string', new UnknownRule()]])
    ->validated();
assertType('array{value: string}', $setRulesValidated);
