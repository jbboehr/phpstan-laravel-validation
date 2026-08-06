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

use ErrorException;
use jbboehr\PhpstanLaravelValidation\Validation\Rule;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode;
use PHPUnit\Framework\TestCase;

final class RuleTreeNodeTest extends TestCase
{
    public function testResolvesSimplePathWithoutWarnings(): void
    {
        set_error_handler(
            static function (int $severity, string $message, string $file, int $line): never {
                throw new ErrorException($message, 0, $severity, $file, $line);
            }
        );

        try {
            $node = (new RuleTreeNode(''))->resolvePath('person');
        } finally {
            restore_error_handler();
        }

        self::assertSame('person', $node->getPath());
    }

    /**
     * @return iterable<string, array{string, bool}>
     */
    public static function blankStringBypassProvider(): iterable
    {
        yield 'optional rule' => ['email', true];
        yield 'sometimes' => ['sometimes|email', true];
        yield 'nullable' => ['nullable|email', true];
        yield 'present' => ['present|email', true];
        yield 'conditional required' => ['required_if:other,value|email', true];
        yield 'conditional accepted' => ['accepted_if:other,value', true];
        yield 'conditional declined' => ['declined_if:other,value', true];
        yield 'optional array in' => ['array|in:foo,bar', true];

        yield 'required' => ['required|email', false];
        yield 'accepted' => ['accepted', false];
        yield 'declined' => ['declined', false];
        yield 'filled' => ['filled|email', false];
        yield 'missing' => ['missing', false];
        yield 'sometimes filled' => ['sometimes|filled|email', false];
        yield 'required array in' => ['required|array|in:foo,bar', false];
    }

    /**
     * @dataProvider blankStringBypassProvider
     */
    public function testAllowsBlankStringBypass(string $rules, bool $expected): void
    {
        $tree = RuleParser::parse(['value' => $rules]);
        $node = $tree->resolvePath('value');

        self::assertSame($expected, $node->allowsBlankStringBypass());
    }

    public function testRequiredWildcardDescendantDoesNotRequireParent(): void
    {
        $tree = RuleParser::parse(['names.*.first' => 'required|string']);

        self::assertTrue($tree->resolvePath('names')->isOptional());
        self::assertFalse($tree->resolvePath('names.*')->isOptional());
        self::assertFalse($tree->resolvePath('names.*.first')->isOptional());
    }

    public function testExplicitRequiredWildcardParentRemainsRequired(): void
    {
        $tree = RuleParser::parse([
            'names' => 'required|array',
            'names.*.first' => 'required|string',
        ]);

        self::assertFalse($tree->resolvePath('names')->isOptional());
    }

    public function testRequiredNamedDescendantRequiresParent(): void
    {
        $tree = RuleParser::parse(['names.named.first' => 'required|string']);

        self::assertFalse($tree->resolvePath('names')->isOptional());
        self::assertFalse($tree->resolvePath('names.named')->isOptional());
    }

    public function testNestedWildcardsStopRequirednessAtEachWildcardBoundary(): void
    {
        $tree = RuleParser::parse(['people.*.cars.*.model' => 'required|string']);

        self::assertTrue($tree->resolvePath('people')->isOptional());
        self::assertTrue($tree->resolvePath('people.*.cars')->isOptional());
        self::assertFalse($tree->resolvePath('people.*.cars.*')->isOptional());
        self::assertFalse($tree->resolvePath('people.*.cars.*.model')->isOptional());
    }

    public function testRequiredNamedChildStillPropagatesAlongsideWildcardChild(): void
    {
        $tree = RuleParser::parse([
            'items.*.name' => 'required|string',
            'items.named.label' => 'required|string',
        ]);

        self::assertFalse($tree->resolvePath('items')->isOptional());
        self::assertFalse($tree->resolvePath('items.*.name')->isOptional());
        self::assertFalse($tree->resolvePath('items.named.label')->isOptional());
    }

