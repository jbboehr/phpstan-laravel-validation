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

use Illuminate\Validation\Validator;
use jbboehr\PhpstanLaravelValidation\Type\ValidatorType;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\ObjectType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use PHPStan\Type\VerbosityLevel;

final class ValidatorTypeTest extends PHPStanTestCase
{
    public function testDifferentRulePayloadsRemainAUnion(): void
    {
        self::getContainer();

        $nameType = self::createValidatorType(['name' => 'required|string']);
        $ageType = self::createValidatorType(['age' => 'required|integer']);

        self::assertTrue($nameType->isSuperTypeOf($ageType)->no());
        self::assertTrue($ageType->isSuperTypeOf($nameType)->no());
        self::assertFalse($nameType->equals($ageType));

        $union = TypeCombinator::union($nameType, $ageType);
        self::assertInstanceOf(UnionType::class, $union);
        self::assertCount(2, $union->getTypes());
    }

    public function testIdenticalRulePayloadsCollapse(): void
    {
        self::getContainer();

        $firstType = self::createValidatorType(['name' => 'required|string']);
        $secondType = self::createValidatorType(['name' => 'required|string']);

        self::assertTrue($firstType->equals($secondType));
        self::assertTrue($firstType->isSuperTypeOf($secondType)->yes());
        self::assertInstanceOf(ValidatorType::class, TypeCombinator::union($firstType, $secondType));
    }

    public function testGenericValidatorAbsorbsPayloadInEitherOrder(): void
    {
        self::getContainer();

        $payloadType = self::createValidatorType(['name' => 'required|string']);
        $genericType = new ObjectType(Validator::class);

        self::assertTrue($payloadType->isSuperTypeOf($genericType)->no());
        self::assertTrue($genericType->isSuperTypeOf($payloadType)->yes());
        self::assertTrue(TypeCombinator::union($payloadType, $genericType)->equals($genericType));
        self::assertTrue(TypeCombinator::union($genericType, $payloadType)->equals($genericType));
    }

    public function testPayloadDoesNotLeakIntoUserFacingDescription(): void
    {
        self::getContainer();

        $nameType = self::createValidatorType(['name' => 'required|string']);
        $ageType = self::createValidatorType(['age' => 'required|integer']);

        self::assertSame(Validator::class, $nameType->describe(VerbosityLevel::precise()));
        self::assertSame(Validator::class, $ageType->describe(VerbosityLevel::precise()));
        self::assertNotSame(
            $nameType->describe(VerbosityLevel::cache()),
            $ageType->describe(VerbosityLevel::cache())
        );
    }

    public function testPayloadIsNotEqualToGenericValidatorType(): void
    {
        self::getContainer();

        $payloadType = self::createValidatorType(['name' => 'required|string']);

        self::assertFalse($payloadType->equals(new ObjectType(Validator::class)));
    }

    public function testChangesSubtractedTypeOnlyWhenNecessary(): void
    {
        self::getContainer();

        $type = self::createValidatorType(['name' => 'required|string']);
        $validatorRules = $type->getValidatorRules();
        $stringType = new StringType();

        self::assertSame($type, $type->changeSubtractedType(null));

        $withSubtractedType = $type->changeSubtractedType($stringType);
        self::assertInstanceOf(ValidatorType::class, $withSubtractedType);
        self::assertNotSame($type, $withSubtractedType);
        self::assertSame($validatorRules, $withSubtractedType->getValidatorRules());
        $subtractedType = $withSubtractedType->getSubtractedType();
        self::assertNotNull($subtractedType);
        self::assertTrue($stringType->equals($subtractedType));
        self::assertSame($withSubtractedType, $withSubtractedType->changeSubtractedType(new StringType()));

        $withoutSubtractedType = $withSubtractedType->changeSubtractedType(null);
        self::assertInstanceOf(ValidatorType::class, $withoutSubtractedType);
        self::assertNotSame($withSubtractedType, $withoutSubtractedType);
        self::assertSame($validatorRules, $withoutSubtractedType->getValidatorRules());
        self::assertNull($withoutSubtractedType->getSubtractedType());
    }

    public function testExposesValidatorRules(): void
    {
        self::getContainer();

        $validatorRules = RuleParser::parse(['name' => 'required|string']);
        $type = new ValidatorType($validatorRules);

        self::assertSame($validatorRules, $type->getValidatorRules());
    }

    public function testAcceptsOnlyCompatibleValidatorPayloads(): void
    {
        self::getContainer();

        $nameType = self::createValidatorType(['name' => 'required|string']);
        $sameNameType = self::createValidatorType(['name' => 'required|string']);
        $ageType = self::createValidatorType(['age' => 'required|integer']);

        self::assertTrue($nameType->accepts($sameNameType, true)->yes());
        self::assertTrue($nameType->accepts($ageType, true)->no());
        self::assertTrue($nameType->accepts(new StringType(), true)->no());
        self::assertTrue($nameType->accepts(new ObjectType(Validator::class), true)->no());
    }

    public function testTraversesSubtractedType(): void
    {
        self::getContainer();

        $type = self::createValidatorType(['name' => 'required|string']);
        self::assertSame($type, $type->traverse(static function (): never {
            self::fail('Callback must not run without a subtracted type');
        }));

        $withStringSubtracted = $type->changeSubtractedType(new StringType());
        $withIntegerSubtracted = $withStringSubtracted->traverse(
            static fn (): IntegerType => new IntegerType()
        );
        self::assertInstanceOf(ValidatorType::class, $withIntegerSubtracted);
        self::assertSame($type->getValidatorRules(), $withIntegerSubtracted->getValidatorRules());
        self::assertInstanceOf(IntegerType::class, $withIntegerSubtracted->getSubtractedType());
    }

    public function testSimultaneousTraversalDropsExistingSubtractedType(): void
    {
        self::getContainer();

        $type = self::createValidatorType(['name' => 'required|string']);
        self::assertSame(
            $type,
            $type->traverseSimultaneously(new StringType(), static fn (): StringType => new StringType())
        );

        $withSubtractedType = $type->changeSubtractedType(new StringType());
        $traversed = $withSubtractedType->traverseSimultaneously(
            new StringType(),
            static fn (): StringType => new StringType()
        );
        self::assertInstanceOf(ValidatorType::class, $traversed);
        self::assertSame($type->getValidatorRules(), $traversed->getValidatorRules());
        self::assertNull($traversed->getSubtractedType());
    }

    /**
     * @param array<string, string> $rules
     */
    private static function createValidatorType(array $rules): ValidatorType
    {
        return new ValidatorType(RuleParser::parse($rules));
    }
}
