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

namespace jbboehr\PhpstanLaravelValidation\Test;

use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use PHPUnit\Framework\TestCase;

final class ShouldNotHappenExceptionTest extends TestCase
{
    public function testIncludesIssueGuidanceAndPreviousException(): void
    {
        $previous = new \RuntimeException('previous');
        $exception = new ShouldNotHappenException('Unexpected state', $previous);

        self::assertSame(
            'Unexpected state, please open an issue on GitHub '
                . 'https://github.com/jbboehr/phpstan-laravel-validation/issues',
            $exception->getMessage()
        );
        self::assertSame(0, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
    }
}
