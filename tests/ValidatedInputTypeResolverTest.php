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

use jbboehr\PhpstanLaravelValidation\Extension\CallArgumentResolver;
use jbboehr\PhpstanLaravelValidation\Type\ValidatedInputTypeResolver;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestTypeRegistry;
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
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

    public function testOnlyNormalizesNumericPathSegmentsForLists(): void
    {
        $type = $this->resolvePaths(
            'only',
            'array{items: non-empty-list<array{id: string, name: string}>}',
            ['items.0.id']
        );

        self::assertSame(
            'array{items: array{array{id: string}}}',
            $type?->describe(VerbosityLevel::precise())
        );
    }

    public function testExceptPrefersAnExactDottedKeyOverNestedTraversal(): void
    {
        $type = $this->resolvePaths(
            'except',
            "array{'profile.note': string, profile: array{note: string, email: int}}",
            ['profile.note']
        );

        self::assertSame(
            'array{profile: array{note: string, email: int}}',
            $type?->describe(VerbosityLevel::precise())
        );
    }

    public function testExceptAccountsForAnOptionalExactDottedKey(): void
    {
        $type = $this->resolvePaths(
            'except',
            "array{'profile.note'?: string, profile: array{note: string, email: int}}",
            ['profile.note']
        );

        self::assertSame(
            'array{profile: array{note?: string, email: int}}',
            $type?->describe(VerbosityLevel::precise())
        );
    }

    public function testExceptNormalizesNumericPathSegmentsForLists(): void
    {
        $container = self::getContainer();
        $type = $this->resolvePaths(
            'except',
            'array{items: non-empty-list<array{id: string, name: string}>}',
            ['items.0.id']
        );

        self::assertNotNull($type);
        $itemsType = $type->getOffsetValueType(new ConstantStringType('items'));
        self::assertTrue($itemsType->hasOffsetValueType(new ConstantIntegerType(0))->yes());
        $firstItemType = $itemsType->getOffsetValueType(new ConstantIntegerType(0));
        self::assertFalse(
            $firstItemType->hasOffsetValueType(new ConstantStringType('id'))->yes(),
            $type->describe(VerbosityLevel::precise())
        );
        self::assertTrue(
            $firstItemType->hasOffsetValueType(new ConstantStringType('name'))->yes(),
            $type->describe(VerbosityLevel::precise())
        );
        self::assertTrue(
            $type->isSuperTypeOf($container->getByType(TypeStringResolver::class)->resolve(
                'array{items: array{array{name: string}, array{id: string, name: string}}}'
            ))->yes(),
            $type->describe(VerbosityLevel::precise())
        );
    }

    public function testExceptAllowsAListToBecomeSparseAfterRemovingAnElement(): void
    {
        $container = self::getContainer();
        $type = $this->resolvePaths(
            'except',
            'array{items: non-empty-list<string>}',
            ['items.0']
        );

        self::assertNotNull($type);
        self::assertTrue(
            $type->isSuperTypeOf($container->getByType(TypeStringResolver::class)->resolve(
                'array{items: array{1: string}}'
            ))->yes(),
            $type->describe(VerbosityLevel::precise())
        );
    }

    public function testExceptPreservesAnOptionalNestedParent(): void
    {
        $type = $this->resolvePaths(
            'except',
            'array{name: string, address?: array{street: string, city: string}}',
            ['address.street']
        );

        self::assertSame(
            'array{name: string, address?: array{city: string}}',
            $type?->describe(VerbosityLevel::precise())
        );
    }

    public function testExceptPreservesAnOptionalNumericParent(): void
    {
        $container = self::getContainer();
        $type = $this->resolvePaths(
            'except',
            'array{items: array{0?: array{id: string, name: string}}}',
            ['items.0.id']
        );

        self::assertNotNull($type);
        self::assertTrue(
            $type->isSuperTypeOf($container->getByType(TypeStringResolver::class)->resolve(
                'array{items: array{}}'
            ))->yes(),
            $type->describe(VerbosityLevel::precise())
        );
        self::assertTrue(
            $type->isSuperTypeOf($container->getByType(TypeStringResolver::class)->resolve(
                'array{items: array{array{name: string}}}'
            ))->yes(),
            $type->describe(VerbosityLevel::precise())
        );
    }

    public function testExceptHandlesExactDottedKeysPerUnionMember(): void
    {
        $type = $this->resolvePaths(
            'except',
            "array{'profile.note': string}|array{profile: array{note: string, email: int}}",
            ['profile.note']
        );

        self::assertSame(
            'array{}|array{profile: array{email: int}}',
            $type?->describe(VerbosityLevel::precise())
        );
    }

    public function testExceptRespectsTheLaravel1324PathResetBoundary(): void
    {
        foreach (
            [
                '13.23.0' => null,
                '13.24.0' => 'array{a: array{b: int}}',
            ] as $version => $expected
        ) {
            $type = $this->resolvePaths(
                'except',
                'array{a: array{x: int, b: int}, b: int}',
                ['a.x', 'b'],
                $version
            );

            self::assertSame(
                $expected,
                $type?->describe(VerbosityLevel::precise()),
                $version
            );
        }
    }

    /**
     * @param 'except'|'only' $method
     * @param list<string> $paths
     */
    private function resolvePaths(
        string $method,
        string $payloadDescription,
        array $paths,
        ?string $laravelVersion = null
    ): ?Type {
        $container = self::getContainer();
        $payload = $container->getByType(TypeStringResolver::class)->resolve(
            $payloadDescription
        );
        $keysExpression = new Expr\Array_();
        $scope = self::createMock(Scope::class);
        $scope->expects(self::once())
            ->method('getType')
            ->with($keysExpression)
            ->willReturn(self::constantPathList($paths));
        $resolver = $laravelVersion === null
            ? $container->getByType(ValidatedInputTypeResolver::class)
            : new ValidatedInputTypeResolver(
                $container->getByType(FormRequestTypeRegistry::class),
                $container->getByType(CallArgumentResolver::class),
                new LaravelVersionContext('', $laravelVersion)
            );
        $methodCall = new Expr\MethodCall(
            new Expr\Variable('validated'),
            new Identifier($method),
            [new Arg($keysExpression)]
        );

        return $method === 'only'
            ? $resolver->resolveOnlyReturnType($payload, $methodCall, $scope)
            : $resolver->resolveExceptReturnType($payload, $methodCall, $scope);
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
