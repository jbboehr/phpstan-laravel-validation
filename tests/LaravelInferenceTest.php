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

use Composer\InstalledVersions;
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use jbboehr\PhpstanLaravelValidation\Test\Support\LaravelValueType;
use PHPStan\Type;

class LaravelInferenceTest extends \PHPStan\Testing\PHPStanTestCase
{
    /**
     * @param string $location
     * @param array<mixed, mixed> $data
     * @param array<array-key, mixed> $rules
     * @param array<mixed, mixed> $validated
     * @return void
     * @dataProvider laravelExportProvider
     * @group laravel
     */
    public function testLaravelValidationExport(
        string $location,
        array $data,
        array $validated,
        array $rules,
        string $laravelVersion
    ): void {
        self::getContainer();
        $context = new LaravelVersionContext('', $laravelVersion);
        $evaluator = new TypeResolver($context);
        $ruleTree = RuleParser::parse($rules, $context);
        $rulesType = $evaluator->evaluate($ruleTree);
        $validatedType = LaravelValueType::fromValue($validated);
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

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function unconditionalAcceptanceRuleProvider(): iterable
    {
        yield 'accepted' => ['accepted', 'yes'];
        yield 'declined' => ['declined', 'no'];
    }

    /**
     * @dataProvider unconditionalAcceptanceRuleProvider
     */
    public function testUnconditionalAcceptanceRulesRequireMatchedPaths(string $rule, string $valid): void
    {
        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );

        self::assertFalse($factory->make([], ['value' => $rule])->passes());
        self::assertFalse($factory->make([], ['nested.value' => $rule])->passes());
        self::assertFalse($factory->make(['value' => null], ['value' => 'nullable|' . $rule])->passes());

        $sometimes = $factory->make([], ['value' => 'sometimes|' . $rule]);
        self::assertTrue($sometimes->passes());
        self::assertSame([], $sometimes->validated());

        $missingWildcard = $factory->make([], ['items.*.value' => $rule]);
        self::assertTrue($missingWildcard->passes());
        self::assertSame([], $missingWildcard->validated());

        $matchedWildcard = $factory->make(
            ['items' => [['value' => $valid]]],
            ['items.*.value' => $rule]
        );
        self::assertTrue($matchedWildcard->passes());
        self::assertSame(['items' => [['value' => $valid]]], $matchedWildcard->validated());
    }

