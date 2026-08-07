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

use jbboehr\PhpstanLaravelValidation\Test\CustomRules\InvokableIntegerRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\LegacyIntegerRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\StringableRuleBuilder;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\UnknownRule;
use jbboehr\PhpstanLaravelValidation\Validation\CustomRuleTypeResolver;
use PHPStan\Type\ObjectType;
use PHPStan\Type\VerbosityLevel;

require_once __DIR__ . '/CustomRules/Rules.php';

final class CustomRulesInferenceTest extends \PHPStan\Testing\PHPStanTestCase
{
    /**
     * @dataProvider predicateRuleProvider
     */
    public function testRecognizesLaravelPredicateRule(string $className): void
    {
        $resolver = self::getContainer()->getByType(CustomRuleTypeResolver::class);

        self::assertTrue($resolver->isPredicateType(new ObjectType($className)));
    }

    /**
     * @return iterable<string, array{class-string}>
     */
    public static function predicateRuleProvider(): iterable
    {
        yield 'modern validation rule' => [UnknownRule::class];
        yield 'legacy validation rule' => [LegacyIntegerRule::class];
        yield 'legacy invokable rule' => [InvokableIntegerRule::class];
    }

    public function testUnknownPredicateDefaultsToMixed(): void
    {
        $resolver = self::getContainer()->getByType(CustomRuleTypeResolver::class);
        $rule = $resolver->resolveRule(new ObjectType(UnknownRule::class));

        self::assertSame('__Custom', $rule->getRuleName());
        self::assertSame('mixed', $rule->getAcceptedType()?->describe(VerbosityLevel::precise()));
    }

    public function testNonPredicateRuleBuilderIsOpaque(): void
    {
        $resolver = self::getContainer()->getByType(CustomRuleTypeResolver::class);
        $rule = $resolver->resolveRule(new ObjectType(StringableRuleBuilder::class));

        self::assertSame('__Opaque', $rule->getRuleName());
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../extension.neon'];
    }
}
