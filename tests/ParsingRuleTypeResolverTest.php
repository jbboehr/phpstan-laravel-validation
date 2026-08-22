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

use DateTimeImmutable;
use DateTimeZone;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\UnknownRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\GenericParsingRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\MoneyParsingRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\NonImplicitParsingRule;
use jbboehr\PhpstanLaravelValidation\Validation\ParsingRuleTypeResolver;
use jbboehr\PhpstanLaravelValidation\Validation\Rule;
use jbboehr\Rensei\ParsingRule;
use jbboehr\Rensei\Rules\AcceptedRule;
use jbboehr\Rensei\Rules\BaseParsingRule;
use jbboehr\Rensei\Rules\DateTimeRule;
use jbboehr\Rensei\Rules\DeclinedRule;
use jbboehr\Rensei\Rules\FloatRule;
use jbboehr\Rensei\Rules\IntegerRule;
use jbboehr\Rensei\Rules\TimezoneRule;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\IntersectionType;
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
        self::assertSame('float', self::resolve(new ObjectType(FloatRule::class)));
        self::assertSame(
            'DateTimeImmutable',
            self::resolve(new ObjectType(DateTimeRule::class))
        );
        self::assertSame(
            'DateTimeZone',
            self::resolve(new ObjectType(TimezoneRule::class))
        );
        self::assertSame('true', self::resolve(new ObjectType(AcceptedRule::class)));
        self::assertSame('false', self::resolve(new ObjectType(DeclinedRule::class)));
    }

    /**
     * A bound abstract type is declined along with the unbound one.
     *
     * The binding is present, so the produced type is known. Implicitness is
     * not: PHP cannot make an interface require the property, and the
     * expression names no concrete class to read it from. Believing the
     * binding anyway would trust a declaration a non-implicit rule can
     * satisfy, and Laravel skips such a rule for a blank string.
     *
     * This is why `Parse::integer()` returns `IntegerRule` rather than
     * `ParsingRule<int>`.
     */
    public function testDeclinesAnAbstractlyTypedParser(): void
    {
        self::assertNull(self::resolveRule(
            new GenericObjectType(ParsingRule::class, [new IntegerType()])
        ));

        self::assertNull(self::resolveRule(
            new GenericObjectType(BaseParsingRule::class, [new IntegerType()])
        ));

        self::assertNull(self::resolveRule(
            new GenericObjectType(BaseParsingRule::class, [new FloatType()])
        ));

        self::assertNull(self::resolveRule(
            new GenericObjectType(BaseParsingRule::class, [new ObjectType(DateTimeImmutable::class)])
        ));

        self::assertNull(self::resolveRule(
            new GenericObjectType(BaseParsingRule::class, [new ObjectType(DateTimeZone::class)])
        ));

        self::assertNull(self::resolveRule(new ObjectType(BaseParsingRule::class)));
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

    public function testDeclinesAParserThatIsNotImplicit(): void
    {
        // Laravel skips a non-implicit rule for a blank or whitespace-only
        // string, so its produced type would be a promise the runtime breaks.
        self::assertNull(self::resolveRule(new ObjectType(NonImplicitParsingRule::class)));
    }

    public function testDeclinesAParserWhosePropertyShadowsTheImmutableMarker(): void
    {
        self::assertNull(self::resolveRule(new ObjectType(MutableImplicitParsingRule::class)));
    }

    public function testRecognizesAParserInsideAnIntersection(): void
    {
        self::assertSame('int', self::resolve(new IntersectionType([
            new ObjectType(ExtensibleIntegerParsingRule::class),
            new ObjectType(ParsingRuleMarker::class),
        ])));
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

    /**
     * One class name, two answers.
     *
     * The resolver is a single autowired service for a whole analysis, so
     * every parser in the analysed project passes through the same instance.
     * A generic parser binds its produced type at the use site, which means
     * the class name does not determine the answer and cannot stand in for it.
     */
    public function testDistinctBindingsOfOneClassResolveIndependently(): void
    {
        $resolver = new ParsingRuleTypeResolver(self::createReflectionProvider());

        $int = $resolver->resolveRule(
            new GenericObjectType(GenericParsingRule::class, [new IntegerType()])
        );
        $string = $resolver->resolveRule(
            new GenericObjectType(GenericParsingRule::class, [new StringType()])
        );

        self::assertSame('int', self::describe($int));
        self::assertSame('string', self::describe($string));
    }

    /**
     * A declined answer is about the type, not about the class.
     *
     * The unbound form of a generic parser carries no produced type and is
     * declined. Remembering that refusal against the class name would extend
     * it to every bound form seen afterwards, so whether a parser is
     * understood would depend on the order the analysis reached its use sites.
     */
    public function testDecliningAnUnboundFormDoesNotDeclineTheBoundOne(): void
    {
        $resolver = new ParsingRuleTypeResolver(self::createReflectionProvider());

        self::assertNull($resolver->resolveRule(new ObjectType(GenericParsingRule::class)));

        $bound = $resolver->resolveRule(
            new GenericObjectType(GenericParsingRule::class, [new IntegerType()])
        );

        self::assertSame('int', self::describe($bound));
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

    /**
     * The produced type of a rule the caller has already resolved.
     */
    private static function describe(?Rule $rule): string
    {
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

interface ParsingRuleMarker
{
}

/** @extends BaseParsingRule<int> */
class ExtensibleIntegerParsingRule extends BaseParsingRule implements ParsingRuleMarker
{
    public function parse(mixed $value): int
    {
        return 1;
    }

    protected function message(): string
    {
        return 'The :attribute field could not be parsed.';
    }
}

/** @extends BaseParsingRule<int> */
final class MutableImplicitParsingRule extends BaseParsingRule
{
    public bool $implicit = true;

    public function parse(mixed $value): int
    {
        return 1;
    }

    protected function message(): string
    {
        return 'The :attribute field could not be parsed.';
    }
}
