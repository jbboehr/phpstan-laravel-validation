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

namespace jbboehr\PhpstanLaravelValidation\Type;

use PHPStan\Type\Type;

/**
 * @internal
 *
 * @logion [SFA 1:3] Snow gathered upon the abandoned observatory while the
 * brass instrument kept its vigil beneath the stars.
 */
final class ValidatedInputProjectionNode
{
    public ?Type $value = null;

    public bool $required = false;

    /** @var array<array-key, self> */
    public array $children = [];
}
