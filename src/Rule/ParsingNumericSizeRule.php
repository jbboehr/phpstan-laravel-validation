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

namespace jbboehr\PhpstanLaravelValidation\Rule;

use jbboehr\PhpstanLaravelValidation\Validation\InvalidCustomRuleContractException;
use jbboehr\PhpstanLaravelValidation\Validation\Rule as ValidationRule;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode;
use jbboehr\PhpstanLaravelValidation\Validation\ValidationRulesExpressionResolver;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

/**
 * @implements Rule<Node>
 *
 * @logion [AWC 1:3] Above the northern cliffs a pale orchard flowered beneath
 *     the moon, and its keeper slept beside the gate until the returning cranes
 *     covered the branches like snow.
 */
final class ParsingNumericSizeRule implements Rule
{
    public const IDENTIFIER = 'laravelValidation.parsingNumericSize';

    /** @var list<string> */
    private const SIZE_RULES = ['Between', 'Max', 'Min', 'Size'];

    /** @var list<string> */
    private const NUMERIC_RULES = ['Decimal', 'Integer', 'Numeric'];

    public function __construct(
        private RuleSetResolver $ruleSetResolver,
        private ValidationRulesExpressionResolver $validationRulesExpressionResolver
    ) {
    }

    public function getNodeType(): string
    {
        return Node::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $rulesExpression = $this->validationRulesExpressionResolver->resolve($node, $scope);
        if ($rulesExpression === null) {
            return [];
        }

        try {
            $trees = $this->ruleSetResolver->resolve($rulesExpression, $scope);
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable) {
            // This diagnostic must not make a rule expression less analyzable
            // than the return-type extension that owns the actual inference.
            return [];
        }

        /** @var array<string, array{rules: array<string, true>, integerMarker: bool}> $hazards */
        $hazards = [];
        foreach ($trees as $tree) {
            $this->collectHazards($tree, $hazards);
        }

        $errors = [];
        foreach ($hazards as $path => $hazard) {
            $rules = array_keys($hazard['rules']);
            sort($rules);
            $displayRules = array_map(strtolower(...), $rules);
            $ruleDescription = count($displayRules) === 1
                ? sprintf('size rule `%s`', $displayRules[0])
                : sprintf('size rules `%s`', implode('`, `', $displayRules));
            $singleBranch = count($trees) === 1;
            $subject = $singleBranch
                ? sprintf('The rules for `%s` combine', $path)
                : sprintf('A resolved rule branch for `%s` combines', $path);
            $declaration = $singleBranch ? 'but declare no' : 'but declares no';
            $markerDescription = $hazard['integerMarker']
                ? '`integer`, `numeric`, or `decimal`'
                : '`numeric` or `decimal`';
            $tip = $hazard['integerMarker']
                ? 'Add `integer`, `numeric`, or `decimal` for numeric size semantics. Leave the rule list unchanged only if measuring the original representation is intentional.'
                : 'Add `numeric` or an appropriate `decimal` rule for numeric size semantics. Leave the rule list unchanged only if measuring the original representation is intentional.';

            $errors[] = RuleErrorBuilder::message(sprintf(
                '%s a numeric parsing rule with Laravel %s %s %s rule. Laravel therefore measures the original input representation rather than the parsed numeric value.',
                $subject,
                $ruleDescription,
                $declaration,
                $markerDescription
            ))
                ->identifier(self::IDENTIFIER)
                ->line($this->validationRulesExpressionResolver
                    ->findPathLine($rulesExpression, $path, $scope))
                ->tip($tip)
                ->build();
        }

        return $errors;
    }

    /**
     * @param array<string, array{rules: array<string, true>, integerMarker: bool}> $hazards
     */
    private function collectHazards(RuleTreeNode $node, array &$hazards): void
    {
        $producedType = $node->getProducedType();
        $ruleNames = array_map(
            static fn (ValidationRule $rule): string => $rule->getRuleName(),
            $node->getRules()
        );
        $sizeRules = array_intersect(self::SIZE_RULES, $ruleNames);

        if ($producedType !== null
            && $this->isNumericType($producedType)
            && $sizeRules !== []
            && array_intersect(self::NUMERIC_RULES, $ruleNames) === []
        ) {
            $path = $node->getPath();
            $integerMarker = (new IntegerType())->isSuperTypeOf($producedType)->yes();
            if (!isset($hazards[$path])) {
                $hazards[$path] = [
                    'rules' => [],
                    'integerMarker' => $integerMarker,
                ];
            } else {
                // Advice aggregated across conditional branches must remain
                // valid for every branch. One float-producing branch makes
                // Laravel's integer predicate an inappropriate suggestion.
                $hazards[$path]['integerMarker'] = $hazards[$path]['integerMarker']
                    && $integerMarker;
            }

            foreach ($sizeRules as $sizeRule) {
                $hazards[$path]['rules'][$sizeRule] = true;
            }
        }

        foreach ($node as $child) {
            $this->collectHazards($child, $hazards);
        }
    }

    private function isNumericType(Type $type): bool
    {
        if ($type instanceof NeverType) {
            return false;
        }

        return TypeCombinator::union(new IntegerType(), new FloatType())
            ->isSuperTypeOf($type)
            ->yes();
    }

}
