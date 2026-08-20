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

namespace jbboehr\Rensei\Internal;

use jbboehr\Rensei\UnsupportedLaravelVersion;

use function method_exists;
use function sprintf;

/**
 * Runtime capability checks against the validator a rule was handed.
 *
 * @internal
 */
final class ValidatorCapabilities
{
    /**
     * The Laravel release that introduced `Validator::setValue()`.
     */
    public const SET_VALUE_INTRODUCED = '10.7.0';

    /**
     * Assert that the validator can accept a parsed value.
     *
     * The parameter is deliberately `object` rather than the concrete
     * `Illuminate\Validation\Validator`. Against a known class the check is
     * statically decidable, so an analyzer reading the development lock would
     * report it as always true, and no test could construct a validator
     * missing the method. Widening the parameter keeps the check both
     * analyzable and testable.
     */
    public static function assertCanSetValue(object $validator): void
    {
        if (method_exists($validator, 'setValue')) {
            return;
        }

        throw new UnsupportedLaravelVersion(sprintf(
            'Parsing rules require laravel/framework >= %s, which introduced Validator::setValue().',
            self::SET_VALUE_INTRODUCED
        ));
    }
}
