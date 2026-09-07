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
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ClassConstantRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\PassedValidationRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\TrustedAdditionalClassesRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\UnlistedAdditionalClassesSiblingRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ValidationRulesRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest as Requests;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestRuleTypeResolver;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestTypeRegistry;
use jbboehr\PhpstanLaravelValidation\Validation\InvalidCustomRuleContractException;
use PHPStan\Analyser\ResultCache\ResultCacheMetaExtension;
use PHPStan\File\FileHelper;
use PHPStan\Parser\Parser;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\VerbosityLevel;

final class FormRequestTypeRegistryTest extends \PHPStan\Testing\PHPStanTestCase
{
    /** @return iterable<string, array{list<class-string<FormRequest>>, string|null}> */
    public static function requestHierarchies(): iterable
    {
        yield 'leaf' => [[Requests\PolymorphicRequest::class], 'array{value: string}'];
        yield 'documented final leaf' => [[Requests\PolymorphicDocumentedFinalRequest::class], 'array{value: string}'];
        yield 'same inherited rules' => [[
            Requests\PolymorphicRequest::class, Requests\PolymorphicInheritedRequest::class,
        ], 'array{value: string}'];
        yield 'abstract descendant only' => [[
            Requests\PolymorphicRequest::class, Requests\PolymorphicAbstractRequest::class,
        ], 'array{value: string}'];
        yield 'different rules' => [[
            Requests\PolymorphicRequest::class, Requests\PolymorphicChildRequest::class,
        ], 'array{value: array}|array{value: string}'];
        yield 'grandchild through abstract intermediate' => [[
            Requests\PolymorphicRequest::class, Requests\PolymorphicGrandchildRequest::class,
        ], 'array{value: array}|array{value: string}'];
        yield 'abstract receiver' => [[
            Requests\PolymorphicAbstractRequest::class, Requests\PolymorphicGrandchildRequest::class,
        ], 'array{value: array}'];
        yield 'unsafe child despite final rules' => [[
            Requests\PolymorphicFinalRulesRequest::class, Requests\PolymorphicHookRequest::class,
        ], null];
    }

    /**
     * @dataProvider requestHierarchies
     * @param non-empty-list<class-string<FormRequest>> $classes
     */
    public function testReceiverIncludesEveryKnownConcreteContract(array $classes, ?string $expected): void
    {
        $reflection = self::getContainer()->getByType(ReflectionProvider::class)->getClass($classes[0]);
        $registry = $this->createIsolatedRegistry($classes, []);
        self::assertSame($expected, $registry->getType($reflection)?->describe(VerbosityLevel::precise()));
    }

    public function testTrustStillAppliesOnlyToTheConfiguredClass(): void
    {
        $reflection = self::getContainer()->getByType(ReflectionProvider::class)
            ->getClass(Requests\PolymorphicTrustedFinalRequest::class);
        $untrusted = $this->createIsolatedRegistry(
            [Requests\PolymorphicTrustedFinalRequest::class],
            [Requests\PolymorphicRequest::class]
        );
        self::assertNull($untrusted->getType($reflection));

        $trusted = $this->createIsolatedRegistry([], [Requests\PolymorphicTrustedFinalRequest::class]);
        $type = $trusted->getType($reflection);
        self::assertNotNull($type);
        self::assertSame('array{value: string}', $type->describe(VerbosityLevel::precise()));

        $parent = self::getContainer()->getByType(ReflectionProvider::class)
            ->getClass(Requests\PolymorphicRequest::class);
        self::assertNull($untrusted->getType($parent));
        $trustedLeaf = $this->createIsolatedRegistry([], [Requests\PolymorphicRequest::class]);
        self::assertSame('array{value: string}', $trustedLeaf->getType($parent)?->describe(VerbosityLevel::precise()));
    }

    public function testSafeOverrideDoesNotDiscardTheValidatedContract(): void
    {
        $registry = $this->createIsolatedRegistry([
            Requests\PolymorphicSafeRequest::class, Requests\PolymorphicSafeChildRequest::class,
        ], []);
        $parent = self::getContainer()->getByType(ReflectionProvider::class)
            ->getClass(Requests\PolymorphicSafeRequest::class);
        self::assertSame('array{value: string}', $registry->getType($parent)?->describe(VerbosityLevel::precise()));
        self::assertNull($registry->getType($parent, 'safe'));
    }

    public function testAnonymousDescendantsPreventAParentOnlyContract(): void
    {
        $registry = $this->createIsolatedRegistry([], [], [__DIR__ . '/Fixtures/FormRequest/PolymorphicAnonymousRequest.php']);
        $parent = self::getContainer()->getByType(ReflectionProvider::class)
            ->getClass(Requests\PolymorphicAnonymousRequest::class);
        self::assertNull($registry->getType($parent));
        self::assertNull($registry->getType($parent, 'safe'));
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

    public function testLiteralRulesMethodIsEligibleForExportedFingerprinting(): void
    {
        $container = self::getContainer();
        $reflectionProvider = $container->getByType(ReflectionProvider::class);

        self::assertTrue(
            $container->getByType(FormRequestRuleTypeResolver::class)
                ->hasExportableLiteralRulesMethodBody($reflectionProvider->getClass(BasicRequest::class))
        );
        self::assertFalse(
            $container->getByType(FormRequestRuleTypeResolver::class)
                ->hasExportableLiteralRulesMethodBody($reflectionProvider->getClass(ClassConstantRequest::class))
        );
        foreach ([Requests\CustomRuleRequest::class, Requests\InRuleRequest::class] as $className) {
            self::assertFalse(
                $container->getByType(FormRequestRuleTypeResolver::class)
                    ->hasExportableLiteralRulesMethodBody($reflectionProvider->getClass($className)),
                $className
            );
        }
    }

    public function testRuleDependenciesIncludeNestedCustomRulesAndClassConstants(): void
    {
        $container = self::getContainer();
        $reflectionProvider = $container->getByType(ReflectionProvider::class);
        $resolver = $container->getByType(FormRequestRuleTypeResolver::class);

        self::assertContains(
            Requests\FormRequestStringRule::class,
            $resolver->sourceDependencyClassNames($reflectionProvider->getClass(Requests\CustomRuleRequest::class))
        );
        self::assertContains(
            ['className' => Requests\RuleConstants::class, 'constantName' => 'RULES'],
            $resolver->sourceDependencyClassConstantReferences(
                $reflectionProvider->getClass(ClassConstantRequest::class)
            )
        );
    }

    public function testInvalidCustomRuleContractPropagatesThroughTheRegistry(): void
    {
        // The fixture must stay outside normal PHP source discovery.
        require_once __DIR__ . '/CustomRules/InvalidContractRequest.inc';
        $className = 'jbboehr\\PhpstanLaravelValidation\\Test\\CustomRules\\InvalidContractRequest';
        $reflection = self::getContainer()->getByType(ReflectionProvider::class)->getClass($className);
        $registry = $this->createIsolatedRegistry([$className], []);

        $this->expectException(InvalidCustomRuleContractException::class);
        $this->expectExceptionMessage(
            'Invalid PHPStan type array{ for custom validation rule '
                . 'jbboehr\\PhpstanLaravelValidation\\Test\\CustomRules\\InvalidAttributeRule'
        );
        $registry->getType($reflection);
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
            fileHelper: $container->getByType(FileHelper::class),
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
            fileHelper: $container->getByType(FileHelper::class),
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
