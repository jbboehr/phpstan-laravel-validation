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

use Composer\Autoload\ClassLoader;
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Test\Support\InferenceAuditProfiles;
use PHPUnit\Framework\TestCase;

final class LaravelVersionContextTest extends TestCase
{
    /** @var list<string> */
    private array $temporaryDirectories = [];

    /** @var list<ClassLoader> */
    private array $temporaryLoaders = [];

    protected function tearDown(): void
    {
        foreach ($this->temporaryLoaders as $loader) {
            $loader->unregister();
        }

        foreach ($this->temporaryDirectories as $directory) {
            foreach (['composer.lock', 'vendor/composer/installed.php'] as $file) {
                $path = $directory . '/' . $file;
                if (is_file($path)) {
                    unlink($path);
                }
            }
            foreach (['vendor/composer', 'vendor'] as $subdirectory) {
                $path = $directory . '/' . $subdirectory;
                if (is_dir($path)) {
                    rmdir($path);
                }
            }
            rmdir($directory);
        }
    }

    public function testExplicitVersionIsNormalizedAndTreatedAsFrameworkVersion(): void
    {
        $context = new LaravelVersionContext('', 'v13.4');

        self::assertSame('13.4.0', $context->getVersion());
        self::assertTrue($context->isSupported());
        self::assertTrue($context->isAtLeast('13.4.0'));
        self::assertTrue($context->hasFrameworkVersion());
    }

    public function testExplicitVersionOverridesComposerLock(): void
    {
        $directory = $this->createComposerProject([
            'packages' => [
                ['name' => 'laravel/framework', 'version' => 'v10.50.2'],
            ],
        ]);
        $this->registerInstalledPackages($directory, [
            'laravel/framework' => $this->installedPackage('v13.4.0'),
        ]);

        $context = new LaravelVersionContext($directory, '12.22.0');

        self::assertSame('12.22.0', $context->getVersion());
    }

    public function testInstalledFrameworkTakesPrecedenceOverStaleLockAndReplacedComponent(): void
    {
        $directory = $this->createComposerProject([
            'packages' => [
                ['name' => 'laravel/framework', 'version' => 'v10.50.2'],
            ],
        ]);
        $this->registerInstalledPackages($directory, [
            'laravel/framework' => $this->installedPackage('v13.4.0'),
            'illuminate/validation' => [
                'dev_requirement' => false,
                'replaced' => ['self.version'],
            ],
        ]);

        $context = new LaravelVersionContext($directory);

        self::assertSame('13.4.0', $context->getVersion());
        self::assertTrue($context->hasFrameworkVersion());
    }

    public function testInstalledValidationComponentTakesPrecedenceOverLock(): void
    {
        $directory = $this->createComposerProject([
            'packages' => [
                ['name' => 'laravel/framework', 'version' => 'v10.50.2'],
            ],
        ]);
        $this->registerInstalledPackages($directory, [
            'illuminate/validation' => $this->installedPackage('v12.22.0'),
        ]);

        $context = new LaravelVersionContext($directory);

        self::assertSame('12.22.0', $context->getVersion());
        self::assertTrue($context->isSupported());
        self::assertFalse($context->hasFrameworkVersion());
    }

    public function testUnstableInstalledVersionDoesNotFallBackToPotentiallyStaleLock(): void
    {
        $directory = $this->createComposerProject([
            'packages' => [
                ['name' => 'laravel/framework', 'version' => 'v10.50.2'],
            ],
        ]);
        $this->registerInstalledPackages($directory, [
            'laravel/framework' => $this->installedPackage('dev-main'),
        ]);

        $context = new LaravelVersionContext($directory);

        self::assertNull($context->getVersion());
        self::assertFalse($context->isSupported());
    }

    public function testLockIsUsedWhenMatchingInstalledDataHasNoLaravelPackage(): void
    {
        $directory = $this->createComposerProject([
            'packages' => [
                ['name' => 'laravel/framework', 'version' => 'v10.50.2'],
            ],
        ]);
        $this->registerInstalledPackages($directory, [
            'psr/log' => $this->installedPackage('3.0.2'),
        ]);

        $context = new LaravelVersionContext($directory);

        self::assertSame('10.50.2', $context->getVersion());
        self::assertTrue($context->hasFrameworkVersion());
    }

