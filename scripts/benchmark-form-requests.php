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

const FORM_REQUEST_BENCHMARK_ITERATIONS = 5;

$applicationArgument = $argv[1] ?? null;
if (!is_string($applicationArgument) || $applicationArgument === '') {
    fwrite(
        STDERR,
        "Usage: php scripts/benchmark-form-requests.php APPLICATION_ROOT NATIVE_CONFIGURATION [ITERATIONS]\n",
    );
    exit(2);
}

$applicationRoot = realpath($applicationArgument);
if ($applicationRoot === false || !is_dir($applicationRoot)) {
    fwrite(STDERR, sprintf("Application root does not exist: %s\n", $applicationArgument));
    exit(2);
}

$configurationArgument = $argv[2] ?? null;
if (!is_string($configurationArgument) || $configurationArgument === '') {
    fwrite(STDERR, "NATIVE_CONFIGURATION is required.\n");
    exit(2);
}
$nativeConfiguration = realpath(
    str_starts_with($configurationArgument, DIRECTORY_SEPARATOR)
        ? $configurationArgument
        : $applicationRoot . DIRECTORY_SEPARATOR . $configurationArgument,
);
if ($nativeConfiguration === false || !is_file($nativeConfiguration)) {
    fwrite(STDERR, sprintf("Native configuration does not exist: %s\n", $configurationArgument));
    exit(2);
}

$iterations = isset($argv[3])
    ? filter_var($argv[3], FILTER_VALIDATE_INT)
    : FORM_REQUEST_BENCHMARK_ITERATIONS;
if (!is_int($iterations) || $iterations < 1 || $iterations > 20) {
    fwrite(STDERR, "ITERATIONS must be an integer from 1 through 20.\n");
    exit(2);
}

$phpStan = $applicationRoot . '/vendor/bin/phpstan';
$extensionConfiguration = $applicationRoot
    . '/vendor/jbboehr/phpstan-laravel-validation/extension.neon';
foreach ([$phpStan, $extensionConfiguration, $applicationRoot . '/composer.lock'] as $requiredFile) {
    if (!is_file($requiredFile)) {
        fwrite(STDERR, sprintf("Required benchmark file does not exist: %s\n", $requiredFile));
        exit(2);
    }
}

$phpBinary = getenv('FORM_REQUEST_BENCHMARK_PHP');
if (!is_string($phpBinary) || $phpBinary === '') {
    $phpBinary = PHP_BINARY;
}
$memoryLimit = getenv('FORM_REQUEST_BENCHMARK_MEMORY_LIMIT');
if (!is_string($memoryLimit) || $memoryLimit === '') {
    $memoryLimit = '2G';
}
if (preg_match('/^(?:-1|[1-9][0-9]*[KMG]?)$/i', $memoryLimit) !== 1) {
    fwrite(STDERR, "FORM_REQUEST_BENCHMARK_MEMORY_LIMIT is not a valid PHP memory limit.\n");
    exit(2);
}
$timeBinary = findFormRequestBenchmarkTimeBinary();
if ($timeBinary === null) {
    fwrite(
        STDERR,
        "GNU time is required; set FORM_REQUEST_BENCHMARK_TIME to its executable path.\n",
    );
    exit(2);
}

$benchmarkRoot = createFormRequestBenchmarkRoot();
$keepWorkDirectory = getenv('FORM_REQUEST_BENCHMARK_KEEP') === '1';
register_shutdown_function(static function () use ($benchmarkRoot, $keepWorkDirectory): void {
    if ($keepWorkDirectory) {
        fwrite(STDERR, sprintf("Benchmark work directory retained at %s\n", $benchmarkRoot));
        return;
    }

    removeFormRequestBenchmarkDirectory($benchmarkRoot);
});