    public function testResolvedPathsRetainTheirFullNames(): void
    {
        $tree = RuleParser::parse([
            'person.name.first' => 'required|string',
            'settings.timezone\.name' => 'string',
        ]);

        self::assertSame('person', $tree->resolvePath('person')->getPath());
        self::assertSame('person.name', $tree->resolvePath('person.name')->getPath());
        self::assertSame('person.name.first', $tree->resolvePath('person.name.first')->getPath());
        self::assertSame('settings.timezone.name', $tree->resolvePath('settings.timezone\.name')->getPath());

        $escapedSegment = $tree->resolvePath('settings.timezone\.name.label');
        self::assertSame('settings.timezone.name.label', $escapedSegment->getPath());
    }

    public function testReportsWhetherItHasChildren(): void
    {
        $tree = RuleParser::parse([]);

        self::assertFalse($tree->hasChildren());
        $tree->resolvePath('child');
        self::assertTrue($tree->hasChildren());
    }

    public function testLeafOptionalityResolutionReturnsItsState(): void
    {
        $required = RuleParser::parse(['value' => 'required'])->resolvePath('value');
        $sometimes = RuleParser::parse(['value' => 'required|sometimes'])->resolvePath('value');

        self::assertFalse($required->resolveOptional());
        self::assertTrue($sometimes->resolveOptional());
    }

    public function testNullableDoesNotOverrideRequirednessRegardlessOfRuleOrder(): void
    {
        foreach (['required|nullable|string', 'nullable|required|string'] as $rules) {
            $node = RuleParser::parse(['value' => $rules])->resolvePath('value');

            self::assertFalse($node->isOptional());
            self::assertTrue($node->isNullable());
            self::assertFalse($node->allowsNull());
        }
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function conditionalAcceptanceRuleProvider(): iterable
    {
        yield 'accepted if' => ['accepted_if:other,value'];
        yield 'declined if' => ['declined_if:other,value'];
    }

    /**
     * @dataProvider conditionalAcceptanceRuleProvider
     */
    public function testConditionalAcceptanceDoesNotOverrideRequiredness(string $conditionalRule): void
    {
        $node = RuleParser::parse([
            'value' => 'required|' . $conditionalRule,
        ])->resolvePath('value');

        self::assertFalse($node->isOptional());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function conditionalExclusionRuleProvider(): iterable
    {
        yield 'exclude if' => ['exclude_if:other,value'];
        yield 'exclude unless' => ['exclude_unless:other,value'];
        yield 'exclude with' => ['exclude_with:other'];
        yield 'exclude without' => ['exclude_without:other'];
    }

    /**
     * @dataProvider conditionalExclusionRuleProvider
     */
    public function testConditionalExclusionOverridesRequiredness(string $conditionalRule): void
    {
        $node = RuleParser::parse([
            'value' => 'required|' . $conditionalRule,
        ])->resolvePath('value');

        self::assertTrue($node->isOptional());
    }

    public function testRequirednessPropagationIsIndependentOfChildOrder(): void
    {
        $optionalThenRequired = RuleParser::parse([
            'parent.optional' => 'string',
            'parent.required' => 'required|string',
        ]);
        $requiredThenOptional = RuleParser::parse([
            'parent.required' => 'required|string',
            'parent.optional' => 'string',
        ]);

        self::assertFalse($optionalThenRequired->resolvePath('parent')->isOptional());
        self::assertFalse($requiredThenOptional->resolvePath('parent')->isOptional());
    }

    public function testParentWithRequiredChildDoesNotAllowBlankStringBypass(): void
    {
        $parent = RuleParser::parse([
            'parent.child' => 'required|string',
        ])->resolvePath('parent');

        self::assertFalse($parent->allowsBlankStringBypass());
    }

    public function testInsertsRuleAtPath(): void
    {
        $tree = new RuleTreeNode('');

        self::assertSame($tree, $tree->insert('person.name', Rule::create(Rule::RULE_REQUIRED)));
        self::assertSame(
            [Rule::RULE_REQUIRED],
            array_map(
                static fn (Rule $rule): string => $rule->getRuleName(),
                $tree->resolvePath('person.name')->getRules()
            )
        );
    }
}
