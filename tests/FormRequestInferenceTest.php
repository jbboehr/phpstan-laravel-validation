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
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\External\LinkedFormRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\vendor\ExcludedVendorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\BasicRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ExactTrustedChildRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ExactTrustedParentRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\PassedValidationRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ValidationRulesRequest;
use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsFixtureUnderCoverage;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestRuleTypeResolver;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestTypeRegistry;
use PHPStan\Analyser\ResultCache\ResultCacheDependencyExtension;
use PHPStan\Parser\Parser;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

final class FormRequestInferenceTest extends \PHPStan\Testing\TypeInferenceTestCase
{
    use AssertsFixtureUnderCoverage;

    /** @group form-request */
    public function testFileAsserts(): void
    {
        $this->assertFixtureUnderCoverage(__DIR__ . '/form-request/inference.php');
    }

    public function testRegistryIsRegisteredAsResultCacheDependencyProvider(): void
    {
        $services = self::getContainer()->getServicesByTag(ResultCacheDependencyExtension::EXTENSION_TAG);
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

    public function testRequestBelowPrunedDependencyDirectoryIsNotTrustedAsProjectSource(): void
    {
        $reflectionProvider = self::getContainer()->getByType(ReflectionProvider::class);
        $registry = $this->createRegistry(
            analysedPathsFromConfig: [__DIR__ . '/Fixtures/FormRequest']
        );

        self::assertNull($registry->getType(
            $reflectionProvider->getClass(ExcludedVendorRequest::class)
        ));
    }

    public function testExplicitPrunedDirectoryRootRemainsProjectSource(): void
    {
        $type = $this->resolveExcludedVendorRequestType($this->createRegistry(
            analysedPathsFromConfig: [__DIR__ . '/Fixtures/FormRequest'],
            scanDirectories: [__DIR__ . '/Fixtures/FormRequest/vendor']
        ));

        self::assertNotNull($type);
        self::assertSame('array{value: string}', $type->describe(VerbosityLevel::precise()));
    }

    public function testExplicitFileBelowPrunedDirectoryRemainsProjectSource(): void
    {
        $type = $this->resolveExcludedVendorRequestType($this->createRegistry(
            analysedPathsFromConfig: [__DIR__ . '/Fixtures/FormRequest'],
            scanFiles: [__DIR__ . '/Fixtures/FormRequest/vendor/ExcludedVendorRequest.php']
        ));

        self::assertNotNull($type);
        self::assertSame('array{value: string}', $type->describe(VerbosityLevel::precise()));
    }

    public function testExactTrustedClassOutsideProjectSourceIsInferred(): void
    {
        $type = $this->resolveExcludedVendorRequestType($this->createRegistry(
            trustedClasses: [ExcludedVendorRequest::class]
        ));

        self::assertNotNull($type);
        self::assertSame('array{value: string}', $type->describe(VerbosityLevel::precise()));
    }

    public function testExactTrustedClassBypassesLifecycleChecks(): void
    {
        $reflectionProvider = self::getContainer()->getByType(ReflectionProvider::class);
        $registry = $this->createRegistry(trustedClasses: [PassedValidationRequest::class]);
        $type = $registry->getType($reflectionProvider->getClass(PassedValidationRequest::class));

        self::assertNotNull($type);
        self::assertSame('array{unsafe: string}', $type->describe(VerbosityLevel::precise()));
    }

    public function testTrustDoesNotExtendToSubclasses(): void
    {
        $reflectionProvider = self::getContainer()->getByType(ReflectionProvider::class);
        $registry = $this->createRegistry(trustedClasses: [ExactTrustedParentRequest::class]);

        $parentType = $registry->getType(
            $reflectionProvider->getClass(ExactTrustedParentRequest::class)
        );
        self::assertNotNull($parentType);
        self::assertSame(
            'array{trusted_parent: string}',
            $parentType->describe(VerbosityLevel::precise())
        );
        self::assertNull($registry->getType(
            $reflectionProvider->getClass(ExactTrustedChildRequest::class)
        ));
    }

    public function testCanonicalEquivalentTrustedClassNameIsInferred(): void
    {
        $type = $this->resolveExcludedVendorRequestType($this->createRegistry(
            trustedClasses: ['\\' . strtolower(ExcludedVendorRequest::class)]
        ));

        self::assertNotNull($type);
        self::assertSame('array{value: string}', $type->describe(VerbosityLevel::precise()));
    }

    public function testSymlinkedExternalFileDoesNotCrossProjectSourceBoundary(): void
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            self::markTestSkipped('File symlink creation is not reliably available on Windows.');
        }

