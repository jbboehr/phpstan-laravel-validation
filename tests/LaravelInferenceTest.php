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
     * Known-quirky upstream test cases that this extension isn't expected to
     * model correctly, keyed by the Laravel version(s) whose test suite line
     * numbers they were captured at. Laravel restructures its own test files
     * between releases, so these need a new entry (not just a line-number
     * edit) whenever a new major version's fixtures are regenerated.
     *
     * - testValidateEmptyStringsAlwaysPasses / testEmptyExistingAttributesAreValidated:
     *   Laravel treats an empty-string value as "not present" for non-required
     *   rules, so validation passes without the rule (e.g. `array`, `integer`)
     *   actually being satisfied by the empty string that ends up in `validated()`.
     * - testValidateImplicitEachWithAsterisksForRequiredNonExistingKey:
     *   Should probably fix this one maybe.
     * - testNumericKeys: rules keyed by a literal integer (e.g. `[3 => 'required']`)
     *   aren't supported by RuleParser, which requires string paths.
     */
    private const KNOWN_QUIRKS = [
        'testValidateEmptyStringsAlwaysPasses:242', // v9
        'testValidateEmptyStringsAlwaysPasses:244', // v10
        'testValidateEmptyStringsAlwaysPasses:290', // v11
        'testValidateEmptyStringsAlwaysPasses:293', // v11
        'testValidateEmptyStringsAlwaysPasses:310', // v12, v13
        'testValidateEmptyStringsAlwaysPasses:313', // v12, v13
        'testEmptyExistingAttributesAreValidated:250', // v9
        'testEmptyExistingAttributesAreValidated:252', // v10
        'testEmptyExistingAttributesAreValidated:304', // v11
        'testEmptyExistingAttributesAreValidated:324', // v12, v13
        'testValidateImplicitEachWithAsterisksForRequiredNonExistingKey:5735', // v10
        'testValidateImplicitEachWithAsterisksForRequiredNonExistingKey:6989', // v11
        'testValidateImplicitEachWithAsterisksForRequiredNonExistingKey:7342', // v12
        'testValidateImplicitEachWithAsterisksForRequiredNonExistingKey:7537', // v13
        'testNumericKeys:5591', // v12
        'testNumericKeys:5786', // v13
    ];

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
        foreach (self::KNOWN_QUIRKS as $quirk) {
            if (str_contains($location, $quirk)) {
                self::markTestSkipped($location);
            }
        }

        $evaluator = new TypeResolver();
        $ruleTree = RuleParser::parse($rules);
        $rulesType = $evaluator->evaluate($ruleTree);
        $validatedType = $this->convertToType($validated);
        $accepts = $rulesType->accepts($validatedType, true);

        // See: https://github.com/sebastianbergmann/phpunit/issues/5114 ?
        $this->assertInstanceOf(RuleTreeNode::class, $ruleTree); // @phpstan-ignore-line
        $this->assertInstanceOf(Type\Type::class, $rulesType); // @phpstan-ignore-line

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
     * @return array<mixed>
     */
    public static function laravelExportProvider(): array
    {
        $v9 = require __DIR__ . '/fixtures/laravel-export-v9.php';
        $v10 = require __DIR__ . '/fixtures/laravel-export-v10.php';
        $v11 = require __DIR__ . '/fixtures/laravel-export-v11.php';
        $v12 = require __DIR__ . '/fixtures/laravel-export-v12.php';
        $v13 = require __DIR__ . '/fixtures/laravel-export-v13.php';
        assert(is_array($v9) && is_array($v10) && is_array($v11) && is_array($v12) && is_array($v13));

        // 'expandedRules' isn't a parameter of testLaravelValidationExport();
        // drop it so PHPUnit doesn't try (and fail) to match it by name.
        return array_map(static function ($entry) {
            if (is_array($entry)) {
                unset($entry['expandedRules']);
            }
            return $entry;
        }, array_merge($v9, $v10, $v11, $v12, $v13));
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