$versions = formRequestBenchmarkPackageVersions($applicationRoot . '/composer.lock');
$extensionLoadingOverride = getenv('FORM_REQUEST_BENCHMARK_EXTENSION_LOADED');
if (!is_string($extensionLoadingOverride) || $extensionLoadingOverride === '') {
    $extensionAlreadyLoaded = isset($versions['phpstan/extension-installer']);
    $extensionLoadingDetection = 'Composer package detection';
} elseif ($extensionLoadingOverride === '0' || $extensionLoadingOverride === '1') {
    $extensionAlreadyLoaded = $extensionLoadingOverride === '1';
    $extensionLoadingDetection = 'environment override';
} else {
    fwrite(STDERR, "FORM_REQUEST_BENCHMARK_EXTENSION_LOADED must be 0 or 1.\n");
    exit(2);
}
$applicationSize = formRequestBenchmarkApplicationSize($applicationRoot . '/app');
$applicationName = basename($applicationRoot);
$metadata = [
    'recordedAtUtc' => gmdate(DATE_ATOM),
    'application' => $applicationName,
    'applicationRoot' => $applicationRoot,
    'applicationCommit' => captureFormRequestBenchmarkCommand(
        ['git', '-C', $applicationRoot, 'rev-parse', 'HEAD'],
    ),
    'applicationPhpFiles' => $applicationSize['files'],
    'applicationPhpLines' => $applicationSize['lines'],
    'nativeConfiguration' => $nativeConfiguration,
    'php' => PHP_VERSION,
    'phpBinary' => $phpBinary,
    'memoryLimit' => $memoryLimit,
    'timeBinary' => $timeBinary,
    'phpstan' => $versions['phpstan/phpstan'] ?? 'unknown',
    'laravel' => $versions['laravel/framework'] ?? 'unknown',
    'larastan' => $versions['larastan/larastan'] ?? 'unknown',
    'extension' => $versions['jbboehr/phpstan-laravel-validation'] ?? 'unknown',
    'extensionAlreadyLoaded' => $extensionAlreadyLoaded ? 'yes' : 'no',
    'extensionLoadingDetection' => $extensionLoadingDetection,
    'logicalCpus' => formRequestBenchmarkLogicalCpuCount(),
    'cpuModel' => formRequestBenchmarkCpuModel(),
    'iterations' => $iterations,
    'operatingSystem' => php_uname(),
];

fwrite(STDOUT, sprintf("# %s FormRequest PHPStan benchmark\n\n", $applicationName));
foreach ($metadata as $name => $value) {
    fwrite(STDOUT, sprintf("%s: %s\n", $name, (string) $value));
}
fwrite(STDOUT, "\n# Raw samples\n");
fwrite(
    STDOUT,
    "cache,workers,configuration,iteration,wall_seconds,user_seconds,system_seconds,max_rss_kb,errors,diagnostics_hash\n",
);

$configurations = [
    'native' => null,
    'extension-disabled' => false,
    'form-requests' => true,
];
$coldVariants = [];
foreach (['serial' => true, 'default' => false] as $workers => $serial) {
    foreach ($configurations as $configurationName => $formRequestsEnabled) {
        $coldVariants[] = [
            'workers' => $workers,
            'serial' => $serial,
            'configuration' => $configurationName,
            'formRequestsEnabled' => $formRequestsEnabled,
        ];
    }
}

$rows = [];
for ($iteration = 1; $iteration <= $iterations; ++$iteration) {
    $offset = ($iteration - 1) % count($coldVariants);
    $orderedVariants = array_merge(
        array_slice($coldVariants, $offset),
        array_slice($coldVariants, 0, $offset),
    );

    foreach ($orderedVariants as $variant) {
        $id = sprintf(
            'cold-%s-%s-%d',
            $variant['workers'],
            $variant['configuration'],
            $iteration,
        );
        $configuration = writeFormRequestBenchmarkConfiguration(
            $benchmarkRoot,
            $id,
            $nativeConfiguration,
            $extensionConfiguration,
            $variant['formRequestsEnabled'],
            $extensionAlreadyLoaded,
            $variant['serial'],
        );
        $rows[] = runFormRequestBenchmark(
            $id,
            'cold',
            $variant['workers'],
            $variant['configuration'],
            $iteration,
            $configuration,
            $applicationRoot,
            $phpStan,
            $phpBinary,
            $timeBinary,
            $benchmarkRoot,
        );
    }
}

