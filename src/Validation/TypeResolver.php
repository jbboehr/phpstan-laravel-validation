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

namespace jbboehr\PhpstanLaravelValidation\Validation;

use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use PHPStan\Type;
use PHPStan\Type\Accessory\AccessoryNonEmptyStringType;
use PHPStan\Type\Accessory\AccessoryNumericStringType;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;

final class TypeResolver
{
    public function evaluate(RuleTreeNode $node): Type\Type
    {
        if ($node->isWildcard()) {
            $type = $this->evaluateWildcard($node);
        } elseif ($node->hasChildren()) {
            $type = $this->evaluateMap($node);
        } else {
            $type = $this->evaluateLeaf($node);
        }

        // Non-array parent rules can cause validated() to return the complete
        // parent value even when nested rules are also present.
        if ($node->hasChildren() && !$node->isArray() && count($node->getRules()) > 0) {
            $leafType = $this->evaluateLeaf($node);
            $type = $leafType->isArray()->no()
                ? $leafType
                : Type\TypeCombinator::union($type, $leafType);
        }

        if ($node->isNullable()) {
            $type = Type\TypeCombinator::addNull($type);
        }

        return $type;
    }

    public function evaluateMap(RuleTreeNode $node): Type\Type
    {
        $builder = ConstantArrayTypeBuilder::createEmpty();

        foreach ($node as $key => $value) {
            if ($value->isExcluded()) {
                continue;
            }

            $type = $this->evaluate($value);

            $builder->setOffsetValueType(
                is_int($key) ? new ConstantIntegerType($key) : new ConstantStringType($key),
                $type,
                $this->isOutputOptional($value)
            );
        }

        return $builder->getArray();
    }

    public function evaluateWildcard(RuleTreeNode $node): Type\Type
    {
        $children = $node->getIterator();
        if ($children->count() !== 1) {
            // @todo don't throw
            throw new ShouldNotHappenException('wildcard mixed with non-wildcard rules');
        }
        return new Type\ArrayType(
            Type\TypeCombinator::union(new Type\IntegerType(), new Type\StringType()),
            $this->evaluate($children->current())
        );
    }

    public function evaluateLeaf(RuleTreeNode $node): Type\Type
    {
        $types = array_values(array_filter(array_map(function ($rule) use ($node) {
            // Laravel applies `in` to every element when the value also has an
            // `array` rule. The scalar `in` resolver cannot model that safely,
            // so retain the array rule's conservative value type instead.
            if ($node->isArray() && $rule->getRuleName() === 'In') {
                return null;
            }

            return $this->resolveType($rule);
        }, $node->getRules())));

        if (count($types) <= 0) {
            $type = new MixedType();
        } else {
            $type = Type\TypeCombinator::intersect(...$types);
        }

        if ($node->allowsBlankStringBypass()) {
            $type = Type\TypeCombinator::union($type, new StringType());
        }

        return $type;
    }

