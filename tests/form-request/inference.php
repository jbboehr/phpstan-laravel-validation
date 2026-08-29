<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\FormRequestFixtures;

use Illuminate\Foundation\Http\FormRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\AliasRulesRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\AttributedRulesRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\BasicRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\AfterRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ConcreteInheritedRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ConditionalRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\CreateDefaultValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\DatabaseRuleRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\DimensionsRuleRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\EmptyTraitWithValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\CustomValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\CustomRuleRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ClassConstantRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ContainerInjectedRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\GetValidatorInstanceRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\IntermediateWithValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\InheritedEmptyWithValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\KeyedValidatedRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\NumericKeyValidatedRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\NumericSafeRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\OverriddenValidatedRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\OverriddenSafeRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\OverriddenEmptyWithValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\PassedValidationRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ParsingRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ReturnOnlyWithValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\SelfConstantChildRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\StaticConstantChildRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ThisConstantChildRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\TraitAfterRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\TraitRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\TraitWithValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\TrustedWithValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\TrustedSubclassRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\UnresolvedRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\WithValidatorRequest;

use function PHPStan\Testing\assertType;

function inspectAliases(AliasRulesRequest $request): void
{
    assertType(
        "array{id: float|int|numeric-string|Stringable|true, flag: 0|1|'0'|'1'|bool}",
        $request->validated()
    );
}

/** @param array<string, mixed> $dynamicItems */
function inspectBasic(BasicRequest $request, array $dynamicItems): void
{
    assertType(
        'array{name: string, age?: float|int|string|Stringable|true}',
        $request->validated()
    );
    assertType(
        'array{name: string, age?: float|int|string|Stringable|true}',
        $request->validated(null)
    );
    assertType('string', $request->validated('name'));

    $null = null;
    assertType(
        'array{name: string, age?: float|int|string|Stringable|true}',
        $request->validated($null)
    );
    assertType('Illuminate\Support\ValidatedInput', $request->safe());
    assertType(
        'array{name: string, age?: float|int|string|Stringable|true}',
        $request->safe(['name', 'age'])
    );
    assertType(
        'array{name: string, age?: float|int|string|Stringable|true}',
        $request->safe()->all()
    );
    assertType('array{name: string}', $request->safe()->only(['name']));
    assertType('array{name: string}', $request->safe()->except(['age']));
    assertType('array{name: string}', $request->safe()->except('age'));
    assertType(
        "array{name: 'override', age?: float|int|string|Stringable|true, role: 'admin'}",
        $request->safe()->merge(['name' => 'override', 'role' => 'admin'])->all()
    );
    assertType(
        'array{name: string, age?: float|int|string|Stringable|true}',
        $request->safe()->merge(items: [])->all()
    );
    assertType(
        "array{name: 'override'}",
        $request->safe()->merge(['name' => 'override', 'role' => 'admin'])->only(['name'])
    );
    assertType(
        "array{name: 'override', role: 'admin'}",
        $request->safe()->merge(['name' => 'override', 'role' => 'admin'])->except(['age'])
    );
    assertType(
        "array{name: 'second', age?: float|int|string|Stringable|true, role: 'admin'}",
        $request->safe()
            ->merge(['name' => 'first', 'role' => 'admin'])
            ->merge(['name' => 'second'])
            ->toArray()
    );

    /** @var array{role?: 'admin'} $optionalItems */
    $optionalItems = [];
    assertType(
        "array{name: string, age?: float|int|string|Stringable|true, role?: 'admin'}",
        $request->safe()->merge($optionalItems)->all()
    );

    /** @var array{name?: 42} $optionalOverride */
    $optionalOverride = [];
    assertType(
        'array{name: 42|string, age?: float|int|string|Stringable|true}',
        $request->safe()->merge($optionalOverride)->all()
    );

    /** @var array{5?: 'optional'} $optionalNumeric */
    $optionalNumeric = [];
    assertType(
        "array{name: string, age?: float|int|string|Stringable|true, 0: 'optional'|'tail', 1?: 'tail'}",
        $request->safe()->merge($optionalNumeric)->merge([9 => 'tail'])->all()
    );
    assertType('array', $request->safe()->merge($dynamicItems)->all());
    assertType('array', $request->safe()->merge(...[['role' => 'admin']])->all());
    assertType(
        'array',
        $request->safe()->merge($dynamicItems = ['role' => 'admin'])->all()
    );

    $storedMerge = $request->safe()->merge(['role' => 'admin']);
    assertType('array', $storedMerge->all());

    // Stored wrappers can be mutated through aliases or escaped to code that
    // analysis cannot inspect, so their payload provenance is not stable.
    $stored = $request->safe();
    assertType('array', $stored->toArray());
}

