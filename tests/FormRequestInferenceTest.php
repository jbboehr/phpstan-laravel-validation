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

use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsFixtureUnderCoverage;

final class FormRequestInferenceTest extends \PHPStan\Testing\TypeInferenceTestCase
{
    use AssertsFixtureUnderCoverage;

    /** @group form-request */
    public function testFileAsserts(): void
    {
        // Registry initialization resolves both fixtures' request types. Keep
        // their assertions in one test so Infection attributes that work to it.
        $this->assertFixtureUnderCoverage(__DIR__ . '/form-request/inference.php');
        $this->assertFixtureUnderCoverage(__DIR__ . '/form-request/polymorphism.php');
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../extension.neon',
            __DIR__ . '/form-request/inference-phpstan.neon',
        ];
    }
}
