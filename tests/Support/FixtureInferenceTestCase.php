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

namespace jbboehr\PhpstanLaravelValidation\Test\Support;

abstract class FixtureInferenceTestCase extends \PHPStan\Testing\TypeInferenceTestCase
{
    use AssertsFixtureUnderCoverage;

    /** @var string Path relative to tests/. */
    protected const FIXTURE_FILE = '';

    /** @var list<string> Paths relative to tests/. */
    protected const CONFIG_FILES = [];

    final public function testFileAsserts(): void
    {
        $this->assertFixtureUnderCoverage(dirname(__DIR__) . '/' . static::FIXTURE_FILE);
    }

    /** @return non-empty-list<string> */
    final public static function getAdditionalConfigFiles(): array
    {
        return [
            dirname(__DIR__, 2) . '/extension.neon',
            ...array_map(
                static fn (string $file): string => dirname(__DIR__) . '/' . $file,
                static::CONFIG_FILES
            ),
        ];
    }
}