    private function isOutputOptional(RuleTreeNode $node): bool
    {
        if ($node->isOptional()) {
            return true;
        }

        if (!$node->isArray() || !$node->hasChildren()) {
            return false;
        }

        // Laravel omits an array parent that has nested rules and rebuilds its
        // output from those children, so the parent is guaranteed only when a
        // child is guaranteed to emit a value.
        foreach ($node as $child) {
            if (!$child->isExcluded() && !$this->isOutputOptional($child)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @see https://github.com/laravel/framework/blob/9.x/src/Illuminate/Validation/Concerns/ValidatesAttributes.php
     */
    private function resolveType(Rule $rule): ?Type\Type
    {
        // Currently unsupported: Enum, Present, RequiredArrayKeys

        return match ($rule->getRuleName()) {
            "Accepted" => Type\TypeCombinator::union(
                new ConstantStringType("yes"),
                new ConstantStringType("on"),
                new ConstantStringType("1"),
                new ConstantIntegerType(1),
                new ConstantStringType("true"),
                new ConstantBooleanType(true),
            ),

            "ActiveUrl", "Alpha", "AlphaDash", "AlphaNum", "CurrentPassword", "DateFormat",
            "Email", "Ip", "Ipv4", "Ipv6", "Json", "MacAddress", "Timezone", "Url", "Ulid",
            "Uuid" => new IntersectionType([
                new StringType(),
                new AccessoryNonEmptyStringType(),
            ]),

            "After", "Before", "BeforeOrEqual", "Date", "DateEquals" => Type\TypeCombinator::union(
                new Type\ObjectType(\DateTimeInterface::class),
                new IntersectionType([
                    new StringType(),
                    new AccessoryNonEmptyStringType(),
                ])
            ),

            "Ascii", "Lowercase", "String", "Uppercase" => new Type\StringType(),

            "NotRegex", "Regex" => Type\TypeCombinator::union(
                new Type\IntegerType(),
                new Type\FloatType(),
                new Type\StringType(),
                new Type\BooleanType()
            ),

            "Array" => $this->resolveTypeArray($rule),

            "Bail", "Confirmed", "Between", "Different", "Distinct", "DoesntStartWith", "DoesntEndWith",
            "AcceptedIf", "DeclinedIf", "EndsWith", "Exists", "Filled", "Gt", "Gte", "InArray", "Lt", "Lte",
            "Max", "Min", "NotIn", "Exclude",
            "ExcludeIf", "ExcludeUnless", "ExcludeWith", "ExcludeWithout", "Nullable", "Required", "Password",
            "Prohibited", "ProhibitedIf", "ProhibitedUnless", "Prohibits", "RequiredIf", "RequiredUnless",
            "RequiredWith", "RequiredWithAll", "RequiredWithout", "RequiredWithoutAll", "Same", "Size", "Sometimes",
            "StartsWith", "Unique" => null,

            "Boolean" => Type\TypeCombinator::union(
                new Type\BooleanType(),
                new ConstantIntegerType(0),
                new ConstantIntegerType(1),
                new ConstantStringType('0'),
                new ConstantStringType('1'),
            ),

            "Declined" => Type\TypeCombinator::union(
                new ConstantStringType("no"),
                new ConstantStringType("off"),
                new ConstantStringType("0"),
                new ConstantIntegerType(0),
                new ConstantStringType("false"),
                new ConstantBooleanType(false),
            ),

            // We can't use numeric ranges here because laravel doesn't cast it to an integer or float
            "Digits", "DigitsBetween", "Decimal", "MaxDigits", "MinDigits", "MultipleOf",
            "Numeric" => Type\TypeCombinator::union(
                new IntersectionType([
                    new StringType(),
                    new AccessoryNumericStringType(),
                ]),
                new Type\IntegerType(),
                new Type\FloatType()
            ),

            "Integer" => Type\TypeCombinator::union(
                new IntersectionType([
                    new StringType(),
                    new AccessoryNumericStringType(),
                ]),
                new Type\IntegerType()
            ),

            "Dimensions", "File", "Image", "Mimetypes",
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
        $parameters = $rule->getParameters();

        if (count($parameters) <= 0) {
            return new Type\ArrayType(new Type\MixedType(), new Type\MixedType());
        }

        foreach ($parameters as $parameter) {
            if (!is_scalar($parameter)) {
                throw new InvalidRuleException('Cannot have non-scalar key');
            }
            $builder->setOffsetValueType(
                new ConstantStringType((string) $parameter),
                new Type\MixedType(),
                true
            );
        }

        return $builder->getArray();
    }

    /**
     * @todo incorrect for array rules
     */
    private function resolveTypeIn(Rule $rule): Type\Type
    {
        $types = array_values(array_map(function ($str) {
            if (is_scalar($str)) {
                return new ConstantStringType((string) $str);
            } else {
                throw new InvalidRuleException('Cannot have non-scalar key');
            }
        }, $rule->getParameters()));

        if (count($types) > 1) {
            return new UnionType($types);
        } elseif (count($types) === 1) {
            return $types[0];
        } else {
            return new Type\NeverType();
        }
    }
}
