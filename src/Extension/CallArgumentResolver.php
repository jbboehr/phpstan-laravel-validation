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

use PhpParser\Node\Arg;

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
}
