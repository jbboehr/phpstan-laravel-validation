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

use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\Type;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantFloatType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;

class LaravelInferenceTest extends \PHPStan\Testing\PHPStanTestCase
{
    /**
     * @param string $location
     * @param array<mixed, mixed> $data
     * @param array<string, mixed> $rules
     * @param array<mixed, mixed> $validated
     * @return void
     * @dataProvider laravelExportProvider
     * @group laravel
     */
    public function testLaravelValidationExport(string $location, array $data, array $validated, array $rules): void
    {
        $evaluator = new TypeResolver();
        $ruleTree = RuleParser::parse($rules);
        $rulesType = $evaluator->evaluate($ruleTree);
        $validatedType = $this->convertToType($validated);
        $accepts = $rulesType->accepts($validatedType, true);

        // See: https://github.com/sebastianbergmann/phpunit/issues/5114 ?
        $this->assertInstanceOf(RuleTreeNode::class, $ruleTree); // @phpstan-ignore-line
        $this->assertInstanceOf(Type\Type::class, $rulesType); // @phpstan-ignore-line

        if (
            str_contains($location, 'testValidateEmptyStringsAlwaysPasses:242')
            || str_contains($location, 'testEmptyExistingAttributesAreValidated:250')
            || str_contains($location, 'testEmptyExistingAttributesAreValidated:252')
            // Should probably fix this one maybe
            || str_contains($location, 'testValidateImplicitEachWithAsterisksForRequiredNonExistingKey:5735')
        ) {
            return;
        }

        if (!$accepts->yes()) {
            $rulesTypeStr = $rulesType->describe(Type\VerbosityLevel::getRecommendedLevelByType($rulesType));
            $dataTypeStr = $validatedType->describe(Type\VerbosityLevel::getRecommendedLevelByType($validatedType));
            $message = $rulesTypeStr . ' does not accept ' . $dataTypeStr;
            self::fail($message);
//        } else {
//            $rulesTypeStr = $rulesType->describe(Type\VerbosityLevel::getRecommendedLevelByType($rulesType));
//            $dataTypeStr = $validatedType->describe(Type\VerbosityLevel::getRecommendedLevelByType($validatedType));
//            $this->addWarning($rulesTypeStr . ' matches ' . $dataTypeStr);
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function laravelExportProvider(): array
    {
        return array_merge(
            require __DIR__ . '/fixtures/laravel-export-v9.php',
            require __DIR__ . '/fixtures/laravel-export-v10.php'
        );
    }

    private function convertToType(mixed $data): Type\Type
    {
        return match (gettype($data)) {
            "boolean" => new ConstantBooleanType($data),
            "integer" => new ConstantIntegerType($data),
            "double" => new ConstantFloatType($data),
            "string" => new ConstantStringType($data),
            "array" => $this->convertArrayToType($data),
            "object" => new ObjectType(get_class($data)),
            "NULL" => new NullType(),
            "unknown type" => new Type\MixedType(),
            default => new Type\MixedType(),
        };
    }

    /**
     * @param array<mixed, mixed> $data
     * @return Type\Type
     * @throws \PHPStan\ShouldNotHappenException
     */
    private function convertArrayToType(array $data): Type\Type
    {
        $array = ConstantArrayTypeBuilder::createEmpty();
        foreach ($data as $k => $v) {
//            if (is_string($k)) {
//                $k = str_replace('\.', '.', $k);
//            }
            $array->setOffsetValueType(
                $this->convertToType($k),
                $this->convertToType($v),
                false
            );
        }
        return $array->getArray();
    }
}
