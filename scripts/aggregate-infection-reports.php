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
        throw new RuntimeException('Usage: aggregate-infection-reports.php <summary.json> [summary.json ...]');
    }

    $aggregate = [
        'total' => 0,
        'covered' => 0,
        'detected' => 0,
        'ignored' => 0,
        'timeouts' => 0,
        'msi' => 0.0,
        'coveredMsi' => 0.0,
    ];

    foreach ($reportPaths as $reportPath) {
        if (!is_string($reportPath)) {
            throw new RuntimeException('Infection report paths must be strings.');
        }

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
        $aggregate['timeouts'] += integerStat($stats, 'timeOutCount', $reportPath);
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
