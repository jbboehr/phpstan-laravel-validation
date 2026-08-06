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

        $resolver = new TypeResolver(new LaravelVersionContext('', $laravelVersion));

        return $resolver
            ->evaluateMap(RuleParser::parse($rules), $assumeHttpInputNormalization)
            ->describe(VerbosityLevel::precise());
    }
}
