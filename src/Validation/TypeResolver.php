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

final class TypeResolver
{
    private const BUILT_IN_RULE_NAMES = [
        'Accepted', 'AcceptedIf', 'ActiveUrl', 'After', 'AfterOrEqual', 'Alpha', 'AlphaDash', 'AlphaNum',
        'Array', 'ArrayKeys', 'Ascii', 'Bail', 'Base64', 'Before', 'BeforeOrEqual', 'Between', 'Boolean', 'Confirmed',
        'Contains',
        'CurrentPassword', 'Date', 'DateEquals', 'DateFormat', 'Decimal', 'Declined', 'DeclinedIf', 'Different',
        'Digits', 'DigitsBetween', 'Dimensions', 'Distinct', 'DoesntContain', 'DoesntEndWith', 'DoesntStartWith',
        'Email', 'Encoding', 'EndsWith', 'Extensions',
        'Enum', 'Exclude', 'ExcludeIf', 'ExcludeUnless', 'ExcludeWith', 'ExcludeWithout', 'Exists', 'File', 'Filled',
        'Gt', 'Gte', 'HexColor', 'Image', 'In', 'InArray', 'InArrayKeys', 'Integer', 'Ip', 'Ipv4', 'Ipv6', 'Json',
        'List', 'Lowercase', 'Lt', 'Lte',
        'MacAddress', 'Max', 'MaxDigits', 'Mimes', 'Mimetypes', 'Min', 'MinDigits', 'MultipleOf', 'NotIn',
        'Missing', 'MissingIf', 'MissingUnless', 'MissingWith', 'MissingWithAll', 'NotRegex', 'Nullable', 'Numeric',
        'Password', 'Present', 'PresentIf', 'PresentUnless', 'PresentWith', 'PresentWithAll', 'Prohibited',
        'ProhibitedIf', 'ProhibitedIfAccepted', 'ProhibitedIfDeclined', 'ProhibitedUnless', 'Prohibits', 'Regex',
        'Required', 'RequiredArrayKeys', 'RequiredIf', 'RequiredIfAccepted', 'RequiredIfDeclined', 'RequiredUnless',
        'RequiredWith', 'RequiredWithAll', 'RequiredWithout', 'RequiredWithoutAll', 'Same', 'Size', 'Sometimes',
        'StartsWith', 'String', 'Timezone', 'Ulid', 'Unique', 'Uppercase', 'Url', 'Uuid',
    ];

    /**
     * Laravel 11 and later exclude these root request keys from TrimStrings.
     */
    private const DEFAULT_UNTRIMMED_PATHS = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function __construct(
        private ?LaravelVersionContext $laravelVersionContext = null,
        private ?CustomRuleTypeResolver $customRuleTypeResolver = null
    ) {
    }

    public function evaluate(RuleTreeNode $node, bool $assumeHttpInputNormalization = false): Type\Type
    {
        if ($node->isOpaque()) {
            $type = new MixedType();
        } elseif ($node->isWildcard()) {
            $type = $this->evaluateWildcard($node, $assumeHttpInputNormalization);
        } elseif ($node->hasChildren()) {
            $type = $this->evaluateMap($node, $assumeHttpInputNormalization);
        } else {
            $type = $this->evaluateLeaf($node, $assumeHttpInputNormalization);
        }

        // Non-array parent rules can cause validated() to return the complete
        // parent value even when nested rules are also present.
        if ($node->hasChildren() && !$node->isArray() && count($node->getRules()) > 0) {
            $leafType = $this->evaluateLeaf($node, $assumeHttpInputNormalization);
            $type = $leafType->isArray()->no()
                ? $leafType
                : Type\TypeCombinator::union($type, $leafType);
        }

        if ($node->allowsNull()) {
            $type = Type\TypeCombinator::addNull($type);
        }

        return $type;
    }

    public static function isBuiltInRuleName(string $ruleName): bool
    {
        return in_array($ruleName, self::BUILT_IN_RULE_NAMES, true);
    }

    public function evaluateMap(RuleTreeNode $node, bool $assumeHttpInputNormalization = false): Type\Type
    {
        $builder = ConstantArrayTypeBuilder::createEmpty();

        foreach ($node as $key => $value) {
            if ($value->isExcluded()) {
                continue;
            }

            $type = $this->evaluate($value, $assumeHttpInputNormalization);

            $builder->setOffsetValueType(
                is_int($key) ? new ConstantIntegerType($key) : new ConstantStringType($key),
                $type,
                $this->isOutputOptional($value)
            );
        }

        return $builder->getArray();
    }