$warmConfigurations = [];
foreach ($configurations as $configurationName => $formRequestsEnabled) {
    $id = 'warm-default-' . $configurationName;
    $warmConfigurations[$configurationName] = writeFormRequestBenchmarkConfiguration(
        $benchmarkRoot,
        $id,
        $nativeConfiguration,
        $extensionConfiguration,
        $formRequestsEnabled,
        $extensionAlreadyLoaded,
        false,
    );
    fwrite(STDERR, sprintf("Priming warm cache for %s...\n", $configurationName));
    runFormRequestBenchmarkPhpStan(
        'prime-' . $configurationName,
        $warmConfigurations[$configurationName],
        $applicationRoot,
        $phpStan,
        $phpBinary,
        $timeBinary,
        $benchmarkRoot,
    );
}

$configurationNames = array_keys($configurations);
for ($iteration = 1; $iteration <= $iterations; ++$iteration) {
    $offset = ($iteration - 1) % count($configurationNames);
    $orderedConfigurations = array_merge(
        array_slice($configurationNames, $offset),
        array_slice($configurationNames, 0, $offset),
    );
    foreach ($orderedConfigurations as $configurationName) {
        $id = sprintf('warm-default-%s-%d', $configurationName, $iteration);
        $rows[] = runFormRequestBenchmark(
            $id,
            'warm',
            'default',
            $configurationName,
            $iteration,
            $warmConfigurations[$configurationName],
            $applicationRoot,
            $phpStan,
            $phpBinary,
            $timeBinary,
            $benchmarkRoot,
        );
    }
}

$summaries = summarizeFormRequestBenchmarkRows($rows);
fwrite(STDOUT, "\n# Summary\n\n");
fwrite(
    STDOUT,
    "| Cache | Workers | Configuration | n | Median wall (s) | Mean wall (s) | Median max RSS (MiB) | Errors | Diagnostic sets |\n",
);
fwrite(STDOUT, "| --- | --- | --- | ---: | ---: | ---: | ---: | ---: | ---: |\n");
foreach ($summaries as $summary) {
    fwrite(STDOUT, sprintf(
        "| %s | %s | %s | %d | %.3f | %.3f | %.1f | %d | %d |\n",
        $summary['cache'],
        $summary['workers'],
        $summary['configuration'],
        $summary['count'],
        $summary['medianWall'],
        $summary['meanWall'],
        $summary['medianRssKb'] / 1024,
        $summary['errors'],
        $summary['diagnosticSets'],
    ));
}
fwrite(STDOUT, sprintf(
    "\nDiagnostic sets across all samples: %d\n",
    count(array_unique(array_column($rows, 'diagnosticsHash'))),
));

fwrite(STDOUT, "\n# Comparisons\n\n");
$nativeComparisonLabel = $extensionAlreadyLoaded
    ? 'Explicit disabled toggle vs native'
    : 'Extension registration';
foreach (
    [
        ['cold', 'serial', 'cold serial'],
        ['cold', 'default', 'cold default workers'],
        ['warm', 'default', 'warm result cache'],
    ] as [$cache, $workers, $label]
) {
    $native = formRequestBenchmarkSummaryByKey($summaries, $cache, $workers, 'native');
    $disabled = formRequestBenchmarkSummaryByKey(
        $summaries,
        $cache,
        $workers,
        'extension-disabled',
    );
    $enabled = formRequestBenchmarkSummaryByKey($summaries, $cache, $workers, 'form-requests');
    fwrite(STDOUT, sprintf(
        "%s, %s: %+.1f%% wall, %+.1f MiB reported max RSS\n",
        $nativeComparisonLabel,
        $label,
        formRequestBenchmarkPercentageChange($native['medianWall'], $disabled['medianWall']),
        ($disabled['medianRssKb'] - $native['medianRssKb']) / 1024,
    ));
    fwrite(STDOUT, sprintf(
        "FormRequest integration, %s: %+.1f%% wall, %+.1f MiB reported max RSS\n",
        $label,
        formRequestBenchmarkPercentageChange($disabled['medianWall'], $enabled['medianWall']),
        ($enabled['medianRssKb'] - $disabled['medianRssKb']) / 1024,
    ));
    fwrite(STDOUT, sprintf(
        "Total extension impact, %s: %+.1f%% wall, %+.1f MiB reported max RSS\n",
        $label,
        formRequestBenchmarkPercentageChange($native['medianWall'], $enabled['medianWall']),
        ($enabled['medianRssKb'] - $native['medianRssKb']) / 1024,
    ));
}

