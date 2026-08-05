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

    /**
     * @param array<string, string> $rules
     */
    private static function createValidatorType(array $rules): ValidatorType
    {
        return new ValidatorType(RuleParser::parse($rules));
    }
}
