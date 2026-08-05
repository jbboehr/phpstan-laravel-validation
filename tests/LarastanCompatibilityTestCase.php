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

use Composer\InstalledVersions;
use RuntimeException;

abstract class LarastanCompatibilityTestCase extends \PHPStan\Testing\TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public static function dataFileAsserts(): iterable
    {
        if (!InstalledVersions::isInstalled('larastan/larastan')) {
            yield 'Larastan is not installed' => [null, ''];

            return;
        }

        yield from self::gatherAssertTypes(__DIR__ . '/larastan/validator.php');
    }

    /**
     * @dataProvider dataFileAsserts
     * @group larastan
     */
    public function testFileAsserts(
        ?string $assertType,
        string $file,
        mixed ...$args
    ): void {
        if ($assertType === null) {
            self::markTestSkipped('Larastan is not installed.');
        }

        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    protected static function getLarastanConfigFile(): string
    {
        $installPath = InstalledVersions::getInstallPath('larastan/larastan');

        if ($installPath === null) {
            throw new RuntimeException('Unable to locate Larastan.');
        }

        return $installPath . '/extension.neon';
    }
}
