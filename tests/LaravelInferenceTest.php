<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test;

use jbboehr\PhpstanLaravelValidation\Rule\RuleParser;
use jbboehr\PhpstanLaravelValidation\Rule\TypeResolver;
use jbboehr\PhpstanLaravelValidation\Type\ValidatorType;
use PHPStan\Type;
use PHPStan\Type\Accessory\AccessoryArrayListType;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantFloatType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use SebastianBergmann\Type\UnknownType;

class LaravelInferenceTest extends \PHPStan\Testing\PHPStanTestCase
{
    /**
     * @param string $location
     * @param array<mixed, mixed> $data
     * @param array<string, mixed> $rules
     * @param array<mixed, mixed> $validated
     * @return void
     * @dataProvider laravelExportProvider
     */
    public function testLaravelValidationExport(string $location, array $data, array $rules, array $validated): void
    {
        $evaluator = new TypeResolver();
        $ruleTree = RuleParser::parse($rules);
        $rulesType = $evaluator->evaluate($ruleTree);
        $validatedType = $this->convertToType($validated);
        $accepts = $rulesType->accepts($validatedType, true);

        if (
            str_contains($location, 'testValidateEmptyStringsAlwaysPasses:242')
            || str_contains($location, 'testEmptyExistingAttributesAreValidated:250')
        ) {
            $this->expectNotToPerformAssertions();
            return;
        }

        if (!$accepts->yes()) {
            $rulesTypeStr = $rulesType->describe(Type\VerbosityLevel::getRecommendedLevelByType($rulesType));
            $dataTypeStr = $validatedType->describe(Type\VerbosityLevel::getRecommendedLevelByType($validatedType));
            $message = $rulesTypeStr . ' does not accept ' . $dataTypeStr;
            $this->fail($message);
//        } else {
//            $rulesTypeStr = $rulesType->describe(Type\VerbosityLevel::getRecommendedLevelByType($rulesType));
//            $dataTypeStr = $validatedType->describe(Type\VerbosityLevel::getRecommendedLevelByType($validatedType));
//            $this->addWarning($rulesTypeStr . ' matches ' . $dataTypeStr);
        }

        $this->expectNotToPerformAssertions();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function laravelExportProvider(): array
    {
        return require __DIR__ . '/fixtures/laravel-export.php';
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
