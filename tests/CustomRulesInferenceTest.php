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

use jbboehr\PhpstanLaravelValidation\Test\CustomRules\AttributeRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\DuplicatePhpDocRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\InvalidAttributeRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\InvokableIntegerRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\IntegerRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\LegacyIntegerRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\PhpDocRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\PrecedenceRule;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\StringableRuleBuilder;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\UnknownRule;
use jbboehr\PhpstanLaravelValidation\Validation\CustomRuleTypeResolver;
use jbboehr\PhpstanLaravelValidation\Validation\InvalidCustomRuleContractException;
use jbboehr\PhpstanLaravelValidation\Validation\Rule;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\PhpDoc\TypeStringResolver;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\VerbosityLevel;

require_once __DIR__ . '/CustomRules/Rules.php';

final class CustomRulesInferenceTest extends \PHPStan\Testing\TypeInferenceTestCase
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

    public function testConfiguredClassContractNarrowsAcceptedValues(): void
    {
        $rule = $this->createResolver([
            IntegerRule::class => 'int',
        ])->resolveRule(new ObjectType(IntegerRule::class));

        self::assertSame('int', $rule->getAcceptedType()?->describe(VerbosityLevel::precise()));
    }

    /**
     * @dataProvider declaredContractProvider
     */
    public function testResolvesDeclaredContract(string $className, string $expectedType): void
    {
        $rule = $this->createResolver()->resolveRule(new ObjectType($className));

        self::assertSame(
            $expectedType,
            $rule->getAcceptedType()?->describe(VerbosityLevel::precise())
        );
    }

    /**
     * @return iterable<string, array{class-string, string}>
     */
    public static function declaredContractProvider(): iterable
    {
        yield 'attribute' => [AttributeRule::class, 'non-empty-string'];
        yield 'PHPDoc' => [PhpDocRule::class, 'int<1, max>'];
    }

    public function testConfiguredContractPrecedesAttributeAndPhpDoc(): void
    {
        $declaredRule = $this->createResolver()->resolveRule(new ObjectType(PrecedenceRule::class));
        $configuredRule = $this->createResolver([
            PrecedenceRule::class => 'bool',
        ])->resolveRule(new ObjectType(PrecedenceRule::class));

        self::assertSame(
            'string',
            $declaredRule->getAcceptedType()?->describe(VerbosityLevel::precise())
        );
        self::assertSame(
            'bool',
            $configuredRule->getAcceptedType()?->describe(VerbosityLevel::precise())
        );
    }

    public function testRejectsInvalidAttributeType(): void
    {
        $this->expectException(InvalidCustomRuleContractException::class);
        $this->createResolver()->resolveRule(new ObjectType(InvalidAttributeRule::class));
    }

    public function testRejectsDuplicatePhpDocContracts(): void
    {
        $this->expectException(InvalidCustomRuleContractException::class);
        $this->createResolver()->resolveRule(new ObjectType(DuplicatePhpDocRule::class));
    }

    public function testRejectsInvalidConfiguredType(): void
    {
        $this->expectException(InvalidCustomRuleContractException::class);
        $this->createResolver([], ['custom' => 'array{']);
    }

    public function testRejectsNormalizedNameCollision(): void
    {
        $this->expectException(InvalidCustomRuleContractException::class);
        $this->createResolver([], ['custom_rule' => 'int', 'custom-rule' => 'string']);
    }

    public function testRejectsBlankConfiguredName(): void
    {
        $this->expectException(InvalidCustomRuleContractException::class);
        $this->createResolver([], ['   ' => 'int']);
    }

    public function testRejectsBlankConfiguredClass(): void
    {
        $this->expectException(InvalidCustomRuleContractException::class);
        $this->createResolver(['\\' => 'int']);
    }

    public function testRejectsBlankConfiguredType(): void
    {
        $this->expectException(InvalidCustomRuleContractException::class);
        $this->createResolver([], ['custom' => '   ']);
    }

    public function testRejectsNormalizedClassCollision(): void
    {
        $this->expectException(InvalidCustomRuleContractException::class);
        $this->createResolver([
            UnknownRule::class => 'int',
            '\\' . UnknownRule::class => 'string',
        ]);
    }

    /**
     * @dataProvider builtInNameProvider
     */
    public function testRejectsBuiltInNameCollision(string $name): void
    {
        $this->expectException(InvalidCustomRuleContractException::class);
        $this->createResolver([], [$name => 'int']);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function builtInNameProvider(): iterable
    {
        yield 'core rule' => ['required'];
        yield 'Laravel 13 rule' => ['array_keys'];
        yield 'otherwise easy to mistake for a custom alias' => ['extensions'];
    }

    public function testEveryParsedBuiltInRuleNameIsReservedFromCustomAliases(): void
    {
        foreach (Rule::RULES as $ruleName) {
            self::assertTrue(
                TypeResolver::isBuiltInRuleName($ruleName),
                sprintf('Built-in rule %s is missing from the custom-alias collision guard', $ruleName)
            );
        }
    }

    public function testEveryInstalledLaravelAttributeRuleNameIsReservedFromCustomAliases(): void
    {
        $reflection = new \ReflectionClass(\Illuminate\Validation\Concerns\ValidatesAttributes::class);

        foreach ($reflection->getMethods() as $method) {
            if (preg_match('/^validate([A-Z].*)$/', $method->getName(), $matches) !== 1) {
                continue;
            }

            self::assertTrue(
                TypeResolver::isBuiltInRuleName($matches[1]),
                sprintf(
                    'Installed Laravel rule %s is missing from the custom-alias collision guard',
                    $matches[1]
                )
            );
        }
    }

    public function testRejectsConfiguredNonPredicateClassWhenEncountered(): void
    {
        $this->expectException(InvalidCustomRuleContractException::class);
        $this->createResolver([StringableRuleBuilder::class => 'int'])
            ->resolveRule(new ObjectType(StringableRuleBuilder::class));
    }

    /**
     * @param array<string, string> $classes
     * @param array<string, string> $names
     */
    private function createResolver(array $classes = [], array $names = []): CustomRuleTypeResolver
    {
        return new CustomRuleTypeResolver(
            self::getContainer()->getByType(TypeStringResolver::class),
            self::getContainer()->getByType(ReflectionProvider::class),
            $classes,
            $names
        );
    }

    /**
     * @return iterable<mixed>
     */
    public static function dataFileAsserts(): iterable
    {
        yield from self::gatherAssertTypes(__DIR__ . '/custom-rules/inference.php');
    }

    /**
     * @dataProvider dataFileAsserts
     */
    public function testFileAsserts(
        string $assertType,
        string $file,
        mixed ...$args
    ): void {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../extension.neon',
            __DIR__ . '/custom-rules/phpstan.neon',
        ];
    }
}