    public function testTrimStringsAloneDoesNotEliminateBlankStringBypass(): void
    {
        self::flushHttpInputNormalizationState();

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
            self::flushHttpInputNormalizationState();
        }
    }

    public function testDefaultHttpInputNormalizationChangesOptionalBlankBehavior(): void
    {
        self::flushHttpInputNormalizationState();

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
            self::flushHttpInputNormalizationState();
        }
    }

    public function testDefaultPasswordTrimExceptionVariesByLaravelMajor(): void
    {
        self::flushHttpInputNormalizationState();

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
            $laravelVersion = self::frameworkVersion();
            $hasDefaultPasswordException = version_compare($laravelVersion, '11.0.0', '>=');
            $rulesType = (new TypeResolver(new LaravelVersionContext('', $laravelVersion)))
                ->evaluate(RuleParser::parse($rules), true);

            if ($hasDefaultPasswordException) {
                self::assertSame('   ', $request->input('password'));
                self::assertTrue($validator->passes());
                self::assertSame(['password' => '   '], $validator->validated());

                $validatedType = $this->convertToType($validator->validated());
                self::assertTrue($rulesType->accepts($validatedType, true)->yes());
                return;
            }

            self::assertNull($request->input('password'));
            self::assertFalse($validator->passes());
        } finally {
            self::flushHttpInputNormalizationState();
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

    public function testHexColorRuleFollowsRuntimeVersionBoundaries(): void
    {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $laravelVersion = self::frameworkVersion();

        $blank = $factory->make(['value' => ''], ['value' => 'hex_color']);
        self::assertTrue($blank->passes());
        self::assertSame(['value' => ''], $blank->validated());

        if (version_compare($laravelVersion, '10.33.0', '<')) {
            $context = new LaravelVersionContext('', $laravelVersion);
            $rulesType = (new TypeResolver($context))->evaluate(RuleParser::parse([
                'value' => 'required|hex_color',
            ], $context));
            self::assertSame(
                'array{value: mixed}',
                $rulesType->describe(Type\VerbosityLevel::precise())
            );

            $this->expectException(\BadMethodCallException::class);
            $factory->make(['value' => '#FFF'], ['value' => 'required|hex_color'])->passes();
            return;
        }

        $nativeString = $factory->make(
            ['value' => '#FFF'],
            ['value' => 'required|hex_color']
        );
        self::assertTrue($nativeString->passes());
        self::assertSame(['value' => '#FFF'], $nativeString->validated());

        $context = new LaravelVersionContext('', $laravelVersion);
        $rulesType = (new TypeResolver($context))->evaluate(RuleParser::parse([
            'value' => 'required|hex_color',
        ], $context));
        $validatedType = $this->convertToType($nativeString->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());

        foreach ([123, 1.5, true, false, null] as $invalidValue) {
            $invalid = $factory->make(
                ['value' => $invalidValue],
                ['value' => 'required|hex_color']
            );
            self::assertFalse($invalid->passes());
        }

        $invalidStringable = $factory->make(
            ['value' => new \Illuminate\Support\Stringable('not-a-color')],
            ['value' => 'required|hex_color']
        );
        self::assertFalse($invalidStringable->passes());

        $value = new \Illuminate\Support\Stringable('#FFF');
        $stringable = $factory->make(
            ['value' => $value],
            ['value' => 'required|hex_color']
        );

        if (version_compare($laravelVersion, '13.4.0', '>=')) {
            self::assertFalse($stringable->passes());
            $stringableType = $this->convertToType(['value' => $value]);
            self::assertTrue($rulesType->accepts($stringableType, true)->no());
            return;
        }

        self::assertTrue($stringable->passes());
        self::assertSame(['value' => $value], $stringable->validated());

        $validatedType = $this->convertToType($stringable->validated());
        self::assertTrue($rulesType->accepts($validatedType, true)->yes());
    }

    public function testBase64RuleFollowsRuntimeVersionBoundary(): void
    {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $laravelVersion = self::frameworkVersion();
        $context = new LaravelVersionContext('', $laravelVersion);

        $blank = $factory->make(['value' => ''], ['value' => 'base64']);
        self::assertTrue($blank->passes());
        self::assertSame(['value' => ''], $blank->validated());
        $blankType = (new TypeResolver($context))->evaluate(RuleParser::parse([
            'value' => 'base64',
        ], $context));
        self::assertTrue($blankType->accepts(
            $this->convertToType($blank->validated()),
            true
        )->yes());

        if (version_compare($laravelVersion, '13.21.0', '<')) {
            self::assertSame(
                'array{value: mixed}',
                (new TypeResolver($context))->evaluate(RuleParser::parse([
                    'value' => 'required|base64',
                ], $context))->describe(Type\VerbosityLevel::precise())
            );

            $this->expectException(\BadMethodCallException::class);
            $factory->make(
                ['value' => 'TGFyYXZlbA=='],
                ['value' => 'required|base64']
            )->passes();
            return;
        }

        $valid = $factory->make(
            ['value' => 'TGFyYXZlbA=='],
            ['value' => 'required|base64']
        );
        self::assertTrue($valid->passes());
        self::assertSame(['value' => 'TGFyYXZlbA=='], $valid->validated());

        $rulesType = (new TypeResolver($context))->evaluate(RuleParser::parse([
            'value' => 'required|base64',
        ], $context));
        self::assertTrue($rulesType->accepts(
            $this->convertToType($valid->validated()),
            true
        )->yes());

        foreach (
            [
                'not-base64',
                1,
                1.0,
                true,
                false,
                ['TGFyYXZlbA=='],
                new \Illuminate\Support\Stringable('TGFyYXZlbA=='),
            ] as $invalidValue
        ) {
            self::assertFalse($factory->make(
                ['value' => $invalidValue],
                ['value' => 'required|base64']
            )->passes());
        }

        $nullable = $factory->make(
            ['value' => null],
            ['value' => 'nullable|base64']
        );
        self::assertTrue($nullable->passes());
        self::assertSame(['value' => null], $nullable->validated());
    }

    public function testListRuleFollowsRuntimeVersionBoundary(): void
    {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $laravelVersion = self::frameworkVersion();
        $context = new LaravelVersionContext('', $laravelVersion);

        $blank = $factory->make(['value' => ''], ['value' => 'list']);
        self::assertTrue($blank->passes());
        self::assertSame(['value' => ''], $blank->validated());
        $blankType = (new TypeResolver($context))->evaluate(RuleParser::parse([
            'value' => 'list',
        ], $context));
        self::assertTrue($blankType->accepts(
            $this->convertToType($blank->validated()),
            true
        )->yes());

        if (version_compare($laravelVersion, '11.0.3', '<')) {
            self::assertSame(
                'array{value: mixed}',
                (new TypeResolver($context))->evaluate(RuleParser::parse([
                    'value' => 'required|list',
                ], $context))->describe(Type\VerbosityLevel::precise())
            );

            try {
                $factory->make(
                    ['value' => [1, 2]],
                    ['value' => 'required|list']
                )->passes();
                self::fail('Laravel unexpectedly provided the list rule before version 11.0.3.');
            } catch (\BadMethodCallException) {
            }

            return;
        }

        foreach (
            [
                'empty optional list' => [[], 'list'],
                'nullable null' => [null, 'nullable|list'],
                'nested values' => [[1, ['nested' => true]], 'required|list'],
            ] as [$value, $rules]
        ) {
            $validator = $factory->make(['value' => $value], ['value' => $rules]);
            self::assertTrue($validator->passes());
            self::assertSame(['value' => $value], $validator->validated());

            $rulesType = (new TypeResolver($context))->evaluate(RuleParser::parse([
                'value' => $rules,
            ], $context));
            self::assertTrue($rulesType->accepts(
                $this->convertToType($validator->validated()),
                true
            )->yes());
        }

        foreach (
            [
                'associative array' => ['key' => 'value'],
                'sparse array' => [0 => 'zero', 2 => 'two'],
                'string' => 'value',
                'array-like object' => new \ArrayObject([1, 2]),
            ] as $value
        ) {
            self::assertFalse($factory->make(
                ['value' => $value],
                ['value' => 'required|list']
            )->passes());
        }

        $projectionRules = [
            'items' => 'required|list',
            'items.*.id' => 'missing',
        ];
        $projectionInput = ['items' => [['name' => 'Ada']]];
        $projection = $factory->make($projectionInput, $projectionRules);
        self::assertTrue($projection->passes());
        self::assertSame(
            version_compare($laravelVersion, '11.23.0', '>=') ? [] : $projectionInput,
            $projection->validated()
        );

        $projectionType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $projectionRules,
            $context
        ));
        self::assertTrue($projectionType->isSuperTypeOf(
            $this->convertToType($projection->validated())
        )->yes());

        $zeroMatchRules = [
            'items' => 'present|list',
            'items.*.id' => 'required|integer',
        ];
        $zeroMatch = $factory->make(['items' => []], $zeroMatchRules);
        self::assertTrue($zeroMatch->passes());
        self::assertSame(['items' => []], $zeroMatch->validated());

        $zeroMatchType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $zeroMatchRules,
            $context
        ));
        self::assertTrue($zeroMatchType->isSuperTypeOf(
            $this->convertToType($zeroMatch->validated())
        )->yes());
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

    private static function flushHttpInputNormalizationState(): void
    {
        foreach (
            [
                \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
                \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            ] as $middleware
        ) {
            if (method_exists($middleware, 'flushState')) {
                $middleware::flushState();
            }
        }
    }

    private static function frameworkVersion(): string
    {
        $version = InstalledVersions::getPrettyVersion('laravel/framework');

        return $version === null
            ? \Illuminate\Foundation\Application::VERSION
            : ltrim($version, 'v');
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

        $warnings = [];
        set_error_handler(static function (int $severity, string $message) use (&$warnings): bool {
            if ($severity !== E_WARNING) {
                return false;
            }

            $warnings[] = $message;
            return true;
        });
        try {
            self::assertTrue($validator->passes());
        } finally {
            restore_error_handler();
        }

        // Laravel 11.23.0 added an optional confirmation parameter but read
        // its absent first element with `?:`. Laravel 11.23.1 fixes the access
        // to use `??`; retain the runtime witness without failing on Laravel's
        // two warnings (one for each confirmed field).
        self::assertSame(
            self::frameworkVersion() === '11.23.0'
                ? ['Undefined array key 0', 'Undefined array key 0']
                : [],
            $warnings
        );
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

        return array_merge(
            self::withLaravelVersion($v10, '10.50.2'),
            self::withLaravelVersion($v11, '11.55.0'),
            self::withLaravelVersion($v12, '12.64.0'),
            self::withLaravelVersion($v13, '13.23.0')
        );
    }

    /**
     * @param array<mixed> $entries
     * @return array<mixed>
     */
    private static function withLaravelVersion(array $entries, string $laravelVersion): array
    {
        return array_map(static function ($entry) use ($laravelVersion) {
            if (is_array($entry)) {
                // expandedRules isn't a test parameter; replace it with the
                // version whose upstream suite produced this fixture.
                unset($entry['expandedRules']);
                $entry['laravelVersion'] = $laravelVersion;
            }
            return $entry;
        }, $entries);
    }

    private function convertToType(mixed $data): Type\Type
    {
        return LaravelValueType::fromValue($data);
    }
}
