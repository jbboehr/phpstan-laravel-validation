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

const DEFAULT_ITERATIONS = 5;

$bookStackArgument = $argv[1] ?? null;
if (!is_string($bookStackArgument) || $bookStackArgument === '') {
    fwrite(STDERR, "Usage: php scripts/benchmark-bookstack.php BOOKSTACK_ROOT [ITERATIONS]\n");
    exit(2);
}

$bookStackRoot = realpath($bookStackArgument);
if ($bookStackRoot === false || !is_dir($bookStackRoot)) {
    fwrite(STDERR, sprintf("BookStack root does not exist: %s\n", $bookStackArgument));
    exit(2);
}

$iterations = isset($argv[2]) ? filter_var($argv[2], FILTER_VALIDATE_INT) : DEFAULT_ITERATIONS;
if (!is_int($iterations) || $iterations < 1 || $iterations > 20) {
    fwrite(STDERR, "ITERATIONS must be an integer from 1 through 20.\n");
    exit(2);
}

$phpStan = $bookStackRoot . '/vendor/bin/phpstan';
$nativeConfiguration = $bookStackRoot . '/phpstan.neon.dist';
$extensionConfiguration = $bookStackRoot
    . '/vendor/jbboehr/phpstan-laravel-validation/extension.neon';
foreach ([$phpStan, $nativeConfiguration, $extensionConfiguration] as $requiredFile) {
    if (!is_file($requiredFile)) {
        fwrite(STDERR, sprintf("Required benchmark file does not exist: %s\n", $requiredFile));
        exit(2);
    }
}

$phpBinary = getenv('BOOKSTACK_BENCHMARK_PHP');
if (!is_string($phpBinary) || $phpBinary === '') {
    $phpBinary = PHP_BINARY;
}
$timeBinary = findTimeBinary();
if ($timeBinary === null) {
    fwrite(STDERR, "GNU time is required; set BOOKSTACK_BENCHMARK_TIME to its executable path.\n");
    exit(2);
}

$benchmarkRoot = createBenchmarkRoot();
$keepWorkDirectory = getenv('BOOKSTACK_BENCHMARK_KEEP') === '1';
register_shutdown_function(static function () use ($benchmarkRoot, $keepWorkDirectory): void {
    if ($keepWorkDirectory) {
        fwrite(STDERR, sprintf("Benchmark work directory retained at %s\n", $benchmarkRoot));
        return;
    }

    removeBenchmarkDirectory($benchmarkRoot);
});

$versions = packageVersions($bookStackRoot . '/composer.lock');
$applicationSize = applicationSize($bookStackRoot . '/app');
$metadata = [
    'recordedAtUtc' => gmdate(DATE_ATOM),
    'bookstackRoot' => $bookStackRoot,
    'bookstackCommit' => captureCommand(['git', '-C', $bookStackRoot, 'rev-parse', 'HEAD']),
    'bookstackAppPhpFiles' => $applicationSize['files'],
    'bookstackAppPhpLines' => $applicationSize['lines'],
    'php' => PHP_VERSION,
    'phpBinary' => $phpBinary,
    'timeBinary' => $timeBinary,
    'phpstan' => $versions['phpstan/phpstan'] ?? 'unknown',
    'laravel' => $versions['laravel/framework'] ?? 'unknown',
    'larastan' => $versions['larastan/larastan'] ?? 'unknown',
    'extension' => $versions['jbboehr/phpstan-laravel-validation'] ?? 'unknown',
    'logicalCpus' => logicalCpuCount(),
    'cpuModel' => cpuModel(),
    'iterations' => $iterations,
    'operatingSystem' => php_uname(),
];

fwrite(STDOUT, "# BookStack PHPStan benchmark\n\n");
foreach ($metadata as $name => $value) {
    fwrite(STDOUT, sprintf("%s: %s\n", $name, (string) $value));
}
fwrite(STDOUT, "\n# Raw samples\n");
fwrite(STDOUT, "cache,workers,configuration,iteration,wall_seconds,user_seconds,system_seconds,max_rss_kb,errors\n");

