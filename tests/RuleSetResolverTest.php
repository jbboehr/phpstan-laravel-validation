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

use jbboehr\PhpstanLaravelValidation\Test\CustomRules\StringableRuleBuilder;
use jbboehr\PhpstanLaravelValidation\Test\CustomRules\UnknownRule;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\VerbosityLevel;

require_once __DIR__ . '/CustomRules/Rules.php';

final class RuleSetResolverTest extends PHPStanTestCase
{
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../extension.neon'];
    }

    public function testResolvesStaticObjectRuleWithoutEvaluatingIt(): void
    {
        $rules = self::constantArray([
            'value' => self::constantArray([
                0 => new ConstantStringType('required'),
                1 => new ObjectType(UnknownRule::class),
            ]),
        ]);

        $trees = $this->resolve($rules);

        self::assertCount(1, $trees);
        self::assertSame(
            'array{value: mixed}',
            self::describe($trees[0])
        );
    }

    public function testPreservesFiniteRuleAlternatives(): void
    {
        $rules = self::constantArray([
            'value' => self::constantArray([
                0 => new ConstantStringType('required'),
                1 => TypeCombinator::union(
                    new ObjectType(UnknownRule::class),
                    new ObjectType(StringableRuleBuilder::class)
                ),
            ]),
        ]);

        $descriptions = array_map(self::describe(...), $this->resolve($rules));
        sort($descriptions);

        self::assertSame([
            'array{value: mixed}',
            'array{value?: mixed}',
        ], $descriptions);
    }

    public function testPreservesOptionalArrayOffsetsAsAlternatives(): void
    {
        $rules = self::constantArray([
            'value' => new ConstantStringType('required|string'),
        ], ['value']);

        $descriptions = array_map(self::describe(...), $this->resolve($rules));
        sort($descriptions);

        self::assertSame([
            'array{value: string}',
            'mixed',
        ], $descriptions);
    }

    public function testFallsBackToOpaqueRulesWhenAlternativesExplode(): void
    {
        $items = [];
        $optional = [];
        for ($index = 0; $index < 8; ++$index) {
            $key = 'value' . $index;
            $items[$key] = new ConstantStringType('string');
            $optional[] = $key;
        }

        $trees = $this->resolve(self::constantArray($items, $optional));

        self::assertCount(1, $trees);
        self::assertTrue($trees[0]->resolvePath('*')->isOpaque());
        self::assertSame('array<int|string, mixed>', self::describe($trees[0]));
    }

    public function testFallsBackToTheConstantExpressionEvaluator(): void
    {
        $expression = new Expr\Array_([
            new Expr\ArrayItem(
                new String_('required|string'),
                new String_('value')
            ),
        ]);

        $trees = $this->resolve(new MixedType(), $expression);

        self::assertCount(1, $trees);
        self::assertSame('array{value: string}', self::describe($trees[0]));
    }

    public function testReturnsNoRulesWhenStaticResolutionAndEvaluationFail(): void
    {
        self::assertSame([], $this->resolve(new MixedType()));
    }

    /**
     * @param array<array-key, Type> $items
     * @param list<array-key> $optionalKeys
     */
    private static function constantArray(array $items, array $optionalKeys = []): Type
    {
        $builder = ConstantArrayTypeBuilder::createEmpty();
        foreach ($items as $key => $value) {
            $keyType = is_int($key)
                ? new ConstantIntegerType($key)
                : new ConstantStringType($key);
            $builder->setOffsetValueType($keyType, $value, in_array($key, $optionalKeys, true));
        }

        return $builder->getArray();
    }

    /**
     * @return list<\jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode>
     */
    private function resolve(Type $type, ?Expr $expression = null): array
    {
        $expression ??= new Expr\Variable('rules');
        $scope = $this->createMock(Scope::class);
        $scope->method('getType')->with($expression)->willReturn($type);

        return self::getContainer()
            ->getByType(RuleSetResolver::class)
            ->resolve($expression, $scope);
    }

    private static function describe(
        \jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode $tree
    ): string {
        return self::getContainer()
            ->getByType(TypeResolver::class)
            ->evaluate($tree)
            ->describe(VerbosityLevel::precise());
    }
}
