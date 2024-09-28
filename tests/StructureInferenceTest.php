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

class StructureInferenceTest extends \PHPStan\Testing\TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public function dataFileAsserts(): iterable
    {
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/array.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/controller.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/factory.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/function.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/map.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/readme.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/request.php');
    }

    /**
     * @dataProvider dataFileAsserts
     * @param array<string, mixed[]> $args
     * @group structure
     */
    public function testFileAsserts(
        string $assertType,
        string $file,
        mixed ...$args
    ): void {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../extension.neon'];
    }
}
