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

use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Process\Process;

#[Group('subprocess')]
final class InfectionReportAggregatorTest extends \PHPUnit\Framework\TestCase
{
    private string $temporaryDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->temporaryDirectory = sys_get_temp_dir() . '/infection-report-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($this->temporaryDirectory, 0700));
    }

    protected function tearDown(): void
    {
        $directories = glob($this->temporaryDirectory . '/*', GLOB_ONLYDIR);
        foreach ($directories !== false ? $directories : [] as $directory) {
            $files = glob($directory . '/*');
            foreach ($files !== false ? $files : [] as $file) {
                self::assertTrue(unlink($file));
            }
            self::assertTrue(rmdir($directory));
        }
        self::assertTrue(rmdir($this->temporaryDirectory));

        parent::tearDown();
    }

    public function testAggregatesReportsAndEnforcesProjectThresholds(): void
    {
        $first = $this->writeReport('first', [
            'totalMutantsCount' => 100,
            'skippedCount' => 10,
            'ignoredCount' => 2,
            'notCoveredCount' => 20,
            'killedCount' => 50,
            'errorCount' => 5,
            'syntaxErrorCount' => 5,
            'timeOutCount' => 2,
        ], [
            ['/build/project/src/First.php', 10, 'LogicalNot', str_repeat('a', 32)],
            ['/build/project/src/First.php', 20, 'ReturnRemoval', str_repeat('b', 32)],
        ]);
        $second = $this->writeReport('second', [
            'totalMutantsCount' => 50,
            'skippedCount' => 5,
            'ignoredCount' => 3,
            'notCoveredCount' => 10,
            'killedCount' => 20,
            'errorCount' => 2,
            'syntaxErrorCount' => 3,
            'timeOutCount' => 1,
        ], [
            ['/build/project/src/Second.php', 30, 'Increment', str_repeat('c', 32)],
        ]);

        $process = $this->runAggregator($first, $second);

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertSame([
            'total' => 130,
            'covered' => 100,
            'detected' => 85,
            'ignored' => 5,
            'timeouts' => 3,
            'msi' => 65.38,
            'coveredMsi' => 85,
            'timedOutMutants' => [
                [
                    'shard' => 'first',
                    'file' => '/build/project/src/First.php',
                    'line' => 10,
                    'mutator' => 'LogicalNot',
                    'id' => str_repeat('a', 32),
                ],
                [
                    'shard' => 'first',
                    'file' => '/build/project/src/First.php',
                    'line' => 20,
                    'mutator' => 'ReturnRemoval',
                    'id' => str_repeat('b', 32),
                ],
                [
                    'shard' => 'second',
                    'file' => '/build/project/src/Second.php',
                    'line' => 30,
                    'mutator' => 'Increment',
                    'id' => str_repeat('c', 32),
                ],
            ],
        ], json_decode($process->getOutput(), true, 512, JSON_THROW_ON_ERROR));
    }

    public function testFailsWhenAggregateMutationScoresAreBelowThresholds(): void
    {
        $report = $this->writeReport('failing', [
            'totalMutantsCount' => 15,
            'skippedCount' => 0,
            'ignoredCount' => 5,
            'notCoveredCount' => 0,
            'killedCount' => 4,
            'errorCount' => 0,
            'syntaxErrorCount' => 0,
            'timeOutCount' => 0,
        ]);

        $process = $this->runAggregator($report);

        self::assertSame(1, $process->getExitCode());
        self::assertStringContainsString('Aggregate MSI is below infection.json5.dist minMsi (50).', $process->getErrorOutput());
        self::assertStringContainsString('Aggregate covered MSI is below infection.json5.dist minCoveredMsi (80).', $process->getErrorOutput());
    }

    public function testFailsWhenTimeoutAndIgnoredMutantLimitsDoNotMatch(): void
    {
        $report = $this->writeReport('limits', [
            'totalMutantsCount' => 106,
            'skippedCount' => 0,
            'ignoredCount' => 6,
            'notCoveredCount' => 0,
            'killedCount' => 90,
            'errorCount' => 0,
            'syntaxErrorCount' => 0,
            'timeOutCount' => 11,
        ], array_fill(0, 11, [
            '/build/project/src/Slow.php',
            40,
            'MethodCallRemoval',
            str_repeat('d', 32),
        ]));

        $process = $this->runAggregator($report);

        self::assertSame(1, $process->getExitCode());
        self::assertStringContainsString('Aggregate timed-out mutants exceed infection.json5.dist maxTimeouts (10).', $process->getErrorOutput());
        self::assertStringContainsString(
            '[limits] /build/project/src/Slow.php:40 MethodCallRemoval (' . str_repeat('d', 32) . ')',
            $process->getErrorOutput()
        );
        self::assertStringContainsString('Expected 5 ignored non-progressing resolvePath() mutants.', $process->getErrorOutput());
    }

    /**
     * @param array<string, int> $stats
     * @param list<array{string, int, string, string}> $timedOutMutants
     */
    private function writeReport(string $shard, array $stats, array $timedOutMutants = []): string
    {
        $directory = $this->temporaryDirectory . '/' . $shard;
        self::assertTrue(mkdir($directory, 0700));
        $path = $directory . '/infection-summary.json';
        self::assertNotFalse(file_put_contents($path, json_encode(['stats' => $stats], JSON_THROW_ON_ERROR)));
        self::assertNotFalse(file_put_contents(
            $directory . '/infection.log',
            $this->infectionLog($timedOutMutants)
        ));

        return $shard . '=' . $path;
    }

    /** @param list<array{string, int, string, string}> $timedOutMutants */
    private function infectionLog(array $timedOutMutants): string
    {
        $lines = [
            'Timed Out mutants:',
            '==================',
            '',
        ];

        foreach ($timedOutMutants as $index => [$file, $line, $mutator, $id]) {
            $lines[] = sprintf('%d) %s:%d    [M] %s [ID] %s', $index + 1, $file, $line, $mutator, $id);
            $lines[] = '';
        }

        $lines[] = 'Skipped mutants:';
        $lines[] = '================';

        return implode("\n", $lines) . "\n";
    }

    private function runAggregator(string ...$reports): Process
    {
        $process = new Process([
            PHP_BINARY,
            __DIR__ . '/../scripts/aggregate-infection-reports.php',
            ...$reports,
        ], __DIR__ . '/..');
        $process->run();

        return $process;
    }
}
