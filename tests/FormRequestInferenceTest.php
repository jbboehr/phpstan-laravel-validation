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
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\AdditionalClassesAbstractRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\AdditionalClassesRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\AdditionalClassesWrongEntry;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\BasicRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\PassedValidationRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\TrustedAdditionalClassesRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\UnlistedAdditionalClassesSiblingRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ValidationRulesRequest;
use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsFixtureUnderCoverage;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestRuleTypeResolver;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestTypeRegistry;
use PHPStan\Analyser\ResultCache\ResultCacheMetaExtension;
use PHPStan\Parser\Parser;
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

    public function testAdditionalClassesDiscoverWithoutBypassingLifecycleChecks(): void
    {
        $container = self::getContainer();
        $reflectionProvider = $container->getByType(ReflectionProvider::class);
        $parser = $container->getService('currentPhpVersionSimpleDirectParser');
        self::assertInstanceOf(Parser::class, $parser);

        $registry = new FormRequestTypeRegistry(
            reflectionProvider: $reflectionProvider,
            parser: $parser,
            ruleTypeResolver: $container->getByType(FormRequestRuleTypeResolver::class),
            workingDirectory: __DIR__,
            tmpDirectory: \sys_get_temp_dir(),
            enabled: true,
            additionalClasses: ['\\' . BasicRequest::class, PassedValidationRequest::class],
            trustedClasses: [],
            analysedPaths: [],
            analysedPathsFromConfig: [],
            composerAutoloaderProjectPaths: [__DIR__ . '/missing-composer-project'],
            scanFiles: [],
            scanDirectories: []
        );

        self::assertNotNull($registry->getType(
            $reflectionProvider->getClass(BasicRequest::class)
        ));
        self::assertNull($registry->getType(
            $reflectionProvider->getClass(PassedValidationRequest::class)
        ));
    }

    public function testConfiguredClassesDoNotDiscoverSameFileSiblings(): void
    {
        $container = self::getContainer();
        $reflectionProvider = $container->getByType(ReflectionProvider::class);

        $additionalRegistry = $this->createIsolatedRegistry(
            [AdditionalClassesRequest::class],
            []
        );
        self::assertNotNull($additionalRegistry->getType(
            $reflectionProvider->getClass(AdditionalClassesRequest::class)
        ));
        self::assertNull($additionalRegistry->getType(
            $reflectionProvider->getClass(UnlistedAdditionalClassesSiblingRequest::class)
        ));

        foreach ([AdditionalClassesWrongEntry::class, AdditionalClassesAbstractRequest::class] as $className) {
            $registry = $this->createIsolatedRegistry([$className], []);
            self::assertNull($registry->getType(
                $reflectionProvider->getClass(UnlistedAdditionalClassesSiblingRequest::class)
            ));
        }

        $trustedRegistry = $this->createIsolatedRegistry(
            [],
            [TrustedAdditionalClassesRequest::class]
        );
        self::assertNotNull($trustedRegistry->getType(
            $reflectionProvider->getClass(TrustedAdditionalClassesRequest::class)
        ));
        self::assertNull($trustedRegistry->getType(
            $reflectionProvider->getClass(UnlistedAdditionalClassesSiblingRequest::class)
        ));

        $scanningRegistry = $this->createIsolatedRegistry(
            [],
            [],
            [__DIR__ . '/Fixtures/FormRequest/AdditionalClassesRequest.php']
        );
        self::assertNotNull($scanningRegistry->getType(
            $reflectionProvider->getClass(UnlistedAdditionalClassesSiblingRequest::class)
        ));
    }

    /**
     * @param list<string> $additionalClasses
     * @param list<string> $trustedClasses
     * @param list<string> $scanFiles
     */
    private function createIsolatedRegistry(
        array $additionalClasses,
        array $trustedClasses,
        array $scanFiles = []
    ): FormRequestTypeRegistry {
        $container = self::getContainer();
        $parser = $container->getService('currentPhpVersionSimpleDirectParser');
        self::assertInstanceOf(Parser::class, $parser);

        return new FormRequestTypeRegistry(
            reflectionProvider: $container->getByType(ReflectionProvider::class),
            parser: $parser,
            ruleTypeResolver: $container->getByType(FormRequestRuleTypeResolver::class),
            workingDirectory: __DIR__,
            tmpDirectory: \sys_get_temp_dir(),
            enabled: true,
            additionalClasses: $additionalClasses,
            trustedClasses: $trustedClasses,
            analysedPaths: [],
            analysedPathsFromConfig: [],
            composerAutoloaderProjectPaths: [__DIR__ . '/missing-composer-project'],
            scanFiles: $scanFiles,
            scanDirectories: []
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
