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

use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Validation\InvalidRuleException;
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
            'active_url', 'alpha', 'alpha_dash', 'alpha_num', 'current_password', 'date_format:Y-m-d',
            'email', 'ip', 'ipv4', 'ipv6', 'json', 'mac_address', 'timezone', 'url', 'ulid', 'uuid',
            ] as $rule
        ) {
            yield $rule => [$rule, 'non-empty-string'];
        }

        foreach (['after:today', 'before:today', 'before_or_equal:today', 'date', 'date_equals:today'] as $rule) {
            yield $rule => [$rule, 'DateTimeInterface|non-empty-string'];
        }

        foreach (['ascii', 'lowercase', 'string', 'uppercase'] as $rule) {
            yield $rule => [$rule, 'string'];
        }

        foreach (['regex:/foo/', 'not_regex:/foo/'] as $rule) {
            yield $rule => [$rule, 'bool|float|int|string'];
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

        yield 'integer' => ['integer', 'int|numeric-string'];

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

    public function testRejectsWildcardMixedWithNamedChildren(): void
    {
        self::getContainer();
        $node = RuleParser::parse([
            'items.*' => 'string',
            'items.named' => 'string',
        ])->resolvePath('items');

        $this->expectException(ShouldNotHappenException::class);
        (new TypeResolver())->evaluateWildcard($node);
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
    private static function resolve(array $rules): string
    {
        self::getContainer();

        return (new TypeResolver())
            ->evaluateMap(RuleParser::parse($rules))
            ->describe(VerbosityLevel::precise());
    }
}
