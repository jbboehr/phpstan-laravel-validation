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
        yield 'contains' => ['contains:value', 'mixed'];
        yield 'does not contain' => ['doesnt_contain:value', 'mixed'];
        yield 'in array keys' => ['in_array_keys:value', 'mixed'];
        yield 'array keys' => ['array_keys:name,email', 'mixed'];
        yield 'encoding' => ['encoding:UTF-8', 'mixed'];
        yield 'extensions' => ['extensions:txt', 'mixed'];
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
        yield 'boolean alias' => ['bool', "0|1|'0'|'1'|bool"];

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

        yield 'integer alias' => ['int', 'float|int|numeric-string|Stringable|true'];
        yield 'integer strict alias' => ['int:strict', 'float|int|numeric-string|Stringable|true'];

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
        self::assertSame('array{value: 1|float|numeric-string|Stringable|true}', self::resolve([
            'value' => 'required|in:1',
        ]));
        self::assertSame('array{value: 2|float|numeric-string|Stringable}', self::resolve([
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
        self::assertSame($broadType, self::resolveForVersion([
            'value' => 'required|int:strict',
        ], '12.21.0'));
        self::assertSame('array{value: int}', self::resolveForVersion([
            'value' => 'required|integer:strict',
        ], '12.22.0'));
        self::assertSame('array{value: int}', self::resolveForVersion([
            'value' => 'required|int:strict',
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

    public function testPositiveMinimumRefinesKnownStringAndArrayTypes(): void
    {
        self::assertSame('array{value: non-empty-string}', self::resolve([
            'value' => 'required|string|min:1',
        ]));
        self::assertSame('array{value?: string}', self::resolve([
            'value' => 'string|min:1',
        ]));
        self::assertSame('array{value?: non-empty-string}', self::resolve([
            'value' => 'string|min:1',
        ], true));

        self::assertSame('array{value: non-empty-array}', self::resolve([
            'value' => 'required|array|min:1',
        ]));
        self::assertSame('array{value?: non-empty-array|string}', self::resolve([
            'value' => 'array|min:1',
        ]));
        self::assertSame('array{value?: non-empty-array|string|null}', self::resolve([
            'value' => 'nullable|array|min:1',
        ]));
        self::assertSame('array{value: non-empty-array|string}', self::resolve([
            'value' => 'present|array|min:1',
        ]));
    }

    public function testMinimumRefinementRequiresADefinitelyPositiveNumericParameter(): void
    {
        foreach (['0', '-1', 'invalid'] as $minimum) {
            self::assertSame('array{value: array}', self::resolve([
                'value' => 'required|array|min:' . $minimum,
            ]));
        }

        foreach (['+1', ' 1 ', '1e-4000'] as $minimum) {
            self::assertSame('array{value: non-empty-array}', self::resolve([
                'value' => 'required|array|min:' . $minimum,
            ]));
        }

        self::assertSame('array{value: float|int|numeric-string}', self::resolve([
            'value' => 'required|numeric|min:1',
        ]));
        self::assertSame('array{value: mixed}', self::resolve([
            'value' => 'required|min:1',
        ]));

        self::assertSame('array{items?: array<int|string, mixed>}', self::resolve([
            'items' => 'required|array|min:1',
            'items.0' => 'exclude',
        ]));
        self::assertSame('array{items: array{name?: mixed}}', self::resolve([
            'items' => 'required|array:name|min:1',
            'items.name' => 'exclude',
        ]));
        self::assertSame('array{items: array{name: mixed}}', self::resolve([
            'items' => 'required|array:name|min:1',
            'items.name.secret' => 'exclude',
        ]));
        self::assertSame('array{payload?: array{name?: string}}', self::resolve([
            'payload' => 'required|array|min:1',
            'payload.name' => 'sometimes|string',
        ]));
    }

    public function testVersionAwareExtensionsInference(): void
    {
        $file = 'Symfony\\Component\\HttpFoundation\\File\\File';

        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|extensions:txt',
        ], '10.33.0'));
        self::assertSame('array{value: ' . $file . '}', self::resolveForVersion([
            'value' => 'required|extensions:txt',
        ], '10.34.0'));
        self::assertSame('array{value?: string|' . $file . '}', self::resolveForVersion([
            'value' => 'extensions:txt',
        ], '10.34.0'));
        self::assertSame('array{value?: ' . $file . '}', self::resolveForVersion([
            'value' => 'extensions:txt',
        ], '10.34.0', true));
        self::assertSame('array{value?: string|' . $file . '|null}', self::resolveForVersion([
            'value' => 'nullable|extensions:txt',
        ], '10.34.0'));
        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|extensions:txt',
        ], '14.0.0'));
    }

    public function testVersionAwareEncodingInference(): void
    {
        $type = 'array{value: array|bool|float|int|string|Stringable|null}';

        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|encoding:UTF-8',
        ], '12.39.0'));
        self::assertSame($type, self::resolveForVersion([
            'value' => 'required|encoding:UTF-8',
        ], '12.40.0'));
        self::assertSame('array{value?: array|bool|float|int|string|Stringable|null}', self::resolveForVersion([
            'value' => 'encoding:UTF-8',
        ], '12.40.0'));
        self::assertSame('array{value?: array|bool|float|int|string|Stringable|null}', self::resolveForVersion([
            'value' => 'encoding:UTF-8',
        ], '12.40.0', true));
        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|encoding:UTF-8',
        ], '14.0.0'));
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
        self::assertSame('array{value: non-empty-list}', self::resolveForVersion([
            'value' => 'required|list|min:1',
        ], '11.0.3'));
        self::assertSame('array{value?: non-empty-list|string}', self::resolveForVersion([
            'value' => 'list|min:1',
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

        self::assertSame('array{value?: array{name?: mixed}|string}', self::resolveForVersion([
            'value' => 'array:name|list',
        ], '11.0.2'));
        self::assertSame('array{value?: array{}|string}', self::resolveForVersion([
            'value' => 'array:name|list',
        ], '11.0.3'));
    }

    public function testVersionAwareArrayPredicateInference(): void
    {
        foreach (
            [
                'contains:value' => ['11.7.0', '11.8.0'],
                'in_array_keys:value' => ['12.15.0', '12.16.0'],
                'doesnt_contain:value' => ['12.21.0', '12.22.0'],
            ] as $rule => [$before, $introduced]
        ) {
            self::assertSame('array{value: mixed}', self::resolveForVersion([
                'value' => 'required|' . $rule,
            ], $before));
            self::assertSame('array{value: array}', self::resolveForVersion([
                'value' => 'required|' . $rule,
            ], $introduced));
            self::assertSame('array{value?: array|string}', self::resolveForVersion([
                'value' => $rule,
            ], $introduced));
            self::assertSame('array{value?: array}', self::resolveForVersion([
                'value' => $rule,
            ], $introduced, true));
            self::assertSame('array{value?: array|string|null}', self::resolveForVersion([
                'value' => 'nullable|' . $rule,
            ], $introduced));
            self::assertSame('array{value: mixed}', self::resolveForVersion([
                'value' => 'required|' . $rule,
            ], '14.0.0'));
        }
    }

    public function testVersionAwareArrayKeysInference(): void
    {
        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|array_keys:name,email',
        ], '13.23.0'));
        self::assertSame('array{value: array{name?: mixed, email?: mixed}}', self::resolveForVersion([
            'value' => 'required|array_keys:name,email',
        ], '13.24.0'));
        self::assertSame(
            'array{value?: array{name?: mixed, email?: mixed}|string}',
            self::resolveForVersion(['value' => 'array_keys:name,email'], '13.24.0')
        );
        self::assertSame(
            'array{value?: array{name?: mixed, email?: mixed}}',
            self::resolveForVersion(['value' => 'array_keys:name,email'], '13.24.0', true)
        );
        self::assertSame(
            'array{value?: array{name?: mixed, email?: mixed}|string|null}',
            self::resolveForVersion(['value' => 'nullable|array_keys:name,email'], '13.24.0')
        );
        $numericKeys = self::resolveTypeForVersion([
            'value' => 'required|array_keys:0,01',
        ], '13.24.0')->getOffsetValueType(new \PHPStan\Type\Constant\ConstantStringType('value'));
        self::assertTrue(
            $numericKeys->hasOffsetValueType(new \PHPStan\Type\Constant\ConstantIntegerType(0))->maybe()
        );
        self::assertTrue(
            $numericKeys->hasOffsetValueType(new \PHPStan\Type\Constant\ConstantStringType('01'))->maybe()
        );
        self::assertTrue(
            $numericKeys->hasOffsetValueType(new \PHPStan\Type\Constant\ConstantIntegerType(1))->no()
        );

        $emptyParameter = self::resolveTypeForVersion([
            'value' => 'required|array_keys:',
        ], '13.24.0')->getOffsetValueType(new \PHPStan\Type\Constant\ConstantStringType('value'));
        self::assertTrue(
            $emptyParameter->hasOffsetValueType(new \PHPStan\Type\Constant\ConstantStringType(''))->maybe()
        );
        self::assertTrue(
            $emptyParameter->hasOffsetValueType(new \PHPStan\Type\Constant\ConstantStringType('other'))->no()
        );
        self::assertSame('array{value: *NEVER*}', self::resolveForVersion([
            'value' => 'required|array_keys',
        ], '13.24.0'));
        self::assertSame('array{value: mixed}', self::resolveForVersion([
            'value' => 'required|array_keys:name,email',
        ], '14.0.0'));

        self::assertSame('array{user: array{name?: mixed, email?: mixed}}', self::resolveForVersion([
            'user' => 'required|array_keys:name,email',
            'user.name' => 'string',
        ], '13.24.0'));

        self::assertSame('array{value?: array{}|string}', self::resolveForVersion([
            'value' => 'array_keys:name,email|list',
        ], '13.24.0'));
        self::assertSame('array{value: array{0?: mixed}}', self::resolveForVersion([
            'value' => 'required|array_keys:0,2|list',
        ], '13.24.0'));
        self::assertSame('array{value: list{0?: mixed, 1?: mixed}}', self::resolveForVersion([
            'value' => 'required|array_keys:0,1,3|list',
        ], '13.24.0'));
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

        $directRules = [
            'items' => 'required|list',
            'items.*' => 'required|string',
        ];
        self::assertSame(
            'array{items: list<string>}',
            self::resolveForVersion($directRules, '11.22.0')
        );
        self::assertSame(
            'array{items: list<string>}',
            self::resolveForVersion($directRules, '11.23.0')
        );

        foreach (['string', 'nullable|string', 'sometimes|string'] as $childRules) {
            $expectedType = $childRules === 'nullable|string'
                ? 'array{items: list<string|null>}'
                : 'array{items: list<string>}';
            $rules = [
                'items' => 'required|list',
                'items.*' => $childRules,
            ];

            self::assertSame($expectedType, self::resolveForVersion($rules, '11.22.0'));
            self::assertSame($expectedType, self::resolveForVersion($rules, '11.23.0'));
        }

        self::assertSame(
            'array{items?: list<string>|string|null}',
            self::resolveForVersion([
                'items' => 'nullable|list',
                'items.*' => 'string',
            ], '11.23.0')
        );
        self::assertSame(
            'array{items: list<string>|string}',
            self::resolveForVersion([
                'items' => 'present|list',
                'items.*' => 'string',
            ], '11.23.0')
        );
        self::assertSame(
            'array{items?: array<int|string, mixed>}',
            self::resolveForVersion([
                'items' => 'required|list',
                'items.*' => 'exclude_unless:items.*,one|string',
            ], '11.23.0')
        );

        $includedContext = new LaravelVersionContext('', '11.23.0');
        self::assertSame(
            'array{items: list<string>}',
            (new TypeResolver($includedContext, null, true))
                ->evaluate(RuleParser::parse([
                    'items' => 'required|list',
                    'items.*' => 'string',
                ], $includedContext))
                ->describe(VerbosityLevel::precise())
        );

        $optionalDirectRules = [
            'items' => 'list',
            'items.*' => 'required|string',
        ];
        self::assertSame(
            'array{items?: list<string>|string}',
            self::resolveForVersion($optionalDirectRules, '11.22.0')
        );
        self::assertSame(
            'array{items?: list<string>|string}',
            self::resolveForVersion($optionalDirectRules, '11.23.0')
        );
        self::assertSame(
            'array{items: list<string>|string}',
            self::resolveForVersion([
                'items' => 'present|list',
                'items.*' => 'required|string',
            ], '11.23.0')
        );

        self::assertSame(
            'array{items: list{0?: string, 1?: string}}',
            self::resolveForVersion([
                'items' => 'required|list|array:0,1',
                'items.*' => 'required|string',
            ], '11.23.0')
        );
        self::assertSame(
            'array{items: non-empty-list<string>&hasOffset(0)}',
            self::resolveForVersion([
                'items' => 'required|list|required_array_keys:0',
                'items.*' => 'required|string',
            ], '11.23.0')
        );

        $nestedRules = [
            'items' => 'required|list',
            'items.*.id' => 'required|string',
        ];
        self::assertSame(
            'array{items: list}',
            self::resolveForVersion($nestedRules, '11.22.0')
        );
        self::assertSame(
            'array{items: list<array{id: string}>}',
            self::resolveForVersion($nestedRules, '11.23.0')
        );

        self::assertSame(
            'array{items?: array<int|string, array{id?: string}>}',
            self::resolveForVersion([
                'items' => 'required|list',
                'items.*.id' => 'sometimes|string',
            ], '11.23.0')
        );
        self::assertSame(
            'array{items?: array<int, mixed>}',
            self::resolveForVersion([
                'items' => 'required|list',
                'items.0' => 'exclude',
            ], '11.23.0')
        );

        self::assertSame(
            'array{items: array<int|string, array{id?: string, name: string}>}',
            self::resolveForVersion([
                'items' => 'required|list',
                'items.*.id' => 'sometimes|string',
                'items.*.name' => 'required|string',
            ], '11.23.0')
        );

        self::assertSame(
            'array{items: list<array{id: string, tmp?: string}>}',
            self::resolveForVersion([
                'items' => 'required|list',
                'items.*.id' => 'required|string',
                'items.*.tmp' => 'exclude_if:mode,hidden|string',
            ], '11.23.0')
        );

        $conditionalExclusionRules = [
            'items' => 'required|list',
            'items.*.id' => 'required|string|exclude_if:items.*.drop,true',
        ];
        self::assertSame(
            'array{items: array<int|string, mixed>}',
            self::resolveForVersion($conditionalExclusionRules, '11.22.0')
        );
        self::assertSame(
            'array{items?: array<int|string, mixed>}',
            self::resolveForVersion($conditionalExclusionRules, '11.23.0')
        );

        self::assertSame('array{payload: string}', self::resolveForVersion([
            'payload' => 'required|string',
            'payload.child' => 'missing',
        ], '13.24.0'));
    }

    public function testNestedExclusionWideningStopsAtProjectionBoundaries(): void
    {
        $context = new LaravelVersionContext('', '11.23.0');
        $intermediateRules = [
            'user' => 'array',
            'user.profile' => 'array',
            'user.profile.name' => 'exclude_if:mode,hidden|string',
        ];
        self::assertSame(
            'array{user?: array{profile?: array<int|string, mixed>|string}}',
            self::resolveForVersion($intermediateRules, '11.23.0')
        );

        $includedListRules = [
            'items' => 'required|list',
            'items.*.id' => 'required|string',
            'items.*.tmp' => 'exclude_if:mode,hidden|string',
        ];
        self::assertSame(
            'array{items: list}',
            (new TypeResolver($context, null, true))
                ->evaluate(RuleParser::parse($includedListRules, $context))
                ->describe(VerbosityLevel::precise())
        );

        $rawParentRules = [
            'user' => 'array',
            'user.profile.name' => 'exclude_if:mode,hidden|string',
        ];
        self::assertSame(
            'array{user?: array<int|string, mixed>|string}',
            self::resolveForVersion($rawParentRules, '11.23.0')
        );
        self::assertSame(
            'array{user?: array<int|string, mixed>}',
            self::resolveForVersion($rawParentRules, '11.23.0', true)
        );
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

        self::assertSame('array{user?: array<int|string, mixed>}', self::resolve([
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

    public function testNormalizesAnEmptyArrayParameterToTheEmptyStringKey(): void
    {
        self::getContainer();
        $node = RuleParser::parse([
            'value' => 'required|array:',
        ])->resolvePath('value');
        $type = (new TypeResolver())->evaluateLeaf($node);

        self::assertTrue($type->isArray()->yes());
        self::assertTrue($type->hasOffsetValueType(new \PHPStan\Type\Constant\ConstantStringType(''))->maybe());
        self::assertTrue(
            $type->hasOffsetValueType(new \PHPStan\Type\Constant\ConstantStringType('other'))->no()
        );
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
            '1|float|numeric-string|Stringable|true',
            (new TypeResolver())->evaluateLeaf($node)->describe(VerbosityLevel::precise())
        );
    }

    /**
     * @return iterable<string, array{list<scalar|null>, string}>
     */
    public static function numericInParameterProvider(): iterable
    {
        $one = '1|float|numeric-string|Stringable|true';

        yield 'canonical integer' => [['1'], $one];
        yield 'leading zero integer' => [['01'], $one];
        yield 'whitespace integer' => [[' 1 '], $one];
        yield 'explicit positive integer' => [['+1'], $one];
        yield 'integer-valued decimal' => [['1.0'], $one];
        yield 'integer-valued exponent' => [['1e3'], '1000|float|numeric-string|Stringable'];
        yield 'negative exponent integer' => [['-3e0'], '-3|float|numeric-string|Stringable'];
        yield 'negative zero' => [['-0'], '0|float|numeric-string|Stringable'];
        yield 'underflow to zero' => [['1e-4000'], '0|float|numeric-string|Stringable'];
        yield 'fractional parameter' => [['1.5'], 'float|numeric-string|Stringable'];
        yield 'overflowing exponent' => [['1e309'], 'float|numeric-string|Stringable'];
        yield 'non-finite spellings' => [
            ['INF', '-INF', 'NAN'],
            "'-INF'|'INF'|'NAN'|float|Stringable",
        ];
        yield 'maximum native integer' => [
            [(string) PHP_INT_MAX],
            PHP_INT_MAX . '|float|numeric-string|Stringable',
        ];
        yield 'decimal integer outside native range' => [
            [(string) PHP_INT_MAX . '0'],
            'float|numeric-string|Stringable',
        ];
        yield 'unsafe float-sized integer' => [
            ['9007199254740992.0'],
            'float|int|numeric-string|Stringable',
        ];
        yield 'multiple numeric parameters' => [
            ['1', '2.5', '-3e0'],
            '-3|1|float|numeric-string|Stringable|true',
        ];
    }

    /**
     * @dataProvider numericInParameterProvider
     * @param list<scalar|null> $parameters
     */
    public function testNumericInParametersNarrowOnlyRepresentableIntegerClasses(
        array $parameters,
        string $expectedType
    ): void {
        self::getContainer();
        $node = RuleParser::parse([])->resolvePath('value');
        $node->push(Rule::create(Rule::RULE_REQUIRED));
        $node->push(Rule::create('In', $parameters));

        self::assertSame(
            $expectedType,
            (new TypeResolver())->evaluateLeaf($node)->describe(VerbosityLevel::precise())
        );
    }

    public function testFloatOriginInBuilderRetainsBroadIntegerFallback(): void
    {
        self::getContainer();
        self::assertNotSame(
            Rule::create('In', ['2.5'])->getCacheKey(),
            Rule::inBuilder(['2.5'], true)->getCacheKey()
        );
        $node = RuleParser::parse([])->resolvePath('value');
        $node->push(Rule::create(Rule::RULE_REQUIRED));
        $node->push(Rule::inBuilder(['2.5'], true));

        self::assertSame(
            'float|int|numeric-string|Stringable',
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
        return self::resolveTypeForVersion($rules, $laravelVersion, $assumeHttpInputNormalization)
            ->describe(VerbosityLevel::precise());
    }

    /**
     * @param array<string, string> $rules
     */
    private static function resolveTypeForVersion(
        array $rules,
        string $laravelVersion,
        bool $assumeHttpInputNormalization = false
    ): \PHPStan\Type\Type {
        self::getContainer();

        $context = new LaravelVersionContext('', $laravelVersion);
        $resolver = new TypeResolver($context);

        return $resolver->evaluateMap(RuleParser::parse($rules, $context), $assumeHttpInputNormalization);
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
