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

use Illuminate\Foundation\Http\FormRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ValidationRulesRequest;
use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsFixtureUnderCoverage;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestTypeRegistry;
use PHPStan\Analyser\ResultCache\ResultCacheMetaExtension;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\VerbosityLevel;

final class FormRequestInferenceTest extends \PHPStan\Testing\TypeInferenceTestCase
{
    use AssertsFixtureUnderCoverage;

    /** @group form-request */
    public function testFileAsserts(): void
    {
        $this->assertFixtureUnderCoverage(__DIR__ . '/form-request/inference.php');
    }

    public function testRegistryIsRegisteredAsResultCacheMetadata(): void
    {
        $services = self::getContainer()->getServicesByTag(ResultCacheMetaExtension::EXTENSION_TAG);
        $registries = array_values(array_filter(
            $services,
            static fn (mixed $service): bool => $service instanceof FormRequestTypeRegistry
        ));

        self::assertCount(1, $registries);
        self::assertSame('phpstan-laravel-validation.form-requests', $registries[0]->getKey());
    }

    public function testValidationRulesOverrideFollowsInstalledLaravelLifecycle(): void
    {
        $container = self::getContainer();
        $registry = $container->getByType(FormRequestTypeRegistry::class);
        $reflectionProvider = $container->getByType(ReflectionProvider::class);
        $type = $registry->getType($reflectionProvider->getClass(ValidationRulesRequest::class));

        if ($reflectionProvider->getClass(FormRequest::class)->hasNativeMethod('validationRules')) {
            self::assertNull($type);

            return;
        }

        self::assertNotNull($type);
        self::assertSame(
            'array{ordinary: string}',
            $type->describe(VerbosityLevel::precise())
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../extension.neon',
            __DIR__ . '/form-request/phpstan.neon',
        ];
    }
}
