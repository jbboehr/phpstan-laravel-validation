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

/**
 * Runs a TypeInferenceTestCase fixture inside the test body so PHPStan
 * extension execution is visible to PHPUnit's coverage driver.
 *
 * The consuming test must not also analyse the fixture from a data provider,
 * because data-provider discovery occurs before coverage starts and may warm
 * PHPStan's process-level caches.
 */
trait AssertsFixtureUnderCoverage
{
    private function assertFixtureUnderCoverage(string $file): void
    {
        $asserts = self::gatherAssertTypes($file);
        self::assertNotEmpty($asserts);

        foreach ($asserts as $assert) {
            $this->assertFixtureAssertion($assert);
        }
    }

    /**
     * @param array<mixed> $assert
     */
    private function assertFixtureAssertion(array $assert): void
    {
        $assertType = $assert[0] ?? null;
        $assertFile = $assert[1] ?? null;

        if (!is_string($assertType) || !is_string($assertFile)) {
            throw new \LogicException('PHPStan returned an invalid fixture assertion.');
        }

        $this->assertFileAsserts($assertType, $assertFile, ...array_slice($assert, 2));
    }
}
