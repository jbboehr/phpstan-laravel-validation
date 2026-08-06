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
            foreach (['array', 'email', 'integer', 'json'] as $rule) {
                yield $description . '-' . $rule => [$value, $rule];
            }
        }
    }

    /**
     * @dataProvider blankStringProvider
     */
    public function testBlankStringBypassesOptionalNonImplicitRules(string $value, string $rule): void
    {
        self::getContainer();

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

    public function testTrimStringsAloneDoesNotEliminateBlankStringBypass(): void
    {
        \Illuminate\Foundation\Http\Middleware\TrimStrings::flushState();

        try {
            $request = \Illuminate\Http\Request::create('/', 'POST', ['value' => '   ']);
            (new \Illuminate\Foundation\Http\Middleware\TrimStrings())->handle(
                $request,
                static fn ($request) => $request
            );

            self::assertSame('', $request->input('value'));

            $factory = new \Illuminate\Validation\Factory(
                new \Illuminate\Translation\Translator(
                    new \Illuminate\Translation\ArrayLoader(),
                    'en'
                )
            );
            $rules = ['value' => 'array'];
            $validator = $factory->make($request->all(), $rules);

            self::assertTrue($validator->passes());
            self::assertSame(['value' => ''], $validator->validated());

            $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
            $validatedType = $this->convertToType($validator->validated());
            self::assertTrue($rulesType->accepts($validatedType, true)->yes());
        } finally {
            \Illuminate\Foundation\Http\Middleware\TrimStrings::flushState();
        }
    }

    public function testDefaultHttpInputNormalizationChangesOptionalBlankBehavior(): void
    {
        \Illuminate\Foundation\Http\Middleware\TrimStrings::flushState();
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::flushState();

        try {
            $request = \Illuminate\Http\Request::create('/', 'POST', [
                'non_nullable' => '   ',
                'nullable' => '   ',
            ]);
            self::applyDefaultHttpInputNormalization($request);

            self::assertNull($request->input('non_nullable'));
            self::assertNull($request->input('nullable'));

            $factory = new \Illuminate\Validation\Factory(
                new \Illuminate\Translation\Translator(
                    new \Illuminate\Translation\ArrayLoader(),
                    'en'
                )
            );

            $nonNullable = $factory->make(
                ['value' => $request->input('non_nullable')],
                ['value' => 'array']
            );
            self::assertFalse($nonNullable->passes());

            $rules = ['value' => 'nullable|array'];
            $nullable = $factory->make(['value' => $request->input('nullable')], $rules);
            self::assertTrue($nullable->passes());
            self::assertSame(['value' => null], $nullable->validated());

            $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules), true);
            $validatedType = $this->convertToType($nullable->validated());
            self::assertTrue($rulesType->accepts($validatedType, true)->yes());
        } finally {
            \Illuminate\Foundation\Http\Middleware\TrimStrings::flushState();
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::flushState();
        }
    }

    public function testDefaultPasswordTrimExceptionVariesByLaravelMajor(): void
    {
        \Illuminate\Foundation\Http\Middleware\TrimStrings::flushState();
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::flushState();

        try {
            $request = \Illuminate\Http\Request::create('/', 'POST', ['password' => '   ']);
            self::applyDefaultHttpInputNormalization($request);

            $factory = new \Illuminate\Validation\Factory(
                new \Illuminate\Translation\Translator(
                    new \Illuminate\Translation\ArrayLoader(),
                    'en'
                )
            );
            $rules = ['password' => 'array'];
            $validator = $factory->make($request->all(), $rules);

            if (method_exists(\Illuminate\Foundation\Http\Middleware\TrimStrings::class, 'except')) {
                self::assertSame('   ', $request->input('password'));
                self::assertTrue($validator->passes());
                self::assertSame(['password' => '   '], $validator->validated());

                $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules), true);
                $validatedType = $this->convertToType($validator->validated());
                self::assertTrue($rulesType->accepts($validatedType, true)->yes());
                return;
            }

            self::assertNull($request->input('password'));
            self::assertFalse($validator->passes());
        } finally {
            \Illuminate\Foundation\Http\Middleware\TrimStrings::flushState();
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::flushState();
        }
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

    /**
     * @return iterable<string, array{mixed, string}>
     */
    public static function numericValueAcceptedByAlphaRuleProvider(): iterable
    {
        foreach (['alpha_num', 'alpha_num:ascii', 'alpha_dash', 'alpha_dash:ascii'] as $rule) {
            yield $rule . ' integer' => [1, $rule];
            yield $rule . ' float' => [1.0, $rule];
        }

        yield 'alpha_dash negative integer' => [-1, 'alpha_dash'];
        yield 'alpha_dash negative float in ASCII mode' => [-1.0, 'alpha_dash:ascii'];
    }

    /**
     * @dataProvider numericValueAcceptedByAlphaRuleProvider
     */
    public function testAlphaRulesCanPreserveNumericValues(mixed $value, string $rule): void
    {
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

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function asciiCoercibleValueProvider(): iterable
    {
        yield 'integer' => [123];
        yield 'float' => [1.5];
        yield 'infinity' => [INF];
        yield 'true' => [true];
        yield 'false' => [false];
        yield 'null' => [null];
        yield 'stringable object' => [new \Illuminate\Support\Stringable('plain')];
    }

    /**
     * @dataProvider asciiCoercibleValueProvider
     */
    public function testAsciiRuleCoercionFollowsRuntimeSupport(mixed $value): void
    {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $rules = ['value' => 'ascii'];
        $validator = $factory->make(['value' => $value], $rules);

        if (self::asciiRuleRequiresNativeString()) {
            self::assertFalse($validator->passes());
            return;
        }

        self::assertTrue($validator->passes());
        self::assertSame(['value' => $value], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    public function testAsciiRuleCanPreserveResourcesOnCoerciveVersions(): void
    {
        self::getContainer();

        $resource = fopen('php://memory', 'r');
        if ($resource === false) {
            self::fail('Could not open test resource');
        }

        try {
            $factory = new \Illuminate\Validation\Factory(
                new \Illuminate\Translation\Translator(
                    new \Illuminate\Translation\ArrayLoader(),
                    'en'
                )
            );
            $rules = ['value' => 'ascii'];
            $validator = $factory->make(['value' => $resource], $rules);

            if (self::asciiRuleRequiresNativeString()) {
                self::assertFalse($validator->passes());
                return;
            }

            self::assertTrue($validator->passes());
            self::assertSame(['value' => $resource], $validator->validated());

            $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
            $validatedType = $this->convertToType($validator->validated());
            self::assertTrue($rulesType->accepts($validatedType, true)->yes());
        } finally {
            fclose($resource);
        }
    }

    public function testAsciiRuleCanPreserveArraysWhenWarningsAreHandled(): void
    {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $value = ['key' => 'value'];
        $rules = ['value' => 'ascii'];
        $warnings = [];

        set_error_handler(
            static function (int $severity, string $message, string $file, int $line) use (&$warnings): bool {
                $warnings[] = $message;
                return true;
            }
        );

        try {
            $validator = $factory->make(['value' => $value], $rules);
            $passes = $validator->passes();
        } finally {
            restore_error_handler();
        }

        if (self::asciiRuleRequiresNativeString()) {
            self::assertSame([], $warnings);
            self::assertFalse($passes);
            return;
        }

        self::assertNotEmpty($warnings);
        self::assertTrue($passes);
        self::assertSame(['value' => $value], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function jsonValueProvider(): iterable
    {
        yield 'object string' => ['{"value":1}'];
        yield 'numeric string' => ['1'];
        yield 'integer' => [1];
        yield 'negative integer' => [-1];
        yield 'float' => [1.5];
        yield 'true' => [true];
        yield 'stringable object' => [new \Illuminate\Support\Stringable('{"value":1}')];
    }

    /**
     * @dataProvider jsonValueProvider
     */
    public function testJsonRuleCanPreserveNonStringValues(mixed $value): void
    {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $rules = ['value' => 'required|json'];
        $validator = $factory->make(['value' => $value], $rules);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => $value], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function rejectedJsonValueProvider(): iterable
    {
        yield 'false' => [false];
        yield 'null' => [null];
        yield 'infinity' => [INF];
        yield 'array' => [['value' => 1]];
        yield 'ordinary object' => [new \stdClass()];
        yield 'invalid stringable object' => [new \Illuminate\Support\Stringable('invalid')];
    }

    /**
     * @dataProvider rejectedJsonValueProvider
     */
    public function testJsonRuleRejectsValuesOutsideItsCoerciveContract(mixed $value): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $validator = $factory->make(['value' => $value], ['value' => 'json']);

        self::assertFalse($validator->passes());
    }

    /**
     * @return iterable<string, array{int|float, string}>
     */
    public static function numericDateRuleProvider(): iterable
    {
        $rules = [
            'date' => 'required|date',
            'date format' => 'required|date_format:Ymd',
            'before' => 'required|date_format:Ymd|before:20250101',
            'before or equal' => 'required|date_format:Ymd|before_or_equal:20240101',
            'after' => 'required|date_format:Ymd|after:20230101',
            'after or equal' => 'required|date_format:Ymd|after_or_equal:20240101',
            'date equals' => 'required|date_format:Ymd|date_equals:20240101',
        ];

        foreach ($rules as $description => $rule) {
            yield $description . ' integer' => [20240101, $rule];
            yield $description . ' float' => [20240101.0, $rule];
        }
    }

    /**
     * @dataProvider numericDateRuleProvider
     */
    public function testDateRulesCanPreserveNumericScalars(int|float $value, string $rule): void
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

        self::assertTrue($validator->passes());
        self::assertSame(['value' => $value], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function dateTimeRuleProvider(): iterable
    {
        yield 'date' => ['required|date'];
        yield 'before' => ['required|before:2025-01-01'];
        yield 'before or equal' => ['required|before_or_equal:2024-01-01'];
        yield 'after' => ['required|after:2023-01-01'];
        yield 'after or equal' => ['required|after_or_equal:2024-01-01'];
        yield 'date equals' => ['required|date_equals:2024-01-01'];
    }

    /**
     * @dataProvider dateTimeRuleProvider
     */
    public function testDateAndComparisonRulesPreserveDateTimeObjects(string $rule): void
    {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $value = new \DateTimeImmutable('2024-01-01');
        $rules = ['value' => $rule];
        $validator = $factory->make(['value' => $value], $rules);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => $value], $validator->validated());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    /**
     * @return iterable<string, array{mixed, string}>
     */
    public static function rejectedDateValueProvider(): iterable
    {
        yield 'date stringable' => [new \Illuminate\Support\Stringable('2024-01-01'), 'required|date'];
        yield 'date format stringable' => [
            new \Illuminate\Support\Stringable('20240101'),
            'required|date_format:Ymd',
        ];
        yield 'before stringable' => [
            new \Illuminate\Support\Stringable('2024-01-01'),
            'required|before:2025-01-01',
        ];
        yield 'date true' => [true, 'required|date'];
        yield 'date format true' => [true, 'required|date_format:Ymd'];
        yield 'before true' => [true, 'required|before:2025-01-01'];
    }

    /**
     * @dataProvider rejectedDateValueProvider
     */
    public function testDateRulesRejectNonNumericNonDateValues(mixed $value, string $rule): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $validator = $factory->make(['value' => $value], ['value' => $rule]);

        self::assertFalse($validator->passes());
    }

    /**
     * @dataProvider alphaNumRuleProvider
     */
    public function testAlphaNumRejectsNegativeIntegers(string $rule): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $validator = $factory->make(['value' => -1], ['value' => 'required|' . $rule]);

        self::assertFalse($validator->passes());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function alphaNumRuleProvider(): iterable
    {
        yield 'Unicode' => ['alpha_num'];
        yield 'ASCII' => ['alpha_num:ascii'];
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

    private static function asciiRuleRequiresNativeString(): bool
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $validator = $factory->make(
            ['value' => 1],
            ['value' => 'required|ascii']
        );

        return !$validator->passes();
    }

    private static function applyDefaultHttpInputNormalization(\Illuminate\Http\Request $request): void
    {
        (new \Illuminate\Foundation\Http\Middleware\TrimStrings())->handle(
            $request,
            static function (\Illuminate\Http\Request $request) {
                return (new \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull())->handle(
                    $request,
                    static fn ($request) => $request
                );
            }
        );
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

    public function testWildcardAndNamedRulesProjectOverlappingScalarValues(): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $rules = [
            'items.*' => 'string',
            'items.named' => 'required|string',
        ];
        $validator = $factory->make([
            'items' => [
                'first' => 'one',
                'named' => 'two',
            ],
        ], $rules);

        self::assertTrue($validator->passes());
        self::assertSame([
            'items' => [
                'named' => 'two',
                'first' => 'one',
            ],
        ], $validator->validated());

        $missingNamed = $factory->make(['items' => ['first' => 'one']], $rules);
        self::assertFalse($missingNamed->passes());

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $validatedType = $this->convertToType($validator->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    public function testWildcardAndNamedRulesProjectOverlappingNestedValues(): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $rules = [
            'items.*.name' => 'required|string',
            'items.named.label' => 'required|string',
        ];
        $validator = $factory->make([
            'items' => [
                'first' => [
                    'name' => 'First',
                    'extra' => 'discarded',
                ],
                'named' => [
                    'name' => 'Named',
                    'label' => 'Label',
                    'extra' => 'discarded',
                ],
            ],
        ], $rules);

        self::assertTrue($validator->passes());
        self::assertSame([
            'items' => [
                'named' => [
                    'label' => 'Label',
                    'name' => 'Named',
                ],
                'first' => ['name' => 'First'],
            ],
        ], $validator->validated());

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