$rows = [];
$coldVariants = [
    ['workers' => 'serial', 'configuration' => 'baseline', 'extension' => false, 'serial' => true],
    ['workers' => 'serial', 'configuration' => 'extension', 'extension' => true, 'serial' => true],
    ['workers' => 'default', 'configuration' => 'baseline', 'extension' => false, 'serial' => false],
    ['workers' => 'default', 'configuration' => 'extension', 'extension' => true, 'serial' => false],
];

for ($iteration = 1; $iteration <= $iterations; ++$iteration) {
    $offset = ($iteration - 1) % count($coldVariants);
    $orderedVariants = array_merge(
        array_slice($coldVariants, $offset),
        array_slice($coldVariants, 0, $offset),
    );

    foreach ($orderedVariants as $variant) {
        $id = sprintf('cold-%s-%s-%d', $variant['workers'], $variant['configuration'], $iteration);
        $configuration = writeBenchmarkConfiguration(
            $benchmarkRoot,
            $id,
            $nativeConfiguration,
            $extensionConfiguration,
            $variant['extension'],
            $variant['serial'],
        );
        $rows[] = runBenchmark(
            $id,
            'cold',
            $variant['workers'],
            $variant['configuration'],
            $iteration,
            $configuration,
            $bookStackRoot,
            $phpStan,
            $phpBinary,
            $timeBinary,
            $benchmarkRoot,
        );
    }
}

$warmConfigurations = [];
foreach ([false, true] as $withExtension) {
    $configurationName = $withExtension ? 'extension' : 'baseline';
    $id = 'warm-default-' . $configurationName;
    $warmConfigurations[$configurationName] = writeBenchmarkConfiguration(
        $benchmarkRoot,
        $id,
        $nativeConfiguration,
        $extensionConfiguration,
        $withExtension,
        false,
    );
    fwrite(STDERR, sprintf("Priming warm cache for %s...\n", $configurationName));
    runPhpStan(
        'prime-' . $configurationName,
        $warmConfigurations[$configurationName],
        $bookStackRoot,
        $phpStan,
        $phpBinary,
        $timeBinary,
        $benchmarkRoot,
    );
}

for ($iteration = 1; $iteration <= $iterations; ++$iteration) {
    $configurationNames = $iteration % 2 === 1
        ? ['baseline', 'extension']
        : ['extension', 'baseline'];
    foreach ($configurationNames as $configurationName) {
        $id = sprintf('warm-default-%s-%d', $configurationName, $iteration);
        $rows[] = runBenchmark(
            $id,
            'warm',
            'default',
            $configurationName,
            $iteration,
            $warmConfigurations[$configurationName],
            $bookStackRoot,
            $phpStan,
            $phpBinary,
            $timeBinary,
            $benchmarkRoot,
        );
    }
}

$summaries = summarizeRows($rows);
fwrite(STDOUT, "\n# Summary\n\n");
fwrite(STDOUT, "| Cache | Workers | Configuration | n | Median wall (s) | Mean wall (s) | Median max RSS (MiB) |\n");
fwrite(STDOUT, "| --- | --- | --- | ---: | ---: | ---: | ---: |\n");
foreach ($summaries as $summary) {
    fwrite(STDOUT, sprintf(
        "| %s | %s | %s | %d | %.3f | %.3f | %.1f |\n",
        $summary['cache'],
        $summary['workers'],
        $summary['configuration'],
        $summary['count'],
        $summary['medianWall'],
        $summary['meanWall'],
        $summary['medianRssKb'] / 1024,
    ));
}

