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

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\NodeFinder;

/**
 * @logion [AWC 1:1] In the winter of the dimmed crown, the keeper of the eastern
 *     hospice received three strangers beneath one roof; and when the bells named
 *     the dawn, each departed by a different road, yet all bore the same blessing
 *     into the provinces.
 */
final class CallArgumentResolver
{
    /**
     * @param array<Arg> $arguments
     */
    public function find(array $arguments, string $parameterName, int $position): ?Arg
    {
        foreach ($arguments as $argument) {
            if (!$argument->unpack && $argument->name?->toString() === $parameterName) {
                return $argument;
            }
        }

        $argument = $arguments[$position] ?? null;
        if ($argument === null || $argument->unpack || $argument->name !== null) {
            return null;
        }

        return $argument;
    }

    /**
     * @param array<Arg> $arguments
     */
    public function otherArgumentMayChangeEvaluationState(
        array $arguments,
        Arg $dataArgument
    ): bool {
        foreach ($arguments as $argument) {
            if ($argument === $dataArgument) {
                continue;
            }

            // Traversable unpacking invokes user-defined iterator methods.
            if ($argument->unpack) {
                return true;
            }

            if ($this->expressionMayChangeEvaluationState($argument->value)) {
                return true;
            }
        }

        return false;
    }

    public function expressionMayChangeEvaluationState(Expr $expression): bool
    {
        // Calls are not the only executable expressions: property hooks,
        // magic methods, autoloaders, interpolation, and suspension can all
        // change state while PHP evaluates an argument.
        return (new NodeFinder())->findFirst(
            [$expression],
            static fn (Node $candidate): bool =>
                $candidate instanceof Expr\Assign
                || $candidate instanceof Expr\AssignOp
                || $candidate instanceof Expr\AssignRef
                || $candidate instanceof Expr\CallLike
                || $candidate instanceof Expr\Closure
                || $candidate instanceof Expr\ArrowFunction
                || $candidate instanceof Expr\PreInc
                || $candidate instanceof Expr\PreDec
                || $candidate instanceof Expr\PostInc
                || $candidate instanceof Expr\PostDec
                || $candidate instanceof Expr\ArrayDimFetch
                || $candidate instanceof Expr\PropertyFetch
                || $candidate instanceof Expr\NullsafePropertyFetch
                || $candidate instanceof Expr\StaticPropertyFetch
                || $candidate instanceof Expr\ClassConstFetch
                || $candidate instanceof Expr\Cast\String_
                || $candidate instanceof Expr\BinaryOp\Concat
                || $candidate instanceof \PhpParser\Node\Scalar\Encapsed
                || $candidate instanceof Expr\Clone_
                || $candidate instanceof Expr\Print_
                || $candidate instanceof Expr\ShellExec
                || $candidate instanceof Expr\Yield_
                || $candidate instanceof Expr\YieldFrom
                || $candidate instanceof Expr\Include_
                || $candidate instanceof Expr\Eval_
                || ($candidate instanceof Expr\Variable && !is_string($candidate->name))
        ) !== null;
    }
}