        $sourceRoot = sys_get_temp_dir() . '/phpstan-form-request-link-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($sourceRoot));
        $link = $sourceRoot . '/LinkedFormRequest.php';

        try {
            self::assertTrue(symlink(
                __DIR__ . '/Fixtures/External/LinkedFormRequest.php',
                $link
            ));
            require_once $link;

            $reflectionProvider = self::getContainer()->getByType(ReflectionProvider::class);
            $registry = $this->createRegistry(analysedPathsFromConfig: [$sourceRoot]);
            self::assertNull($registry->getType(
                $reflectionProvider->getClass(LinkedFormRequest::class)
            ));
        } finally {
            if (is_link($link)) {
                unlink($link);
            }
            rmdir($sourceRoot);
        }
    }

    public function testDependencyHashNormalizesLeadingNamespaceSeparator(): void
    {
        $registry = $this->createRegistry(
            analysedPathsFromConfig: [__DIR__ . '/Fixtures/FormRequest']
        );

        self::assertSame(
            $registry->getHash('\\' . BasicRequest::class),
            $registry->getHash(BasicRequest::class)
        );
    }

    public function testObsoleteDependencyKeyHashDoesNotDependOnReflectionLookupOrder(): void
    {
        $canonicalName = 'jbboehr\\PhpstanLaravelValidation\\Test\\Fixtures\\FormRequest\\ObsoleteDependencyRequest';
        $obsoleteName = strtolower($canonicalName);
        $sourcePaths = [__DIR__ . '/Fixtures/FormRequest'];

        $obsoleteFirst = $this->createRegistry(analysedPathsFromConfig: $sourcePaths);
        $hashBeforeCanonicalLookup = $obsoleteFirst->getHash($obsoleteName);

        $canonicalFirst = $this->createRegistry(analysedPathsFromConfig: $sourcePaths);
        $canonicalHash = $canonicalFirst->getHash($canonicalName);
        $hashAfterCanonicalLookup = $canonicalFirst->getHash($obsoleteName);

        self::assertSame($hashBeforeCanonicalLookup, $hashAfterCanonicalLookup);
        self::assertNotSame($canonicalHash, $hashAfterCanonicalLookup);
    }

    /**
     * @param list<string> $trustedClasses
     * @param list<string> $analysedPathsFromConfig
     * @param list<string> $scanFiles
     * @param list<string> $scanDirectories
     */
    private function createRegistry(
        array $trustedClasses = [],
        array $analysedPathsFromConfig = [],
        array $scanFiles = [],
        array $scanDirectories = []
    ): FormRequestTypeRegistry {
        $container = self::getContainer();
        $parser = $container->getService('currentPhpVersionSimpleDirectParser');
        self::assertInstanceOf(Parser::class, $parser);

        return new FormRequestTypeRegistry(
            reflectionProvider: $container->getByType(ReflectionProvider::class),
            parser: $parser,
            ruleTypeResolver: $container->getByType(FormRequestRuleTypeResolver::class),
            workingDirectory: dirname(__DIR__),
            enabled: true,
            trustedClasses: $trustedClasses,
            analysedPaths: [],
            analysedPathsFromConfig: $analysedPathsFromConfig,
            composerAutoloaderProjectPaths: [__DIR__ . '/Fixtures/NoComposerProject'],
            scanFiles: $scanFiles,
            scanDirectories: $scanDirectories
        );
    }

    private function resolveExcludedVendorRequestType(FormRequestTypeRegistry $registry): ?Type
    {
        $reflectionProvider = self::getContainer()->getByType(ReflectionProvider::class);

        return $registry->getType($reflectionProvider->getClass(ExcludedVendorRequest::class));
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../extension.neon',
            __DIR__ . '/form-request/phpstan.neon',
        ];
    }
}