fwrite(STDOUT, "\n# Measurement notes\n\n");
fwrite(STDOUT, "- Cold runs use a fresh PHPStan tmpDir for every sample.\n");
fwrite(
    STDOUT,
    "- Warm runs reuse a primed result cache; their default-worker setting normally starts no workers.\n",
);
fwrite(
    STDOUT,
    $extensionAlreadyLoaded
        ? "- The native configuration already loads the extension; extension-disabled adds only an explicit false toggle.\n"
        : "- The extension-disabled comparison isolates extension registration from FormRequest discovery and resolution.\n",
);
fwrite(
    STDOUT,
    "- Diagnostic hashes make changed error sets visible even when their counts are equal.\n",
);
fwrite(
    STDOUT,
    "- GNU time's default-parallel RSS is not aggregate process-tree memory; use serial figures for the clearest memory comparison.\n",
);

$jsonOutput = getenv('FORM_REQUEST_BENCHMARK_JSON');
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
 *     errors: int,
 *     diagnosticsHash: string
 * }
 */
function runFormRequestBenchmark(
    string $id,
    string $cache,
    string $workers,
    string $configurationName,
    int $iteration,
    string $configuration,
    string $applicationRoot,
    string $phpStan,
    string $phpBinary,
    string $timeBinary,
    string $benchmarkRoot,
): array {
    fwrite(STDERR, sprintf("Running %s...\n", $id));
    $measurement = runFormRequestBenchmarkPhpStan(
        $id,
        $configuration,
        $applicationRoot,
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
        "%s,%s,%s,%d,%.3f,%.3f,%.3f,%d,%d,%s\n",
        $cache,
        $workers,
        $configurationName,
        $iteration,
        $row['wallSeconds'],
        $row['userSeconds'],
        $row['systemSeconds'],
        $row['maxRssKb'],
        $row['errors'],
        $row['diagnosticsHash'],
    ));

    return $row;
}

/**
 * @return array{
 *     wallSeconds: float,
 *     userSeconds: float,
 *     systemSeconds: float,
 *     maxRssKb: int,
 *     errors: int,
 *     diagnosticsHash: string
 * }
 */
