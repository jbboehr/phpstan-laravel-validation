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

use jbboehr\PhpstanLaravelValidation\Validation\Rule;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;
use PHPUnit\Framework\TestCase;

/**
 * The parsing rule channel in the analyzer, driven by hand-built rule trees.
 *
 * Discovery of a produced type from a real rule object is covered separately;
 * these cases pin what the resolver does once it has one.
 */
final class ParsingRuleTypeTest extends TestCase
{
    public function testAProducedTypeReplacesRatherThanConstrains(): void
    {
        // A predicate would intersect: string ∩ int is never. A parsing rule
        // replaces, because the value it describes is the one it produced.
        $node = self::treeFor(['string', Rule::parsing(new IntegerType())]);

        self::assertSame('int', self::describe((new TypeResolver())->evaluateLeaf($node)));
    }

    public function testAProducedTypeSupersedesAgreeingPredicates(): void
    {
        $node = self::treeFor(['integer', Rule::parsing(new IntegerType())]);

        self::assertSame('int', self::describe((new TypeResolver())->evaluateLeaf($node)));
    }

    public function testAParsingRuleSuppressesTheBlankStringBypass(): void
    {
        // A parsing rule is implicit at runtime, so Laravel does not skip it
        // for a blank string and the raw string cannot survive into output.
        $node = self::treeFor([Rule::parsing(new IntegerType())]);

        self::assertFalse($node->allowsBlankStringBypass());
        self::assertSame('int', self::describe((new TypeResolver())->evaluateLeaf($node)));
    }

    public function testAnOrdinaryCustomRuleStillAllowsTheBlankStringBypass(): void
    {
        $node = self::treeFor([Rule::custom(new IntegerType())]);

        self::assertTrue($node->allowsBlankStringBypass());
    }

    public function testTwoParsingRulesUnionTheirProducedTypes(): void
    {
        $node = self::treeFor([
            Rule::parsing(new IntegerType()),
            Rule::parsing(new StringType()),
        ]);

        self::assertSame('int|string', self::describe((new TypeResolver())->evaluateLeaf($node)));
    }

    public function testNodesWithoutAParsingRuleReportNoProducedType(): void
    {
        $node = self::treeFor(['integer']);

        self::assertNull($node->getProducedType());
    }

    public function testNodesWithAParsingRuleReportTheProducedType(): void
    {
        $node = self::treeFor([Rule::parsing(new IntegerType())]);

        self::assertSame('int', self::describe($node->getProducedType()));
    }

    public function testAnUnresolvedParsingRuleProducesMixedRatherThanRetainingPredicateTypes(): void
    {
        $node = self::treeFor(['string', Rule::unresolvedParsing()]);

        self::assertTrue($node->hasParsingRule());
        self::assertNull($node->getProducedType());
        self::assertSame('mixed', self::describe((new TypeResolver())->evaluateLeaf($node)));
    }

    public function testAParsingRuleDoesNotConstrainTheCallerInput(): void
    {
        // The parsed value lands in the validator's own copy of the data. The
        // caller's array keeps the original representation, so refining it by
        // the produced type would claim an impossible type -- string ∩ int.
        $tree = self::rootFor(['value' => [Rule::parsing(new IntegerType())]]);

        $inputType = (new TypeResolver())->refineSuccessfulDirectInput(
            $tree,
            self::stringShape()
        );

        self::assertSame(self::describe(self::stringShape()), self::describe($inputType));
    }

    public function testAParsingRuleContributesNoPresenceClaim(): void
    {
        $node = self::treeFor([Rule::parsing(new IntegerType())]);

        self::assertTrue($node->isOptional());
    }

    public function testTheProducedTypeParticipatesInTheCacheKey(): void
    {
        // Otherwise PHPStan's result cache would serve a type from before the
        // parser's produced type changed.
        $integer = Rule::parsing(new IntegerType());
        $string = Rule::parsing(new StringType());

        self::assertNotSame($integer->getCacheKey(), $string->getCacheKey());
        self::assertSame($integer->getCacheKey(), Rule::parsing(new IntegerType())->getCacheKey());
    }

    public function testAProducedTypeIsDistinctFromAnAcceptedType(): void
    {
        $parsing = Rule::parsing(new IntegerType());
        $custom = Rule::custom(new IntegerType());

        self::assertNull($parsing->getAcceptedType());
        self::assertNull($custom->getProducedType());
        self::assertNotSame($parsing->getCacheKey(), $custom->getCacheKey());
    }

    /**
     * `__Parse` is unreachable from a string rule: normalizeName() strips the
     * underscores, so no user rule name can acquire parsing behavior.
     */
    public function testTheParseSentinelCannotBeSpelledAsAStringRule(): void
    {
        self::assertNotSame(Rule::RULE_PARSE, RuleParser::normalizeName(Rule::RULE_PARSE));
        self::assertSame('Parse', RuleParser::normalizeName(Rule::RULE_PARSE));
    }

    /**
     * @param list<mixed> $rules
     */
    private static function treeFor(array $rules, string $path = 'value'): \jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode
    {
        return RuleParser::parse([$path => $rules])->resolvePath($path);
    }

    /**
     * @param array<string, mixed> $rules
     */
    private static function rootFor(array $rules): \jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode
    {
        return RuleParser::parse($rules);
    }

    private static function stringShape(): Type
    {
        $builder = \PHPStan\Type\Constant\ConstantArrayTypeBuilder::createEmpty();
        $builder->setOffsetValueType(
            new \PHPStan\Type\Constant\ConstantStringType('value'),
            new StringType()
        );

        return $builder->getArray();
    }

    private static function describe(?Type $type): string
    {
        self::assertNotNull($type);

        return $type->describe(VerbosityLevel::precise());
    }
}
