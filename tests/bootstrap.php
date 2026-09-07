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

use Infection\StreamWrapper\IncludeInterceptor;

require_once dirname(__DIR__) . '/vendor/autoload.php';

if (class_exists(IncludeInterceptor::class, false)) {
    // PHPStan's file-read trap restores the native file wrapper. Load the
    // active mutant before reflection can make a later autoload bypass it.
    // The pinned interceptor exposes its target only through this property.
    $source = (new ReflectionProperty(IncludeInterceptor::class, 'intercept'))->getValue();
    if (!is_string($source)) {
        throw new RuntimeException('Infection did not configure a source file to intercept.');
    }
    require_once $source;
}
