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

use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use PHPStan\Analyser\ResultCache\ResultCacheMetaExtension;

final class VersionAwareInferenceTest extends \PHPStan\Testing\TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public static function dataFileAsserts(): iterable
    {
        yield from self::gatherAssertTypes(__DIR__ . '/version-aware/inference.php');
    }

    /**
     * @dataProvider dataFileAsserts
     */
    public function testFileAsserts(
        string $assertType,
        string $file,
        mixed ...$args
    ): void {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public function testVersionContextIsRegisteredAsResultCacheMetadata(): void
    {
        $services = self::getContainer()->getServicesByTag(ResultCacheMetaExtension::EXTENSION_TAG);
        $contexts = array_values(array_filter(
            $services,
            static fn (mixed $service): bool => $service instanceof LaravelVersionContext
        ));

        self::assertCount(1, $contexts);
        self::assertSame('phpstan-laravel-validation.laravel-version', $contexts[0]->getKey());
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../extension.neon',
            __DIR__ . '/version-aware/phpstan.neon',
        ];
    }
}
