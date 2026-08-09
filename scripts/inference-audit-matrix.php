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

namespace jbboehr\PhpstanLaravelValidation\Script;

use jbboehr\PhpstanLaravelValidation\Test\Support\InferenceAuditProfiles;
use RuntimeException;

require __DIR__ . '/../vendor/autoload.php';

$options = getopt('', ['composer:', 'list', 'profile:', 'reinstall', 'update']);
$profiles = InferenceAuditProfiles::all();
$profileNames = repeatedMatrixOption($options, 'profile');
if ($profileNames === []) {
    $profileNames = array_keys($profiles);
}

$unknownProfiles = array_values(array_diff($profileNames, array_keys($profiles)));
if ($unknownProfiles !== []) {
    fwrite(STDERR, 'Unknown audit profile: ' . implode(', ', $unknownProfiles) . "\n");
    exit(2);
}

if (isset($options['list'])) {
    if (isset($options['update']) || isset($options['reinstall']) || isset($options['composer'])) {
        fwrite(STDERR, "--list cannot be combined with execution options\n");
        exit(2);
    }
    foreach ($profileNames as $profileName) {
        fwrite(STDOUT, $profileName . "\t" . $profiles[$profileName]['minimumPhp'] . "\n");
    }
    exit(0);
}

foreach ($profileNames as $profileName) {
    $minimumPhp = $profiles[$profileName]['minimumPhp'];
    if (version_compare(PHP_VERSION, $minimumPhp, '<')) {
        fwrite(STDERR, sprintf(
            "Audit profile %s requires PHP %s or newer; current PHP is %s.\n"
                . "Run a compatible PHP binary or use composer test:audit:matrix:nix.\n",
            $profileName,
            $minimumPhp,
            PHP_VERSION,
        ));
        exit(2);
    }
}

$composerOption = $options['composer'] ?? getenv('COMPOSER_BINARY');
if (is_array($composerOption)) {
    fwrite(STDERR, "--composer may be specified only once\n");
    exit(2);
}
$composer = is_string($composerOption) && $composerOption !== '' ? $composerOption : 'composer';
$auditRoot = __DIR__ . '/../tmp/version-audit';
putenv('COMPOSER_NO_BLOCKING=1');
putenv('COMPOSER_NO_SECURITY_BLOCKING=1');

foreach ($profileNames as $profileName) {
    $profile = $profiles[$profileName];
    $profileDirectory = $auditRoot . '/' . $profileName;

    if (isset($options['reinstall']) && is_dir($profileDirectory)) {
        removeAuditDirectory($profileDirectory, $auditRoot);
    }
    if (!is_dir($profileDirectory)
        && !mkdir($profileDirectory, 0777, true)
        && !is_dir($profileDirectory)
    ) {
        throw new RuntimeException('Could not create ' . $profileDirectory);
    }

    writeAuditComposerManifest($profileDirectory, $profile['constraint']);
    $autoload = $profileDirectory . '/vendor/autoload.php';
    $needsUpdate = !$profile['exact']
        || !is_file($autoload)
        || installedLaravelVersion($profileDirectory . '/composer.lock') !== $profile['expected'];

    fwrite(STDOUT, sprintf(
        "[%s] Laravel %s on PHP %s%s\n",
        $profileName,
        $profile['constraint'],
        PHP_VERSION,
        $needsUpdate ? ' (resolving dependencies)' : ' (using cached exact install)',
    ));

    if ($needsUpdate) {
        runAuditCommand([
            $composer,
            'update',
            '--working-dir=' . $profileDirectory,
            '--prefer-dist',
            '--no-interaction',
            '--no-plugins',
            '--no-progress',
            '--no-scripts',
            '--no-audit',
            '--with-all-dependencies',
            '--ignore-platform-req=php+',
        ]);
    }

    $auditCommand = [
        PHP_BINARY,
        __DIR__ . '/inference-audit.php',
        '--laravel-autoload=' . $autoload,
        '--baseline=' . $profileName,
    ];
    if (isset($options['update'])) {
        $auditCommand[] = '--update';
    }
    runAuditCommand($auditCommand);
}

/**
 * @param array<string, mixed> $options
 * @return list<string>
 */
function repeatedMatrixOption(array $options, string $name): array
{
    $value = $options[$name] ?? [];
    if (is_string($value)) {
        return [$value];
    }
    if (!is_array($value)) {
        return [];
    }

    $values = [];
    foreach ($value as $item) {
        if (is_string($item) && !in_array($item, $values, true)) {
            $values[] = $item;
        }
    }

    return $values;
}

function writeAuditComposerManifest(string $directory, string $constraint): void
{
    $manifest = [
        'name' => 'phpstan-laravel-validation/version-audit',
        'description' => 'Disposable Laravel runtime for the inference audit',
        'type' => 'project',
        'require' => ['laravel/framework' => $constraint],
        'minimum-stability' => 'stable',
        'prefer-stable' => true,
        'config' => [
            'allow-plugins' => false,
            'sort-packages' => true,
        ],
    ];
    $json = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n";
    if (file_put_contents($directory . '/composer.json', $json) === false) {
        throw new RuntimeException('Could not write audit Composer manifest');
    }
}

function installedLaravelVersion(string $lockFile): ?string
{
    if (!is_file($lockFile)) {
        return null;
    }
    $lock = json_decode((string) file_get_contents($lockFile), true);
    if (!is_array($lock)) {
        return null;
    }

    foreach (['packages', 'packages-dev'] as $key) {
        $packages = $lock[$key] ?? null;
        if (!is_array($packages)) {
            continue;
        }
        foreach ($packages as $package) {
            if (is_array($package)
                && ($package['name'] ?? null) === 'laravel/framework'
                && is_string($package['version'] ?? null)
            ) {
                return ltrim($package['version'], 'v');
            }
        }
    }

    return null;
}

/**
 * @param list<string> $command
 */
function runAuditCommand(array $command): void
{
    $process = proc_open($command, [STDIN, STDOUT, STDERR], $pipes);
    if (!is_resource($process)) {
        throw new RuntimeException('Could not start: ' . implode(' ', $command));
    }
    $status = proc_close($process);
    if ($status !== 0) {
        exit($status > 0 ? $status : 1);
    }
}

function removeAuditDirectory(string $directory, string $auditRoot): void
{
    $normalizedRoot = rtrim(str_replace('\\', '/', $auditRoot), '/');
    $normalizedDirectory = rtrim(str_replace('\\', '/', $directory), '/');
    if (!str_starts_with($normalizedDirectory, $normalizedRoot . '/')
        || dirname($normalizedDirectory) !== $normalizedRoot
    ) {
        throw new RuntimeException('Refusing to remove unexpected audit directory: ' . $directory);
    }

    $iterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
        \RecursiveIteratorIterator::CHILD_FIRST,
    );
    foreach ($iterator as $item) {
        if (!$item instanceof \SplFileInfo) {
            throw new RuntimeException('Unexpected audit directory entry');
        }
        if ($item->isDir() && !$item->isLink()) {
            if (!rmdir($item->getPathname())) {
                throw new RuntimeException('Could not remove audit directory ' . $item->getPathname());
            }
        } elseif (!unlink($item->getPathname())) {
            throw new RuntimeException('Could not remove audit file ' . $item->getPathname());
        }
    }
    if (!rmdir($directory)) {
        throw new RuntimeException('Could not remove audit profile directory ' . $directory);
    }
}