function inspectParsing(ParsingRequest $request): void
{
    // This fixture's configured Laravel 10.0 context deliberately proves that
    // analysis can recover the produced type below the runtime's 10.7 floor.
    // The runtime fails closed there, as the public parsing guide documents.
    assertType('array{age: int}', $request->validated());
    assertType('array{age: int}', $request->safe()->all());
    assertType('array{age: int}', $request->safe(['age']));
}

function inspectInherited(ConcreteInheritedRequest $request): void
{
    assertType("array{inherited: 0|1|'0'|'1'|bool}", $request->validated());
}

function inspectTrait(TraitRequest $request): void
{
    assertType('array{from_trait: string}', $request->validated());
}

function inspectConditional(ConditionalRequest $request): void
{
    assertType(
        'array{created: string}|array{updated: float|int|numeric-string|Stringable|true}',
        $request->validated()
    );
}

function inspectConstants(
    ClassConstantRequest $constant,
    ContainerInjectedRequest $injected
): void {
    assertType('array{constant: string}', $constant->validated());
    assertType('array{injected: array}', $injected->validated());
}

function inspectCustomRule(CustomRuleRequest $request): void
{
    assertType('array{custom: non-empty-string}', $request->validated());
}

function inspectInheritedConstants(
    SelfConstantChildRequest $selfConstant,
    StaticConstantChildRequest $staticConstant,
    ThisConstantChildRequest $thisConstant
): void {
    assertType('array{parent: string}', $selfConstant->validated());
    assertType('mixed', $staticConstant->validated());
    assertType('mixed', $thisConstant->validated());
}

function inspectAttributedRules(AttributedRulesRequest $request): void
{
    assertType('array{attributed: string}', $request->validated());
}

function inspectFallbacks(
    UnresolvedRequest $unresolved,
    WithValidatorRequest $withValidator,
    PassedValidationRequest $passedValidation,
    AfterRequest $after,
    CustomValidatorRequest $customValidator,
    GetValidatorInstanceRequest $getValidatorInstance,
    CreateDefaultValidatorRequest $createDefaultValidator,
    IntermediateWithValidatorRequest $intermediateWithValidator,
    TraitWithValidatorRequest $traitWithValidator,
    TraitAfterRequest $traitAfter
): void {
    assertType('mixed', $unresolved->validated());
    assertType('array{unsafe: string}', $withValidator->validated());
    assertType('mixed', $passedValidation->validated());
    assertType('mixed', $after->validated());
    assertType('mixed', $customValidator->validated());
    assertType('mixed', $getValidatorInstance->validated());
    assertType('mixed', $createDefaultValidator->validated());
    assertType('mixed', $intermediateWithValidator->validated());
    assertType('mixed', $traitWithValidator->validated());
    assertType('mixed', $traitAfter->validated());
}

function inspectEmptyWithValidatorHooks(
    InheritedEmptyWithValidatorRequest $inherited,
    EmptyTraitWithValidatorRequest $trait,
    OverriddenEmptyWithValidatorRequest $overridden,
    ReturnOnlyWithValidatorRequest $returnOnly
): void {
    assertType('array{inherited_empty_hook: string}', $inherited->validated());
    assertType('array{trait_empty_hook: string}', $trait->validated());
    assertType('mixed', $overridden->validated());
    assertType('mixed', $returnOnly->validated());
}

function inspectTrusted(
    TrustedWithValidatorRequest $request,
    TrustedSubclassRequest $subclass
): void {
    assertType('array{trusted: string}', $request->validated());
    assertType('array{trusted: string}', $subclass->validated());
}

function inspectOverride(OverriddenValidatedRequest $request): void
{
    assertType('string', $request->validated());
}

function inspectSafeOverride(OverriddenSafeRequest $request): void
{
    assertType('array{custom: string}', $request->safe());
    assertType('array{custom: string}', $request->safe(['ignored']));
}

