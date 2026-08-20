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

namespace jbboehr\Rensei;

use RuntimeException;

/**
 * Signals that the installed Laravel release cannot support parsing rules.
 *
 * Parsing rules write their result back through `Validator::setValue()`, which
 * Laravel added in v10.7.0. Static analysis supports Laravel 10.0 and later, so
 * the two floors differ and Composer cannot express the difference: a
 * package-level constraint would refuse to install for the majority of users,
 * who want only the analyzer. The requirement is therefore enforced where it
 * can be conditional on actual use.
 */
final class UnsupportedLaravelVersion extends RuntimeException
{
}
