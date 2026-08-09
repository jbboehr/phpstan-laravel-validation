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

use jbboehr\PhpstanLaravelValidation\Validation\InvalidRuleException;
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Validation\Rule;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\VerbosityLevel;

final class TypeResolverTest extends PHPStanTestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function resolvedRuleTypeProvider(): iterable
    {
        yield 'accepted' => ['accepted', "1|'1'|'on'|'true'|'yes'|true"];
        yield 'accepted if' => ['accepted_if:other,value', 'mixed'];

        foreach (
            [
            'active_url', 'alpha', 'current_password',
            'email', 'ip', 'ipv4', 'ipv6', 'mac_address', 'timezone', 'url', 'ulid', 'uuid',
            ] as $rule
        ) {
            yield $rule => [$rule, 'non-empty-string'];
        }

        foreach (['alpha_dash', 'alpha_dash:ascii'] as $rule) {
            yield $rule => [$rule, 'float|int|non-empty-string'];
        }

        foreach (['alpha_num', 'alpha_num:ascii'] as $rule) {
            yield $rule => [$rule, 'float|int<0, max>|non-empty-string'];
        }

        yield 'date format' => ['date_format:Y-m-d', 'float|int|non-empty-string'];

        foreach (
            [
            'after:today', 'after_or_equal:today', 'before:today',
            'before_or_equal:today', 'date', 'date_equals:today',
            ] as $rule
        ) {
            yield $rule => [$rule, 'DateTimeInterface|float|int|non-empty-string'];
        }

        yield 'ascii' => ['ascii', 'array|bool|float|int|resource|string|Stringable|null'];
        yield 'base64' => ['base64', 'mixed'];
        yield 'hex color' => ['hex_color', 'mixed'];
        yield 'list' => ['list', 'mixed'];
        yield 'required array keys' => [
            'required_array_keys:name,email',
            "non-empty-array&hasOffset('email')&hasOffset('name')",
        ];

        foreach (['lowercase', 'string', 'uppercase'] as $rule) {
            yield $rule => [$rule, 'string'];
        }

        foreach (['regex:/foo/', 'not_regex:/foo/'] as $rule) {
            yield $rule => [$rule, 'float|int|string'];
        }

        yield 'array' => ['array', 'array'];
        yield 'boolean' => ['boolean', "0|1|'0'|'1'|bool"];

        yield 'declined' => ['declined', "0|'0'|'false'|'no'|'off'|false"];
        yield 'declined if' => ['declined_if:other,value', 'mixed'];

        $numericRules = [
            'digits:2',
            'digits_between:1,2',
            'decimal:2',
            'max_digits:2',
            'min_digits:2',
            'multiple_of:2',
            'numeric',
        ];
        foreach ($numericRules as $rule) {
            yield $rule => [$rule, 'float|int|numeric-string'];
        }

        yield 'integer' => ['integer', 'float|int|numeric-string|Stringable|true'];
        yield 'integer strict' => ['integer:strict', 'float|int|numeric-string|Stringable|true'];
        yield 'json' => ['json', 'float|int|non-empty-string|Stringable|true'];

        foreach (['dimensions:min_width=1', 'file', 'image', 'mimetypes:text/plain', 'mimes:txt'] as $rule) {
            yield $rule => [$rule, 'Symfony\\Component\\HttpFoundation\\File\\File'];
        }

        yield 'in' => ['in:one,two', "'one'|'two'|Stringable"];
    }

    /**
     * @dataProvider resolvedRuleTypeProvider
     */
    public function testResolvesSupportedRuleExactly(string $rule, string $expectedType): void
    {
        self::getContainer();

        $node = RuleParser::parse([
            'value' => 'required|' . $rule,
        ])->resolvePath('value');

        self::assertSame(
            $expectedType,
            (new TypeResolver())->evaluateLeaf($node)->describe(VerbosityLevel::precise())
        );
    }

    public function testEvaluatesLeafAndSingleValueInRulesExactly(): void
    {
        self::assertSame("array{value: 'only'|Stringable}", self::resolve([
            'value' => 'required|in:only',
        ]));
        self::assertSame('array{value: float|int|numeric-string|Stringable|true}', self::resolve([
            'value' => 'required|in:1',
        ]));
        self::assertSame('array{value: float|int|numeric-string|Stringable}', self::resolve([
            'value' => 'required|in:2',
        ]));
        self::assertSame('array{value?: string|Stringable|false|null}', self::resolve([
            'value' => 'in:',
        ]));
        self::assertSame('array{value: *NEVER*}', self::resolve([
            'value' => 'required|in',
        ]));
        self::assertSame("array{value: 'xResource id #1'|Stringable}", self::resolve([
            'value' => 'required|in:xResource id #1',
        ]));
        self::assertSame("array{value: 'Resource id #1x'|Stringable}", self::resolve([
            'value' => 'required|in:Resource id #1x',
        ]));
        self::assertSame('array{value: string}', self::resolve([
            'value' => 'required|string',
        ]));
    }

    public function testUnconditionalRequiredRejectsNullableOutputRegardlessOfRuleOrder(): void
    {
        foreach (['required|nullable|string', 'nullable|required|string'] as $rules) {
            self::assertSame('array{value: string}', self::resolve([
                'value' => $rules,
            ]));
        }

        self::assertSame('array{value?: string|null}', self::resolve([
            'value' => 'nullable|string',
        ]));
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function unconditionalAcceptanceShapeProvider(): iterable
    {
        yield 'accepted' => ['accepted', "1|'1'|'on'|'true'|'yes'|true"];
        yield 'declined' => ['declined', "0|'0'|'false'|'no'|'off'|false"];
    }

    /**
     * @dataProvider unconditionalAcceptanceShapeProvider
     */
    public function testUnconditionalAcceptanceRulesRequireMatchedOutputPaths(
        string $rule,
        string $valueType
    ): void {
        self::assertSame('array{value: ' . $valueType . '}', self::resolve([
            'value' => $rule,
        ]));
        self::assertSame('array{nested: array{value: ' . $valueType . '}}', self::resolve([
            'nested.value' => $rule,
        ]));
        self::assertSame(
            'array{items?: array<int|string, array{value: ' . $valueType . '}>}',
            self::resolve(['items.*.value' => $rule])
        );
        self::assertSame('array{value?: ' . $valueType . '}', self::resolve([
            'value' => 'sometimes|' . $rule,
        ]));
        self::assertSame('array{value: ' . $valueType . '}', self::resolve([
            'value' => 'nullable|' . $rule,
        ]));
    }

    public function testNormalizedHttpInputSuppressesBlankStringBypass(): void
    {
        self::assertSame('array{value?: array}', self::resolve([
            'value' => 'array',
        ], true));
        self::assertSame('array{value?: array|null}', self::resolve([
            'value' => 'nullable|array',
        ], true));
        self::assertSame('array{value?: non-empty-string}', self::resolve([
            'value' => 'email',
        ], true));
        self::assertSame(
            'array{people?: array<int|string, array{email?: non-empty-string}>}',
            self::resolve(['people.*.email' => 'email'], true)
        );
    }

    public function testNormalizedHttpInputRetainsDefaultUntrimmedPaths(): void
    {
        foreach (['current_password', 'password', 'password_confirmation'] as $path) {
            self::assertSame(
                'array{' . $path . '?: array|string}',
                self::resolve([$path => 'array'], true)
            );
        }
    }

    public function testVersionAwareIntegerStrictInference(): void
    {
        $broadType = 'array{value: float|int|numeric-string|Stringable|true}';

        self::assertSame($broadType, self::resolveForVersion([
            'value' => 'required|integer:strict',
        ], '12.21.0'));
        self::assertSame('array{value: int}', self::resolveForVersion([
            'value' => 'required|integer:strict',
        ], '12.22.0'));
        self::assertSame($broadType, self::resolveForVersion([
            'value' => 'required|integer',
        ], '13.4.0'));
        self::assertSame($broadType, self::resolveForVersion([
            'value' => 'required|integer:strict',
        ], '14.0.0'));
    }

    public function testVersionAwareAsciiInference(): void
    {
        $broadType = 'array{value: array|bool|float|int|resource|string|Stringable|null}';

        self::assertSame($broadType, self::resolveForVersion([
            'value' => 'required|ascii',
        ], '13.3.0'));
        self::assertSame('array{value: string}', self::resolveForVersion([
            'value' => 'required|ascii',
        ], '13.4.0'));
        self::assertSame($broadType, self::resolveForVersion([
            'value' => 'required|ascii',
        ], '14.0.0'));
    }

    public function testVersionAwareHexColorInference(): void
    {
        $coerciveType = 'array{value: non-empty-string|Stringable}';

        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|hex_color',
        ], '10.32.1'));
        self::assertSame($coerciveType, self::resolveForVersion([
            'value' => 'required|hex_color',
        ], '10.33.0'));
        self::assertSame($coerciveType, self::resolveForVersion([
            'value' => 'required|hex_color',
        ], '13.3.0'));
        self::assertSame('array{value: non-empty-string}', self::resolveForVersion([
            'value' => 'required|hex_color',
        ], '13.4.0'));
        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|hex_color',
        ], '14.0.0'));

        self::assertSame('array{value?: string|Stringable}', self::resolveForVersion([
            'value' => 'hex_color',
        ], '13.3.0'));
        self::assertSame('array{value?: non-empty-string|Stringable}', self::resolveForVersion([
            'value' => 'hex_color',
        ], '13.3.0', true));
        self::assertSame('array{value?: string}', self::resolveForVersion([
            'value' => 'hex_color',
        ], '13.4.0'));
        self::assertSame('array{value?: non-empty-string}', self::resolveForVersion([
            'value' => 'hex_color',
        ], '13.4.0', true));
    }

    public function testVersionAwareBase64Inference(): void
    {
        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|base64',
        ], '13.20.0'));
        self::assertSame('array{value: non-empty-string}', self::resolveForVersion([
            'value' => 'required|base64',
        ], '13.21.0'));
        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|base64',
        ], '14.0.0'));

        self::assertSame('array{value?: string}', self::resolveForVersion([
            'value' => 'base64',
        ], '13.21.0'));
        self::assertSame('array{value?: non-empty-string}', self::resolveForVersion([
            'value' => 'base64',
        ], '13.21.0', true));
        self::assertSame('array{value?: string|null}', self::resolveForVersion([
            'value' => 'nullable|base64',
        ], '13.21.0'));
        self::assertSame('array{value?: non-empty-string|null}', self::resolveForVersion([
            'value' => 'nullable|base64',
        ], '13.21.0', true));
    }

    public function testVersionAwareListInference(): void
    {
        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|list',
        ], '10.50.2'));
        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|list',
        ], '11.0.2'));
        self::assertSame('array{value: list}', self::resolveForVersion([
            'value' => 'required|list',
        ], '11.0.3'));
        self::assertSame('array{value: list}', self::resolveForVersion([
            'value' => 'required|list',
        ], '13.24.0'));
        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|list',
        ], '14.0.0'));

        self::assertSame('array{value?: list|string}', self::resolveForVersion([
            'value' => 'list',
        ], '11.0.3'));
        self::assertSame('array{value?: list}', self::resolveForVersion([
            'value' => 'list',
        ], '11.0.3', true));
        self::assertSame('array{value?: list|string|null}', self::resolveForVersion([
            'value' => 'nullable|list',
        ], '11.0.3'));
        self::assertSame('array{value?: list|null}', self::resolveForVersion([
            'value' => 'nullable|list',
        ], '11.0.3', true));
    }

    public function testListParentProjectionChangesInLaravel1123(): void
    {
        $rules = [
            'items' => 'required|list',
            'items.*.id' => 'missing',
        ];

        self::assertSame(
            'array{items: array<int|string, mixed>}',
            self::resolveForVersion($rules, '11.22.0')
        );
        self::assertSame(
            'array{items?: array<int|string, mixed>}',
            self::resolveForVersion($rules, '11.23.0')
        );
        self::assertSame(
            'array{items?: mixed}',
            self::resolveForVersion($rules, '14.0.0')
        );
        self::assertSame('array{items?: mixed}', self::resolve($rules));

        self::assertSame('array{payload: string}', self::resolveForVersion([
            'payload' => 'required|string',
            'payload.child' => 'missing',
        ], '13.24.0'));
    }

    public function testVersionAwareDefaultHttpNormalizationExceptions(): void
    {
        self::assertSame('array{password?: array}', self::resolveForVersion([
            'password' => 'array',
        ], '10.50.2', true));
        self::assertSame('array{password?: array|string}', self::resolveForVersion([
            'password' => 'array',
        ], '11.0.0', true));
        self::assertSame('array{password?: array|string}', self::resolveForVersion([
            'password' => 'array',
        ], '14.0.0', true));
    }

    public function testHttpInputNormalizationDefaultsToRawValidatorSemantics(): void
    {
        self::getContainer();
        $resolver = new TypeResolver();
        $tree = RuleParser::parse([
            'value' => 'array',
            'items.*' => 'array',
        ]);

        self::assertSame(
            'array{value?: array|string, items?: array<int|string, array|string>}',
            $resolver->evaluateMap($tree)->describe(VerbosityLevel::precise())
        );
        self::assertSame(
            'array<int|string, array|string>',
            $resolver->evaluateWildcard($tree->resolvePath('items'))->describe(VerbosityLevel::precise())
        );
        self::assertSame(
            'array|string',
            $resolver->evaluateLeaf($tree->resolvePath('value'))->describe(VerbosityLevel::precise())
        );
    }

    public function testNonArrayParentRuleTakesPrecedenceOverNestedShape(): void
    {
        self::assertSame('array{foo: string}', self::resolve([
            'foo' => 'required|string',
            'foo.bar' => 'sometimes|string',
        ]));
    }

    public function testRequiredArrayOutputDependsOnRequiredChildren(): void
    {
        self::assertSame('array{foo?: array{bar?: string}}', self::resolve([
            'foo' => 'required|array',
            'foo.bar' => 'sometimes|string',
        ]));
        self::assertSame('array{foo: array{bar: string}}', self::resolve([
            'foo' => 'required|array',
            'foo.bar' => 'required|string',
        ]));
        self::assertSame('array{foo: array{bar: string}}', self::resolve([
            'foo' => 'required|array',
            'foo.excluded' => 'exclude|string',
            'foo.bar' => 'required|string',
        ]));
    }

    public function testPresentRequiresOutputWithoutImplyingNonBlankRequiredness(): void
    {
        self::assertSame(
            'array{value: mixed, integer: float|int|string|Stringable|true, '
            . 'nullable: float|int|string|Stringable|true|null, optional?: string}',
            self::resolve([
                'value' => 'present',
                'integer' => 'present|integer',
                'nullable' => 'present|nullable|integer',
                'optional' => 'sometimes|present|string',
            ])
        );
    }

    public function testPresentPropagatesThroughNamedPathsButNotWildcardCollections(): void
    {
        self::assertSame(
            'array{nested: array{value: string}, items?: array<int|string, array{value: string}>}',
            self::resolve([
                'nested.value' => 'present|string',
                'items.*.value' => 'present|string',
            ])
        );
    }

    public function testArrayParentsRetainBlankValuesWhenWildcardsExpandToNoRules(): void
    {
        self::assertSame(
            'array{items: array<int|string, array{id: float|int|numeric-string|Stringable|true}>|string}',
            self::resolve([
                'items' => 'present|array',
                'items.*.id' => 'required|integer',
            ])
        );
        self::assertSame(
            'array{items: array<int|string, array{id: float|int|numeric-string|Stringable|true}>}',
            self::resolve([
                'items' => 'present|array',
                'items.*.id' => 'required|integer',
            ], true)
        );

        self::assertSame(
            'array{items: array<int|string, array{id: float|int|numeric-string|Stringable|true}>}',
            self::resolve([
                'items' => 'required|array',
                'items.*.id' => 'required|integer',
            ])
        );
    }

    public function testDeeperZeroMatchWildcardCanPreserveTheCompleteArrayParent(): void
    {
        self::assertSame(
            'array{payload: array|string}',
            self::resolve([
                'payload' => 'present|array',
                'payload.items.*.id' => 'required|integer',
            ])
        );

        self::assertSame(
            'array{payload?: array|string}',
            self::resolve([
                'payload' => 'present|array',
                'payload.items.*.id' => 'missing',
            ])
        );
    }

    public function testMultipleDeepWildcardPathsRemainConservativelyOptional(): void
    {
        self::assertSame(
            'array{payload?: array|string}',
            self::resolve([
                'payload' => 'present|array',
                'payload.required_items.*.id' => 'required|string',
                'payload.optional_items.*.name' => 'string',
            ])
        );
    }

    public function testLiteralDescendantRulesPreventRawWildcardParentPreservation(): void
    {
        self::assertSame(
            'array{payload?: array{name?: string, items?: array<int|string, '
            . 'array{id: float|int|numeric-string|Stringable|true}>}}',
            self::resolve([
                'payload' => 'present|array',
                'payload.name' => 'sometimes|string',
                'payload.items.*.id' => 'required|integer',
            ])
        );

        self::assertSame(
            'array{payload?: array{items?: array<int|string, '
            . 'array{id: float|int|numeric-string|Stringable|true}>, name?: string}}',
            self::resolve([
                'payload' => 'present|array',
                'payload.items.*.id' => 'required|integer',
                'payload.name' => 'sometimes|string',
            ])
        );

        self::assertSame(
            'array{payload?: array<int|string, array{id: float|int|numeric-string|Stringable|true}|string>}',
            self::resolve([
                'payload' => 'present|array',
                'payload.*.id' => 'required|integer',
                'payload.name' => 'sometimes|string',
            ])
        );
    }

    public function testMissingOmitsNamedNestedAndWildcardOnlyProjections(): void
    {
        self::assertSame('array{}', self::resolve([
            'value' => 'missing',
            'nested.value' => 'missing',
            'items.*.value' => 'missing',
        ]));

        self::assertSame('array{}', self::resolve([
            'payload' => 'required|array',
            'payload.value' => 'missing',
        ]));
    }

    public function testMissingChildDoesNotHideAParentPreservedByNonArrayRules(): void
    {
        self::assertSame('array{payload?: string}', self::resolve([
            'payload' => 'string',
            'payload.value' => 'missing',
        ]));
    }

    public function testParameterizedArrayParentIsPreservedAroundNestedRules(): void
    {
        self::assertSame('array{payload: array{name?: mixed}}', self::resolve([
            'payload' => 'required|array:name',
            'payload.child' => 'missing',
        ]));

        self::assertSame('array{payload: array{name?: mixed, child?: mixed}}', self::resolve([
            'payload' => 'required|array:name,child',
            'payload.child' => 'required|string',
        ]));
    }

    public function testRequiredArrayKeysConstrainPreservedArrayOffsets(): void
    {
        self::assertSame(
            "array{required: non-empty-array&hasOffset('email')&hasOffset('name'), "
            . "optional?: (non-empty-array&hasOffset('name'))|string, "
            . "present: (non-empty-array&hasOffset('name'))|string, "
            . "numeric: non-empty-array&hasOffset(0), empty: non-empty-array&hasOffset('')}",
            self::resolve([
                'required' => 'required|required_array_keys:name,email',
                'optional' => 'required_array_keys:name',
                'present' => 'present|required_array_keys:name',
                'numeric' => 'required|required_array_keys:0',
                'empty' => 'required|required_array_keys:',
            ])
        );

        self::assertSame(
            'array{value: array{name: mixed, email?: mixed}}',
            self::resolve([
                'value' => 'required|array:name,email|required_array_keys:name',
            ])
        );
    }

    public function testRequiredArrayKeysOnlyRequireMatchingProjectedChildren(): void
    {
        self::assertSame('array{user: array{name: string}}', self::resolve([
            'user' => 'required|array|required_array_keys:name',
            'user.name' => 'string',
        ]));

        self::assertSame('array{user?: array{email?: string}}', self::resolve([
            'user' => 'required|array|required_array_keys:name',
            'user.email' => 'string',
        ]));

        self::assertSame('array{user?: array{name?: string}}', self::resolve([
            'user' => 'present|array|required_array_keys:name',
            'user.name' => 'string',
        ]));

        self::assertSame('array{user?: array{name?: string}}', self::resolve([
            'user' => 'required|array|required_array_keys:name',
            'user.name' => 'exclude_if:mode,hidden|string',
        ]));

        self::assertSame('array{user?: array{name?: array{first?: string}}}', self::resolve([
            'user' => 'required|array|required_array_keys:name',
            'user.name.first' => 'string',
        ]));

        self::assertSame('array{user: array{string}}', self::resolve([
            'user' => 'required|array|required_array_keys:0',
            'user.0' => 'string',
        ]));

        self::assertSame("array{user: non-empty-array&hasOffset('name')}", self::resolve([
            'user' => 'required|required_array_keys:name',
            'user.name' => 'string',
        ]));

        self::getContainer();
        $opaque = RuleParser::parse([
            'user' => 'required|array|required_array_keys:name',
            'user.name' => [Rule::opaque()],
        ]);
        self::assertSame(
            'array{user?: array{name?: mixed}}',
            (new TypeResolver())->evaluate($opaque)->describe(VerbosityLevel::precise())
        );
    }

    public function testMissingWildcardOnlyProjectionCanPreserveAnArrayParent(): void
    {
        self::assertSame('array{items?: array<int|string, mixed>|string}', self::resolve([
            'items' => 'present|array',
            'items.*.id' => 'missing',
        ]));
    }

    public function testNestedMapWithoutParentRulesRetainsItsShape(): void
    {
        self::assertSame('array{foo: array{bar: string}}', self::resolve([
            'foo.bar' => 'required|string',
        ]));
    }

    public function testEvaluatesWildcardMixedWithNamedChildrenConservatively(): void
    {
        self::getContainer();
        $node = RuleParser::parse([
            'items.*' => 'string',
            'items.named' => 'required|integer',
        ])->resolvePath('items');

        self::assertSame(
            'array<int|string, float|int|string|Stringable|true>',
            (new TypeResolver())->evaluateWildcard($node)->describe(VerbosityLevel::precise())
        );
    }

    public function testEvaluatesNestedWildcardAndNamedChildrenConservatively(): void
    {
        self::getContainer();
        $node = RuleParser::parse([
            'items.*.name' => 'required|string',
            'items.named.label' => 'required|string',
        ])->resolvePath('items');

        self::assertSame(
            'array<int|string, array{label: string}|array{name: string}>',
            (new TypeResolver())->evaluateWildcard($node)->describe(VerbosityLevel::precise())
        );
    }

    public function testExcludedWildcardDoesNotHideNamedChildType(): void
    {
        self::getContainer();
        $node = RuleParser::parse([
            'items.*' => 'exclude',
            'items.named' => 'required|string',
        ])->resolvePath('items');

        self::assertSame(
            'array<int|string, string>',
            (new TypeResolver())->evaluateWildcard($node)->describe(VerbosityLevel::precise())
        );
    }

    public function testAllExcludedWildcardChildrenFallBackToMixedValues(): void
    {
        self::getContainer();
        $node = RuleParser::parse([
            'items.*' => 'exclude',
            'items.named' => 'exclude',
        ])->resolvePath('items');

        self::assertSame(
            'array<int|string, mixed>',
            (new TypeResolver())->evaluateWildcard($node)->describe(VerbosityLevel::precise())
        );
    }

    public function testRejectsNonScalarArrayKey(): void
    {
        self::getContainer();
        $node = RuleParser::parse([])->resolvePath('value');
        $node->push(Rule::create(Rule::RULE_ARRAY, [[]]));

        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionMessage('Cannot have non-scalar key');
        (new TypeResolver())->evaluateLeaf($node);
    }

    public function testRejectsNonScalarRequiredArrayKey(): void
    {
        self::getContainer();
        $node = RuleParser::parse([])->resolvePath('value');
        $node->push(Rule::create(Rule::RULE_REQUIRED_ARRAY_KEYS, [[]]));

        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionMessage('Cannot have non-scalar key');
        (new TypeResolver())->evaluateLeaf($node);
    }

    public function testNormalizesScalarRequiredArrayKeysLikePhpArrayKeys(): void
    {
        self::getContainer();
        $node = RuleParser::parse([])->resolvePath('value');
        $node->push(Rule::create(Rule::RULE_REQUIRED));
        $node->push(Rule::create(Rule::RULE_REQUIRED_ARRAY_KEYS, [true, false, 1.0, 1.5, 2, '01']));

        self::assertSame(
            "non-empty-array&hasOffset('01')&hasOffset('1.5')&hasOffset(0)&hasOffset(1)&hasOffset(2)",
            (new TypeResolver())->evaluateLeaf($node)->describe(VerbosityLevel::precise())
        );
    }

    public function testRejectsNonScalarInValue(): void
    {
        self::getContainer();
        $node = RuleParser::parse([])->resolvePath('value');
        $node->push(Rule::create('In', [[]]));

        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionMessage('Cannot have non-scalar key');
        (new TypeResolver())->evaluateLeaf($node);
    }

    public function testCastsScalarInValuesToStrings(): void
    {
        self::getContainer();
        $node = RuleParser::parse([])->resolvePath('value');
        $node->push(Rule::create(Rule::RULE_REQUIRED));
        $node->push(Rule::create('In', [1]));

        self::assertSame(
            'float|int|numeric-string|Stringable|true',
            (new TypeResolver())->evaluateLeaf($node)->describe(VerbosityLevel::precise())
        );
    }

    public function testNumericRulePathsFollowLaravelKeyPreservationBoundary(): void
    {
        self::assertSame(
            'array{string, string}',
            self::resolveNumericPathsForVersion('11.0.0')
        );
        self::assertSame(
            'array{3: string, 5: string}',
            self::resolveNumericPathsForVersion('12.0.0')
        );
    }

    public function testNumericRulePathsAreConservativeWithoutAVersion(): void
    {
        self::getContainer();
        $tree = RuleParser::parse([3 => 'required|string']);

        self::assertSame(
            'array<int|string, mixed>',
            (new TypeResolver())->evaluate($tree)->describe(VerbosityLevel::precise())
        );
    }

    public function testOpaqueRuleMakesThePathOptionalAndMixed(): void
    {
        self::getContainer();
        $tree = RuleParser::parse([
            'value' => [Rule::create(Rule::RULE_REQUIRED), Rule::opaque()],
        ]);
        $node = $tree->resolvePath('value');

        self::assertTrue($node->isOpaque());
        self::assertTrue($node->isOptional());
        self::assertSame(
            'array{value?: mixed}',
            (new TypeResolver())->evaluate($tree)->describe(VerbosityLevel::precise())
        );
    }

    public function testNumericStringPathSegmentsRemainLiteral(): void
    {
        self::getContainer();
        $context = new LaravelVersionContext('', '10.0.0');
        $tree = RuleParser::parse(['items.3.name' => 'required|string'], $context);

        self::assertSame(
            'array{items: array{3: array{name: string}}}',
            (new TypeResolver($context))->evaluate($tree)->describe(VerbosityLevel::precise())
        );
    }

    /**
     * @param array<string, string> $rules
     */
    private static function resolve(array $rules, bool $assumeHttpInputNormalization = false): string
    {
        self::getContainer();

        $resolver = new TypeResolver();
        $tree = RuleParser::parse($rules);

        return $resolver
            ->evaluateMap($tree, $assumeHttpInputNormalization)
            ->describe(VerbosityLevel::precise());
    }

    /**
     * @param array<string, string> $rules
     */
    private static function resolveForVersion(
        array $rules,
        string $laravelVersion,
        bool $assumeHttpInputNormalization = false
    ): string {
        self::getContainer();

        $context = new LaravelVersionContext('', $laravelVersion);
        $resolver = new TypeResolver($context);

        return $resolver
            ->evaluateMap(RuleParser::parse($rules, $context), $assumeHttpInputNormalization)
            ->describe(VerbosityLevel::precise());
    }

    private static function resolveNumericPathsForVersion(string $laravelVersion): string
    {
        self::getContainer();
        $context = new LaravelVersionContext('', $laravelVersion);
        $tree = RuleParser::parse([
            3 => 'required|string',
            5 => 'required|string',
        ], $context);

        return (new TypeResolver($context))->evaluate($tree)->describe(VerbosityLevel::precise());
    }
}
