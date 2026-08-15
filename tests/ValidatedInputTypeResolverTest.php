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

use jbboehr\PhpstanLaravelValidation\Type\ValidatedInputTypeResolver;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDoc\TypeStringResolver;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

final class ValidatedInputTypeResolverTest extends PHPStanTestCase
{
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../extension.neon',
            __DIR__ . '/phpstan.neon',
        ];
    }

    public function testOnlyProjectsNestedOptionalAndNumericPaths(): void
    {
        $container = self::getContainer();
        $payload = $container->getByType(TypeStringResolver::class)->resolve(
            'array{name: string, profile: array{email: string, note?: string}, '
                . 'items: array{0: array{id: int}}}'
        );
        $keys = self::constantPathList([
            'name',
            'profile.email',
            'profile.note',
            'items.0.id',
            'absent',
        ]);
        $keysExpression = new Expr\Array_();
        $scope = self::createMock(Scope::class);
        $scope->expects(self::once())
            ->method('getType')
            ->with($keysExpression)
            ->willReturn($keys);

        $type = $container->getByType(ValidatedInputTypeResolver::class)->resolveOnlyReturnType(
            $payload,
            new Expr\MethodCall(
                new Expr\Variable('validated'),
                new Identifier('only'),
                [new Arg($keysExpression)]
            ),
            $scope
        );

        self::assertSame(
            'array{name: string, profile: array{email: string, note?: string}, '
                . 'items: array{array{id: int}}}',
            $type?->describe(VerbosityLevel::precise())
        );
    }

    public function testOnlyDeclinesWildcardAndDynamicSelectors(): void
    {
        $container = self::getContainer();
        $resolver = $container->getByType(ValidatedInputTypeResolver::class);
        $payload = $container->getByType(TypeStringResolver::class)->resolve(
            'array{items: list<array{id: int}>}'
        );

        foreach (
            [
                'wildcard' => self::constantPathList(['items.*.id']),
                'dynamic' => new ArrayType(new ConstantIntegerType(0), new StringType()),
            ] as $name => $keys
        ) {
            $keysExpression = new Expr\Array_();
            $scope = self::createMock(Scope::class);
            $scope->expects(self::once())
                ->method('getType')
                ->with($keysExpression)
                ->willReturn($keys);

            self::assertNull($resolver->resolveOnlyReturnType(
                $payload,
                new Expr\MethodCall(
                    new Expr\Variable('validated'),
                    new Identifier('only'),
                    [new Arg($keysExpression)]
                ),
                $scope
            ), $name);
        }
    }

    /**
     * @param list<string> $paths
     */
    private static function constantPathList(array $paths): Type
    {
        $builder = ConstantArrayTypeBuilder::createEmpty();
        foreach ($paths as $index => $path) {
            $builder->setOffsetValueType(
                new ConstantIntegerType($index),
                new ConstantStringType($path)
            );
        }

        return $builder->getArray();
    }
}
