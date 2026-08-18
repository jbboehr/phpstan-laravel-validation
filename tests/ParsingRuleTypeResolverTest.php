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

use jbboehr\PhpstanLaravelValidation\Test\CustomRules\UnknownRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\MoneyParsingRule;
use jbboehr\PhpstanLaravelValidation\Validation\ParsingRuleTypeResolver;
use jbboehr\PhpstanLaravelValidation\Validation\Rule;
use jbboehr\Rensei\ParsingRule;
use jbboehr\Rensei\Rules\IntegerRule;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\VerbosityLevel;

require_once __DIR__ . '/CustomRules/Rules.php';

/**
 * Discovery of a parser's produced type from its generic binding.
 *
 * The point of these cases is that no concrete rule class is named anywhere
 * in the resolver: a parser defined outside this package is understood on the
 * same terms as one shipped with it.
 */
final class ParsingRuleTypeResolverTest extends PHPStanTestCase
{
    public function testReadsTheProducedTypeFromAConcreteParser(): void
    {
        // The shape produced by `new IntegerRule()` at a call site.
        self::assertSame('int', self::resolve(new ObjectType(IntegerRule::class)));
    }

    public function testReadsTheProducedTypeFromTheDeclaredInterface(): void
    {
        // The shape produced by `Parse::integer()`, whose return type is
        // documented as ParsingRule<int>.
        self::assertSame('int', self::resolve(
            new GenericObjectType(ParsingRule::class, [new IntegerType()])
        ));
    }

    public function testReadsTheProducedTypeOfAThirdPartyParser(): void
    {
        // Defined in tests, never named by the resolver.
        self::assertSame(
            'non-empty-string',
            self::resolve(new ObjectType(MoneyParsingRule::class))
        );
    }

    public function testUnionsTheProducedTypesOfSeveralParsers(): void
    {
        self::assertSame('int|non-empty-string', self::resolve(TypeCombinator::union(
            new ObjectType(IntegerRule::class),
            new ObjectType(MoneyParsingRule::class)
        )));
    }

    public function testDeclinesTheBareInterface(): void
    {
        // Without a bound argument the template resolves to its default,
        // which carries no information. Declining keeps the attribute on the
        // conservative predicate path rather than claiming parsing semantics
        // -- and with them the loss of the blank-string union -- for a rule
        // whose produced type is unknown.
        self::assertNull(self::resolveRule(new ObjectType(ParsingRule::class)));
    }

    public function testDeclinesAnOrdinaryRule(): void
    {
        self::assertNull(self::resolveRule(new ObjectType(UnknownRule::class)));
    }

    public function testDeclinesAUnionThatIsNotEntirelyParsers(): void
    {
        self::assertNull(self::resolveRule(TypeCombinator::union(
            new ObjectType(IntegerRule::class),
            new ObjectType(UnknownRule::class)
        )));
    }

    public function testDeclinesNonObjects(): void
    {
        self::assertNull(self::resolveRule(new StringType()));
    }

    /**
     * The template name is read by string. If the interface ever renames it,
     * discovery silently stops working, so pin it here.
     */
    public function testTheProducedTypeTemplateIsNamedT(): void
    {
        $reflection = self::createReflectionProvider()->getClass(ParsingRule::class);

        self::assertSame(['T'], array_keys($reflection->getTemplateTypeMap()->getTypes()));
    }

    private static function resolve(Type $type): string
    {
        $rule = self::resolveRule($type);

        self::assertNotNull($rule);
        self::assertSame(Rule::RULE_PARSE, $rule->getRuleName());

        $produced = $rule->getProducedType();
        self::assertNotNull($produced);

        return $produced->describe(VerbosityLevel::precise());
    }

    private static function resolveRule(Type $type): ?Rule
    {
        return (new ParsingRuleTypeResolver(self::createReflectionProvider()))->resolveRule($type);
    }
}
