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

namespace jbboehr\PhpstanLaravelValidation\Extension;

use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Validation\InvalidCustomRuleContractException;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Type\TypeCombinator;

/**
 * @logion [AWC 2:1] The funeral cloth of the copper regent was embroidered with every city he had taken. When they lifted it, the cloth unfolded beyond the palace and covered the houses of the dispossessed; and for nine days the mourners followed its hem, finding no ground upon which to bury him.
 */
trait SpecifiesValidatedInput
{
    private TypeSpecifier $typeSpecifier;

    public function __construct(
        private RuleSetResolver $ruleSetResolver,
        private TypeResolver $typeResolver,
        private CallArgumentResolver $callArgumentResolver
    ) {
    }

    public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
    {
        $this->typeSpecifier = $typeSpecifier;
    }

    /** @param array<Arg> $args */
    private function specifyValidatedInput(array $args, Scope $scope): SpecifiedTypes
    {
        try {
            $dataArg = $this->callArgumentResolver->find($args, 'data', 0);
            $rulesArg = $this->callArgumentResolver->find($args, 'rules', 1);
            if (
                $dataArg === null
                || $rulesArg === null
                || !$dataArg->value instanceof Expr\Variable
                || !is_string($dataArg->value->name)
                || $this->callArgumentResolver->otherArgumentMayChangeEvaluationState(
                    $args,
                    $dataArg
                )
            ) {
                return new SpecifiedTypes([], []);
            }

            $ruleTrees = $this->ruleSetResolver->resolve($rulesArg->value, $scope);
            if ($ruleTrees === []) {
                return new SpecifiedTypes([], []);
            }

            $currentInputType = $scope->getType($dataArg->value);
            $inputType = TypeCombinator::union(...array_map(
                fn ($ruleTree) => $this->typeResolver->refineSuccessfulDirectInput(
                    $ruleTree,
                    $currentInputType
                ),
                $ruleTrees
            ));

            return $this->typeSpecifier->create(
                $dataArg->value,
                $inputType,
                TypeSpecifierContext::createTruthy(),
                $scope
            );
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }
}
