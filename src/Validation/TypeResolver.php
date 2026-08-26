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
use PHPStan\Type\Accessory\NonEmptyArrayType;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;

final class TypeResolver
{
    private const CONDITIONAL_PRESENT_RULES_INTRODUCED = '10.32.0';

    private const EXCLUSION_RULE_NAMES = [
        Rule::RULE_EXCLUDE,
        Rule::RULE_EXCLUDE_IF,
        Rule::RULE_EXCLUDE_UNLESS,
        Rule::RULE_EXCLUDE_WITH,
        Rule::RULE_EXCLUDE_WITHOUT,
    ];

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
        private ?CustomRuleTypeResolver $customRuleTypeResolver = null,
        private bool $includeUnvalidatedArrayKeys = false,
        private bool $experimentalConditionalPresenceInference = false
    ) {
    }

    public function evaluate(RuleTreeNode $node, bool $assumeHttpInputNormalization = false): Type\Type
    {
        // An executable custom or opaque rule can mutate validator data or
        // register a callback that runs after parser write-back. When parsing
        // and arbitrary runtime behavior coexist, no produced output shape is
        // statically enforceable.
        if ($this->hasParsingRule($node) && $this->hasNonParsingExecutableRule($node)) {
            return new MixedType();
        }

        if ($node->isOpaque()) {
            $type = new MixedType();
        } elseif ($node->isWildcard()) {
            $type = $this->evaluateWildcard($node, $assumeHttpInputNormalization);
        } elseif ($node->hasChildren()) {
            $type = $this->evaluateMap($node, $assumeHttpInputNormalization);
        } else {
            $type = $this->evaluateLeaf($node, $assumeHttpInputNormalization);
        }

        $exclusionMutatedParentType = $this->resolveExclusionMutatedParentType(
            $node,
            $assumeHttpInputNormalization
        );
        if ($exclusionMutatedParentType !== null) {
            $type = Type\TypeCombinator::union($type, $exclusionMutatedParentType);
        }

        $directWildcardDescribesList = $this->directWildcardProjectionDescribesPreservedList($node);
        if ($directWildcardDescribesList) {
            // A direct wildcard validates every complete list element, so its
            // projected element type can be combined with constraints from
            // the parent rule. Retain the non-array blank bypass separately.
            $type = Type\TypeCombinator::intersect(
                $type,
                $this->evaluateLeaf($node, $assumeHttpInputNormalization)
            );

            if (
                $node->allowsBlankStringBypass()
                && $this->blankStringCanReachValidation($node, $assumeHttpInputNormalization)
            ) {
                $type = Type\TypeCombinator::union($type, new StringType());
            }
        }

        // Unless Laravel definitely reconstructs this parent from its nested
        // rules, validated() may preserve the complete parent value. A literal
        // `array` always reconstructs; a literal `list` does so only from
        // Laravel 11.23. Parameterized arrays preserve the permitted parent.
        if (
            $node->hasChildren()
            && $this->mayPreserveCompleteParent($node)
            && count($node->getRules()) > 0
            && !$directWildcardDescribesList
        ) {
            $leafType = $this->evaluateLeaf($node, $assumeHttpInputNormalization);
            if ($exclusionMutatedParentType !== null) {
                $leafType = Type\TypeCombinator::union(
                    $leafType,
                    $exclusionMutatedParentType
                );
            }

            if ($node->isArray() || $this->hasRequiredArrayKeysRule($node)) {
                // Parameterized array and required-array-key rules describe
                // the complete parent Laravel preserves. Their array type is
                // therefore already a sound upper bound, including any
                // nested rule output.
                $type = $leafType;
            } else {
                // Inclusion preserves the complete parent instead of choosing
                // between preservation and reconstructed child output. Keep
                // constraints such as listness from the parent's own rule.
                $parentIsOnlyOutcome = $this->includeUnvalidatedArrayKeys
                    || $leafType->isArray()->no();
                $type = $parentIsOnlyOutcome
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

        // A parser attached to a parent and nested child rules describe two
        // different projection paths. Laravel may rebuild the child shape,
        // but when a parameterized parent is preserved and an optional child
        // is absent, validated() can return the parser-produced parent value
        // unchanged. Neither outcome subsumes the other in general.
        if ($node->hasChildren() && $node->hasParsingRule()) {
            $type = Type\TypeCombinator::union(
                $type,
                $node->getProducedType() ?? new MixedType()
            );
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

    /**
     * Describe constraints that successful validation places on the caller's
     * original array. This is deliberately narrower in scope than evaluate():
     * nested paths and runtime projection behavior describe validated output,
     * not necessarily the representation of the input array.
     */
    public function refineSuccessfulDirectInput(
        RuleTreeNode $node,
        Type\Type $inputType
    ): Type\Type {
        // Custom and opaque rules execute application-defined behavior. A
        // rule on any path can mutate a caller variable captured by reference,
        // invalidating every otherwise safe direct-field constraint.
        //
        // A parsing rule is excluded for the opposite reason: it describes
        // validated output, not the input. Its produced type says nothing
        // about the caller's array, which keeps the original representation,
        // so constraining that array by it would report an impossible type.
        if ($this->hasExecutableRule($node)) {
            return $inputType;
        }

        $type = $inputType;

        foreach ($node as $key => $child) {
            if (
                $key === '*'
                || $child->hasChildren()
                || $child->isOpaque()
                || $child->isExcluded()
                || $child->isMissing()
                || $this->hasExclusionRule($child)
            ) {
                continue;
            }

            $offsetType = is_int($key)
                ? new ConstantIntegerType($key)
                : new ConstantStringType($key);
            $hasOffset = $type->hasOffsetValueType($offsetType);

            // An optional rule constrains a value only when the caller's
            // existing type already proves that the offset is present.
            if ($child->isOptional() && !$hasOffset->yes()) {
                continue;
            }

            $valueType = $this->evaluate($child);
            if ($hasOffset->yes()) {
                $valueType = Type\TypeCombinator::intersect(
                    $type->getOffsetValueType($offsetType),
                    $valueType
                );
                $type = $type->setExistingOffsetValueType($offsetType, $valueType);
                continue;
            }

            $type = $type->setOffsetValueType($offsetType, $valueType);
        }

        return $type;
    }

    private function hasExecutableRule(RuleTreeNode $node): bool
    {
        foreach ($node->getRules() as $rule) {
            if (in_array($rule->getRuleName(), [
                Rule::RULE_CUSTOM,
                Rule::RULE_OPAQUE,
                Rule::RULE_PARSE,
            ], true)) {
                return true;
            }
        }

        foreach ($node as $child) {
            if ($this->hasExecutableRule($child)) {
                return true;
            }
        }

        return false;
    }

    private function hasParsingRule(RuleTreeNode $node): bool
    {
        foreach ($node->getRules() as $rule) {
            if ($rule->getRuleName() === Rule::RULE_PARSE) {
                return true;
            }
        }

        foreach ($node as $child) {
            if ($this->hasParsingRule($child)) {
                return true;
            }
        }

        return false;
    }

    private function hasNonParsingExecutableRule(RuleTreeNode $node): bool
    {
        foreach ($node->getRules() as $rule) {
            if (in_array($rule->getRuleName(), [Rule::RULE_CUSTOM, Rule::RULE_OPAQUE], true)) {
                return true;
            }
        }

        foreach ($node as $child) {
            if ($this->hasNonParsingExecutableRule($child)) {
                return true;
            }
        }

        return false;
    }

    public function evaluateMap(RuleTreeNode $node, bool $assumeHttpInputNormalization = false): Type\Type
    {
        $conditionalPresence = $this->resolveConditionalPresenceInference(
            $node,
            $assumeHttpInputNormalization
        );
        return $this->evaluateMapBranch(
            $node,
            $assumeHttpInputNormalization,
            $conditionalPresence['targetKey'] ?? null,
            $conditionalPresence['effect'] ?? null
        );
    }

    private function evaluateMapBranch(
        RuleTreeNode $node,
        bool $assumeHttpInputNormalization,
        int|string|null $targetKey = null,
        ?string $targetEffect = null
    ): Type\Type {
        $builder = ConstantArrayTypeBuilder::createEmpty();

        foreach ($node as $key => $value) {
            if ($key === $targetKey && $targetEffect === Rule::RULE_MISSING) {
                continue;
            }

            if ($value->isExcluded() || $this->isUnconditionallyMissingProjection($value)) {
                continue;
            }

            $branchNode = $value;
            if ($key === $targetKey && $targetEffect === Rule::RULE_PRESENT) {
                $branchNode = clone $value;
                $branchNode->push(Rule::create(Rule::RULE_PRESENT));
            }

            $type = $this->evaluate($branchNode, $assumeHttpInputNormalization);

            $builder->setOffsetValueType(
                is_int($key) ? new ConstantIntegerType($key) : new ConstantStringType($key),
                $type,
                $this->isOutputOptional($branchNode)
                    && !$this->requiredArrayKeyGuaranteesProjectedChild($node, $key, $branchNode)
            );
        }

        return $builder->getArray();
    }

    /**
     * @return array{
     *   targetKey: int|string,
     *   effect: string|null
     * }|null
     */
    private function resolveConditionalPresenceInference(
        RuleTreeNode $node,
        bool $assumeHttpInputNormalization
    ): ?array {
        if (
            !$this->experimentalConditionalPresenceInference
            || $node->getPath() !== ''
            || $this->hasExecutableRule($node)
            || $this->hasUnknownRuleName($node)
            || $this->hasAnyExclusionRule($node)
        ) {
            return null;
        }

        /**
         * @var array{
         *   targetKey: int|string,
         *   rule: Rule,
         *   controllerPath: string,
         *   comparisonValues: list<int|float|string>
         * }|null $candidate
         */
        $candidate = null;
        foreach ($node as $targetKey => $target) {
            if ($targetKey === '*' || $target->hasChildren()) {
                continue;
            }

            foreach ($target->getRules() as $rule) {
                $ruleName = $rule->getRuleName();
                if (!in_array($ruleName, [
                    Rule::RULE_MISSING_IF,
                    Rule::RULE_MISSING_UNLESS,
                    Rule::RULE_PRESENT_IF,
                    Rule::RULE_PRESENT_UNLESS,
                ], true)) {
                    continue;
                }

                if (
                    in_array($ruleName, [
                        Rule::RULE_PRESENT_IF,
                        Rule::RULE_PRESENT_UNLESS,
                    ], true)
                    && (
                        $this->laravelVersionContext === null
                        || !$this->laravelVersionContext->isAtLeast(
                            self::CONDITIONAL_PRESENT_RULES_INTRODUCED
                        )
                    )
                ) {
                    // Before Laravel 10.32 these names can refer to an
                    // application-defined extension with an arbitrary
                    // contract. An unknown version cannot prove that the
                    // built-in presence behavior is available either.
                    return null;
                }

                if ($candidate !== null || $this->hasCompetingPresenceRule($target, $rule)) {
                    return null;
                }

                $parameters = $rule->getParameters();
                if (count($parameters) < 2 || !is_string($parameters[0])) {
                    return null;
                }

                $comparisonValues = [];
                foreach (array_slice($parameters, 1) as $parameter) {
                    if (!is_int($parameter) && !is_float($parameter) && !is_string($parameter)) {
                        return null;
                    }

                    $comparisonValues[] = $parameter;
                }

                $candidate = [
                    'targetKey' => $targetKey,
                    'rule' => $rule,
                    'controllerPath' => $parameters[0],
                    'comparisonValues' => $comparisonValues,
                ];
            }
        }

        if ($candidate === null) {
            return null;
        }

        $targetKey = $candidate['targetKey'];
        $rule = $candidate['rule'];
        $controllerPath = $candidate['controllerPath'];
        $controllerKey = null;
        $controller = null;
        foreach ($node as $key => $child) {
            if ($child->getPath() === $controllerPath) {
                $controllerKey = $key;
                $controller = $child;
                break;
            }
        }

        if (
            $controller === null
            || $controllerKey === null
            || $controllerKey === $targetKey
            || $controller->hasChildren()
            || $controller->isOpaque()
            || $controller->isExcluded()
            || $controller->isMissing()
            || $this->isOutputOptional($controller)
            || !$controller->requiresNonBlankValue()
            || $this->hasRuleNamed($controller, 'Boolean')
        ) {
            return null;
        }

        $controllerType = $this->evaluate($controller, $assumeHttpInputNormalization);
        $constantTypes = $controllerType->getConstantScalarTypes();
        if ($constantTypes === []) {
            return null;
        }

        $constantUnion = Type\TypeCombinator::union(...$constantTypes);
        if (!$controllerType->equals($constantUnion)) {
            return null;
        }

        $comparisonValues = $candidate['comparisonValues'];
        $matches = null;
        foreach ($constantTypes as $constantType) {
            $value = $constantType->getValue();
            if (is_bool($value) || $value === null) {
                // Laravel converts dependent parameters to booleans according
                // to the controller's original rule spelling. RuleParser has
                // already normalized aliases, so it cannot reproduce that
                // decision without guessing.
                return null;
            }

            $constantMatches = $this->dependentScalarValueMatches($value, $comparisonValues);
            if ($matches !== null && $matches !== $constantMatches) {
                // PHPStan normalizes the correlated array union into an
                // optional aggregate at extension call sites. Preserve that
                // conservative result when the controller permits both
                // matching and non-matching values.
                return null;
            }

            $matches = $constantMatches;
        }

        $ruleName = $rule->getRuleName();
        $activeWhenMatching = in_array($ruleName, [
            Rule::RULE_MISSING_IF,
            Rule::RULE_PRESENT_IF,
        ], true);
        $active = $matches === $activeWhenMatching;

        return [
            'targetKey' => $targetKey,
            'effect' => $active
                ? (in_array($ruleName, [
                    Rule::RULE_MISSING_IF,
                    Rule::RULE_MISSING_UNLESS,
                ], true) ? Rule::RULE_MISSING : Rule::RULE_PRESENT)
                : null,
        ];
    }

    /**
     * @param int|float|string $value
     * @param array<int, int|float|string> $comparisonValues
     */
    private function dependentScalarValueMatches(
        int|float|string $value,
        array $comparisonValues
    ): bool {
        foreach ($comparisonValues as $comparisonValue) {
            // Laravel uses non-strict in_array() for non-boolean, non-null
            // controlling values. PHP's spaceship operator applies the same
            // scalar comparison semantics without hiding the intentional
            // coercion behind a non-strict membership check.
            if (($value <=> $comparisonValue) === 0) {
                return true;
            }
        }

        return false;
    }

    private function hasAnyExclusionRule(RuleTreeNode $node): bool
    {
        if ($this->hasExclusionRule($node)) {
            return true;
        }

        foreach ($node as $child) {
            if ($this->hasAnyExclusionRule($child)) {
                return true;
            }
        }

        return false;
    }

    private function hasUnknownRuleName(RuleTreeNode $node): bool
    {
        foreach ($node->getRules() as $rule) {
            if (
                $rule->getRuleName() !== Rule::RULE_NOOP
                && !self::isBuiltInRuleName($rule->getRuleName())
            ) {
                return true;
            }
        }

        foreach ($node as $child) {
            if ($this->hasUnknownRuleName($child)) {
                return true;
            }
        }

        return false;
    }

    private function hasRuleNamed(RuleTreeNode $node, string $ruleName): bool
    {
        foreach ($node->getRules() as $rule) {
            if ($rule->getRuleName() === $ruleName) {
                return true;
            }
        }

        return false;
    }

    private function hasCompetingPresenceRule(RuleTreeNode $node, Rule $candidate): bool
    {
        foreach ($node->getRules() as $rule) {
            if ($rule === $candidate) {
                continue;
            }

            if (in_array($rule->getRuleName(), [
                Rule::RULE_ACCEPTED,
                Rule::RULE_ACCEPTED_IF,
                Rule::RULE_DECLINED,
                Rule::RULE_DECLINED_IF,
                Rule::RULE_MISSING,
                Rule::RULE_MISSING_IF,
                Rule::RULE_MISSING_UNLESS,
                Rule::RULE_PRESENT,
                Rule::RULE_PRESENT_IF,
                Rule::RULE_PRESENT_UNLESS,
                Rule::RULE_REQUIRED,
                Rule::RULE_SOMETIMES,
                'MissingWith',
                'MissingWithAll',
                'PresentWith',
                'PresentWithAll',
                'Prohibited',
                'ProhibitedIf',
                'ProhibitedUnless',
                'RequiredIf',
                'RequiredIfAccepted',
                'RequiredIfDeclined',
                'RequiredUnless',
                'RequiredWith',
                'RequiredWithAll',
                'RequiredWithout',
                'RequiredWithoutAll',
            ], true)) {
                return true;
            }
        }

        return false;
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

        $type = new Type\ArrayType(
            Type\TypeCombinator::union(new Type\IntegerType(), new Type\StringType()),
            $valueType
        );

        if (!$this->wildcardProjectionPreservesList($node)) {
            return $type;
        }

        return Type\TypeCombinator::intersect(
            $type,
            new AccessoryArrayListType()
        );
    }

    public function evaluateLeaf(RuleTreeNode $node, bool $assumeHttpInputNormalization = false): Type\Type
    {
        // A parsing rule replaces the value rather than constraining it, so
        // its produced type supersedes every predicate on the same attribute
        // instead of intersecting with them. Sizing rules are skipped for the
        // same reason: min and max constrain the original representation.
        //
        // The blank-string union is skipped because a parsing rule is
        // implicit, so Laravel does not bypass it for a blank string. A node
        // with children is left to the ordinary path: a parsing rule on a
        // structure has no coherent meaning, and this method also runs for
        // parents on the projection-preserving paths.
        if (!$node->hasChildren() && $node->hasParsingRule()) {
            return $node->getProducedType() ?? new MixedType();
        }

        $allowedKeysListType = $this->resolveAllowedKeysListIntersection($node);
        $types = array_values(array_filter(array_map(function ($rule) use ($node, $allowedKeysListType) {
            // Laravel applies `in` to every element when the value also has an
            // `array` rule. The scalar `in` resolver cannot model that safely,
            // so retain the array rule's conservative value type instead.
            if ($node->isArray() && $rule->getRuleName() === 'In') {
                return null;
            }

            if (
                $allowedKeysListType !== null
                && $this->isAllowedKeysListComponent($rule)
            ) {
                return null;
            }

            return $this->resolveType($rule);
        }, $node->getRules())));

        if ($allowedKeysListType !== null) {
            $types[] = $allowedKeysListType;
        }

        if (count($types) <= 0) {
            $type = new MixedType();
        } else {
            $type = Type\TypeCombinator::intersect(...$types);
        }

        $type = $this->refinePositiveMinimum($node, $type);

        if (
            $node->allowsBlankStringBypass()
            && $this->blankStringCanReachValidation($node, $assumeHttpInputNormalization)
        ) {
            $type = Type\TypeCombinator::union($type, new StringType());
        }

        return $type;
    }

    private function refinePositiveMinimum(RuleTreeNode $node, Type\Type $type): Type\Type
    {
        if (!$this->hasPositiveMinimum($node)) {
            return $type;
        }

        if ($type->isString()->yes()) {
            return Type\TypeCombinator::intersect(
                $type,
                new AccessoryNonEmptyStringType()
            );
        }

        if ($type->isArray()->yes()) {
            // Min constrains the array before nested rules project validated
            // output. A direct exclusion can remove its last element after
            // the parent has passed, so input non-emptiness is not an output
            // invariant in that branch.
            if ($this->hasPotentiallyExcludedDirectChild($node)) {
                return $type;
            }

            return Type\TypeCombinator::intersect(
                $type,
                new NonEmptyArrayType()
            );
        }

        return $type;
    }

    private function hasPositiveMinimum(RuleTreeNode $node): bool
    {
        foreach ($node->getRules() as $rule) {
            if ($rule->getRuleName() !== 'Min') {
                continue;
            }

            $parameters = $rule->getParameters();
            if (!isset($parameters[0]) || !is_scalar($parameters[0])) {
                continue;
            }

            $parameter = trim((string) $parameters[0]);
            if (
                !is_numeric($parameter)
                || str_starts_with($parameter, '-')
            ) {
                continue;
            }

            $mantissa = explode('e', strtolower($parameter), 2)[0];
            if (strpbrk($mantissa, '123456789') !== false) {
                return true;
            }
        }

        return false;
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
                // the raw parent. A direct wildcard is already present for
                // every matched element, so ordinary optional, nullable, and
                // sometimes rules retain it even though the same rule would
                // describe an optional literal path.
                if (
                    (
                        $this->directWildcardChildPreservesMatchedValue($child)
                        || !$this->isOutputOptional($child)
                    )
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
        if ($this->includeUnvalidatedArrayKeys) {
            return false;
        }

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
        if ($this->includeUnvalidatedArrayKeys) {
            return true;
        }

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

    private function wildcardProjectionPreservesList(RuleTreeNode $node): bool
    {
        if (
            !$node->hasBareListRule()
            || $this->laravelVersionContext === null
            || !$this->laravelVersionContext->isAtLeast('11.0.3')
            || count($node) !== 1
        ) {
            return false;
        }

        foreach ($node as $key => $child) {
            if ($key !== '*') {
                return false;
            }

            if (!$child->hasChildren()) {
                return $this->directWildcardChildPreservesMatchedValue($child);
            }

            // Laravel projects wildcard paths in rule insertion order. The
            // first path that can emit output must cover every matched list
            // element; otherwise a later required path can append an earlier
            // numeric key and produce insertion order such as [1, 0]. Rules
            // directly on this intermediate node lose their ordering relative
            // to descendants in RuleTreeNode, so decline that ambiguous case.
            if (count($child->getRules()) > 0) {
                return false;
            }

            foreach ($child as $nestedKey => $nestedChild) {
                if (
                    $nestedChild->isExcluded()
                    || $this->isUnconditionallyMissingProjection($nestedChild)
                ) {
                    continue;
                }

                return $nestedKey !== '*'
                    && !$nestedChild->hasChildren()
                    && !$this->isOutputOptional($nestedChild);
            }

            return false;
        }

        return false;
    }

    /**
     * Wildcard expansion creates a concrete rule path only for elements that
     * already exist. Ordinary optionality therefore cannot omit a matched
     * direct element from validated output; only projection-changing or
     * opaque behavior can do so.
     */
    private function directWildcardChildPreservesMatchedValue(RuleTreeNode $child): bool
    {
        if (
            $child->hasChildren()
            || $child->isOpaque()
            || $child->isExcluded()
            || $this->isUnconditionallyMissingProjection($child)
        ) {
            return false;
        }

        return !$this->hasExclusionRule($child);
    }

    private function hasExclusionRule(RuleTreeNode $node): bool
    {
        foreach ($node->getRules() as $rule) {
            if (in_array($rule->getRuleName(), self::EXCLUSION_RULE_NAMES, true)) {
                return true;
            }
        }

        return false;
    }

    private function directWildcardProjectionDescribesPreservedList(RuleTreeNode $node): bool
    {
        if (!$this->wildcardProjectionPreservesList($node)) {
            return false;
        }

        foreach ($node as $child) {
            return !$child->hasChildren();
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
        if ($rule->getRuleName() === Rule::RULE_CUSTOM) {
            return $rule->getAcceptedType() ?? new MixedType();
        }

        if ($rule->getRuleName() === Rule::RULE_PARSE) {
            // The produced type replaces the leaf type in evaluateLeaf(). It
            // must never reach the intersection this method feeds.
            return null;
        }

        if ($rule->getRuleName() === Rule::RULE_OPAQUE) {
            return new MixedType();
        }

        if ($rule->getRuleName() === Rule::RULE_NOOP) {
            return null;
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

            "DateFormat" => $this->resolveTypeDateFormat($rule),

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
            "Max", "Min", "Missing", "MissingIf", "MissingUnless", "NotIn", "Exclude",
            "ExcludeIf", "ExcludeUnless", "ExcludeWith", "ExcludeWithout", "Nullable", "Required", "Password",
            "Present", "PresentIf", "PresentUnless", "Prohibited", "ProhibitedIf", "ProhibitedUnless", "Prohibits", "RequiredIf", "RequiredUnless",
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

            "Encoding" => $this->resolveTypeEncoding(),

            "Extensions" => $this->resolveTypeExtensions(),

            "HexColor" => $this->resolveTypeHexColor(),

            "In" => $this->resolveTypeIn($rule),

            "List" => $this->resolveTypeList(),

            "RequiredArrayKeys" => $this->resolveTypeRequiredArrayKeys($rule),

            "Contains" => $this->resolveTypeIntroducedArrayRule('11.8.0'),

            "InArrayKeys" => $this->resolveTypeIntroducedArrayRule('12.16.0'),

            "DoesntContain" => $this->resolveTypeIntroducedArrayRule('12.22.0'),

            "ArrayKeys" => $this->resolveTypeArrayKeys($rule),

            default => $this->resolveDefault($rule),
        };
    }

    private function resolveDefault(Rule $rule): Type\Type
    {
        return $this->customRuleTypeResolver?->resolveName($rule->getRuleName())
            ?? new Type\MixedType();
    }

    private function resolveTypeDateFormat(Rule $rule): Type\Type
    {
        $stringType = new IntersectionType([
            new StringType(),
            new AccessoryNonEmptyStringType(),
        ]);

        if (!$this->dateFormatMayAcceptNumericScalar($rule)) {
            return $stringType;
        }

        // DateTime::createFromFormat accepts numeric scalars through weak
        // string coercion, compares the formatted value loosely, and Laravel
        // preserves the original value. Retain both native numeric types when
        // any format can produce a numeric string.
        return Type\TypeCombinator::union(
            new Type\FloatType(),
            new Type\IntegerType(),
            $stringType,
        );
    }

    private function dateFormatMayAcceptNumericScalar(Rule $rule): bool
    {
        $formats = $rule->getParameters();
        if ($formats === []) {
            return true;
        }

        foreach ($formats as $format) {
            if (!is_string($format) || $format === '') {
                return true;
            }

            if ($this->dateFormatMayProduceNumericString($format)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Whether DateTime::format() may produce a PHP numeric string.
     *
     * This deliberately recognizes only numeric-producing format directives.
     * An unknown or textual directive keeps the broad numeric fallback rather
     * than risking a false narrowing. Literal separators are interpreted with
     * a small numeric-string state machine so `Y-m-d` is rejected while `Ymd`,
     * `U.u`, and an escaped exponent such as `Y\\eH` remain possible.
     */
    private function dateFormatMayProduceNumericString(string $format): bool
    {
        /** @var array<int, true> $states */
        $states = [0 => true];
        $length = strlen($format);

        for ($index = 0; $index < $length; $index++) {
            $character = $format[$index];

            if ($character === '\\') {
                if (++$index >= $length) {
                    return true;
                }

                if (preg_match('/^[A-DF-Za-df-z]$/D', $format[$index]) === 1) {
                    return true;
                }

                $states = $this->advanceNumericStringStates($states, $format[$index]);
            } elseif (str_contains('djNwzWmntLoYyBgGhHisuIv', $character)) {
                $states = $this->advanceNumericStringStates($states, '0');
            } elseif (str_contains('UXxOZ', $character)) {
                $unsigned = $this->advanceNumericStringStates($states, '0');
                $positive = $this->advanceNumericStringStates(
                    $this->advanceNumericStringStates($states, '+'),
                    '0'
                );
                $negative = $this->advanceNumericStringStates(
                    $this->advanceNumericStringStates($states, '-'),
                    '0'
                );
                $states = $unsigned + $positive + $negative;
            } elseif (preg_match('/^[A-Za-z]$/D', $character) === 1) {
                return true;
            } else {
                $states = $this->advanceNumericStringStates($states, $character);
            }

            if ($states === []) {
                return false;
            }
        }

        return array_intersect_key($states, [2 => true, 3 => true, 4 => true, 7 => true, 9 => true]) !== [];
    }

    /**
     * @param array<int, true> $states
     * @return array<int, true>
     */
    private function advanceNumericStringStates(array $states, string $character): array
    {
        $next = [];
        foreach ($states as $state => $_) {
            $nextState = match (true) {
                $state === 0 && ctype_space($character) => 0,
                $state === 0 && ($character === '+' || $character === '-') => 1,
                ($state === 0 || $state === 1) && $character === '.' => 8,
                ($state === 0 || $state === 1) && ctype_digit($character) => 2,
                $state === 2 && ctype_digit($character) => 2,
                $state === 2 && $character === '.' => 3,
                ($state === 2 || $state === 3 || $state === 4)
                    && ($character === 'e' || $character === 'E') => 5,
                ($state === 3 || $state === 4 || $state === 8) && ctype_digit($character) => 4,
                $state === 5 && ($character === '+' || $character === '-') => 6,
                ($state === 5 || $state === 6 || $state === 7) && ctype_digit($character) => 7,
                in_array($state, [2, 3, 4, 7, 9], true) && ctype_space($character) => 9,
                default => null,
            };

            if ($nextState !== null) {
                $next[$nextState] = true;
            }
        }

        return $next;
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

    private function resolveTypeExtensions(): Type\Type
    {
        // Laravel did not add this rule until 10.34. Before that release an
        // application may register the same name with an arbitrary contract.
        if (
            $this->laravelVersionContext === null
            || !$this->laravelVersionContext->isAtLeast('10.34.0')
        ) {
            return new MixedType();
        }

        return new Type\ObjectType('Symfony\\Component\\HttpFoundation\\File\\File');
    }

    private function resolveTypeEncoding(): Type\Type
    {
        // Laravel did not add this rule until 12.40. Before that release an
        // application may register the same name with an arbitrary contract.
        if (
            $this->laravelVersionContext === null
            || !$this->laravelVersionContext->isAtLeast('12.40.0')
        ) {
            return new MixedType();
        }

        // Laravel passes arrays and file contents directly to
        // mb_check_encoding(). Other weakly coercible scalar and Stringable
        // values are accepted by that function, while validated() preserves
        // the original native value instead of the checked string or content.
        return Type\TypeCombinator::union(
            new Type\ArrayType(new MixedType(), new MixedType()),
            new Type\BooleanType(),
            new Type\FloatType(),
            new Type\IntegerType(),
            new Type\NullType(),
            new Type\ObjectType(\Stringable::class),
            new Type\StringType(),
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
            $key = $this->normalizeAllowedArrayKeyParameter($parameter);
            $builder->setOffsetValueType(
                is_int($key) ? new ConstantIntegerType($key) : new ConstantStringType($key),
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

    private function resolveTypeIntroducedArrayRule(string $introduced): Type\Type
    {
        // Before Laravel provides the built-in rule, applications may
        // register the same name with an arbitrary runtime contract.
        if (
            $this->laravelVersionContext === null
            || !$this->laravelVersionContext->isAtLeast($introduced)
        ) {
            return new MixedType();
        }

        return new Type\ArrayType(new MixedType(), new MixedType());
    }

    private function resolveTypeArrayKeys(Rule $rule): Type\Type
    {
        // Laravel did not add this rule until 13.24. Before that release an
        // application may register the same name with an arbitrary contract.
        if (
            $this->laravelVersionContext === null
            || !$this->laravelVersionContext->isAtLeast('13.24.0')
        ) {
            return new MixedType();
        }

        $parameters = $rule->getParameters();
        if ($parameters === []) {
            // Laravel throws when this rule is evaluated without at least one
            // parameter, so no non-blank value can reach validated output.
            return new Type\NeverType();
        }

        $builder = ConstantArrayTypeBuilder::createEmpty();
        foreach ($parameters as $parameter) {
            $key = $this->normalizeAllowedArrayKeyParameter($parameter);
            $builder->setOffsetValueType(
                is_int($key) ? new ConstantIntegerType($key) : new ConstantStringType($key),
                new MixedType(),
                true
            );
        }

        return $builder->getArray();
    }

    private function resolveAllowedKeysListIntersection(RuleTreeNode $node): ?Type\Type
    {
        if (
            !$node->hasBareListRule()
            || $this->laravelVersionContext === null
            || !$this->laravelVersionContext->isAtLeast('11.0.3')
        ) {
            return null;
        }

        /** @var array<int|string, true>|null $allowedKeys */
        $allowedKeys = null;
        foreach ($node->getRules() as $rule) {
            $ruleKeys = $this->allowedKeysForListIntersection($rule);
            if ($ruleKeys === null) {
                continue;
            }

            $allowedKeys = $allowedKeys === null
                ? $ruleKeys
                : array_intersect_key($allowedKeys, $ruleKeys);
        }

        if ($allowedKeys === null) {
            return null;
        }

        // A list can contain only the consecutive integer keys 0..n. Stop at
        // the first key the allowed-key rules reject; any later numeric or
        // string keys can never occur in a successful list.
        $builder = ConstantArrayTypeBuilder::createEmpty();
        for ($key = 0; isset($allowedKeys[$key]); $key++) {
            $builder->setOffsetValueType(
                new ConstantIntegerType($key),
                new MixedType(),
                true
            );
        }

        return Type\TypeCombinator::intersect(
            $builder->getArray(),
            $this->resolveTypeList()
        );
    }

    /** @return array<int|string, true>|null */
    private function allowedKeysForListIntersection(Rule $rule): ?array
    {
        if ($rule->getParameters() === []) {
            return null;
        }

        if (
            $rule->getRuleName() !== Rule::RULE_ARRAY
            && (
                $rule->getRuleName() !== 'ArrayKeys'
                || $this->laravelVersionContext === null
                || !$this->laravelVersionContext->isAtLeast('13.24.0')
            )
        ) {
            return null;
        }

        $keys = [];
        foreach ($rule->getParameters() as $parameter) {
            $keys[$this->normalizeAllowedArrayKeyParameter($parameter)] = true;
        }

        return $keys;
    }

    private function isAllowedKeysListComponent(Rule $rule): bool
    {
        return $rule->getRuleName() === Rule::RULE_LIST
            || $this->allowedKeysForListIntersection($rule) !== null;
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

    private function resolveExclusionMutatedParentType(
        RuleTreeNode $node,
        bool $assumeHttpInputNormalization
    ): ?Type\Type {
        if (
            !$node->hasChildren()
            || (
                !$node->hasBareArrayRule()
                && !$node->hasBareListRule()
                && !$this->hasRequiredArrayKeysRule($node)
            )
        ) {
            return null;
        }

        $hasDirectExclusion = $this->hasPotentiallyExcludedDirectChild($node);
        if ($this->includeUnvalidatedArrayKeys) {
            $mayExposeMutation = $hasDirectExclusion;
        } else {
            $mayExposeMutation = $hasDirectExclusion
                && $this->mayPreserveCompleteParent($node);
            if (
                !$mayExposeMutation
                && $this->mayReconstructParentFromNestedRules($node)
                && $this->isOutputOptional($node)
            ) {
                $mayExposeMutation = $this->hasExclusionThatCanEraseProjectedChild($node);
            }
        }

        if (!$mayExposeMutation) {
            return null;
        }

        // Exclusion rules mutate Validator::$data and remove concrete rules
        // before validated() projects the parent. A list can therefore become
        // sparse, and a required array offset can disappear entirely.
        $keyType = $node->hasBareListRule()
            && $this->laravelVersionContext !== null
            && $this->laravelVersionContext->isAtLeast('11.0.3')
            ? new Type\IntegerType()
            : Type\TypeCombinator::union(
                new Type\IntegerType(),
                new Type\StringType()
            );

        $type = new Type\ArrayType($keyType, new MixedType());
        if (
            $node->allowsBlankStringBypass()
            && $this->blankStringCanReachValidation($node, $assumeHttpInputNormalization)
        ) {
            return Type\TypeCombinator::union($type, new StringType());
        }

        return $type;
    }

    private function hasExclusionThatCanEraseProjectedChild(RuleTreeNode $node): bool
    {
        foreach ($node as $child) {
            foreach ($child->getRules() as $rule) {
                if (in_array($rule->getRuleName(), self::EXCLUSION_RULE_NAMES, true)) {
                    return true;
                }
            }

            // A concrete non-exclusion rule keeps this child in Laravel's
            // projected rule set even if a deeper path is removed. Descendant
            // exclusions may mutate the child, but cannot expose the complete
            // raw value of this parent.
            if (count($child->getRules()) > 0) {
                continue;
            }

            if (
                $this->isOutputOptional($child)
                && $this->hasExclusionThatCanEraseProjectedChild($child)
            ) {
                return true;
            }
        }

        return false;
    }

    private function hasPotentiallyExcludedDirectChild(RuleTreeNode $node): bool
    {
        foreach ($node as $child) {
            foreach ($child->getRules() as $rule) {
                if (in_array($rule->getRuleName(), self::EXCLUSION_RULE_NAMES, true)) {
                    return true;
                }
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
            if (in_array($rule->getRuleName(), self::EXCLUSION_RULE_NAMES, true)) {
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

        // Arr::exists() stringifies floats instead of applying PHP's ordinary
        // truncating array-key cast.
        if (is_float($parameter)) {
            $parameter = (string) $parameter;
        }

        if (!is_string($parameter)) {
            return (int) $parameter;
        }

        // PHP converts canonical integer strings to integer array keys. This
        // matches required_array_keys at runtime.
        return array_key_first(array_fill_keys([$parameter], null));
    }

    private function normalizeAllowedArrayKeyParameter(mixed $parameter): int|string
    {
        if (!is_scalar($parameter) && $parameter !== null) {
            throw new InvalidRuleException('Cannot have non-scalar key');
        }

        // Both allowed-key predicates construct their comparison set with
        // array_fill_keys(). Stringifying first reproduces its treatment of
        // null, booleans, floats, and canonical integer strings.
        $key = (string) $parameter;
        if (
            preg_match('/^(?:0|-?[1-9][0-9]*)$/D', $key) === 1
            && (string) (int) $key === $key
        ) {
            return (int) $key;
        }

        return $key;
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
                $integerType = $this->resolveIntegerTypeForNumericInParameter($parameter);
                if ($integerType !== null) {
                    $types[] = $integerType;
                }
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

            // PHP formats floats according to its configurable precision
            // before Laravel compares them. Multiple nearby float values can
            // therefore stringify to the same numeric parameter, and PHPStan
            // has no type for that formatting-dependent equivalence class.
            $types[] = new Type\FloatType();
            if ($rule->hasRuntimeFormattedFloatParameter()) {
                // Rule::in() stringifies float arguments when Laravel parses
                // the builder. Application code can change PHP's precision
                // first, so analysis cannot know which native integer that
                // runtime-formatted parameter may compare equal to.
                $types[] = new Type\IntegerType();
            }
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

    private function resolveIntegerTypeForNumericInParameter(string $parameter): ?Type\Type
    {
        if (
            preg_match(
                '/^[\x20\t\n\r\v\f]*([+-]?)([0-9]+)[\x20\t\n\r\v\f]*$/D',
                $parameter,
                $matches
            ) === 1
        ) {
            $digits = ltrim($matches[2], '0');
            if ($digits === '') {
                $digits = '0';
            }

            $normalized = $matches[1] === '-' && $digits !== '0'
                ? '-' . $digits
                : $digits;
            $integer = (int) $parameter;

            // Exact decimal integer spellings, including signs, whitespace,
            // and leading zeroes, compare to only one native integer while
            // the normalized value remains inside this PHP runtime's range.
            return (string) $integer === $normalized
                ? new ConstantIntegerType($integer)
                : null;
        }

        $numericValue = (float) $parameter;
        if (!is_finite($numericValue) || floor($numericValue) !== $numericValue) {
            return null;
        }

        // Decimal and exponent spellings are compared as floats. Above the
        // largest exactly represented integer, one parameter may accept
        // several adjacent native integers. Retain the broad branch there.
        if (
            abs($numericValue) > 9007199254740991.0
            || $numericValue < PHP_INT_MIN
            || $numericValue > PHP_INT_MAX
        ) {
            return new Type\IntegerType();
        }

        $integer = (int) $numericValue;
        return new ConstantIntegerType($integer);
    }
}