fwrite(STDOUT, "\n# Comparisons\n\n");
foreach (
    [
        ['cold', 'serial', 'Extension overhead, cold serial'],
        ['cold', 'default', 'Extension overhead, cold default workers'],
        ['warm', 'default', 'Extension overhead, warm result cache'],
    ] as [$cache, $workers, $label]
) {
    $baseline = summaryByKey($summaries, $cache, $workers, 'baseline');
    $extension = summaryByKey($summaries, $cache, $workers, 'extension');
    fwrite(STDOUT, sprintf(
        "%s: %+.1f%% wall, %+.1f MiB reported max RSS\n",
        $label,
        percentageChange($baseline['medianWall'], $extension['medianWall']),
        ($extension['medianRssKb'] - $baseline['medianRssKb']) / 1024,
    ));
}
foreach (['baseline', 'extension'] as $configurationName) {
    $serial = summaryByKey($summaries, 'cold', 'serial', $configurationName);
    $parallel = summaryByKey($summaries, 'cold', 'default', $configurationName);
    fwrite(STDOUT, sprintf(
        "Default-worker speedup, %s: %.2fx\n",
        $configurationName,
        $serial['medianWall'] / $parallel['medianWall'],
    ));
}
fwrite(STDOUT, "\n# Measurement notes\n\n");
fwrite(STDOUT, "- Cold runs use a fresh PHPStan tmpDir for every sample.\n");
fwrite(STDOUT, "- Warm runs reuse a primed result cache; their default-worker setting normally starts no workers.\n");
fwrite(STDOUT, "- GNU time reports maximum RSS for the command it observes. In default-parallel mode this is not aggregate process-tree memory; use the serial figures for the clearest incremental-memory comparison.\n");

$jsonOutput = getenv('BOOKSTACK_BENCHMARK_JSON');
if (is_string($jsonOutput) && $jsonOutput !== '') {
    $encoded = json_encode(
        ['metadata' => $metadata, 'samples' => $rows, 'summary' => $summaries],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
    );
    if (file_put_contents($jsonOutput, $encoded . "\n") === false) {
        throw new RuntimeException(sprintf('Could not write benchmark JSON to %s.', $jsonOutput));
    }
}

/**
 * @return array{
 *     cache: string,
 *     workers: string,
 *     configuration: string,
 *     iteration: int,
 *     wallSeconds: float,
 *     userSeconds: float,
 *     systemSeconds: float,
 *     maxRssKb: int,
 *     errors: int
 * }
 */
function runBenchmark(
    string $id,
    string $cache,
    string $workers,
    string $configurationName,
    int $iteration,
    string $configuration,
    string $bookStackRoot,
    string $phpStan,
    string $phpBinary,
    string $timeBinary,
    string $benchmarkRoot,
): array {
    fwrite(STDERR, sprintf("Running %s...\n", $id));
    $measurement = runPhpStan(
        $id,
        $configuration,
        $bookStackRoot,
        $phpStan,
        $phpBinary,
        $timeBinary,
        $benchmarkRoot,
    );

    $row = [
        'cache' => $cache,
        'workers' => $workers,
        'configuration' => $configurationName,
        'iteration' => $iteration,
        ...$measurement,
    ];
    fwrite(STDOUT, sprintf(
        "%s,%s,%s,%d,%.3f,%.3f,%.3f,%d,%d\n",
        $cache,
        $workers,
        $configurationName,
        $iteration,
        $row['wallSeconds'],
        $row['userSeconds'],
        $row['systemSeconds'],
        $row['maxRssKb'],
        $row['errors'],
    ));

    return $row;
}

/**
 * @return array{
 *     wallSeconds: float,
 *     userSeconds: float,
 *     systemSeconds: float,
 *     maxRssKb: int,
 *     errors: int
 * }
 */