    public function evaluateWildcard(RuleTreeNode $node, bool $assumeHttpInputNormalization = false): Type\Type
    {
        $valueTypes = [];
        foreach ($node as $child) {
            if ($child->isExcluded()) {
                continue;
            }

            $valueTypes[] = $this->evaluate($child, $assumeHttpInputNormalization);
        }

        // A wildcard and explicitly named children can select overlapping
        // paths. PHPStan cannot express every resulting key correlation, so
        // retain the union of every value shape that Laravel may project.
        $valueType = count($valueTypes) > 0
            ? Type\TypeCombinator::union(...$valueTypes)
            : new MixedType();

        return new Type\ArrayType(
            Type\TypeCombinator::union(new Type\IntegerType(), new Type\StringType()),
            $valueType
        );
    }

    public function evaluateLeaf(RuleTreeNode $node, bool $assumeHttpInputNormalization = false): Type\Type
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

        if (
            $node->allowsBlankStringBypass()
            && $this->blankStringCanReachValidation($node, $assumeHttpInputNormalization)
        ) {
            $type = Type\TypeCombinator::union($type, new StringType());
        }

        return $type;
    }

    private function blankStringCanReachValidation(
        RuleTreeNode $node,
        bool $assumeHttpInputNormalization
    ): bool {
        if (!$assumeHttpInputNormalization) {
            return true;
        }

        if (!in_array($node->getPath(), self::DEFAULT_UNTRIMMED_PATHS, true)) {
            return false;
        }

        // Laravel 10 trims these paths. Laravel 11 and later exclude them by
        // default. Without a supported full-framework version, retaining the
        // possible blank string is the only safe assumption.
        if (
            $this->laravelVersionContext === null
            || !$this->laravelVersionContext->hasFrameworkVersion()
        ) {
            return true;
        }

        return $this->laravelVersionContext->isAtLeast('11.0.0');
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

        if ($rule->getRuleName() === Rule::RULE_CUSTOM) {
            return $rule->getAcceptedType() ?? new MixedType();
        }

        if ($rule->getRuleName() === Rule::RULE_OPAQUE) {
            return new MixedType();
        }

        return match ($rule->getRuleName()) {
            "Accepted" => Type\TypeCombinator::union(
                new ConstantStringType("yes"),
                new ConstantStringType("on"),
                new ConstantStringType("1"),
                new ConstantIntegerType(1),
                new ConstantStringType("true"),
                new ConstantBooleanType(true),
            ),

            "ActiveUrl", "Alpha", "CurrentPassword",
            "Email", "Ip", "Ipv4", "Ipv6", "MacAddress", "Timezone", "Url", "Ulid",
            "Uuid" => new IntersectionType([
                new StringType(),
                new AccessoryNonEmptyStringType(),
            ]),

            // DateTime::createFromFormat accepts numeric scalars through weak
            // string coercion, and Laravel preserves the original value.
            "DateFormat" => Type\TypeCombinator::union(
                new Type\FloatType(),
                new Type\IntegerType(),
                new IntersectionType([
                    new StringType(),
                    new AccessoryNonEmptyStringType(),
                ]),
            ),

            // Laravel admits any scalar or Stringable value to its JSON check,
            // then preserves the original native value. False and
            // non-finite floats fail at runtime, but PHPStan cannot express
            // the accepted float subset.
            "Json" => Type\TypeCombinator::union(
                new Type\FloatType(),
                new Type\IntegerType(),
                new IntersectionType([
                    new StringType(),
                    new AccessoryNonEmptyStringType(),
                ]),
                new Type\ObjectType(\Stringable::class),
                new ConstantBooleanType(true),
            ),

            // Laravel admits numeric scalars before applying these regexes,
            // then preserves the original value in validated output.
            "AlphaDash" => Type\TypeCombinator::union(
                new Type\FloatType(),
                new Type\IntegerType(),
                new IntersectionType([
                    new StringType(),
                    new AccessoryNonEmptyStringType(),
                ]),
            ),

            "AlphaNum" => Type\TypeCombinator::union(
                new Type\FloatType(),
                Type\IntegerRangeType::fromInterval(0, null),
                new IntersectionType([
                    new StringType(),
                    new AccessoryNonEmptyStringType(),
                ]),
            ),

            // Laravel's date and comparison rules admit numeric scalars and
            // DateTimeInterface objects, then preserve their native values.
            "After", "AfterOrEqual", "Before", "BeforeOrEqual", "Date", "DateEquals" =>
            Type\TypeCombinator::union(
                new Type\ObjectType(\DateTimeInterface::class),
                new Type\FloatType(),
                new Type\IntegerType(),
                new IntersectionType([
                    new StringType(),
                    new AccessoryNonEmptyStringType(),
                ])
            ),

            // Laravel 10 through 13.3 cast arbitrary values to string for the
            // ASCII predicate and preserve the original value. Laravel 13.4+
            // requires a native string.
            "Ascii" => $this->resolvesAsciiAsNativeString()
                ? new Type\StringType()
                : Type\TypeCombinator::union(
                    new Type\ArrayType(new MixedType(), new MixedType()),
                    new Type\BooleanType(),
                    new Type\FloatType(),
                    new Type\IntegerType(),
                    new Type\NullType(),
                    new Type\ObjectType(\Stringable::class),
                    new Type\ResourceType(),
                    new Type\StringType(),
                ),

            "Lowercase", "String", "Uppercase" => new Type\StringType(),

            "NotRegex", "Regex" => Type\TypeCombinator::union(
                new Type\IntegerType(),
                new Type\FloatType(),
                new Type\StringType()
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

            // Laravel delegates the non-strict integer rule to
            // FILTER_VALIDATE_INT, then preserves the original value. That
            // accepts integral floats, true, and compatible Stringable
            // objects in addition to integers and numeric strings. Laravel
            // 12.22+ supports integer:strict; earlier supported releases
            // ignore that parameter.
            "Integer" => $this->resolvesIntegerAsStrict($rule)
                ? new Type\IntegerType()
                : Type\TypeCombinator::union(
                    new IntersectionType([
                        new StringType(),
                        new AccessoryNumericStringType(),
                    ]),
                    new Type\IntegerType(),
                    new Type\FloatType(),
                    new Type\ObjectType(\Stringable::class),
                    new ConstantBooleanType(true),
                ),

            "Dimensions", "File", "Image", "Mimetypes",
            "Mimes" => new Type\ObjectType('Symfony\\Component\\HttpFoundation\\File\\File'),

            "In" => $this->resolveTypeIn($rule),

            default => $this->resolveDefault($rule),
        };
    }

    private function resolveDefault(Rule $rule): Type\Type
    {
        return $this->customRuleTypeResolver?->resolveName($rule->getRuleName())
            ?? new Type\MixedType();
    }

    private function resolvesAsciiAsNativeString(): bool
    {
        return $this->laravelVersionContext !== null
            && $this->laravelVersionContext->isAtLeast('13.4.0');
    }

    private function resolvesIntegerAsStrict(Rule $rule): bool
    {
        return in_array('strict', $rule->getParameters(), true)
            && $this->laravelVersionContext !== null
            && $this->laravelVersionContext->isAtLeast('12.22.0');
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

    private function resolveTypeIn(Rule $rule): Type\Type
    {
        $parameters = array_map(function ($parameter): string {
            // PHP's CSV parser represents the sole empty value in `in:` as
            // null, while Laravel's loose comparison treats it like ''.
            if ($parameter === null) {
                return '';
            }

            if (!is_scalar($parameter)) {
                throw new InvalidRuleException('Cannot have non-scalar key');
            }

            return (string) $parameter;
        }, $rule->getParameters());

        if (count($parameters) === 0) {
            return new Type\NeverType();
        }

        // Laravel casts every non-array value to string and then performs a
        // non-strict in_array() comparison. Stringable objects can therefore
        // satisfy every non-empty parameter list.
        $types = [new Type\ObjectType(\Stringable::class)];
        $hasNumericParameter = false;
        $acceptsTrue = false;

        foreach ($parameters as $parameter) {
            if (is_numeric($parameter)) {
                $hasNumericParameter = true;
                $acceptsTrue = $acceptsTrue || (float) $parameter === 1.0;
            } else {
                $types[] = new ConstantStringType($parameter);
            }

            if (in_array($parameter, ['INF', '-INF', 'NAN'], true)) {
                $types[] = new Type\FloatType();
            }

            if (preg_match('/^Resource id #\d+$/', $parameter) === 1) {
                $types[] = new Type\ResourceType();
            }
        }

        if ($hasNumericParameter) {
            $types[] = new IntersectionType([
                new StringType(),
                new AccessoryNumericStringType(),
            ]);
            $types[] = new Type\IntegerType();
            $types[] = new Type\FloatType();
        }

        if ($acceptsTrue) {
            $types[] = new ConstantBooleanType(true);
        }

        if (in_array('', $parameters, true)) {
            $types[] = new ConstantBooleanType(false);
            $types[] = new Type\NullType();
        }

        return Type\TypeCombinator::union(...$types);
    }
}
