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

namespace jbboehr\PhpstanLaravelValidation\Validation;

use Composer\InstalledVersions;
use PHPStan\Analyser\ResultCache\ResultCacheMetaExtension;

final class LaravelVersionContext implements ResultCacheMetaExtension
{
    private const MIN_SUPPORTED_MAJOR = 10;
    private const MAX_SUPPORTED_MAJOR = 13;

    private ?string $version = null;
    private bool $frameworkVersion = false;

    public function __construct(string $workingDirectory, string $configuredVersion = 'auto')
    {
        if ($configuredVersion !== 'auto') {
            $this->version = self::normalizeVersion($configuredVersion);
            if ($this->version === null) {
                throw new \InvalidArgumentException(sprintf(
                    'phpstanLaravelValidation.laravelVersion must be "auto" or a stable numeric version, %s given',
                    $configuredVersion
                ));
            }

            $this->frameworkVersion = true;
            return;
        }

        $installedVersion = self::findInstalledVersion($workingDirectory);
        if ($installedVersion['detected']) {
            $this->version = $installedVersion['version'];
            $this->frameworkVersion = $installedVersion['framework'];
            return;
        }

        $lock = self::readComposerLock($workingDirectory);
        if ($lock === null) {
            return;
        }

        foreach (['laravel/framework', 'illuminate/validation'] as $packageName) {
            $version = self::findPackageVersion($lock, $packageName);
            if ($version === null) {
                continue;
            }

            $this->version = $version;
            $this->frameworkVersion = $packageName === 'laravel/framework';
            return;
        }
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function isSupported(): bool
    {
        if ($this->version === null) {
            return false;
        }

        $major = (int) explode('.', $this->version)[0];

        return $major >= self::MIN_SUPPORTED_MAJOR
            && $major <= self::MAX_SUPPORTED_MAJOR;
    }

    public function isAtLeast(string $version): bool
    {
        return $this->isSupported()
            && version_compare((string) $this->version, $version, '>=');
    }

    public function hasFrameworkVersion(): bool
    {
        return $this->frameworkVersion && $this->isSupported();
    }

    public function getKey(): string
    {
        return 'phpstan-laravel-validation.laravel-version';
    }

    public function getHash(): string
    {
        return hash('sha256', implode('|', [
            $this->version ?? 'unknown',
            $this->frameworkVersion ? 'framework' : 'component',
        ]));
    }

    /**
     * @return array{detected: bool, version: ?string, framework: bool}
     */
    private static function findInstalledVersion(string $workingDirectory): array
    {
        if ($workingDirectory === '') {
            return ['detected' => false, 'version' => null, 'framework' => false];
        }

        foreach (InstalledVersions::getAllRawData() as $installed) {
            $installPath = self::findRootInstallPath($installed);
            if ($installPath === null || !self::isSameDirectory($workingDirectory, $installPath)) {
                continue;
            }

            foreach (['laravel/framework', 'illuminate/validation'] as $packageName) {
                $package = $installed['versions'][$packageName] ?? null;
                if ($package === null) {
                    continue;
                }

                $version = $package['pretty_version'] ?? null;
                if (!is_string($version)) {
                    // Replaced and provided packages are present in InstalledVersions
                    // without being the package whose implementation is installed.
                    continue;
                }

                return [
                    'detected' => true,
                    'version' => self::normalizeVersion($version),
                    'framework' => $packageName === 'laravel/framework',
                ];
            }
        }

        return ['detected' => false, 'version' => null, 'framework' => false];
    }

    private static function findRootInstallPath(mixed $installed): ?string
    {
        if (!is_array($installed)) {
            return null;
        }

        $root = $installed['root'] ?? null;
        if (!is_array($root)) {
            return null;
        }

        $installPath = $root['install_path'] ?? null;
        return is_string($installPath) ? $installPath : null;
    }

    private static function isSameDirectory(string $left, string $right): bool
    {
        $realLeft = realpath($left);
        $realRight = realpath($right);
        if (is_string($realLeft) && is_string($realRight)) {
            return $realLeft === $realRight;
        }

        return rtrim(str_replace('\\', '/', $left), '/')
            === rtrim(str_replace('\\', '/', $right), '/');
    }

    /**
     * @return array<mixed, mixed>|null
     */
    private static function readComposerLock(string $workingDirectory): ?array
    {
        if ($workingDirectory === '') {
            return null;
        }

        $contents = @file_get_contents(rtrim($workingDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'composer.lock');
        if (!is_string($contents)) {
            return null;
        }

        try {
            $lock = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }

        return is_array($lock) ? $lock : null;
    }

    /**
     * @param array<mixed, mixed> $lock
     */
    private static function findPackageVersion(array $lock, string $packageName): ?string
    {
        foreach (['packages', 'packages-dev'] as $section) {
            $packages = $lock[$section] ?? null;
            if (!is_array($packages)) {
                continue;
            }

            foreach ($packages as $package) {
                if (!is_array($package) || ($package['name'] ?? null) !== $packageName) {
                    continue;
                }

                $version = $package['version'] ?? null;
                return is_string($version) ? self::normalizeVersion($version) : null;
            }
        }

        return null;
    }

    private static function normalizeVersion(string $version): ?string
    {
        if (preg_match('/^v?(\d+)(?:\.(\d+))?(?:\.(\d+))?$/D', $version, $matches) !== 1) {
            return null;
        }

        return sprintf(
            '%d.%d.%d',
            (int) $matches[1],
            isset($matches[2]) ? (int) $matches[2] : 0,
            isset($matches[3]) ? (int) $matches[3] : 0
        );
    }
}
