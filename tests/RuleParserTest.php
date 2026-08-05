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
use jbboehr\PhpstanLaravelValidation\Validation\InvalidRuleException;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use PHPUnit\Framework\TestCase;

final class RuleParserTest extends TestCase
{
    public function testExplodesPipeSeparatedRules(): void
    {
        $rules = RuleParser::explodeRules('required|string');

        self::assertCount(2, $rules);
        self::assertSame(Rule::RULE_REQUIRED, $rules[0]->getRuleName());
        self::assertSame('String', $rules[1]->getRuleName());
    }

    public function testParsesAssociativeArrayRuleAndParameters(): void
    {
        $rule = RuleParser::parseRule([
            'rule' => 'in',
            'first' => 'one',
            'second' => 'two',
        ]);

        self::assertInstanceOf(Rule::class, $rule);
        self::assertSame('In', $rule->getRuleName());
        self::assertSame(['one', 'two'], $rule->getParameters());
    }

    public function testArrayRuleRequiresAStringName(): void
    {
        self::assertNull(RuleParser::parseArrayRule([]));
        self::assertNull(RuleParser::parseArrayRule([1, 'parameter']));

        $rule = RuleParser::parseArrayRule(['required', 'parameter']);
        self::assertInstanceOf(Rule::class, $rule);
        self::assertSame(Rule::RULE_REQUIRED, $rule->getRuleName());
        self::assertSame(['parameter'], $rule->getParameters());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function regexRuleProvider(): iterable
    {
        yield 'regex' => ['REGEX'];
        yield 'not regex with underscore' => ['NOT_REGEX'];
        yield 'not regex without underscore' => ['NOTREGEX'];
    }

    /**
     * @dataProvider regexRuleProvider
     */
    public function testRegexParameterIsNotParsedAsCsv(string $ruleName): void
    {
        $rule = RuleParser::parseStringRule($ruleName . ':/^one,two:https$/');

        self::assertSame(['/^one,two:https$/'], $rule->getParameters());
    }

    public function testNormalizesHyphenatedAndUnderscoredNames(): void
    {
        self::assertSame('RequiredWithoutAll', RuleParser::normalizeName('required-without_all'));
    }

    public function testNonArrayRuleMapProducesAnEmptyTree(): void
    {
        self::assertCount(0, RuleParser::parse(null));
    }

    public function testRejectsNonStringRulePath(): void
    {
        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionMessage('Invalid rule path: 0');

        RuleParser::parse([0 => 'required']);
    }

    public function testRejectsInvalidRuleDefinition(): void
    {
        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionMessage('Invalid rule definition: true');

        RuleParser::explodeRules(true);
    }

    public function testRejectsInvalidRuleType(): void
    {
        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionMessage('Invalid rule type: boolean true');

        RuleParser::parseRule(true);
    }
}