function runFormRequestBenchmarkPhpStan(
    string $id,
    string $configuration,
    string $applicationRoot,
    string $phpStan,
    string $phpBinary,
    string $timeBinary,
    string $benchmarkRoot,
): array {
    $metricsFile = $benchmarkRoot . '/' . $id . '.time';
    $outputFile = $benchmarkRoot . '/' . $id . '.json';
    $errorFile = $benchmarkRoot . '/' . $id . '.stderr';
    $memoryLimit = getenv('FORM_REQUEST_BENCHMARK_MEMORY_LIMIT');
    if (!is_string($memoryLimit) || $memoryLimit === '') {
        $memoryLimit = '2G';
    }
    $process = proc_open(
        [
            $timeBinary,
            '-f',
            "%e\t%U\t%S\t%M\t%x",
            '-o',
            $metricsFile,
            $phpBinary,
            $phpStan,
            'analyse',
            '--configuration=' . $configuration,
            '--memory-limit=' . $memoryLimit,
            '--error-format=json',
            '--no-progress',
        ],
        [
            0 => ['file', '/dev/null', 'r'],
            1 => ['file', $outputFile, 'w'],
            2 => ['file', $errorFile, 'w'],
        ],
        $pipes,
        $applicationRoot,
    );
    if (!is_resource($process)) {
        throw new RuntimeException(sprintf('Could not start benchmark command %s.', $id));
    }
    $exitCode = proc_close($process);

    $metrics = file_get_contents($metricsFile);
    if ($metrics === false) {
        throw new RuntimeException(sprintf('Could not read timing output for %s.', $id));
    }
    $metricLines = preg_split('/\R/', trim($metrics));
    $metricsLine = is_array($metricLines) ? end($metricLines) : false;
    $fields = is_string($metricsLine) ? explode("\t", $metricsLine) : [];
    if (count($fields) !== 5) {
        throw new RuntimeException(sprintf('Unexpected GNU time output for %s: %s', $id, trim($metrics)));
    }
    if (!in_array($exitCode, [0, 1], true) || !in_array((int) $fields[4], [0, 1], true)) {
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
    if (!is_array($decoded) || !is_array($decoded['totals'] ?? null)) {
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
        'diagnosticsHash' => formRequestBenchmarkDiagnosticsHash($decoded),
    ];
}

function writeFormRequestBenchmarkConfiguration(
    string $benchmarkRoot,
    string $id,
    string $nativeConfiguration,
    string $extensionConfiguration,
    ?bool $formRequestsEnabled,
    bool $extensionAlreadyLoaded,
    bool $serial,
): string {
    $configuration = $benchmarkRoot . '/' . $id . '.neon';
    $cacheDirectory = $benchmarkRoot . '/cache/' . $id;
    $contents = "includes:\n"
        . '    - ' . formRequestBenchmarkNeonString($nativeConfiguration) . "\n";
    if ($formRequestsEnabled !== null && !$extensionAlreadyLoaded) {
        $contents .= '    - ' . formRequestBenchmarkNeonString($extensionConfiguration) . "\n";
    }
    $contents .= "\nparameters:\n"
        . '    tmpDir: ' . formRequestBenchmarkNeonString($cacheDirectory) . "\n";
    if ($formRequestsEnabled !== null) {
        $contents .= "    phpstanLaravelValidation:\n"
            . "        formRequests:\n"
            . '            enabled: ' . ($formRequestsEnabled ? 'true' : 'false') . "\n";
    }
    if ($serial) {
        $contents .= "    parallel:\n        maximumNumberOfProcesses: 1\n";
    }

    if (file_put_contents($configuration, $contents) === false) {
        throw new RuntimeException(sprintf('Could not write benchmark configuration %s.', $configuration));
    }

    return $configuration;
}

function formRequestBenchmarkNeonString(string $value): string
{
    return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
}

/** @param array<mixed> $decoded */
function formRequestBenchmarkDiagnosticsHash(array $decoded): string
{
    $records = [];
    $files = $decoded['files'] ?? null;
    if (is_array($files)) {
        foreach ($files as $fileName => $fileResult) {
            if (!is_string($fileName) || !is_array($fileResult)) {
                continue;
            }
            $messages = $fileResult['messages'] ?? null;
            if (!is_array($messages)) {
                continue;
            }
            foreach ($messages as $message) {
                $records[] = json_encode(
                    ['file' => $fileName, 'message' => formRequestBenchmarkCanonicalize($message)],
                    JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
                );
            }
        }
    }

    $generalErrors = $decoded['errors'] ?? null;
    if (is_array($generalErrors)) {
        foreach ($generalErrors as $error) {
            $records[] = json_encode(
                ['generalError' => formRequestBenchmarkCanonicalize($error)],
                JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
            );
        }
    }

    sort($records, SORT_STRING);

    return hash('sha256', implode("\n", $records));
}

function formRequestBenchmarkCanonicalize(mixed $value): mixed
{
    if (!is_array($value)) {
        return $value;
    }
    if (!array_is_list($value)) {
        ksort($value, SORT_STRING);
    }
    foreach ($value as $key => $item) {
        $value[$key] = formRequestBenchmarkCanonicalize($item);
    }

    return $value;
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
 *     errors: int,
 *     diagnosticsHash: string
 * }> $rows
 * @return list<array{
 *     cache: string,
 *     workers: string,
 *     configuration: string,
 *     count: int,
 *     medianWall: float,
 *     meanWall: float,
 *     medianRssKb: float,
 *     errors: int,
 *     diagnosticSets: int
 * }>
 */
function summarizeFormRequestBenchmarkRows(array $rows): array
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
            'medianWall' => formRequestBenchmarkMedian($wallTimes),
            'meanWall' => array_sum($wallTimes) / count($wallTimes),
            'medianRssKb' => formRequestBenchmarkMedian($rssValues),
            'errors' => max(array_column($group, 'errors')),
            'diagnosticSets' => count(array_unique(array_column($group, 'diagnosticsHash'))),
        ];
    }
    usort(
        $summaries,
        static fn (array $left, array $right): int => [
            $left['cache'],
            $left['workers'],
            $left['configuration'],
        ] <=> [$right['cache'], $right['workers'], $right['configuration']],
    );

    return $summaries;
}

