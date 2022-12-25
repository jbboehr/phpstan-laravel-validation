<?php
declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Rule;

use PHPStan\Type;
use PHPStan\Type\Accessory\AccessoryNonEmptyStringType;
use PHPStan\Type\Accessory\AccessoryNumericStringType;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\MixedType;
use PHPStan\Type\UnionType;

final class TypeResolver
{
    public function evaluate(RuleTreeLeafNode $node): Type\Type
    {
        if ($node instanceof RuleTreeMapNode) {
            return $this->evaluateMap($node);
        } elseif ($node instanceof RuleTreeArrayNode) {
            return $this->evaluateArray($node);
        } else {
            return $this->evaluateLeaf($node);
        }
    }

    public function evaluateMap(RuleTreeMapNode $node): Type\Type
    {
        $builder = ConstantArrayTypeBuilder::createEmpty();

        foreach ($node as $key => $value) {
            if ($value->isExcluded()) {
                continue;
            }

            $type = $this->evaluate($value);

            $builder->setOffsetValueType(
                new ConstantStringType($key),
                $type,
                $value->isOptional()
            );

            if ($value->hasConfirmation()) {
                $builder->setOffsetValueType(
                    new ConstantStringType($key . '_confirmation'),
                    $type,
                    $value->isOptional()
                );
            }
        }

        return $builder->getArray();
    }

    public function evaluateArray(RuleTreeArrayNode $node): Type\Type
    {
        // @TODO This may allow string keys, double-check
        return new Type\ArrayType(
            new Type\IntegerType(),
            $this->evaluate($node->getChild())
        );
    }

    public function evaluateLeaf(RuleTreeLeafNode $node): Type\Type
    {
        $types = array_filter(array_map(function ($rule) {
            return $this->resolveType($rule);
        }, $node->getRules()));

        $types = $this->deduplicateTypes(...$types);

        if (count($types) >= 2) {
            return new IntersectionType($types);
        } elseif (count($types) === 1) {
            return $types[0];
        }

        return new MixedType();
    }

    /**
     * @param Type\Type ...$types
     * @return Type\Type[]
     */
    private function deduplicateTypes(Type\Type ...$types): array
    {
        $deduplicate = [];
        $arr = [];

        // @todo remove string if numeric-string, etc

        foreach ($types as $type) {
            if (
                $type instanceof Type\StringType ||
                $type instanceof Type\MixedType ||
                $type instanceof AccessoryNumericStringType ||
                $type instanceof AccessoryNonEmptyStringType
            ) {
                $key = get_class($type);
            } elseif ($type instanceof Type\ObjectType) {
                $key = get_class($type) . '|' . $type->getClassName();
            } else {
                $key = null;
            }

            if (isset($deduplicate[$key])) {
                continue;
            }

            $arr[] = $type;

            if ($key) {
                $deduplicate[$key] = $type;
            }
        }

        // this might not be necessary
        if (isset($deduplicate[Type\StringType::class]) && isset($deduplicate[AccessoryNonEmptyStringType::class])) {
            unset($deduplicate[Type\StringType::class]);
        }

        return array_values($deduplicate) + $arr;
    }

    /**
     * @see https://github.com/laravel/framework/blob/9.x/src/Illuminate/Validation/Concerns/ValidatesAttributes.php
     */
    private function resolveType(Rule $rule): ?Type\Type
    {
        // Currently unsupported: Enum, Present, RequiredArrayKeys

        return match ($rule->getRuleName()) {
            "Accepted", "AcceptedIf" => new UnionType([
                new ConstantStringType("yes"),
                new ConstantStringType("on"),
                new ConstantStringType("1"),
                new ConstantIntegerType(1),
                new ConstantStringType("true"),
                new ConstantBooleanType(true),
            ]),

            "ActiveUrl", "Alpha", "AlphaDash", "AlphaNum", "Before", "BeforeOrEqual", "CurrentPassword", "Date",
            "DateEquals", "DateFormat", "Email", "Ip", "Ipv4", "Ipv6", "Json", "MacAddress", "Timezone", "Url", "Ulid",
            "Uuid" => new AccessoryNonEmptyStringType(),

            "Ascii", "Lowercase", "NotRegex", "Regex", "String", "Uppercase" => new Type\StringType(),

            "Array" => $this->resolveTypeArray($rule),

            "Bail", "Confirmed", "Between", "Different", "Dimensions", "Distinct", "DoesntStartWith", "DoesntEndWith",
            "EndsWith", "Exists", "Filled", "Gt", "Gte", "InArray", "Lt", "Lte", "Max", "Min", "NotIn", "Exclude",
            "ExcludeIf", "ExcludeUnless", "ExcludeWith", "ExcludeWithout", "Nullable", "Required", "Password",
            "Prohibited", "ProhibitedIf", "ProhibitedUnless", "Prohibits", "RequiredIf", "RequiredUnless",
            "RequiredWith", "RequiredWithAll", "RequiredWithout", "RequiredWithoutAll", "Same", "Size", "Sometimes",
            "StartsWith", "Unique" => null,

            "Boolean" => new UnionType([
                new Type\BooleanType(),
                new ConstantIntegerType(0),
                new ConstantIntegerType(1),
                new ConstantStringType('0'),
                new ConstantStringType('1'),
            ]),

            "Declined", "DeclinedIf" => new UnionType([
                new ConstantStringType("no"),
                new ConstantStringType("off"),
                new ConstantStringType("0"),
                new ConstantIntegerType(0),
                new ConstantStringType("false"),
                new ConstantBooleanType(false),
            ]),

            // We can't use numeric ranges here because laravel doesn't cast it to an integer or float
            "Digits", "DigitsBetween", "Decimal", "Integer", "MaxDigits", "MinDigits", "MultipleOf",
            "Numeric" => new AccessoryNumericStringType(),

            "File", "Image", "Mimetypes",
            "Mimes" => new Type\ObjectType('Symfony\\Component\\HttpFoundation\\File\\File'),

            "In" => $this->resolveTypeIn($rule),

            default => $this->resolveDefault($rule),
        };
    }

    private function resolveDefault(Rule $rule): Type\Type
    {
        return new Type\MixedType();
    }

    private function resolveTypeArray(Rule $rule): Type\Type
    {
        $builder = ConstantArrayTypeBuilder::createEmpty();

        foreach ($rule->getParameters() as $parameter) {
            if (!is_scalar($parameter)) {
                throw new InvalidRuleException('Cannot have non-scalar key');
            }
            $builder->setOffsetValueType(
                new ConstantStringType((string) $parameter),
                new Type\MixedType(),
                false
            );
        }

        return $builder->getArray();
    }

    /**
     * @todo incorrect for array rules
     */
    private function resolveTypeIn(Rule $rule): Type\Type
    {
        return new UnionType(array_map(function ($str) {
            if (is_scalar($str)) {
                return new ConstantStringType((string) $str);
            } else {
                throw new InvalidRuleException('Cannot have non-scalar key');
            }
        }, $rule->getParameters()));
    }
}
