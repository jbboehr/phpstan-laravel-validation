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

$nested = Validator::make([], [
    'items.*.code' => ['required', 'string', new UnknownRule()],
])->validated();
assertType('array{items?: array<int|string, array{code: string}>}', $nested);

$facadeValidated = Validator::validate([], [
    'value' => ['required', 'string', new UnknownRule()],
]);
assertType('array{value: string}', $facadeValidated);

$factory = new Factory(new Translator(new ArrayLoader(), 'en'));
$factoryValidated = $factory->make([], [
    'value' => ['required', 'string', new UnknownRule()],
])->validated();
assertType('array{value: string}', $factoryValidated);

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
