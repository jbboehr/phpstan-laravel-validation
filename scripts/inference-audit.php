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

use Composer\Autoload\ClassLoader;
use jbboehr\PhpstanLaravelValidation\Test\Support\InferenceAudit;
use jbboehr\PhpstanLaravelValidation\Test\Support\InferenceAuditCases;
use jbboehr\PhpstanLaravelValidation\Test\Support\InferenceAuditProfiles;
use PHPStan\Testing\PHPStanTestCase;
use RuntimeException;

$options = getopt('', ['baseline:', 'laravel-autoload:', 'update']);
$laravelAutoload = $options['laravel-autoload'] ?? null;

if ($laravelAutoload !== null) {
    if (!is_string($laravelAutoload) || !is_file($laravelAutoload)) {
        fwrite(STDERR, "--laravel-autoload must name an existing Composer autoload.php\n");
        exit(2);
    }
    require $laravelAutoload;
}

$projectLoader = require __DIR__ . '/../vendor/autoload.php';
if ($laravelAutoload !== null && $projectLoader instanceof ClassLoader) {
    spl_autoload_unregister([$projectLoader, 'loadClass']);
    $projectLoader->register(false);
}

final class InferenceAuditContainer extends PHPStanTestCase
{
    /**
     * @return list<string>
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../extension.neon'];
    }
}

InferenceAuditContainer::getContainer();
$actual = InferenceAudit::run(InferenceAuditCases::cases());
$baselineName = $options['baseline'] ?? null;

if ($baselineName === null) {
    fwrite(STDOUT, auditJson($actual));
    exit(0);
}
if (!is_string($baselineName) || preg_match('/^[0-9A-Za-z.-]+$/D', $baselineName) !== 1) {
    fwrite(STDERR, "--baseline contains invalid characters\n");
    exit(2);
}

$profiles = InferenceAuditProfiles::all();
if (!isset($profiles[$baselineName])) {
    fwrite(STDERR, 'Unknown audit baseline: ' . $baselineName . "\n");
    exit(2);
}

$profile = $profiles[$baselineName];
assertAuditVersion($actual['laravel'], $profile['expected'], $profile['exact']);
$baselineFile = __DIR__ . '/../tests/fixtures/version-audit/' . $baselineName . '.json';
$recorded = [
    'profile' => $baselineName,
    'constraint' => $profile['constraint'],
    'recordedVersion' => $actual['laravel'],
    'commit' => $profile['commit'],
    'cases' => $actual['cases'],
];

if (isset($options['update'])) {
    $directory = dirname($baselineFile);
    if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
        throw new RuntimeException('Could not create audit baseline directory');
    }
    $temporaryFile = tempnam($directory, '.inference-audit.');
    if ($temporaryFile === false) {
        throw new RuntimeException('Could not create temporary audit baseline');
    }

    try {
        if (file_put_contents($temporaryFile, auditJson($recorded)) === false) {
            throw new RuntimeException('Could not write audit baseline');
        }
        if (!rename($temporaryFile, $baselineFile)) {
            throw new RuntimeException('Could not install audit baseline');
        }
    } finally {
        if (is_file($temporaryFile)) {
            unlink($temporaryFile);
        }
    }

    fwrite(STDOUT, 'Updated ' . $baselineFile . "\n");
    exit(0);
}

if (!is_file($baselineFile)) {
    fwrite(STDERR, 'Missing audit baseline: ' . $baselineFile . "\n");
    exit(2);
}

$expected = json_decode((string) file_get_contents($baselineFile), true, 512, JSON_THROW_ON_ERROR);
if (!is_array($expected) || !isset($expected['cases']) || !is_array($expected['cases'])) {
    fwrite(STDERR, 'Invalid audit baseline: ' . $baselineFile . "\n");
    exit(2);
}

if ($expected['cases'] !== $actual['cases']) {
    $caseIds = array_unique(array_merge(array_keys($expected['cases']), array_keys($actual['cases'])));
    $changed = array_values(array_filter(
        $caseIds,
        static fn (string $id): bool => ($expected['cases'][$id] ?? null) !== ($actual['cases'][$id] ?? null)
    ));
    fwrite(STDERR, 'Audit baseline differs for: ' . implode(', ', $changed) . "\n");
    exit(1);
}

fwrite(STDOUT, $baselineName . ' audit baseline matches Laravel ' . $actual['laravel'] . "\n");

/**
 * @param array<mixed, mixed> $value
 */
function auditJson(array $value): string
{
    return json_encode(
        $value,
        JSON_PRETTY_PRINT
            | JSON_PRESERVE_ZERO_FRACTION
            | JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | JSON_THROW_ON_ERROR
    ) . "\n";
}

function assertAuditVersion(string $actual, string $expected, bool $exact): void
{
    $matches = $exact ? $actual === $expected : str_starts_with($actual, $expected . '.');
    if (!$matches) {
        $description = $exact ? $expected : $expected . '.x';
        fwrite(STDERR, 'Audit profile expects Laravel ' . $description . ', got ' . $actual . "\n");
        exit(2);
    }
}
