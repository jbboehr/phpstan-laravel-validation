<?php
/**
 * Copyright (c) anno Domini nostri Jesu Christi MMXXIV John Boehr & contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test;

use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\Type;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantFloatType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;

class LaravelInferenceTest extends \PHPStan\Testing\PHPStanTestCase
{
    /**
     * Known-quirky upstream test cases that this extension isn't expected to
     * model correctly, keyed by the Laravel version(s) whose test suite line
     * numbers they were captured at. Laravel restructures its own test files
     * between releases, so these need a new entry (not just a line-number
     * edit) whenever a new major version's fixtures are regenerated.
     *
     * - testNumericKeys: rules keyed by a literal integer (e.g. `[3 => 'required']`)
     *   aren't supported by RuleParser, which requires string paths.
     */
    private const KNOWN_QUIRKS = [
        'testNumericKeys:5591', // v12
        'testNumericKeys:5786', // v13
    ];

    /**
     * @param string $location
     * @param array<mixed, mixed> $data
     * @param array<string, mixed> $rules
     * @param array<mixed, mixed> $validated
     * @return void
     * @dataProvider laravelExportProvider
     * @group laravel
     */
    public function testLaravelValidationExport(string $location, array $data, array $validated, array $rules): void
    {
        foreach (self::KNOWN_QUIRKS as $quirk) {
            if (str_contains($location, $quirk)) {
                self::markTestSkipped($location);
            }
        }

        $evaluator = new TypeResolver();
        $ruleTree = RuleParser::parse($rules);
        $rulesType = $evaluator->evaluate($ruleTree);
        $validatedType = $this->convertToType($validated);
        $accepts = $rulesType->accepts($validatedType, true);

        // See: https://github.com/sebastianbergmann/phpunit/issues/5114 ?
        $this->assertInstanceOf(RuleTreeNode::class, $ruleTree); // @phpstan-ignore-line
        $this->assertInstanceOf(Type\Type::class, $rulesType); // @phpstan-ignore-line

        if (!$accepts->yes()) {
            $rulesTypeStr = $rulesType->describe(Type\VerbosityLevel::getRecommendedLevelByType($rulesType));
            $dataTypeStr = $validatedType->describe(Type\VerbosityLevel::getRecommendedLevelByType($validatedType));
            $message = $rulesTypeStr . ' does not accept ' . $dataTypeStr;
            self::fail($message);
//        } else {
//            $rulesTypeStr = $rulesType->describe(Type\VerbosityLevel::getRecommendedLevelByType($rulesType));
//            $dataTypeStr = $validatedType->describe(Type\VerbosityLevel::getRecommendedLevelByType($validatedType));
//            $this->addWarning($rulesTypeStr . ' matches ' . $dataTypeStr);
        }
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function blankStringProvider(): iterable
    {
        foreach (
            [
            'empty' => '',
            'space' => ' ',
            'tab-and-newline' => "\t\n",
            ] as $description => $value
        ) {
            foreach (['array', 'email', 'integer'] as $rule) {
                yield $description . '-' . $rule => [$value, $rule];
            }
        }
    }

    /**
     * @dataProvider blankStringProvider
     */
    public function testBlankStringBypassesOptionalNonImplicitRules(string $value, string $rule): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $validator = $factory->make(['value' => $value], ['value' => $rule]);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => $value], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse(['value' => $rule]));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function nonIntegerValuesAcceptedByIntegerProvider(): iterable
    {
        yield 'zero integral float' => [0.0];
        yield 'positive integral float' => [1.0];
        yield 'negative integral float' => [-1.0];
        yield 'boolean' => [true];
        yield 'stringable object' => [new \Illuminate\Support\Stringable('1')];
    }

    /**
     * @return iterable<string, array{mixed, bool}>
     */
    public static function nonIntegerValuesForStrictIntegerProvider(): iterable
    {
        $supportsStrict = self::supportsStrictIntegerRule();

        foreach (self::nonIntegerValuesAcceptedByIntegerProvider() as $description => [$value]) {
            yield $description => [$value, $supportsStrict];
        }
    }

    /**
     * @dataProvider nonIntegerValuesAcceptedByIntegerProvider
     */
    public function testIntegerRuleCanPreserveNonIntegerValues(mixed $value): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $validator = $factory->make(
            ['value' => $value],
            ['value' => 'required|integer']
        );

        self::assertTrue($validator->passes());
        self::assertSame(['value' => $value], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse([
            'value' => 'required|integer',
        ]));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    /**
     * @dataProvider nonIntegerValuesForStrictIntegerProvider
     */
    public function testIntegerStrictRuleFollowsRuntimeSupport(mixed $value, bool $supportsStrict): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $validator = $factory->make(
            ['value' => $value],
            ['value' => 'required|integer:strict']
        );

        if ($supportsStrict) {
            self::assertFalse($validator->passes());
            return;
        }

        self::assertTrue($validator->passes());
        self::assertSame(['value' => $value], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse([
            'value' => 'required|integer:strict',
        ]));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    public function testIntegerStrictRuleAcceptsAndPreservesNativeInteger(): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $validator = $factory->make(
            ['value' => 1],
            ['value' => 'required|integer:strict']
        );

        self::assertTrue($validator->passes());
        self::assertSame(['value' => 1], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse([
            'value' => 'required|integer:strict',
        ]));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    private static function supportsStrictIntegerRule(): bool
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $validator = $factory->make(
            ['value' => '1'],
            ['value' => 'required|integer:strict']
        );

        return !$validator->passes();
    }

    public function testConfirmedComparisonFieldIsOnlyValidatedWhenItHasRules(): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $data = [
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'pin' => '1234',
            'pin_confirmation' => '1234',
        ];
        $rules = [
            'password' => 'required|string|confirmed',
            'pin' => 'required|string|confirmed',
            'pin_confirmation' => 'required|string',
        ];
        $validator = $factory->make($data, $rules);

        self::assertTrue($validator->passes());
        self::assertSame([
            'password' => 'secret',
            'pin' => '1234',
            'pin_confirmation' => '1234',
        ], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function conditionalValueRuleProvider(): iterable
    {
        yield 'accepted if' => ['accepted_if:other,match', 'yes'];
        yield 'declined if' => ['declined_if:other,match', 'no'];
    }

    /**
     * @dataProvider conditionalValueRuleProvider
     */
    public function testConditionalValueRulesRemainConservative(string $rule, string $matchingValue): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $rules = ['value' => 'required|' . $rule];

        $nonMatchingCondition = $factory->make([
            'other' => 'different',
            'value' => 42,
        ], $rules);
        self::assertTrue($nonMatchingCondition->passes());
        self::assertSame(['value' => 42], $nonMatchingCondition->validated());

        $matchingCondition = $factory->make([
            'other' => 'match',
            'value' => $matchingValue,
        ], $rules);
        self::assertTrue($matchingCondition->passes());
        self::assertSame(['value' => $matchingValue], $matchingCondition->validated());

        $missingValue = $factory->make(['other' => 'different'], $rules);
        self::assertFalse($missingValue->passes());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        foreach ([$nonMatchingCondition, $matchingCondition] as $validator) {
            $validatedType = $this->convertToType($validator->validated());
            self::assertTrue($rulesType->accepts($validatedType, true)->yes());
        }
    }

    public function testConditionalExclusionChangesTheValidatedShape(): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $rules = [
            'kind' => 'required|string',
            'value' => 'required|string|exclude_if:kind,guest',
        ];

        $excluded = $factory->make([
            'kind' => 'guest',
            'value' => 'secret',
        ], $rules);
        self::assertTrue($excluded->passes());
        self::assertSame(['kind' => 'guest'], $excluded->validated());

        $included = $factory->make([
            'kind' => 'member',
            'value' => 'visible',
        ], $rules);
        self::assertTrue($included->passes());
        self::assertSame([
            'kind' => 'member',
            'value' => 'visible',
        ], $included->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        foreach ([$excluded, $included] as $validator) {
            $validatedType = $this->convertToType($validator->validated());
            self::assertTrue($rulesType->accepts($validatedType, true)->yes());
        }
    }

    public function testRequiredWildcardDescendantDoesNotRequireMissingParent(): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $rules = ['person.*.email' => 'required|string|email'];
        $validator = $factory->make([], $rules);

        self::assertTrue($validator->passes());
        self::assertSame([], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    /**
     * @return iterable<string, array{mixed, string}>
     */
    public static function scalarInValueProvider(): iterable
    {
        yield 'literal string' => ['1', 'in:1'];
        yield 'integer' => [1, 'in:1'];
        yield 'float' => [1.0, 'in:1'];
        yield 'boolean' => [true, 'in:1'];
        yield 'boolean with equivalent integer parameter' => [true, 'in:01'];
        yield 'boolean with equivalent exponent parameter' => [true, 'in:1e0'];
        yield 'equivalent integer string' => ['01', 'in:1'];
        yield 'equivalent float string' => ['1.0', 'in:1'];
        yield 'equivalent exponent string' => ['1e0', 'in:1'];
        yield 'stringable object' => [new \Illuminate\Support\Stringable('one'), 'in:one'];
        yield 'false for empty parameter' => [false, 'in:'];
    }

    /**
     * @dataProvider scalarInValueProvider
     */
    public function testScalarInRuleAcceptsRuntimeValues(mixed $value, string $rule): void
    {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $rules = ['value' => 'required|' . $rule];
        $validator = $factory->make(['value' => $value], $rules);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => $value], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    public function testScalarInRuleAcceptsPresentNullForAnEmptyParameter(): void
    {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $rules = ['value' => 'in:'];
        $validator = $factory->make(['value' => null], $rules);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => null], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    /**
     * @return iterable<string, array{mixed, string}>
     */
    public static function rejectedScalarInValueProvider(): iterable
    {
        yield 'true for another numeric parameter' => [true, 'required|in:2'];
        yield 'value for no parameters' => ['value', 'required|in'];
    }

    /**
     * @dataProvider rejectedScalarInValueProvider
     */
    public function testScalarInRuleRejectsValuesOutsideItsLooseComparison(mixed $value, string $rule): void
    {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $rules = ['value' => $rule];
        $validator = $factory->make(['value' => $value], $rules);

        self::assertFalse($validator->passes());
    }

    public function testScalarInRuleAcceptsAResourceWithTheSameStringValue(): void
    {
        self::getContainer();

        $resource = fopen('php://memory', 'r');
        if ($resource === false) {
            self::fail('Unable to open an in-memory stream');
        }

        try {
            $factory = new \Illuminate\Validation\Factory(
                new \Illuminate\Translation\Translator(
                    new \Illuminate\Translation\ArrayLoader(),
                    'en'
                )
            );
            $rules = ['value' => 'required|in:' . (string) $resource];
            $validator = $factory->make(['value' => $resource], $rules);

            self::assertTrue($validator->passes());
            self::assertSame(['value' => $resource], $validator->validated());

            $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
            $validatedType = $this->convertToType($validator->validated());
            self::assertTrue($rulesType->accepts($validatedType, true)->yes());
        } finally {
            fclose($resource);
        }
    }

    public function testScalarInRuleRejectsResourcesWhenTheParameterOnlyContainsTheirStringValue(): void
    {
        self::getContainer();

        $resource = fopen('php://memory', 'r');
        if ($resource === false) {
            self::fail('Unable to open an in-memory stream');
        }

        try {
            $factory = new \Illuminate\Validation\Factory(
                new \Illuminate\Translation\Translator(
                    new \Illuminate\Translation\ArrayLoader(),
                    'en'
                )
            );
            $resourceString = (string) $resource;

            foreach (['prefix' . $resourceString, $resourceString . 'suffix'] as $parameter) {
                $rules = ['value' => 'required|in:' . $parameter];
                $validator = $factory->make(['value' => $resource], $rules);

                self::assertFalse($validator->passes());

                $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
                $inputType = $this->convertToType(['value' => $resource]);
                self::assertTrue($rulesType->accepts($inputType, true)->no());
            }
        } finally {
            fclose($resource);
        }
    }

    public function testArrayRuleWithoutKeyParametersPreservesNestedKeys(): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $data = [
            'user' => [
                'name' => 'Ada',
                'admin' => true,
                'metadata' => ['source' => 'import'],
            ],
        ];
        $rules = ['user' => 'required|array'];
        $validator = $factory->make($data, $rules);

        self::assertTrue($validator->passes());
        self::assertSame($data, $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    public function testArrayRuleKeyParametersRejectUndeclaredNestedKeys(): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $validator = $factory->make([
            'user' => [
                'name' => 'Ada',
                'admin' => true,
            ],
        ], ['user' => 'required|array:name']);

        self::assertFalse($validator->passes());
    }

    /**
     * @return iterable<string, array{array<mixed>, array<string, string>, array<mixed>}>
     */
    public static function parentAndChildRulesProvider(): iterable
    {
        yield 'required string parent' => [
            ['foo' => 'value'],
            ['foo' => 'required|string', 'foo.bar' => 'sometimes|string'],
            ['foo' => 'value'],
        ];
        yield 'required untyped parent' => [
            ['foo' => 7],
            ['foo' => 'required', 'foo.bar' => 'sometimes|string'],
            ['foo' => 7],
        ];
        yield 'required array with no validated children' => [
            ['foo' => ['extra' => 42]],
            ['foo' => 'required|array', 'foo.bar' => 'sometimes|string'],
            [],
        ];
        yield 'required array with required child' => [
            ['foo' => ['bar' => 'value', 'extra' => 42]],
            ['foo' => 'required|array', 'foo.bar' => 'required|string'],
            ['foo' => ['bar' => 'value']],
        ];
        yield 'required array with required wildcard child' => [
            ['foo' => [['bar' => 'value', 'extra' => 42]]],
            ['foo' => 'required|array', 'foo.*.bar' => 'required|string'],
            ['foo' => [['bar' => 'value']]],
        ];
        yield 'nested required arrays with no validated leaf' => [
            ['foo' => ['bar' => ['extra' => 42]]],
            [
                'foo' => 'required|array',
                'foo.bar' => 'required|array',
                'foo.bar.baz' => 'sometimes|string',
            ],
            [],
        ];
    }

    /**
     * @param array<mixed> $data
     * @param array<string, string> $rules
     * @param array<mixed> $validated
     * @dataProvider parentAndChildRulesProvider
     */
    public function testParentAndChildRulesAcceptRuntimeOutput(array $data, array $rules, array $validated): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $validator = $factory->make($data, $rules);

        self::assertTrue($validator->passes());
        self::assertSame($validated, $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    /**
     * @return array<mixed>
     */
    public static function laravelExportProvider(): array
    {
        $v10 = require __DIR__ . '/fixtures/laravel-export-v10.php';
        $v11 = require __DIR__ . '/fixtures/laravel-export-v11.php';
        $v12 = require __DIR__ . '/fixtures/laravel-export-v12.php';
        $v13 = require __DIR__ . '/fixtures/laravel-export-v13.php';
        assert(is_array($v10) && is_array($v11) && is_array($v12) && is_array($v13));

        // 'expandedRules' isn't a parameter of testLaravelValidationExport();
        // drop it so PHPUnit doesn't try (and fail) to match it by name.
        return array_map(static function ($entry) {
            if (is_array($entry)) {
                unset($entry['expandedRules']);
            }
            return $entry;
        }, array_merge($v10, $v11, $v12, $v13));
    }

    private function convertToType(mixed $data): Type\Type
    {
        return match (gettype($data)) {
            "boolean" => new ConstantBooleanType($data),
            "integer" => new ConstantIntegerType($data),
            "double" => new ConstantFloatType($data),
            "string" => new ConstantStringType($data),
            "array" => $this->convertArrayToType($data),
            "object" => new ObjectType(get_class($data)),
            "NULL" => new NullType(),
            "resource" => new Type\ResourceType(),
            "unknown type" => new Type\MixedType(),
            default => new Type\MixedType(),
        };
    }

    /**
     * @param array<mixed, mixed> $data
     * @return Type\Type
     * @throws \PHPStan\ShouldNotHappenException
     */
    private function convertArrayToType(array $data): Type\Type
    {
        $array = ConstantArrayTypeBuilder::createEmpty();
        foreach ($data as $k => $v) {
//            if (is_string($k)) {
//                $k = str_replace('\.', '.', $k);
//            }
            $array->setOffsetValueType(
                $this->convertToType($k),
                $this->convertToType($v),
                false
            );
        }
        return $array->getArray();
    }
}