function runPhpStan(
    string $id,
    string $configuration,
    string $bookStackRoot,
    string $phpStan,
    string $phpBinary,
    string $timeBinary,
    string $benchmarkRoot,
): array {
    $metricsFile = $benchmarkRoot . '/' . $id . '.time';
    $outputFile = $benchmarkRoot . '/' . $id . '.json';
    $errorFile = $benchmarkRoot . '/' . $id . '.stderr';
    $command = [
        $timeBinary,
        '-f',
        "%e\t%U\t%S\t%M\t%x",
        '-o',
        $metricsFile,
        $phpBinary,
        $phpStan,
        'analyse',
        '--configuration=' . $configuration,
        '--error-format=json',
        '--no-progress',
    ];
    $process = proc_open(
        $command,
        [
            0 => ['file', '/dev/null', 'r'],
            1 => ['file', $outputFile, 'w'],
            2 => ['file', $errorFile, 'w'],
        ],
        $pipes,
        $bookStackRoot,
    );
    if (!is_resource($process)) {
        throw new RuntimeException(sprintf('Could not start benchmark command %s.', $id));
    }
    $exitCode = proc_close($process);

    $metrics = file_get_contents($metricsFile);
    if ($metrics === false) {
        throw new RuntimeException(sprintf('Could not read timing output for %s.', $id));
    }
    $fields = explode("\t", trim($metrics));
    if (count($fields) !== 5) {
        throw new RuntimeException(sprintf('Unexpected GNU time output for %s: %s', $id, trim($metrics)));
    }
    if ($exitCode !== 0 || (int) $fields[4] !== 0) {
        $error = file_get_contents($errorFile);
        throw new RuntimeException(sprintf(
            "PHPStan benchmark %s exited with %d.\n%s",
            $id,
            $exitCode,
            $error === false ? '' : trim($error),
        ));
    }

    $output = file_get_contents($outputFile);
    if ($output === false) {
        throw new RuntimeException(sprintf('Could not read PHPStan output for %s.', $id));
    }
    $decoded = json_decode($output, true, flags: JSON_THROW_ON_ERROR);
    if (!is_array($decoded) || !isset($decoded['totals']) || !is_array($decoded['totals'])) {
        throw new RuntimeException(sprintf('Unexpected PHPStan JSON output for %s.', $id));
    }
    $fileErrors = $decoded['totals']['file_errors'] ?? null;
    $generalErrors = $decoded['totals']['errors'] ?? null;
    if (!is_int($fileErrors) || !is_int($generalErrors)) {
        throw new RuntimeException(sprintf('PHPStan totals are missing for %s.', $id));
    }

    return [
        'wallSeconds' => (float) $fields[0],
        'userSeconds' => (float) $fields[1],
        'systemSeconds' => (float) $fields[2],
        'maxRssKb' => (int) $fields[3],
        'errors' => $fileErrors + $generalErrors,
    ];
}

function writeBenchmarkConfiguration(
    string $benchmarkRoot,
    string $id,
    string $nativeConfiguration,
    string $extensionConfiguration,
    bool $withExtension,
    bool $serial,
): string {
    $configuration = $benchmarkRoot . '/' . $id . '.neon';
    $cacheDirectory = $benchmarkRoot . '/cache/' . $id;
    $contents = "includes:\n"
        . '    - ' . neonString($nativeConfiguration) . "\n";
    if ($withExtension) {
        $contents .= '    - ' . neonString($extensionConfiguration) . "\n";
    }
    $contents .= "\nparameters:\n"
        . '    tmpDir: ' . neonString($cacheDirectory) . "\n";
    if ($serial) {
        $contents .= "    parallel:\n        maximumNumberOfProcesses: 1\n";
    }

    if (file_put_contents($configuration, $contents) === false) {
        throw new RuntimeException(sprintf('Could not write benchmark configuration %s.', $configuration));
    }

    return $configuration;
}

function neonString(string $value): string
{
    return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
}

/**
 * @param list<array{
 *     cache: string,
 *     workers: string,
 *     configuration: string,
 *     iteration: int,
 *     wallSeconds: float,
 *     userSeconds: float,
 *     systemSeconds: float,
 *     maxRssKb: int,
 *     errors: int
 * }> $rows
 * @return list<array{
 *     cache: string,
 *     workers: string,
 *     configuration: string,
 *     count: int,
 *     medianWall: float,
 *     meanWall: float,
 *     medianRssKb: float
 * }>
 */