function inspectKeyedValidated(KeyedValidatedRequest $request, string $dynamicKey): void
{
    assertType('string', $request->validated('name'));
    assertType('string|null', $request->validated('nickname'));
    assertType('float|int|string|Stringable|true|null', $request->validated('age'));
    assertType('float|int|string|Stringable|true', $request->validated('age', 'unknown'));
    assertType('non-empty-string', $request->validated('profile.email'));
    assertType('string|null', $request->validated('profile.note'));
    assertType('null', $request->validated('absent'));
    assertType('42', $request->validated('absent', 42));
    assertType('42', $request->validated(default: 42, key: 'absent'));
    assertType(
        'array{name: string, nickname?: string|null, age?: float|int|string|Stringable|true, '
        . 'profile: array{email: non-empty-string, note?: string}, '
        . 'items?: array<int|string, array{id: float|int|numeric-string|Stringable|true}>|string}',
        $request->validated(default: 42)
    );
    assertType('null', $request->validated('name.character'));
    assertType('null', $request->validated(''));
    assertType('mixed', $request->validated('absent', static fn (): string => 'fallback'));
    assertType('mixed', $request->validated('items.*.id'));
    assertType('mixed', $request->validated('profile.{first}'));
    assertType('mixed', $request->validated('profile.\\{first}'));
    assertType('mixed', $request->validated('profile.\\*'));
    assertType('mixed', $request->validated($dynamicKey));
    assertType(
        'array{profile: array{email: non-empty-string, note?: string}}',
        $request->safe(['profile.email', 'profile.note', 'absent'])
    );
    assertType(
        'array{profile?: array{note?: string}}',
        $request->safe()->only(['profile.note'])
    );
    assertType(
        'array{name: string, profile: array{email: non-empty-string}}',
        $request->safe()->except(['nickname', 'age', 'items', 'profile.note'])
    );
    assertType(
        'array{name: string, nickname?: string|null, age?: float|int|string|Stringable|true, '
        . "profile: array{replacement: true}, "
        . 'items?: array<int|string, array{id: float|int|numeric-string|Stringable|true}>|string}',
        $request->safe()->merge(['profile' => ['replacement' => true]])->all()
    );
    assertType('array', $request->safe()->except([$dynamicKey]));

    $key = random_int(0, 1) === 1 ? 'name' : 'age';
    assertType('float|int|string|Stringable|true|null', $request->validated($key));

    $nullableKey = random_int(0, 1) === 1 ? 'name' : null;
    assertType(
        'array{name: string, nickname?: string|null, age?: float|int|string|Stringable|true, '
        . 'profile: array{email: non-empty-string, note?: string}, '
        . 'items?: array<int|string, array{id: float|int|numeric-string|Stringable|true}>|string}|string',
        $request->validated($nullableKey)
    );
}

function inspectNumericSafe(NumericSafeRequest $request): void
{
    assertType(
        'array{items: array{array{id: string}}}',
        $request->safe(['items.0.id'])
    );
    assertType(
        'array{items: array{array{id: string}}}',
        $request->safe()->only(['items.0.id'])
    );
    assertType(
        'array{items: array{array{}}}',
        $request->safe()->except(['items.0.id'])
    );
}

function inspectDatabaseRuleRequest(DatabaseRuleRequest $request): void
{
    assertType(
        'array{parent_id?: float|int|string|Stringable|true|null}',
        $request->validated()
    );
}

function inspectDimensionsRuleRequest(DimensionsRuleRequest $request): void
{
    assertType(
        'array{avatar: Symfony\Component\HttpFoundation\File\File}',
        $request->validated()
    );
}

/**
 * @param array{2: 'first', 1: 'second'}|array{1: 'second', 2: 'first'} $orderedItems
 * @param list{'list first', 'list second'} $listItems
 */
function inspectNumericKeyValidated(
    NumericKeyValidatedRequest $request,
    array $orderedItems,
    array $listItems
): void {
    assertType('string', $request->validated(0));
    assertType('string', $request->validated('0'));
    assertType('null', $request->validated(1));
    assertType('array{}', $request->safe()->except([0]));
    assertType(
        "array{string, 'appended'}",
        $request->safe()->merge([5 => 'appended'])->all()
    );

    assertType('array', $request->safe()->merge($orderedItems)->all());
    assertType('array', $request->safe()->merge([...$orderedItems])->all());
    assertType(
        "array{0: string, 1: 'first', 2: 'second', extra: true}",
        $request->safe()
            ->merge([5 => 'first', 6 => 'second'])
            ->merge(['extra' => true])
            ->all()
    );
    assertType(
        "array{string, 'list first', 'list second'}",
        $request->safe()->merge($listItems)->all()
    );
}

/** @param BasicRequest|TraitRequest $request */
function inspectReceiverUnion(FormRequest $request): void
{
    assertType('array{from_trait: string}|array{name: string, age?: float|int|string|Stringable|true}', $request->validated());
}

/** @param BasicRequest|UnresolvedRequest $request */
function inspectReceiverUnionFallback(FormRequest $request): void
{
    assertType('mixed', $request->validated());
}