/** @param list<float|int> $values */
function formRequestBenchmarkMedian(array $values): float
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
 *     medianRssKb: float,
 *     errors: int,
 *     diagnosticSets: int
 * }> $summaries
 * @return array{
 *     cache: string,
 *     workers: string,
 *     configuration: string,
 *     count: int,
 *     medianWall: float,
 *     meanWall: float,
 *     medianRssKb: float,
 *     errors: int,
 *     diagnosticSets: int
 * }
 */
function formRequestBenchmarkSummaryByKey(
    array $summaries,
    string $cache,
    string $workers,
    string $configuration,
): array {
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

function formRequestBenchmarkPercentageChange(float $baseline, float $candidate): float
{
    return $baseline === 0.0 ? 0.0 : (($candidate / $baseline) - 1) * 100;
}

/** @return array<string, string> */
function formRequestBenchmarkPackageVersions(string $composerLock): array
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

function formRequestBenchmarkLogicalCpuCount(): int
{
    $cpuInfo = file_get_contents('/proc/cpuinfo');
    if ($cpuInfo === false) {
        return 0;
    }
    $count = preg_match_all('/^processor\s*:/m', $cpuInfo);

    return is_int($count) ? $count : 0;
}

function formRequestBenchmarkCpuModel(): string
{
    $cpuInfo = file_get_contents('/proc/cpuinfo');
    if ($cpuInfo === false || preg_match('/^model name\s*:\s*(.+)$/m', $cpuInfo, $matches) !== 1) {
        return 'unknown';
    }

    return trim($matches[1]);
}

/** @return array{files: int, lines: int} */
function formRequestBenchmarkApplicationSize(string $directory): array
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
        $lines += substr_count($contents, "\n") + 1;
    }

    return ['files' => $files, 'lines' => $lines];
}

function findFormRequestBenchmarkTimeBinary(): ?string
{
    $configured = getenv('FORM_REQUEST_BENCHMARK_TIME');
    if (is_string($configured) && $configured !== '') {
        return is_file($configured) ? $configured : null;
    }
    foreach (['/usr/bin/time', '/run/current-system/sw/bin/time'] as $candidate) {
        if (is_file($candidate)) {
            return $candidate;
        }
    }

    return null;
}

function createFormRequestBenchmarkRoot(): string
{
    $base = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
        . DIRECTORY_SEPARATOR . 'phpstan-laravel-validation-form-request-benchmark-'
        . bin2hex(random_bytes(8));
    if (!mkdir($base, 0700, true) && !is_dir($base)) {
        throw new RuntimeException(sprintf('Could not create benchmark directory %s.', $base));
    }

    return $base;
}

function removeFormRequestBenchmarkDirectory(string $directory): void
{
    if (!is_dir($directory)) {
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
        if ($entry->isDir()) {
            rmdir($entry->getPathname());
        } else {
            unlink($entry->getPathname());
        }
    }
    rmdir($directory);
}

/** @param list<string> $command */
function captureFormRequestBenchmarkCommand(array $command): string
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
    $outputPipes = captureFormRequestBenchmarkOutputPipes($pipes);
    if ($outputPipes === null) {
        proc_terminate($process);
        proc_close($process);

        return 'unknown';
    }
    $stdout = stream_get_contents($outputPipes['stdout']);
    $stderr = stream_get_contents($outputPipes['stderr']);
    fclose($outputPipes['stdout']);
    fclose($outputPipes['stderr']);
    $exitCode = proc_close($process);

    return $exitCode === 0 && is_string($stdout)
        ? trim($stdout)
        : 'unknown' . (is_string($stderr) && $stderr !== '' ? ': ' . trim($stderr) : '');
}
/** @return array{stdout: resource, stderr: resource}|null */
function captureFormRequestBenchmarkOutputPipes(mixed $pipes): ?array
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