function summarizeRows(array $rows): array
{
    $groups = [];
    foreach ($rows as $row) {
        $key = implode('|', [$row['cache'], $row['workers'], $row['configuration']]);
        $groups[$key][] = $row;
    }

    $summaries = [];
    foreach ($groups as $group) {
        $wallTimes = array_column($group, 'wallSeconds');
        $rssValues = array_column($group, 'maxRssKb');
        $summaries[] = [
            'cache' => $group[0]['cache'],
            'workers' => $group[0]['workers'],
            'configuration' => $group[0]['configuration'],
            'count' => count($group),
            'medianWall' => median($wallTimes),
            'meanWall' => array_sum($wallTimes) / count($wallTimes),
            'medianRssKb' => median($rssValues),
        ];
    }

    usort($summaries, static function (array $left, array $right): int {
        return [$left['cache'], $left['workers'], $left['configuration']]
            <=> [$right['cache'], $right['workers'], $right['configuration']];
    });

    return $summaries;
}

/**
 * @param list<float|int> $values
 */
function median(array $values): float
{
    sort($values, SORT_NUMERIC);
    $count = count($values);
    $middle = intdiv($count, 2);

    return $count % 2 === 1
        ? (float) $values[$middle]
        : ((float) $values[$middle - 1] + (float) $values[$middle]) / 2;
}

/**
 * @param list<array{
 *     cache: string,
 *     workers: string,
 *     configuration: string,
 *     count: int,
 *     medianWall: float,
 *     meanWall: float,
 *     medianRssKb: float
 * }> $summaries
 * @return array{
 *     cache: string,
 *     workers: string,
 *     configuration: string,
 *     count: int,
 *     medianWall: float,
 *     meanWall: float,
 *     medianRssKb: float
 * }
 */
function summaryByKey(array $summaries, string $cache, string $workers, string $configuration): array
{
    foreach ($summaries as $summary) {
        if (
            $summary['cache'] === $cache
            && $summary['workers'] === $workers
            && $summary['configuration'] === $configuration
        ) {
            return $summary;
        }
    }

    throw new RuntimeException(sprintf('Missing summary for %s/%s/%s.', $cache, $workers, $configuration));
}

function percentageChange(float $baseline, float $candidate): float
{
    return $baseline === 0.0 ? 0.0 : (($candidate / $baseline) - 1) * 100;
}

/** @return array<string, string> */
function packageVersions(string $composerLock): array
{
    $contents = file_get_contents($composerLock);
    if ($contents === false) {
        return [];
    }
    $decoded = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
    if (!is_array($decoded)) {
        return [];
    }

    $versions = [];
    foreach (['packages', 'packages-dev'] as $sectionName) {
        $packages = $decoded[$sectionName] ?? null;
        if (!is_array($packages)) {
            continue;
        }
        foreach ($packages as $package) {
            if (
                !is_array($package)
                || !is_string($package['name'] ?? null)
                || !is_string($package['version'] ?? null)
            ) {
                continue;
            }
            $source = $package['source'] ?? null;
            $distribution = $package['dist'] ?? null;
            $reference = is_array($source) && is_string($source['reference'] ?? null)
                ? $source['reference']
                : (is_array($distribution) && is_string($distribution['reference'] ?? null)
                    ? $distribution['reference']
                    : null);
            $versions[$package['name']] = $reference !== null && $reference !== ''
                ? $package['version'] . '@' . substr($reference, 0, 12)
                : $package['version'];
        }
    }

    return $versions;
}

function logicalCpuCount(): int
{
    $cpuInfo = file_get_contents('/proc/cpuinfo');
    if ($cpuInfo === false) {
        return 0;
    }

    $count = preg_match_all('/^processor\s*:/m', $cpuInfo);
    return is_int($count) ? $count : 0;
}

function cpuModel(): string
{
    $cpuInfo = file_get_contents('/proc/cpuinfo');
    if ($cpuInfo === false || preg_match('/^model name\s*:\s*(.+)$/m', $cpuInfo, $matches) !== 1) {
        return 'unknown';
    }

    return trim($matches[1]);
}

