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
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode;
use jbboehr\PhpstanLaravelValidation\Validation\ValidationRulesExpressionResolver;
use jbboehr\Rensei\Internal\ValidatorCapabilities;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Node>
 *
 * @logion [AWC 1:4] On the morning of the violet eclipse, the fishermen drew
 *     up blue feathers warm as bread; they kept none, and the gulls followed
 *     them home in silence.
 */
final class ParsingRuleLaravelVersionRule implements Rule
{
    public const IDENTIFIER = 'laravelValidation.parsingRuleLaravelVersion';

    public function __construct(
        private LaravelVersionContext $laravelVersionContext,
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
        if (!$this->laravelVersionContext->hasFrameworkVersion()
            || $this->laravelVersionContext->isAtLeast(
                ValidatorCapabilities::SET_VALUE_INTRODUCED
            )
        ) {
            return [];
        }

        $rulesExpression = $this->validationRulesExpressionResolver->resolve($node, $scope);
        if ($rulesExpression === null) {
            return [];
        }

        try {
            $trees = $this->ruleSetResolver->resolve($rulesExpression, $scope);
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable) {
            // A compatibility diagnostic must not reject a rule expression
            // that the return-type extension handles conservatively.
            return [];
        }

        /** @var array<string, true> $paths */
        $paths = [];
        foreach ($trees as $tree) {
            $this->collectParsingPaths($tree, $paths);
        }

        $errors = [];
        foreach (array_keys($paths) as $path) {
            $errors[] = RuleErrorBuilder::message(sprintf(
                'Parsing rule for `%s` requires laravel/framework >= %s because parsed values are written through Validator::setValue(); detected %s.',
                $path,
                ValidatorCapabilities::SET_VALUE_INTRODUCED,
                $this->laravelVersionContext->getVersion()
            ))
                ->identifier(self::IDENTIFIER)
                ->line($this->validationRulesExpressionResolver
                    ->findPathLine($rulesExpression, $path, $scope))
                ->tip(sprintf(
                    'Upgrade laravel/framework to %s or newer, or remove the parsing rule. Ordinary Laravel validation inference remains supported on this version.',
                    ValidatorCapabilities::SET_VALUE_INTRODUCED
                ))
                ->build();
        }

        return $errors;
    }

    /** @param array<string, true> $paths */
    private function collectParsingPaths(RuleTreeNode $node, array &$paths): void
    {
        if ($node->hasParsingRule()) {
            $paths[$node->getPath()] = true;
        }

        foreach ($node as $child) {
            $this->collectParsingPaths($child, $paths);
        }
    }
}
