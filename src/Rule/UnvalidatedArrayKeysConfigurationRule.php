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
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;

/**
 * @implements Rule<CallLike>
 *
 * @logion [RAS 1:1] At the fourth hour the marble satellite opened its hidden chapel,
 *     and there appeared twelve vessels filled with light from a sun not yet kindled;
 *     the angel beside them neither spoke nor veiled his face, but turned every vessel
 *     toward the ruined city, and the stones remembered summer.
 */
final class UnvalidatedArrayKeysConfigurationRule implements Rule
{
    public const IDENTIFIER = 'laravelValidation.unvalidatedArrayKeysConfiguration';

    private const INCLUDE_METHOD = 'includeunvalidatedarraykeys';

    private const EXCLUDE_METHOD = 'excludeunvalidatedarraykeys';

    public function __construct(private bool $includeUnvalidatedArrayKeys)
    {
    }

    public function getNodeType(): string
    {
        return CallLike::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (
            !$node instanceof MethodCall
            && !$node instanceof StaticCall
        ) {
            return [];
        }
        if ($node->isFirstClassCallable() || !$node->name instanceof Node\Identifier) {
            return [];
        }

        $method = $node->name->toLowerString();
        $conflictingMethod = $this->includeUnvalidatedArrayKeys
            ? self::EXCLUDE_METHOD
            : self::INCLUDE_METHOD;
        if ($method !== $conflictingMethod || !$this->isLaravelFactoryCall($node, $scope)) {
            return [];
        }

        $configuredValue = $this->includeUnvalidatedArrayKeys ? 'true' : 'false';
        $requiredValue = $this->includeUnvalidatedArrayKeys ? 'false' : 'true';

        return [
            RuleErrorBuilder::message(sprintf(
                'Calling %s() conflicts with phpstanLaravelValidation.includeUnvalidatedArrayKeys: %s and may make inferred validated array shapes unsound.',
                $node->name->toString(),
                $configuredValue
            ))
                ->identifier(self::IDENTIFIER)
                ->tip(sprintf(
                    'Set phpstanLaravelValidation.includeUnvalidatedArrayKeys to %s if this factory mode applies to inferred validation output.',
                    $requiredValue
                ))
                ->build(),
        ];
    }

    private function isLaravelFactoryCall(
        MethodCall|StaticCall $node,
        Scope $scope
    ): bool {
        if ($node instanceof MethodCall) {
            $receiverType = TypeCombinator::removeNull($scope->getType($node->var));

            return (new ObjectType(\Illuminate\Validation\Factory::class))
                ->isSuperTypeOf($receiverType)
                ->yes();
        }

        if (!$node->class instanceof Node\Name) {
            return false;
        }

        $calledClass = new ObjectType($scope->resolveName($node->class));

        return (new ObjectType(\Illuminate\Support\Facades\Validator::class))
            ->isSuperTypeOf($calledClass)
            ->yes();
    }
}