    public function testInstalledVersionsFromUnrelatedComposerRootAreIgnored(): void
    {
        $unrelatedDirectory = $this->createTemporaryDirectory();
        $this->registerInstalledPackages($unrelatedDirectory, [
            'laravel/framework' => $this->installedPackage('v13.4.0'),
        ]);
        $analyzedDirectory = $this->createComposerProject([
            'packages' => [
                ['name' => 'laravel/framework', 'version' => 'v10.50.2'],
            ],
        ]);

        $context = new LaravelVersionContext($analyzedDirectory);

        self::assertSame('10.50.2', $context->getVersion());
        self::assertTrue($context->hasFrameworkVersion());
    }

    public function testInstalledDatasetWithoutRootInstallPathIsIgnored(): void
    {
        $legacyDirectory = $this->createTemporaryDirectory();
        $this->registerInstalledPackages(
            $legacyDirectory,
            ['laravel/framework' => $this->installedPackage('v13.4.0')],
            false
        );
        $analyzedDirectory = $this->createComposerProject([
            'packages' => [
                ['name' => 'laravel/framework', 'version' => 'v10.50.2'],
            ],
        ]);

        $context = new LaravelVersionContext($analyzedDirectory);

        self::assertSame('10.50.2', $context->getVersion());
        self::assertTrue($context->hasFrameworkVersion());
    }

    public function testAutoDetectionPrefersFrameworkAcrossLockSections(): void
    {
        $directory = $this->createComposerProject([
            'packages' => [
                ['name' => 'illuminate/validation', 'version' => 'v13.3.0'],
            ],
            'packages-dev' => [
                ['name' => 'laravel/framework', 'version' => 'v13.4.0'],
            ],
        ]);

        $context = new LaravelVersionContext($directory);

        self::assertSame('13.4.0', $context->getVersion());
        self::assertTrue($context->hasFrameworkVersion());
    }

    public function testAutoDetectionReadsRegularPackagesAfterUnrelatedEntries(): void
    {
        $directory = $this->createComposerProject([
            'packages' => [
                ['name' => 'psr/log', 'version' => '3.0.2'],
                ['name' => 'laravel/framework', 'version' => 'v10.50.2'],
            ],
        ]);

        $context = new LaravelVersionContext($directory . DIRECTORY_SEPARATOR);

        self::assertSame('10.50.2', $context->getVersion());
        self::assertTrue($context->hasFrameworkVersion());
    }

    public function testAutoDetectionFallsBackToValidationComponent(): void
    {
        $directory = $this->createComposerProject([
            'packages-dev' => [
                ['name' => 'illuminate/validation', 'version' => 'v12.22.0'],
            ],
        ]);

        $context = new LaravelVersionContext($directory);

        self::assertSame('12.22.0', $context->getVersion());
        self::assertTrue($context->isSupported());
        self::assertFalse($context->hasFrameworkVersion());
    }

    public function testResultCacheHashTracksEffectiveVersionContext(): void
    {
        $framework = new LaravelVersionContext('', '13.4.0');
        $sameFramework = new LaravelVersionContext('', 'v13.4');
        $otherFramework = new LaravelVersionContext('', '13.3.0');
        $componentDirectory = $this->createComposerProject([
            'packages' => [
                ['name' => 'illuminate/validation', 'version' => 'v13.4.0'],
            ],
        ]);
        $component = new LaravelVersionContext($componentDirectory);

        self::assertSame('phpstan-laravel-validation.laravel-version', $framework->getKey());
        self::assertSame($framework->getHash(), $sameFramework->getHash());
        self::assertNotSame($framework->getHash(), $otherFramework->getHash());
        self::assertNotSame($framework->getHash(), $component->getHash());
    }

