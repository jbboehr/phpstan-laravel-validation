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

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\NeverType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;

/**
 * @implements Rule<MethodCall>
 *
 * @logion [RAS 1:4] There appeared above the silent vineyards a stair of black
 *     glass, and upon every step stood a child bearing an unlit lantern; yet the
 *     stars withdrew before them, as though the night had received a procession
 *     older than light.
 */
final class ValidatorMutationRule implements Rule
{
    public const IDENTIFIER = 'laravelValidation.validatorMutation';

    /** @var array<string, non-empty-string> */
    private const METHODS = [
        'setdata' => 'setData',
        'setvalue' => 'setValue',
        'setrules' => 'setRules',
        'addrules' => 'addRules',
        'sometimes' => 'sometimes',
    ];

    private const TRUSTED_SET_VALUE_CLASS = \jbboehr\Rensei\Rules\BaseParsingRule::class;

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->isFirstClassCallable()) {
            return [];
        }

        $methods = $this->resolveMethodNames($node, $scope);
        if (
            $methods === []
            || !$this->containsOnlyMutationMethods($methods)
            || !$this->mayBeLaravelValidator($scope->getType($node->var), $methods)
            || $this->isTrustedParsingWriteBack($methods, $scope)
        ) {
            return [];
        }

        $displayMethods = array_map(
            static fn (string $method): string => self::METHODS[$method] . '()',
            $methods
        );
        $methodDescription = count($displayMethods) === 1
            ? 'method ' . $displayMethods[0]
            : 'methods ' . implode(', ', array_slice($displayMethods, 0, -1))
                . ' or ' . $displayMethods[array_key_last($displayMethods)];

        return [
            RuleErrorBuilder::message(sprintf(
                'Do not call Laravel validator mutation %s. Mutating a validator invalidates its inferred output contract and can reuse stale validation state.',
                $methodDescription
            ))
                ->identifier(self::IDENTIFIER)
                ->tip('Construct a new validator with the complete data and rules instead.')
                ->build(),
        ];
    }

    /** @return list<string> */
    private function resolveMethodNames(MethodCall $node, Scope $scope): array
    {
        if ($node->name instanceof Node\Identifier) {
            return [$node->name->toLowerString()];
        }

        $names = $scope->getType($node->name)->getConstantStrings();
        if ($names === []) {
            return [];
        }

        return array_values(array_unique(array_map(
            static fn ($name): string => strtolower($name->getValue()),
            $names
        )));
    }

    /** @param list<string> $methods */
    private function containsOnlyMutationMethods(array $methods): bool
    {
        foreach ($methods as $method) {
            if (!isset(self::METHODS[$method])) {
                return false;
            }
        }

        return true;
    }

    /** @param list<string> $methods */
    private function mayBeLaravelValidator(Type $type, array $methods): bool
    {
        if ($type->isNull()->yes()) {
            return false;
        }

        $type = TypeCombinator::removeNull($type);
        if ($type instanceof NeverType) {
            return false;
        }

        foreach ($methods as $method) {
            $validatorType = new ObjectType(
                $method === 'sometimes'
                    ? \Illuminate\Contracts\Validation\Validator::class
                    : \Illuminate\Validation\Validator::class
            );

            if ($this->typeContains($type, $validatorType)) {
                return true;
            }
        }

        return false;
    }

    private function typeContains(Type $type, ObjectType $validatorType): bool
    {
        if ($type instanceof UnionType) {
            foreach ($type->getTypes() as $innerType) {
                if ($validatorType->isSuperTypeOf($innerType)->yes()) {
                    return true;
                }
            }

            return false;
        }

        return $validatorType->isSuperTypeOf($type)->yes();
    }

    /** @param list<string> $methods */
    private function isTrustedParsingWriteBack(array $methods, Scope $scope): bool
    {
        return $methods === ['setvalue']
            && $scope->getClassReflection()?->getName() === self::TRUSTED_SET_VALUE_CLASS;
    }
}
