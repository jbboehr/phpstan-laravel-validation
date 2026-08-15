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

use jbboehr\PhpstanLaravelValidation\Evaluator\UnsafeConstExprEvaluator;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\MixedType;
use PHPUnit\Framework\TestCase;

final class UnsafeConstExprEvaluatorTest extends TestCase
{
    public function testUsesConstantScalarTypeInformationBeforeAstFallback(): void
    {
        $expression = new Expr\Variable('value');
        $scope = self::createMock(Scope::class);
        $scope->expects(self::once())
            ->method('getType')
            ->with($expression)
            ->willReturn(new ConstantStringType('resolved'));

        self::assertSame('resolved', (new UnsafeConstExprEvaluator())->evaluate($expression, $scope));
    }

    public function testRecursivelyConvertsConstantArrayTypes(): void
    {
        $array = ConstantArrayTypeBuilder::createEmpty();
        $array->setOffsetValueType(new ConstantStringType('name'), new ConstantStringType('Ada'));
        $array->setOffsetValueType(new ConstantIntegerType(3), new ConstantIntegerType(42));

        $expression = new Expr\Variable('rules');
        $scope = self::createMock(Scope::class);
        $scope->expects(self::once())
            ->method('getType')
            ->with($expression)
            ->willReturn($array->getArray());

        self::assertSame(
            ['name' => 'Ada', 3 => 42],
            (new UnsafeConstExprEvaluator())->evaluate($expression, $scope)
        );
    }

    public function testFallsBackToAstEvaluationWhenTheTypeIsNotConstant(): void
    {
        $expression = new Expr\BinaryOp\Plus(new Scalar\Int_(20), new Scalar\Int_(22));
        $scope = self::createMock(Scope::class);
        $scope->expects(self::once())
            ->method('getType')
            ->with($expression)
            ->willReturn(new MixedType());

        self::assertSame(42, (new UnsafeConstExprEvaluator())->evaluate($expression, $scope));
    }

    public function testResolvesExplicitClassPseudoConstantsWithoutClassScope(): void
    {
        $expression = new Expr\ClassConstFetch(new Name(self::class), 'class');
        $scope = self::createMock(Scope::class);
        $scope->expects(self::once())
            ->method('getType')
            ->with($expression)
            ->willReturn(new MixedType());

        self::assertSame(self::class, (new UnsafeConstExprEvaluator())->evaluate($expression, $scope));
    }
}
