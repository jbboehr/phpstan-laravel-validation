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
use PHPStan\Type\Accessory\AccessoryArrayListType;
use PHPStan\Type\Accessory\AccessoryNonEmptyStringType;
use PHPStan\Type\Accessory\AccessoryNumericStringType;
use PHPStan\Type\Accessory\HasOffsetType;
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

        // Unless Laravel definitely reconstructs this parent from its nested
        // rules, validated() may preserve the complete parent value. A literal
        // `array` always reconstructs; a literal `list` does so only from
        // Laravel 11.23. Parameterized arrays preserve the permitted parent.
        if (
            $node->hasChildren()
            && $this->mayPreserveCompleteParent($node)
            && count($node->getRules()) > 0
        ) {
            $leafType = $this->evaluateLeaf($node, $assumeHttpInputNormalization);
            if ($node->isArray() || $this->hasRequiredArrayKeysRule($node)) {
                // Parameterized array and required-array-key rules describe
                // the complete parent Laravel preserves. Their array type is
                // therefore already a sound upper bound, including any
                // nested rule output.
                $type = $leafType;
            } else {
                $type = $leafType->isArray()->no()
                    ? $leafType
                    : Type\TypeCombinator::union($type, $leafType);
            }
        }

        // Laravel expands wildcard rules from runtime data. If every nested
        // rule depends on a wildcard and expansion finds no matches, the
        // parent array rule remains the only concrete rule in validated().
        // Laravel can then preserve the raw parent instead of rebuilding it
        // from descendants.
        if ($this->canPreserveRawParentAfterZeroWildcardMatches($node)) {
            if ($node->isWildcard()) {
                // A direct wildcard covers every non-empty array element, so
                // the nested type already contains the only raw array case:
                // an empty array. Only blank-string bypass is missing.
                if (
                    $node->allowsBlankStringBypass()
                    && $this->blankStringCanReachValidation($node, $assumeHttpInputNormalization)
                ) {
                    $type = Type\TypeCombinator::union($type, new StringType());
                }
            } else {
                // A deeper wildcard can miss while unrelated parent keys are
                // present, so retain the complete parent rule type.
                $type = Type\TypeCombinator::union(
                    $type,
                    $this->evaluateLeaf($node, $assumeHttpInputNormalization)
                );
            }
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
            if ($value->isExcluded() || $this->isUnconditionallyMissingProjection($value)) {
                continue;
            }

            $type = $this->evaluate($value, $assumeHttpInputNormalization);

            $builder->setOffsetValueType(
                is_int($key) ? new ConstantIntegerType($key) : new ConstantStringType($key),
                $type,
                $this->isOutputOptional($value)
                    && !$this->requiredArrayKeyGuaranteesProjectedChild($node, $key, $value)
            );
        }

        return $builder->getArray();
    }

    public function evaluateWildcard(RuleTreeNode $node, bool $assumeHttpInputNormalization = false): Type\Type
    {
        $valueTypes = [];
        foreach ($node as $child) {
            if ($child->isExcluded() || $this->isUnconditionallyMissingProjection($child)) {
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

        if (!$this->mayReconstructParentFromNestedRules($node) || !$node->hasChildren()) {
            return false;
        }

        // With one wildcard-only projection path, every successful branch can
        // retain the parent: zero matches preserve the raw value, while a
        // matched required descendant emits projected output. Multiple paths
        // are left conservative because one may expand without another.
        if (
            $this->canPreserveRawParentAfterZeroWildcardMatches($node)
            && $this->singleWildcardPathGuaranteesProjectedOutput($node)
        ) {
            return false;
        }

        // Laravel omits an array parent that has nested rules and rebuilds its
        // output from those children, so the parent is guaranteed only when a
        // child is guaranteed to emit a value.
        foreach ($node as $key => $child) {
            if ($child->isExcluded() || $this->isUnconditionallyMissingProjection($child)) {
                continue;
            }

            if ($key === '*') {
                // A wildcard child can guarantee output only when a successful
                // parent cannot be empty, or when the zero-match branch keeps
                // the raw parent. Presence alone permits an empty value.
                if (
                    !$this->isOutputOptional($child)
                    && (
                        $node->requiresNonBlankValue()
                        || $this->canPreserveRawParentAfterZeroWildcardMatches($node)
                    )
                ) {
                    return false;
                }

                continue;
            }

            if (
                !$this->isOutputOptional($child)
                || $this->requiredArrayKeyGuaranteesProjectedChild($node, $key, $child)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Laravel emits only nested rule paths for an array parent. If every
     * projected descendant is governed by an unconditional missing rule, no
     * part of that subtree can appear in successful validated output.
     */
    private function isUnconditionallyMissingProjection(RuleTreeNode $node): bool
    {
        if ($node->isMissing()) {
            return true;
        }

        if (!$node->hasChildren()) {
            return false;
        }

        // If this Laravel version may preserve the complete parent, missing
        // descendants do not guarantee that the subtree disappears.
        if (count($node->getRules()) > 0 && $this->mayPreserveCompleteParent($node)) {
            return false;
        }

        // A wildcard-only descendant set can expand to no concrete nested
        // rules. In that branch Laravel may preserve an array parent's raw
        // value, so missing descendants do not guarantee omission.
        if ($this->canPreserveRawParentAfterZeroWildcardMatches($node)) {
            return false;
        }

        foreach ($node as $child) {
            if (!$this->isUnconditionallyMissingProjection($child)) {
                return false;
            }
        }

        return true;
    }

    private function canPreserveRawParentAfterZeroWildcardMatches(RuleTreeNode $node): bool
    {
        return $this->mayReconstructParentFromNestedRules($node)
            && $this->hasWildcardDescendant($node)
            && !$this->hasLiteralDescendantRule($node);
    }

    /**
     * Laravel may skip the raw parent and reconstruct it from nested rules.
     * An unknown version must include the post-11.23 `list` behavior.
     */
    private function mayReconstructParentFromNestedRules(RuleTreeNode $node): bool
    {
        if ($node->hasBareArrayRule()) {
            return true;
        }

        if (!$node->hasBareListRule()) {
            return false;
        }

        return $this->laravelVersionContext === null
            || !$this->laravelVersionContext->isSupported()
            || $this->laravelVersionContext->isAtLeast('11.23.0');
    }

    /**
     * Before Laravel 11.23, `list` is only a value predicate and the complete
     * parent survives nested rules. Unknown versions retain that possibility.
     */
    private function mayPreserveCompleteParent(RuleTreeNode $node): bool
    {
        if ($node->hasBareArrayRule()) {
            return false;
        }

        if (!$node->hasBareListRule()) {
            return true;
        }

        return $this->laravelVersionContext === null
            || !$this->laravelVersionContext->isSupported()
            || !$this->laravelVersionContext->isAtLeast('11.23.0');
    }

    private function hasWildcardDescendant(RuleTreeNode $node): bool
    {
        foreach ($node as $key => $child) {
            if ($key === '*' || $this->hasWildcardDescendant($child)) {
                return true;
            }
        }

        return false;
    }

    private function singleWildcardPathGuaranteesProjectedOutput(RuleTreeNode $node): bool
    {
        if (count($node) !== 1) {
            return false;
        }

        foreach ($node as $key => $child) {
            if ($key === '*') {
                return $this->projectionContainsGuaranteedOutput($child);
            }

            // canPreserveRawParentAfterZeroWildcardMatches() already
            // excludes literal descendant rules, but retain the guard here so
            // this helper remains locally sound.
            if (count($child->getRules()) > 0) {
                return false;
            }

            return $this->singleWildcardPathGuaranteesProjectedOutput($child);
        }

        return false;
    }

    private function projectionContainsGuaranteedOutput(RuleTreeNode $node): bool
    {
        foreach ($node as $child) {
            if ($child->isExcluded() || $this->isUnconditionallyMissingProjection($child)) {
                continue;
            }

            if (!$this->isOutputOptional($child)) {
                return true;
            }
        }

        return false;
    }

    private function hasLiteralDescendantRule(RuleTreeNode $node): bool
    {
        foreach ($node as $key => $child) {
            // Rules below a wildcard do not exist in Validator::getRules()
            // when that wildcard has no runtime matches.
            if ($key === '*') {
                continue;
            }

            if (count($child->getRules()) > 0 || $this->hasLiteralDescendantRule($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @see https://github.com/laravel/framework/blob/9.x/src/Illuminate/Validation/Concerns/ValidatesAttributes.php
     */
    private function resolveType(Rule $rule): ?Type\Type
    {
        // Currently unsupported: Enum

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

            "Base64" => $this->resolveTypeBase64(),

            "Bail", "Confirmed", "Between", "Different", "Distinct", "DoesntStartWith", "DoesntEndWith",
            "AcceptedIf", "DeclinedIf", "EndsWith", "Exists", "Filled", "Gt", "Gte", "InArray", "Lt", "Lte",
            "Max", "Min", "Missing", "NotIn", "Exclude",
            "ExcludeIf", "ExcludeUnless", "ExcludeWith", "ExcludeWithout", "Nullable", "Required", "Password",
            "Present", "Prohibited", "ProhibitedIf", "ProhibitedUnless", "Prohibits", "RequiredIf", "RequiredUnless",
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

            "HexColor" => $this->resolveTypeHexColor(),

            "In" => $this->resolveTypeIn($rule),

            "List" => $this->resolveTypeList(),

            "RequiredArrayKeys" => $this->resolveTypeRequiredArrayKeys($rule),

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

    private function resolveTypeBase64(): Type\Type
    {
        // Laravel did not add this rule until version 13.21. Before that
        // release a project can register the same name with an arbitrary
        // runtime contract, so no built-in narrowing is sound.
        if (
            $this->laravelVersionContext === null
            || !$this->laravelVersionContext->isAtLeast('13.21.0')
        ) {
            return new MixedType();
        }

        return new IntersectionType([
            new StringType(),
            new AccessoryNonEmptyStringType(),
        ]);
    }

    private function resolveTypeHexColor(): Type\Type
    {
        // Laravel did not add this rule until 10.33. Before that release a
        // project can register the same name with an arbitrary runtime
        // contract, so no built-in narrowing is sound.
        if (
            $this->laravelVersionContext === null
            || !$this->laravelVersionContext->isAtLeast('10.33.0')
        ) {
            return new MixedType();
        }

        $stringType = new IntersectionType([
            new StringType(),
            new AccessoryNonEmptyStringType(),
        ]);

        // Until Laravel 13.4, preg_match() coerces compatible Stringable
        // objects and validated() preserves the object. Laravel 13.4 adds an
        // explicit native-string guard.
        if ($this->laravelVersionContext->isAtLeast('13.4.0')) {
            return $stringType;
        }

        return Type\TypeCombinator::union(
            $stringType,
            new Type\ObjectType(\Stringable::class)
        );
    }

    private function resolveTypeList(): Type\Type
    {
        // Laravel did not add this rule until version 11.0.3. Before that
        // release a project can register the same name with an arbitrary
        // runtime contract, so no built-in narrowing is sound.
        if (
            $this->laravelVersionContext === null
            || !$this->laravelVersionContext->isAtLeast('11.0.3')
        ) {
            return new MixedType();
        }

        return new IntersectionType([
            new Type\ArrayType(new Type\IntegerType(), new MixedType()),
            new AccessoryArrayListType(),
        ]);
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

    private function resolveTypeRequiredArrayKeys(Rule $rule): Type\Type
    {
        $types = [new Type\ArrayType(new MixedType(), new MixedType())];

        foreach ($rule->getParameters() as $parameter) {
            $key = $this->normalizeArrayKeyParameter($parameter);
            $types[] = new HasOffsetType(
                is_int($key) ? new ConstantIntegerType($key) : new ConstantStringType($key)
            );
        }

        return Type\TypeCombinator::intersect(...$types);
    }

    private function hasRequiredArrayKeysRule(RuleTreeNode $node): bool
    {
        foreach ($node->getRules() as $rule) {
            if ($rule->getRuleName() === Rule::RULE_REQUIRED_ARRAY_KEYS) {
                return true;
            }
        }

        return false;
    }

    /**
     * A required_array_keys parameter constrains input, but a bare array rule
     * can rebuild validated output from child rules and discard unvalidated
     * required keys. Only a matching direct child with a usable rule can turn
     * that input guarantee into a required projected offset.
     */
    private function requiredArrayKeyGuaranteesProjectedChild(
        RuleTreeNode $parent,
        int|string $key,
        RuleTreeNode $child
    ): bool {
        if (count($child->getRules()) === 0 || $child->isOpaque()) {
            return false;
        }

        // A blank parent can bypass non-implicit rules. When a literal array
        // also rebuilds nested output, that successful branch can omit the
        // parent and all of its children.
        if (
            $this->mayReconstructParentFromNestedRules($parent)
            && $parent->allowsBlankStringBypass()
        ) {
            return false;
        }

        foreach ($child->getRules() as $rule) {
            if (in_array($rule->getRuleName(), [
                Rule::RULE_EXCLUDE,
                Rule::RULE_EXCLUDE_IF,
                Rule::RULE_EXCLUDE_UNLESS,
                Rule::RULE_EXCLUDE_WITH,
                Rule::RULE_EXCLUDE_WITHOUT,
            ], true)) {
                return false;
            }
        }

        foreach ($parent->getRules() as $rule) {
            if ($rule->getRuleName() !== Rule::RULE_REQUIRED_ARRAY_KEYS) {
                continue;
            }

            foreach ($rule->getParameters() as $parameter) {
                if ($this->normalizeArrayKeyParameter($parameter) === $key) {
                    return true;
                }
            }
        }

        return false;
    }

    private function normalizeArrayKeyParameter(mixed $parameter): int|string
    {
        if ($parameter === null) {
            return '';
        }

        if (!is_scalar($parameter)) {
            throw new InvalidRuleException('Cannot have non-scalar key');
        }

        // Arr::exists() deliberately stringifies floats before calling
        // array_key_exists(); PHP's ordinary array-key cast would truncate
        // them to integers instead.
        if (is_float($parameter)) {
            $parameter = (string) $parameter;
        }

        if (!is_string($parameter)) {
            return (int) $parameter;
        }

        // PHP converts canonical integer strings to integer array keys. This
        // is also how Arr::exists() interprets required-array-key parameters.
        return array_key_first(array_fill_keys([$parameter], null));
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
