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

use RuntimeException;
use Throwable;

const EXPECTED_IGNORED_MUTANTS = 5;
const MAXIMUM_TIMEOUTS = 10;
const MINIMUM_MSI = 50;
const MINIMUM_COVERED_MSI = 80;

try {
    $arguments = $_SERVER['argv'] ?? null;
    if (!is_array($arguments)) {
        throw new RuntimeException('Could not read command-line arguments.');
    }

    $reportPaths = array_slice($arguments, 1);
    if ($reportPaths === []) {
        throw new RuntimeException(
            'Usage: aggregate-infection-reports.php <shard=summary.json> [shard=summary.json ...]'
        );
    }

    $aggregate = [
        'total' => 0,
        'covered' => 0,
        'detected' => 0,
        'ignored' => 0,
        'timeouts' => 0,
        'msi' => 0.0,
        'coveredMsi' => 0.0,
        'timedOutMutants' => [],
    ];

    foreach ($reportPaths as $reportArgument) {
        if (!is_string($reportArgument)) {
            throw new RuntimeException('Infection report paths must be strings.');
        }
        [$shard, $reportPath] = reportArgument($reportArgument);

        $contents = file_get_contents($reportPath);
        if ($contents === false) {
            throw new RuntimeException(sprintf('Could not read Infection report: %s', $reportPath));
        }

        $report = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        $stats = is_array($report) ? ($report['stats'] ?? null) : null;
        if (!is_array($stats)) {
            throw new RuntimeException(sprintf('Infection report has no stats object: %s', $reportPath));
        }

        $totalMutants = integerStat($stats, 'totalMutantsCount', $reportPath);
        $skipped = integerStat($stats, 'skippedCount', $reportPath);
        $ignored = integerStat($stats, 'ignoredCount', $reportPath);
        $notCovered = integerStat($stats, 'notCoveredCount', $reportPath);
        $total = $totalMutants - $skipped - $ignored;
        $covered = $total - $notCovered;

        if ($total < 0 || $covered < 0) {
            throw new RuntimeException(sprintf('Infection report contains inconsistent counts: %s', $reportPath));
        }

        $aggregate['total'] += $total;
        $aggregate['covered'] += $covered;
        $aggregate['detected'] += integerStat($stats, 'killedCount', $reportPath)
            + integerStat($stats, 'errorCount', $reportPath)
            + integerStat($stats, 'syntaxErrorCount', $reportPath);
        $aggregate['ignored'] += $ignored;
        $timeoutCount = integerStat($stats, 'timeOutCount', $reportPath);
        $aggregate['timeouts'] += $timeoutCount;
        array_push(
            $aggregate['timedOutMutants'],
            ...timedOutMutants(dirname($reportPath) . '/infection.log', $shard, $timeoutCount),
        );
    }

    $aggregate['msi'] = percentage($aggregate['detected'], $aggregate['total']);
    $aggregate['coveredMsi'] = percentage($aggregate['detected'], $aggregate['covered']);

    fwrite(STDOUT, json_encode($aggregate, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n");

    $failed = false;
    if ($aggregate['timeouts'] > MAXIMUM_TIMEOUTS) {
        fwrite(STDERR, sprintf(
            "Aggregate timed-out mutants exceed infection.json5.dist maxTimeouts (%d).\n",
            MAXIMUM_TIMEOUTS,
        ));
        writeTimedOutMutants($aggregate['timedOutMutants']);
        $failed = true;
    }
    if ($aggregate['ignored'] !== EXPECTED_IGNORED_MUTANTS) {
        fwrite(STDERR, sprintf(
            "Expected %d ignored non-progressing resolvePath() mutants.\n",
            EXPECTED_IGNORED_MUTANTS,
        ));
        $failed = true;
    }
    if ($aggregate['msi'] < MINIMUM_MSI) {
        fwrite(STDERR, sprintf(
            "Aggregate MSI is below infection.json5.dist minMsi (%d).\n",
            MINIMUM_MSI,
        ));
        $failed = true;
    }
    if ($aggregate['coveredMsi'] < MINIMUM_COVERED_MSI) {
        fwrite(STDERR, sprintf(
            "Aggregate covered MSI is below infection.json5.dist minCoveredMsi (%d).\n",
            MINIMUM_COVERED_MSI,
        ));
        $failed = true;
    }

    exit($failed ? 1 : 0);
} catch (Throwable $throwable) {
    fwrite(STDERR, $throwable->getMessage() . "\n");
    exit(2);
}

/** @param array<array-key, mixed> $stats */
function integerStat(array $stats, string $name, string $reportPath): int
{
    $value = $stats[$name] ?? null;
    if (!is_int($value) || $value < 0) {
        throw new RuntimeException(sprintf('Infection report has an invalid %s: %s', $name, $reportPath));
    }

    return $value;
}

function percentage(int $detected, int $total): float
{
    return $total === 0 ? 0.0 : round(10000 * $detected / $total) / 100;
}

/** @return array{string, string} */
function reportArgument(string $argument): array
{
    $parts = explode('=', $argument, 2);
    if (
        count($parts) !== 2
        || preg_match('/^[a-z0-9][a-z0-9-]*$/D', $parts[0]) !== 1
        || $parts[1] === ''
    ) {
        throw new RuntimeException(sprintf('Invalid Infection report argument: %s', $argument));
    }

    return [$parts[0], $parts[1]];
}

/**
 * @return list<array{shard: string, file: string, line: int, mutator: string, id: string}>
 */
function timedOutMutants(string $logPath, string $shard, int $expectedCount): array
{
    if ($expectedCount === 0) {
        return [];
    }

    $contents = file_get_contents($logPath);
    if ($contents === false) {
        throw new RuntimeException(sprintf('Could not read Infection text report: %s', $logPath));
    }
    if (preg_match('/^Timed Out mutants:\R=+\R(?<body>.*?)^Skipped mutants:\R/ms', $contents, $section) !== 1) {
        throw new RuntimeException(sprintf('Infection text report has no timed-out mutant section: %s', $logPath));
    }

    $matchCount = preg_match_all(
        '/^\d+\)\s+(.+):(\d+)\s+\[M\]\s+(\S+)\s+\[ID\]\s+([0-9a-f]+)\s*$/m',
        $section['body'],
        $matches,
        PREG_SET_ORDER,
    );
    if ($matchCount === false || $matchCount !== $expectedCount) {
        throw new RuntimeException(sprintf(
            'Infection text report contains %d timed-out mutant identities; expected %d: %s',
            $matchCount === false ? 0 : $matchCount,
            $expectedCount,
            $logPath,
        ));
    }

    $mutants = [];
    foreach ($matches as $match) {
        $mutants[] = [
            'shard' => $shard,
            'file' => $match[1],
            'line' => (int) $match[2],
            'mutator' => $match[3],
            'id' => $match[4],
        ];
    }

    return $mutants;
}

/**
 * @param list<array{shard: string, file: string, line: int, mutator: string, id: string}> $mutants
 */
function writeTimedOutMutants(array $mutants): void
{
    fwrite(STDERR, "Timed-out mutants:\n");
    foreach ($mutants as $mutant) {
        fwrite(STDERR, sprintf(
            "- [%s] %s:%d %s (%s)\n",
            $mutant['shard'],
            $mutant['file'],
            $mutant['line'],
            $mutant['mutator'],
            $mutant['id'],
        ));
    }
}