/** @return array{files: int, lines: int} */
function applicationSize(string $directory): array
{
    if (!is_dir($directory)) {
        return ['files' => 0, 'lines' => 0];
    }

    $files = 0;
    $lines = 0;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
    );
    foreach ($iterator as $entry) {
        if (!$entry instanceof SplFileInfo || !$entry->isFile() || $entry->getExtension() !== 'php') {
            continue;
        }
        $contents = file_get_contents($entry->getPathname());
        if ($contents === false) {
            throw new RuntimeException(sprintf('Could not read %s.', $entry->getPathname()));
        }
        ++$files;
        $lines += substr_count($contents, "\n") + (str_ends_with($contents, "\n") ? 0 : 1);
    }

    return ['files' => $files, 'lines' => $lines];
}

/** @param list<string> $command */
function captureCommand(array $command): string
{
    $process = proc_open(
        $command,
        [
            0 => ['file', '/dev/null', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ],
        $pipes,
    );
    if (!is_resource($process)) {
        return 'unknown';
    }
    $outputPipes = captureCommandOutputPipes($pipes);
    if ($outputPipes === null) {
        proc_terminate($process);
        proc_close($process);

        return 'unknown';
    }
    $output = stream_get_contents($outputPipes['stdout']);
    fclose($outputPipes['stdout']);
    fclose($outputPipes['stderr']);

    return proc_close($process) === 0 && is_string($output) ? trim($output) : 'unknown';
}

/** @return array{stdout: resource, stderr: resource}|null */
function captureCommandOutputPipes(mixed $pipes): ?array
{
    if (!is_array($pipes)) {
        return null;
    }

    $stdout = $pipes[1] ?? null;
    $stderr = $pipes[2] ?? null;
    if (!is_resource($stdout) || !is_resource($stderr)) {
        foreach ($pipes as $pipe) {
            if (is_resource($pipe)) {
                fclose($pipe);
            }
        }

        return null;
    }

    return ['stdout' => $stdout, 'stderr' => $stderr];
}

function findTimeBinary(): ?string
{
    $configured = getenv('BOOKSTACK_BENCHMARK_TIME');
    $candidates = is_string($configured) && $configured !== '' ? [$configured] : [];
    $candidates = [
        ...$candidates,
        '/usr/bin/time',
        '/run/current-system/sw/bin/time',
        ...pathExecutables('gtime'),
        ...pathExecutables('time'),
    ];
    foreach (array_unique($candidates) as $candidate) {
        if (is_file($candidate) && is_executable($candidate)) {
            return $candidate;
        }
    }

    return null;
}

/** @return list<string> */
function pathExecutables(string $name): array
{
    $path = getenv('PATH');
    if (!is_string($path)) {
        return [];
    }

    return array_map(
        static fn (string $directory): string => rtrim($directory, '/') . '/' . $name,
        explode(PATH_SEPARATOR, $path),
    );
}

function createBenchmarkRoot(): string
{
    $root = rtrim(sys_get_temp_dir(), '/') . '/phpstan-laravel-validation-bookstack-benchmark-'
        . bin2hex(random_bytes(8));
    if (!mkdir($root, 0700) && !is_dir($root)) {
        throw new RuntimeException(sprintf('Could not create benchmark directory %s.', $root));
    }

    return $root;
}

function removeBenchmarkDirectory(string $directory): void
{
    $prefix = rtrim(sys_get_temp_dir(), '/') . '/phpstan-laravel-validation-bookstack-benchmark-';
    if (!str_starts_with($directory, $prefix) || !is_dir($directory)) {
        return;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST,
    );
    foreach ($iterator as $entry) {
        if (!$entry instanceof SplFileInfo) {
            continue;
        }
        if ($entry->isDir() && !$entry->isLink()) {
            rmdir($entry->getPathname());
        } else {
            unlink($entry->getPathname());
        }
    }
    rmdir($directory);
}