    public function testEveryAuditProfileIsInsideTheSupportedVersionRange(): void
    {
        $majors = [];
        foreach (InferenceAuditProfiles::all() as $profile) {
            $context = new LaravelVersionContext('', $profile['expected']);
            self::assertTrue($context->isSupported());
            $majors[] = (int) explode('.', $profile['expected'])[0];
        }
        if ($majors === []) {
            self::fail('The Laravel inference audit must define at least one profile.');
        }

        $minimumMajor = min($majors);
        $maximumMajor = max($majors);
        self::assertFalse((new LaravelVersionContext('', (string) ($minimumMajor - 1)))->isSupported());
        self::assertFalse((new LaravelVersionContext('', (string) ($maximumMajor + 1)))->isSupported());
    }

    public function testMissingMalformedAndUnsupportedVersionsRemainConservative(): void
    {
        $emptyWorkingDirectory = new LaravelVersionContext('');
        self::assertNull($emptyWorkingDirectory->getVersion());

        $missing = new LaravelVersionContext($this->createTemporaryDirectory());
        self::assertNull($missing->getVersion());
        self::assertFalse($missing->isSupported());
        self::assertFalse($missing->isAtLeast('12.22.0'));

        $directory = $this->createTemporaryDirectory();
        file_put_contents($directory . '/composer.lock', '{');
        $malformed = new LaravelVersionContext($directory);
        self::assertNull($malformed->getVersion());

        $unsupported = new LaravelVersionContext('', '14');
        self::assertSame('14.0.0', $unsupported->getVersion());
        self::assertFalse($unsupported->isSupported());
        self::assertFalse($unsupported->isAtLeast('13.4.0'));
        self::assertFalse($unsupported->hasFrameworkVersion());
    }

    /**
     * @dataProvider invalidExplicitVersionProvider
     */
    public function testInvalidExplicitVersionIsRejected(string $version): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('must be "auto" or a stable numeric version');

        new LaravelVersionContext('', $version);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function invalidExplicitVersionProvider(): iterable
    {
        yield 'development branch' => ['13.x-dev'];
        yield 'leading content' => ['release-13.4.0'];
        yield 'trailing newline' => ["13.4.0\n"];
    }

    /**
     * @param array<string, mixed> $lock
     */
    private function createComposerProject(array $lock): string
    {
        $directory = $this->createTemporaryDirectory();
        file_put_contents($directory . '/composer.lock', json_encode($lock, JSON_THROW_ON_ERROR));

        return $directory;
    }

    private function createTemporaryDirectory(): string
    {
        $directory = sys_get_temp_dir() . '/phpstan-laravel-validation-' . bin2hex(random_bytes(8));
        mkdir($directory);
        $this->temporaryDirectories[] = $directory;

        return $directory;
    }

    /**
     * @param array<string, array<string, mixed>> $packages
     */
    private function registerInstalledPackages(
        string $directory,
        array $packages,
        bool $includeRootInstallPath = true
    ): void {
        $vendorDirectory = $directory . '/vendor';
        mkdir($vendorDirectory);
        mkdir($vendorDirectory . '/composer');

        $data = [
            'root' => [
                'name' => 'example/project',
                'pretty_version' => 'dev-main',
                'version' => 'dev-main',
                'reference' => null,
                'type' => 'project',
                'aliases' => [],
                'dev' => true,
            ],
            'versions' => $packages,
        ];
        if ($includeRootInstallPath) {
            $data['root']['install_path'] = $directory;
        }
        file_put_contents(
            $vendorDirectory . '/composer/installed.php',
            '<?php return ' . var_export($data, true) . ';'
        );

        $loader = new ClassLoader($vendorDirectory);
        $loader->register();
        $this->temporaryLoaders[] = $loader;
    }

    /**
     * @return array<string, mixed>
     */
    private function installedPackage(string $version): array
    {
        return [
            'pretty_version' => $version,
            'version' => $version,
            'reference' => null,
            'type' => 'library',
            'aliases' => [],
            'dev_requirement' => false,
        ];
    }
}
