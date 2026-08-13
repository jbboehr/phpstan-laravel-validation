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
     * @return iterable<string, array{string, string, mixed, mixed}>
     */
    public static function ruleAliasProvider(): iterable
    {
        yield 'integer' => ['int', 'integer', '42', 'not-an-integer'];
        yield 'boolean' => ['bool', 'boolean', '1', '2'];
    }

    /**
     * @dataProvider ruleAliasProvider
     */
    public function testRuleAliasesMatchCanonicalRules(
        string $alias,
        string $canonical,
        mixed $accepted,
        mixed $rejected
    ): void {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );

        foreach ([[$accepted, true], [$rejected, false]] as [$value, $expectedPasses]) {
            $aliasValidator = $factory->make(['value' => $value], [
                'value' => 'required|' . $alias,
            ]);
            $canonicalValidator = $factory->make(['value' => $value], [
                'value' => 'required|' . $canonical,
            ]);

            $canonicalPasses = $canonicalValidator->passes();
            $aliasPasses = $aliasValidator->passes();
            self::assertSame($expectedPasses, $canonicalPasses);
            self::assertSame($canonicalPasses, $aliasPasses);
            if ($canonicalPasses) {
                self::assertSame($canonicalValidator->validated(), $aliasValidator->validated());
            }
        }

        $aliasType = (new TypeResolver())->evaluate(RuleParser::parse([
            'value' => 'required|' . $alias,
        ]));
        $canonicalType = (new TypeResolver())->evaluate(RuleParser::parse([
            'value' => 'required|' . $canonical,
        ]));
        self::assertSame(
            $canonicalType->describe(Type\VerbosityLevel::precise()),
            $aliasType->describe(Type\VerbosityLevel::precise())
        );
    }

    public function testFactoryValidateReturnsOutputAcceptedByInference(): void
    {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $data = [
            'person' => [
                'name' => 'Ada',
                'age' => '42',
            ],
        ];
        $rules = [
            'person' => 'required|array',
            'person.name' => 'required|string',
            'person.age' => 'required|integer',
        ];

        $validated = $factory->validate($data, $rules);
        self::assertSame($data, $validated);
        self::assertSame(
            $factory->make($data, $rules)->validated(),
            $validated
        );
        self::assertSame(
            $validated,
            $factory->validate(rules: $rules, data: $data)
        );

        $rulesType = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        self::assertTrue(
            $rulesType->accepts($this->convertToType($validated), true)->yes()
        );
    }

    public function testFactoryUnvalidatedArrayKeyModesMatchInference(): void
    {
        self::getContainer();

        $laravelVersion = ltrim(
            (string) InstalledVersions::getPrettyVersion('laravel/framework'),
            'v'
        );
        $context = new LaravelVersionContext('', $laravelVersion);
        $newFactory = static fn (): \Illuminate\Validation\Factory =>
            new \Illuminate\Validation\Factory(
                new \Illuminate\Translation\Translator(
                    new \Illuminate\Translation\ArrayLoader(),
                    'en'
                )
            );

        $cases = [
            'associative parent' => [
                [
                    'payload' => [
                        'name' => 'Ada',
                        'admin' => true,
                    ],
                ],
                [
                    'payload' => 'required|array',
                    'payload.name' => 'required|string',
                ],
                ['payload' => ['name' => 'Ada']],
            ],
            'list-shaped parent' => [
                [
                    'items' => [
                        ['id' => '1', 'extra' => 'a'],
                        ['id' => '2', 'extra' => 'b'],
                    ],
                ],
                [
                    'items' => 'required|array',
                    'items.*.id' => 'required|integer',
                ],
                [
                    'items' => [
                        ['id' => '1'],
                        ['id' => '2'],
                    ],
                ],
            ],
        ];

        if ($context->isAtLeast('11.23.0')) {
            $cases['literal list parent'] = [
                [
                    'items' => [
                        ['id' => '1', 'extra' => 'a'],
                        ['id' => '2', 'extra' => 'b'],
                    ],
                ],
                [
                    'items' => 'required|list',
                    'items.*.id' => 'required|integer',
                ],
                [
                    'items' => [
                        ['id' => '1'],
                        ['id' => '2'],
                    ],
                ],
            ];
            $cases['literal list parent with sparse nested matches'] = [
                [
                    'items' => [
                        ['other' => 'x'],
                        ['id' => '2'],
                    ],
                ],
                [
                    'items' => 'required|list',
                    'items.*.id' => 'sometimes|integer',
                ],
                [
                    'items' => [
                        1 => ['id' => '2'],
                    ],
                ],
            ];
        }

        foreach ($cases as $name => [$data, $rules, $expectedDefault]) {
            $defaultFactory = $newFactory();
            $default = $defaultFactory->validate($data, $rules);
            self::assertSame($expectedDefault, $default, $name . ': default output');

            $includedFactory = $newFactory();
            $includedFactory->includeUnvalidatedArrayKeys();
            $included = $includedFactory->validate($data, $rules);
            self::assertSame($data, $included, $name . ': included output');

            $includedFactory->excludeUnvalidatedArrayKeys();
            self::assertSame(
                $expectedDefault,
                $includedFactory->validate($data, $rules),
                $name . ': restored default output'
            );

            $ruleTree = RuleParser::parse($rules, $context);
            $defaultType = (new TypeResolver($context))->evaluate($ruleTree);
            $includedType = (new TypeResolver($context, null, true))->evaluate($ruleTree);
            self::assertTrue(
                $defaultType->accepts($this->convertToType($default), true)->yes(),
                $name . ': default inference contains runtime output'
            );
            self::assertTrue(
                $includedType->accepts($this->convertToType($included), true)->yes(),
                $name . ': included inference contains runtime output'
            );
        }

        $mutatedCases = [
            'excluded required array offset' => [
                [
                    'payload' => [
                        'name' => 'Ada',
                        'other' => true,
                    ],
                ],
                [
                    'payload' => 'required|array|required_array_keys:name',
                    'payload.name' => 'exclude',
                ],
                ['payload' => ['other' => true]],
            ],
            'blank parent after nested conditional exclusion' => [
                [
                    'user' => '',
                    'mode' => 'hidden',
                ],
                [
                    'user' => 'array',
                    'user.profile.name' => 'exclude_if:mode,hidden|string',
                    'mode' => 'required|string',
                ],
                [
                    'user' => '',
                    'mode' => 'hidden',
                ],
            ],
            'intermediate parent survives nested conditional exclusion' => [
                [
                    'user' => [
                        'profile' => [
                            'name' => 'Ada',
                            'other' => true,
                        ],
                    ],
                    'mode' => 'hidden',
                ],
                [
                    'user' => 'array',
                    'user.profile' => 'array',
                    'user.profile.name' => 'exclude_if:mode,hidden|string',
                    'mode' => 'required|string',
                ],
                [
                    'user' => [
                        'profile' => [
                            'other' => true,
                        ],
                    ],
                    'mode' => 'hidden',
                ],
            ],
        ];

        if ($context->isAtLeast('11.0.3')) {
            $mutatedCases['excluded list element'] = [
                ['items' => ['zero', 'one']],
                [
                    'items' => 'required|list',
                    'items.0' => 'exclude',
                ],
                ['items' => [1 => 'one']],
            ];
            $mutatedCases['nested list exclusion preserves outer order'] = [
                [
                    'items' => [
                        ['id' => 'zero', 'tmp' => 'drop'],
                        ['id' => 'one', 'tmp' => 'drop'],
                    ],
                    'mode' => 'hidden',
                ],
                [
                    'items' => 'required|list',
                    'items.*.id' => 'required|string',
                    'items.*.tmp' => 'exclude_if:mode,hidden|string',
                    'mode' => 'required|string',
                ],
                $context->isAtLeast('11.23.0')
                    ? [
                        'mode' => 'hidden',
                        'items' => [
                            ['id' => 'zero'],
                            ['id' => 'one'],
                        ],
                    ]
                    : [
                        'items' => [
                            ['id' => 'zero'],
                            ['id' => 'one'],
                        ],
                        'mode' => 'hidden',
                    ],
                [
                    'items' => [
                        ['id' => 'zero'],
                        ['id' => 'one'],
                    ],
                    'mode' => 'hidden',
                ],
            ];
        }

        foreach ($mutatedCases as $name => $case) {
            [$data, $rules, $expectedDefault] = $case;
            $expectedByMode = [
                'default' => $expectedDefault,
                'included' => $case[3] ?? $expectedDefault,
            ];

            foreach (['default' => false, 'included' => true] as $mode => $includeUnvalidatedArrayKeys) {
                $factory = $newFactory();
                if ($includeUnvalidatedArrayKeys) {
                    $factory->includeUnvalidatedArrayKeys();
                }

                $validated = $factory->validate($data, $rules);
                self::assertSame($expectedByMode[$mode], $validated, $name . ': ' . $mode . ' output');

                $ruleTree = RuleParser::parse($rules, $context);
                $type = (new TypeResolver(
                    $context,
                    null,
                    $includeUnvalidatedArrayKeys
                ))->evaluate($ruleTree);
                self::assertTrue(
                    $type->accepts($this->convertToType($validated), true)->yes(),
                    $name . ': ' . $mode . ' inference contains mutated runtime output'
                );
            }
        }
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

    /**
     * @return iterable<string, array{string, string, array<mixed, mixed>, bool}>
     */
    public static function introducedArrayPredicateProvider(): iterable
    {
        yield 'contains' => [
            'contains:needle',
            '11.8.0',
            ['needle', 'nested' => ['value' => 1]],
            false,
        ];
        yield 'in array keys' => [
            'in_array_keys:name',
            '12.16.0',
            ['name' => null, 'extra' => ['value' => 1]],
            false,
        ];
        yield 'does not contain' => [
            'doesnt_contain:blocked',
            '12.22.0',
            ['allowed', 'nested' => ['value' => 1]],
            true,
        ];
    }

    /**
     * @param array<mixed, mixed> $validValue
     * @dataProvider introducedArrayPredicateProvider
     */
    public function testIntroducedArrayPredicatesFollowRuntimeVersionBoundaries(
        string $rule,
        string $introduced,
        array $validValue,
        bool $emptyArrayPasses
    ): void {
        self::getContainer();

        $factory = new \Illuminate\Validation\Factory(
            new \Illuminate\Translation\Translator(
                new \Illuminate\Translation\ArrayLoader(),
                'en'
            )
        );
        $laravelVersion = self::frameworkVersion();
        $context = new LaravelVersionContext('', $laravelVersion);

        $blank = $factory->make(['value' => ''], ['value' => $rule]);
        self::assertTrue($blank->passes());
        self::assertSame(['value' => ''], $blank->validated());
        $blankType = (new TypeResolver($context))->evaluate(RuleParser::parse([
            'value' => $rule,
        ], $context));
        self::assertTrue($blankType->accepts(
            $this->convertToType($blank->validated()),
            true
        )->yes());

        $requiredRules = ['value' => 'required|' . $rule];
        $rulesType = (new TypeResolver($context))->evaluate(RuleParser::parse($requiredRules, $context));

        if (version_compare($laravelVersion, $introduced, '<')) {
            self::assertSame('array{value: mixed}', $rulesType->describe(Type\VerbosityLevel::precise()));

            try {
                $factory->make(['value' => $validValue], $requiredRules)->passes();
                self::fail(sprintf(
                    'Laravel unexpectedly provided %s before version %s.',
                    $rule,
                    $introduced
                ));
            } catch (\BadMethodCallException) {
            }

            return;
        }

        self::assertSame('array{value: array}', $rulesType->describe(Type\VerbosityLevel::precise()));

        $valid = $factory->make(['value' => $validValue], $requiredRules);
        self::assertTrue($valid->passes());
        self::assertSame(['value' => $validValue], $valid->validated());
        self::assertTrue($rulesType->accepts(
            $this->convertToType($valid->validated()),
            true
        )->yes());

        foreach (
            [
                'not-an-array',
                1,
                1.5,
                true,
                new \ArrayObject(['value']),
                new \Illuminate\Support\Stringable('value'),
            ] as $invalidValue
        ) {
            self::assertFalse($factory->make(
                ['value' => $invalidValue],
                $requiredRules
            )->passes());
        }

        $empty = $factory->make(['value' => []], ['value' => $rule]);
        self::assertSame($emptyArrayPasses, $empty->passes());
        if ($emptyArrayPasses) {
            self::assertSame(['value' => []], $empty->validated());
        }
    }

    public function testArrayKeysRuleFollowsRuntimeVersionBoundary(): void
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

        $blank = $factory->make(['value' => ''], ['value' => 'array_keys:name,email']);
        self::assertTrue($blank->passes());
        self::assertSame(['value' => ''], $blank->validated());
        $blankType = (new TypeResolver($context))->evaluate(RuleParser::parse([
            'value' => 'array_keys:name,email',
        ], $context));
        self::assertTrue($blankType->accepts(
            $this->convertToType($blank->validated()),
            true
        )->yes());

        $requiredRules = ['value' => 'required|array_keys:name,email'];
        $rulesType = (new TypeResolver($context))->evaluate(RuleParser::parse($requiredRules, $context));

        if (version_compare($laravelVersion, '13.24.0', '<')) {
            self::assertSame('array{value: mixed}', $rulesType->describe(Type\VerbosityLevel::precise()));
            self::assertFalse((new \ReflectionClass(\Illuminate\Validation\Rule::class))->hasMethod('arrayKeys'));

            try {
                $factory->make(['value' => ['name' => 'Ada']], $requiredRules)->passes();
                self::fail('Laravel unexpectedly provided array_keys before version 13.24.');
            } catch (\BadMethodCallException) {
            }

            return;
        }

        self::assertTrue((new \ReflectionClass(\Illuminate\Validation\Rule::class))->hasMethod('arrayKeys'));
        self::assertSame(
            'array{value: array{name?: mixed, email?: mixed}}',
            $rulesType->describe(Type\VerbosityLevel::precise())
        );

        foreach (
            [
                'allowed subset' => [
                    ['value' => ['name' => 'Ada']],
                    $requiredRules,
                    ['value' => ['name' => 'Ada']],
                ],
                'empty array' => [
                    ['value' => []],
                    ['value' => 'array_keys:name,email'],
                    ['value' => []],
                ],
                'numeric and numeric-looking string keys' => [
                    ['value' => [0 => 'zero', '01' => 'leading']],
                    ['value' => 'required|array_keys:0,01'],
                    ['value' => [0 => 'zero', '01' => 'leading']],
                ],
                'empty parameter permits the empty-string key' => [
                    ['value' => ['' => 'empty']],
                    ['value' => 'required|array_keys:'],
                    ['value' => ['' => 'empty']],
                ],
                'nested rules preserve the complete permitted parent' => [
                    ['value' => ['name' => 'Ada', 'email' => 'ada@example.test']],
                    [
                        'value' => 'required|array_keys:name,email',
                        'value.name' => 'string',
                    ],
                    ['value' => ['name' => 'Ada', 'email' => 'ada@example.test']],
                ],
                'missing child does not project away the parent' => [
                    ['value' => ['name' => 'Ada']],
                    [
                        'value' => 'required|array_keys:name',
                        'value.child' => 'missing',
                    ],
                    ['value' => ['name' => 'Ada']],
                ],
                'string allowed keys intersect a list at the empty array' => [
                    ['value' => []],
                    ['value' => 'array_keys:name,email|list'],
                    ['value' => []],
                ],
                'sparse allowed keys retain the contiguous list prefix' => [
                    ['value' => [0 => 'zero']],
                    ['value' => 'required|array_keys:0,2|list'],
                    ['value' => [0 => 'zero']],
                ],
            ] as $caseId => [$data, $rules, $expected]
        ) {
            $validator = $factory->make($data, $rules);
            self::assertTrue($validator->passes(), $caseId);
            self::assertSame($expected, $validator->validated(), $caseId);

            $inferred = (new TypeResolver($context))->evaluate(RuleParser::parse($rules, $context));
            self::assertTrue($inferred->accepts(
                $this->convertToType($validator->validated()),
                true
            )->yes(), $caseId);
        }

        foreach (
            [
                ['value' => ['name' => 'Ada', 'extra' => true]],
                ['value' => 'not-an-array'],
                ['value' => new \ArrayObject(['name' => 'Ada'])],
            ] as $invalidData
        ) {
            self::assertFalse($factory->make($invalidData, $requiredRules)->passes());
        }

        self::assertFalse($factory->make(
            ['value' => [0 => 'zero', 1 => 'one']],
            ['value' => 'required|array_keys:0,2|list']
        )->passes());

        try {
            $factory->make(['value' => []], ['value' => 'array_keys'])->passes();
            self::fail('Laravel unexpectedly accepted array_keys without a parameter.');
        } catch (\InvalidArgumentException $exception) {
            self::assertSame(
                'Validation rule array_keys requires at least 1 parameters.',
                $exception->getMessage()
            );
        }

    }

    public function testExtensionsRuleFollowsRuntimeVersionBoundary(): void
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

        $blank = $factory->make(['value' => ''], ['value' => 'extensions:txt']);
        self::assertTrue($blank->passes());
        self::assertSame(['value' => ''], $blank->validated());

        $blankType = (new TypeResolver($context))->evaluate(RuleParser::parse([
            'value' => 'extensions:txt',
        ], $context));
        self::assertTrue($blankType->isSuperTypeOf(
            $this->convertToType($blank->validated())
        )->yes());

        $uploadedFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __FILE__,
            'report.TXT',
            'text/plain',
            UPLOAD_ERR_OK,
            true
        );
        $requiredRules = ['value' => 'required|extensions:txt'];
        $rulesType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $requiredRules,
            $context
        ));

        if (version_compare($laravelVersion, '10.34.0', '<')) {
            self::assertSame('array{value: mixed}', $rulesType->describe(Type\VerbosityLevel::precise()));

            try {
                $factory->make(['value' => $uploadedFile], $requiredRules)->passes();
                self::fail('Laravel unexpectedly provided extensions before version 10.34.');
            } catch (\BadMethodCallException) {
            }

            return;
        }

        self::assertSame(
            'array{value: Symfony\\Component\\HttpFoundation\\File\\File}',
            $rulesType->describe(Type\VerbosityLevel::precise())
        );

        // The non-PHP path is deliberate: Laravel checks the physical path
        // extension for File objects before consulting the client extension.
        $plainFilePath = __DIR__ . '/fixtures/version-audit/10.0.0.json';
        $fileWithClientExtension = new class (
            $plainFilePath
        ) extends \Symfony\Component\HttpFoundation\File\File {
            public function getClientOriginalExtension(): string
            {
                return 'txt';
            }
        };

        foreach (
            [
                'uploaded file' => $uploadedFile,
                'file subclass with a client extension' => $fileWithClientExtension,
            ] as $caseId => $file
        ) {
            $validator = $factory->make(['value' => $file], $requiredRules);
            self::assertTrue($validator->passes(), $caseId);
            self::assertSame(['value' => $file], $validator->validated(), $caseId);
        }

        $uploadedValidator = $factory->make(['value' => $uploadedFile], $requiredRules);
        self::assertTrue($rulesType->isSuperTypeOf(
            $this->convertToType($uploadedValidator->validated())
        )->yes());

        try {
            $factory->make(
                ['value' => new \Symfony\Component\HttpFoundation\File\File($plainFilePath)],
                $requiredRules
            )->passes();
            self::fail('Laravel unexpectedly handled extensions on a plain Symfony File.');
        } catch (\Error $error) {
            self::assertSame(
                'Call to undefined method Symfony\\Component\\HttpFoundation\\File\\File::getClientOriginalExtension()',
                $error->getMessage()
            );
        }

        $phpFamilyUpload = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __FILE__,
            'evil.phtml',
            'application/x-httpd-php',
            UPLOAD_ERR_OK,
            true
        );
        self::assertFalse($factory->make(
            ['value' => $phpFamilyUpload],
            ['value' => 'required|extensions:phtml']
        )->passes());
        $phpEscapeHatch = $factory->make(
            ['value' => $phpFamilyUpload],
            ['value' => 'required|extensions:phtml,php']
        );
        self::assertTrue($phpEscapeHatch->passes());
        self::assertSame(['value' => $phpFamilyUpload], $phpEscapeHatch->validated());

        $failedUpload = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __FILE__,
            'report.txt',
            'text/plain',
            UPLOAD_ERR_INI_SIZE,
            true
        );
        foreach (
            [
                'failed upload' => [$failedUpload, 'extensions:txt'],
                'wrong extension' => [$uploadedFile, 'extensions:json'],
                'case-sensitive parameter' => [$uploadedFile, 'extensions:TXT'],
                'missing parameter' => [$uploadedFile, 'extensions'],
                'string path' => [__FILE__, 'extensions:php'],
                'array-like object' => [new \ArrayObject(), 'extensions:txt'],
            ] as $caseId => [$value, $rule]
        ) {
            self::assertFalse($factory->make(
                ['value' => $value],
                ['value' => $rule]
            )->passes(), $caseId);
        }
    }

    public function testEncodingRuleFollowsRuntimeVersionBoundary(): void
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

        $blank = $factory->make(['value' => ''], ['value' => 'encoding:UTF-8']);
        self::assertTrue($blank->passes());
        self::assertSame(['value' => ''], $blank->validated());

        $blankType = (new TypeResolver($context))->evaluate(RuleParser::parse([
            'value' => 'encoding:UTF-8',
        ], $context));
        self::assertTrue($blankType->isSuperTypeOf(
            $this->convertToType($blank->validated())
        )->yes());

        $requiredRules = ['value' => 'required|encoding:UTF-8'];
        $rulesType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $requiredRules,
            $context
        ));

        if (version_compare($laravelVersion, '12.40.0', '<')) {
            self::assertSame('array{value: mixed}', $rulesType->describe(Type\VerbosityLevel::precise()));

            try {
                $factory->make(['value' => 'plain'], $requiredRules)->passes();
                self::fail('Laravel unexpectedly provided encoding before version 12.40.');
            } catch (\BadMethodCallException) {
            }

            return;
        }

        self::assertSame(
            'array{value: array|bool|float|int|string|Stringable|null}',
            $rulesType->describe(Type\VerbosityLevel::precise())
        );

        $validFile = new \Symfony\Component\HttpFoundation\File\File(
            __DIR__ . '/fixtures/version-audit/10.0.0.json'
        );
        foreach (
            [
                'native string' => ['plain', $requiredRules],
                'integer' => [123, $requiredRules],
                'float' => [1.5, $requiredRules],
                'true' => [true, $requiredRules],
                'array' => [['plain', 'text'], $requiredRules],
                'stringable' => [new \Illuminate\Support\Stringable('plain'), $requiredRules],
                'file content' => [$validFile, $requiredRules],
                'false' => [false, ['value' => 'encoding:UTF-8']],
                'empty array' => [[], ['value' => 'encoding:UTF-8']],
            ] as $caseId => [$value, $rules]
        ) {
            $validator = $factory->make(['value' => $value], $rules);
            self::assertTrue($validator->passes(), $caseId);
            self::assertSame(['value' => $value], $validator->validated(), $caseId);

            $inferred = (new TypeResolver($context))->evaluate(RuleParser::parse($rules, $context));
            self::assertTrue($inferred->isSuperTypeOf(
                $this->convertToType($validator->validated())
            )->yes(), $caseId);
        }

        // PHP deprecates the legacy no-value form of mb_check_encoding(), but
        // Laravel still passes an explicit null through to that form and then
        // preserves the successful null value in validated output.
        set_error_handler(static fn (int $severity): bool => $severity === E_DEPRECATED);
        try {
            $nullRules = ['value' => 'encoding:UTF-8'];
            $nullValidator = $factory->make(['value' => null], $nullRules);
            self::assertTrue($nullValidator->passes());
            self::assertSame(['value' => null], $nullValidator->validated());

            $nullType = (new TypeResolver($context))->evaluate(RuleParser::parse($nullRules, $context));
            self::assertTrue($nullType->isSuperTypeOf(
                $this->convertToType($nullValidator->validated())
            )->yes());
        } finally {
            restore_error_handler();
        }

        foreach (
            [
                'invalid native string' => "\xf0\x28\x8c\x28",
                'invalid nested string' => ["\xf0\x28\x8c\x28"],
            ] as $caseId => $value
        ) {
            self::assertFalse($factory->make(
                ['value' => $value],
                $requiredRules
            )->passes(), $caseId);
        }

        $invalidFile = \Illuminate\Http\UploadedFile::fake()->createWithContent(
            'invalid.txt',
            "\xf0\x28\x8c\x28"
        );
        self::assertFalse($factory->make(
            ['value' => $invalidFile],
            $requiredRules
        )->passes());

        $failedUpload = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __FILE__,
            'valid.txt',
            'text/plain',
            UPLOAD_ERR_CANT_WRITE,
            true
        );
        self::assertFalse($factory->make(
            ['value' => $failedUpload],
            $requiredRules
        )->passes());

        try {
            $factory->make(['value' => new \stdClass()], $requiredRules)->passes();
            self::fail('Laravel unexpectedly accepted a non-stringable object for encoding.');
        } catch (\TypeError) {
        }

        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        try {
            try {
                $factory->make(['value' => $resource], $requiredRules)->passes();
                self::fail('Laravel unexpectedly accepted a resource for encoding.');
            } catch (\TypeError) {
            }
        } finally {
            fclose($resource);
        }

        foreach (['encoding', 'encoding:not-an-encoding'] as $rule) {
            try {
                $factory->make(['value' => 'plain'], ['value' => $rule])->passes();
                self::fail('Laravel unexpectedly accepted an invalid encoding rule.');
            } catch (\InvalidArgumentException) {
            }
        }
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

        $directProjectionRules = [
            'items' => 'required|list',
            'items.*' => 'required|string',
        ];
        $directProjectionInput = ['items' => ['zero', 'one']];
        $directProjection = $factory->make($directProjectionInput, $directProjectionRules);
        self::assertTrue($directProjection->passes());
        self::assertSame($directProjectionInput, $directProjection->validated());

        $directProjectionType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $directProjectionRules,
            $context
        ));
        self::assertSame(
            'array{items: list<string>}',
            $directProjectionType->describe(Type\VerbosityLevel::precise())
        );
        self::assertTrue($directProjectionType->isSuperTypeOf(
            $this->convertToType($directProjection->validated())
        )->yes());

        foreach (
            [
                'optional direct list' => [
                    'list',
                    'array{items?: list<string>|string}',
                ],
                'present direct list' => [
                    'present|list',
                    'array{items: list<string>|string}',
                ],
            ] as $name => [$parentRule, $expectedType]
        ) {
            $blankRules = [
                'items' => $parentRule,
                'items.*' => 'required|string',
            ];
            $blank = $factory->make(['items' => ''], $blankRules);
            self::assertTrue($blank->passes(), $name . ': blank input passes');
            self::assertSame(['items' => ''], $blank->validated(), $name . ': blank output');

            $blankType = (new TypeResolver($context))->evaluate(RuleParser::parse(
                $blankRules,
                $context
            ));
            self::assertSame(
                $expectedType,
                $blankType->describe(Type\VerbosityLevel::precise()),
                $name . ': inferred type'
            );
            self::assertTrue($blankType->isSuperTypeOf(
                $this->convertToType($blank->validated())
            )->yes(), $name . ': inference contains blank output');
        }

        $constrainedProjectionRules = [
            'items' => 'required|list|array:0,1|required_array_keys:0',
            'items.*' => 'required|string',
        ];
        $constrainedProjection = $factory->make(
            $directProjectionInput,
            $constrainedProjectionRules
        );
        self::assertTrue($constrainedProjection->passes());
        self::assertSame($directProjectionInput, $constrainedProjection->validated());
        self::assertSame(
            'array{items: list{0: string, 1?: string}}',
            (new TypeResolver($context))->evaluate(RuleParser::parse(
                $constrainedProjectionRules,
                $context
            ))->describe(Type\VerbosityLevel::precise())
        );

        $nestedProjectionRules = [
            'items' => 'required|list',
            'items.*.id' => 'required|string',
        ];
        $nestedProjectionInput = ['items' => [['id' => 'zero'], ['id' => 'one']]];
        $nestedProjection = $factory->make($nestedProjectionInput, $nestedProjectionRules);
        self::assertTrue($nestedProjection->passes());
        self::assertSame($nestedProjectionInput, $nestedProjection->validated());

        $nestedProjectionType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $nestedProjectionRules,
            $context
        ));
        self::assertSame(
            version_compare($laravelVersion, '11.23.0', '>=')
                ? 'array{items: list<array{id: string}>}'
                : 'array{items: list}',
            $nestedProjectionType->describe(Type\VerbosityLevel::precise())
        );
        self::assertTrue($nestedProjectionType->isSuperTypeOf(
            $this->convertToType($nestedProjection->validated())
        )->yes());

        $reorderedProjectionRules = [
            'items' => 'required|list',
            'items.*.id' => 'sometimes|string',
            'items.*.name' => 'required|string',
        ];
        $reorderedProjectionInput = [
            'items' => [
                ['name' => 'zero'],
                ['id' => 'one', 'name' => 'one'],
            ],
        ];
        $reorderedProjection = $factory->make(
            $reorderedProjectionInput,
            $reorderedProjectionRules
        );
        self::assertTrue($reorderedProjection->passes());
        self::assertSame(
            version_compare($laravelVersion, '11.23.0', '>=')
                ? [
                    'items' => [
                        1 => ['id' => 'one', 'name' => 'one'],
                        0 => ['name' => 'zero'],
                    ],
                ]
                : $reorderedProjectionInput,
            $reorderedProjection->validated()
        );

        $reorderedProjectionType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $reorderedProjectionRules,
            $context
        ));
        self::assertTrue($reorderedProjectionType->isSuperTypeOf(
            $this->convertToType($reorderedProjection->validated())
        )->yes());
        if (version_compare($laravelVersion, '11.23.0', '>=')) {
            self::assertSame(
                'array{items: array<int|string, array{id?: string, name: string}>}',
                $reorderedProjectionType->describe(Type\VerbosityLevel::precise())
            );
        }

        $optionalProjectionRules = [
            'items' => 'required|list',
            'items.*.id' => 'sometimes|string',
        ];
        $optionalProjectionInput = [
            'items' => [
                ['other' => 'zero'],
                ['id' => 'one'],
            ],
        ];
        $optionalProjection = $factory->make($optionalProjectionInput, $optionalProjectionRules);
        self::assertTrue($optionalProjection->passes());
        self::assertSame(
            version_compare($laravelVersion, '11.23.0', '>=')
                ? ['items' => [1 => ['id' => 'one']]]
                : $optionalProjectionInput,
            $optionalProjection->validated()
        );

        $optionalProjectionType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $optionalProjectionRules,
            $context
        ));
        self::assertTrue($optionalProjectionType->isSuperTypeOf(
            $this->convertToType($optionalProjection->validated())
        )->yes());

        $deepProjectionRules = [
            'items' => 'required|list',
            'items.*.*.id' => 'required|string',
        ];
        $deepProjectionInput = [
            'items' => [
                [],
                [['id' => 'one']],
            ],
        ];
        $deepProjection = $factory->make($deepProjectionInput, $deepProjectionRules);
        self::assertTrue($deepProjection->passes());
        self::assertSame(
            version_compare($laravelVersion, '11.23.0', '>=')
                ? ['items' => [1 => [['id' => 'one']]]]
                : $deepProjectionInput,
            $deepProjection->validated()
        );

        $deepProjectionType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $deepProjectionRules,
            $context
        ));
        self::assertTrue($deepProjectionType->isSuperTypeOf(
            $this->convertToType($deepProjection->validated())
        )->yes());

        $conditionalExclusionRules = [
            'items' => 'required|list',
            'items.*.id' => 'required|string|exclude_if:items.*.drop,true',
        ];
        $conditionalExclusionInput = [
            'items' => [
                ['id' => 'zero', 'drop' => true],
                ['id' => 'one', 'drop' => false],
            ],
        ];
        $conditionalExclusion = $factory->make(
            $conditionalExclusionInput,
            $conditionalExclusionRules
        );
        self::assertTrue($conditionalExclusion->passes());
        self::assertSame(
            version_compare($laravelVersion, '11.23.0', '>=')
                ? ['items' => [1 => ['id' => 'one']]]
                : [
                    'items' => [
                        ['drop' => true],
                        ['id' => 'one', 'drop' => false],
                    ],
                ],
            $conditionalExclusion->validated()
        );

        $conditionalExclusionType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $conditionalExclusionRules,
            $context
        ));
        self::assertTrue($conditionalExclusionType->isSuperTypeOf(
            $this->convertToType($conditionalExclusion->validated())
        )->yes());

        $orderedExclusionRules = [
            'items' => 'required|list',
            'items.*.id' => 'required|string',
            'items.*.tmp' => 'exclude_if:mode,hidden|string',
        ];
        $orderedExclusionInput = [
            'items' => [
                ['id' => 'zero', 'tmp' => 'drop'],
                ['id' => 'one', 'tmp' => 'drop'],
            ],
            'mode' => 'hidden',
        ];
        $orderedExclusion = $factory->make($orderedExclusionInput, $orderedExclusionRules);
        self::assertTrue($orderedExclusion->passes());
        self::assertSame(
            [
                'items' => [
                    ['id' => 'zero'],
                    ['id' => 'one'],
                ],
            ],
            $orderedExclusion->validated()
        );

        $orderedExclusionType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $orderedExclusionRules,
            $context
        ));
        self::assertTrue($orderedExclusionType->isSuperTypeOf(
            $this->convertToType($orderedExclusion->validated())
        )->yes());
        self::assertSame(
            version_compare($laravelVersion, '11.23.0', '>=')
                ? 'array{items: list<array{id: string, tmp?: string}>}'
                : 'array{items: list}',
            $orderedExclusionType->describe(Type\VerbosityLevel::precise())
        );

        $allowedKeyListRules = ['value' => 'array:name|list'];
        $allowedKeyList = $factory->make(['value' => []], $allowedKeyListRules);
        self::assertTrue($allowedKeyList->passes());
        self::assertSame(['value' => []], $allowedKeyList->validated());

        $allowedKeyListType = (new TypeResolver($context))->evaluate(RuleParser::parse(
            $allowedKeyListRules,
            $context
        ));
        self::assertTrue($allowedKeyListType->isSuperTypeOf(
            $this->convertToType($allowedKeyList->validated())
        )->yes());

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
