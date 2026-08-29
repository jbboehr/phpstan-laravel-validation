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
final class NixJunitReportCollectorTest extends \PHPUnit\Framework\TestCase
{
    private string $temporaryDirectory;

    /** @var list<string> */
    private array $cleanupDirectories = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->temporaryDirectory = sys_get_temp_dir()
            . '/phpstan-laravel-validation-junit-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($this->temporaryDirectory, 0700));
        $this->cleanupDirectories[] = $this->temporaryDirectory;
    }

    protected function tearDown(): void
    {
        foreach (array_reverse($this->cleanupDirectories) as $directory) {
            $this->removeDirectory($directory);
        }

        parent::tearDown();
    }

    public function testCopiesReportFromSuccessfulNixOutput(): void
    {
        $retainedRoot = $this->temporaryDirectory . '/nix-builds';
        self::assertTrue(mkdir($retainedRoot));
        $output = $this->temporaryDirectory . '/result';
        self::assertTrue(mkdir($output . '/reports', 0700, true));
        $report = '<testsuites tests="1" failures="0"/>';
        self::assertNotFalse(file_put_contents($output . '/reports/phpunit-junit.xml', $report));
        $log = $this->temporaryDirectory . '/nix-build.log';
        self::assertNotFalse(file_put_contents($log, ''));

        $destination = $this->temporaryDirectory . '/collected';
        $process = $this->collect($log, $output, $retainedRoot, $destination);

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertSame($report, file_get_contents($destination . '/phpunit-junit.xml'));
    }

    public function testDoesNotDereferenceSymlinkedReportFromSuccessfulNixOutput(): void
    {
        $retainedRoot = $this->temporaryDirectory . '/nix-builds';
        self::assertTrue(mkdir($retainedRoot));
        $output = $this->temporaryDirectory . '/result';
        self::assertTrue(mkdir($output . '/reports', 0700, true));
        $outside = $this->temporaryDirectory . '/runner-readable-file';
        self::assertNotFalse(file_put_contents($outside, 'not a test report'));
        self::assertTrue(symlink($outside, $output . '/reports/phpunit-junit.xml'));
        $log = $this->temporaryDirectory . '/nix-build.log';
        self::assertNotFalse(file_put_contents($log, ''));

        $destination = $this->temporaryDirectory . '/collected';
        $process = $this->collect($log, $output, $retainedRoot, $destination);

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertDirectoryDoesNotExist($destination);
    }

    public function testDoesNotFollowReportDirectoryOutsideSuccessfulNixOutput(): void
    {
        $retainedRoot = $this->temporaryDirectory . '/nix-builds';
        self::assertTrue(mkdir($retainedRoot));
        $output = $this->temporaryDirectory . '/result';
        self::assertTrue(mkdir($output));
        $outside = $this->temporaryDirectory . '/outside-reports';
        self::assertTrue(mkdir($outside));
        self::assertNotFalse(file_put_contents(
            $outside . '/phpunit-junit.xml',
            'not a test report',
        ));
        self::assertTrue(symlink($outside, $output . '/reports'));
        $log = $this->temporaryDirectory . '/nix-build.log';
        self::assertNotFalse(file_put_contents($log, ''));

        $destination = $this->temporaryDirectory . '/collected';
        $process = $this->collect($log, $output, $retainedRoot, $destination);

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertDirectoryDoesNotExist($destination);
    }

    public function testCopiesReportFromRetainedFailedBuild(): void
    {
        $retainedRoot = $this->temporaryDirectory . '/nix-builds';
        $retained = $retainedRoot . '/nix-123456-987654321/build';
        self::assertTrue(mkdir(
            $retained . '/phpstan-laravel-validation-junit',
            0700,
            true,
        ));
        $report = '<testsuites tests="1" failures="1"/>';
        self::assertNotFalse(file_put_contents(
            $retained . '/phpstan-laravel-validation-junit/phpunit-junit.xml',
            $report,
        ));

        $log = $this->temporaryDirectory . '/nix-build.log';
        self::assertNotFalse(file_put_contents(
            $log,
            "error: builder failed\nnote: keeping build directory \"{$retained}\"\n",
        ));

        $destination = $this->temporaryDirectory . '/collected';
        $process = $this->collect(
            $log,
            $this->temporaryDirectory . '/missing-result',
            $retainedRoot,
            $destination,
        );

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertSame($report, file_get_contents($destination . '/phpunit-junit.xml'));
    }

    public function testDoesNotDereferenceSymlinkedReportFromRetainedFailedBuild(): void
    {
        $retainedRoot = $this->temporaryDirectory . '/nix-builds';
        $retained = $retainedRoot . '/nix-123456-987654321/build';
        self::assertTrue(mkdir(
            $retained . '/phpstan-laravel-validation-junit',
            0700,
            true,
        ));
        $outside = $this->temporaryDirectory . '/runner-readable-file';
        self::assertNotFalse(file_put_contents($outside, 'not a test report'));
        self::assertTrue(symlink(
            $outside,
            $retained . '/phpstan-laravel-validation-junit/phpunit-junit.xml',
        ));

        $log = $this->temporaryDirectory . '/nix-build.log';
        self::assertNotFalse(file_put_contents(
            $log,
            "note: keeping build directory \"{$retained}\"\n",
        ));

        $destination = $this->temporaryDirectory . '/collected';
        $process = $this->collect(
            $log,
            $this->temporaryDirectory . '/missing-result',
            $retainedRoot,
            $destination,
        );

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertDirectoryDoesNotExist($destination);
    }

    public function testIgnoresRetainedDirectoriesOutsideConfiguredRoot(): void
    {
        $retainedRoot = $this->temporaryDirectory . '/nix-builds';
        self::assertTrue(mkdir($retainedRoot));
        $outside = $this->temporaryDirectory . '/outside/nix-123456-987654321/build';
        self::assertTrue(mkdir(
            $outside . '/phpstan-laravel-validation-junit',
            0700,
            true,
        ));
        self::assertNotFalse(file_put_contents(
            $outside . '/phpstan-laravel-validation-junit/phpunit-junit.xml',
            '<testsuites tests="1" failures="1"/>',
        ));

        $log = $this->temporaryDirectory . '/nix-build.log';
        self::assertNotFalse(file_put_contents(
            $log,
            "note: keeping build directory \"{$outside}\"\n",
        ));

        $destination = $this->temporaryDirectory . '/collected';
        $process = $this->collect(
            $log,
            $this->temporaryDirectory . '/missing-result',
            $retainedRoot,
            $destination,
        );

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertDirectoryDoesNotExist($destination);
    }

    public function testMissingBuildLogDoesNotMaskTheOriginalBuildFailure(): void
    {
        $retainedRoot = $this->temporaryDirectory . '/nix-builds';
        self::assertTrue(mkdir($retainedRoot));
        $destination = $this->temporaryDirectory . '/collected';

        $process = $this->collect(
            $this->temporaryDirectory . '/missing-build.log',
            $this->temporaryDirectory . '/missing-result',
            $retainedRoot,
            $destination,
        );

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertDirectoryDoesNotExist($destination);
    }

    public function testMissingRetainedBuildRootDoesNotMaskTheOriginalBuildFailure(): void
    {
        $log = $this->temporaryDirectory . '/nix-build.log';
        self::assertNotFalse(file_put_contents($log, 'build failed'));
        $destination = $this->temporaryDirectory . '/collected';

        $process = $this->collect(
            $log,
            $this->temporaryDirectory . '/missing-result',
            $this->temporaryDirectory . '/missing-build-root',
            $destination,
        );

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertDirectoryDoesNotExist($destination);
    }

    public function testFindsReportAfterEarlierRetainedBuildWithoutOne(): void
    {
        $retainedRoot = $this->temporaryDirectory . '/nix-builds';
        $first = $retainedRoot . '/nix-123456-111111111/build';
        $second = $retainedRoot . '/nix-123456-222222222/build';
        self::assertTrue(mkdir($first, 0700, true));
        self::assertTrue(mkdir(
            $second . '/phpstan-laravel-validation-junit',
            0700,
            true,
        ));
        $report = '<testsuites tests="2" failures="1"/>';
        self::assertNotFalse(file_put_contents(
            $second . '/phpstan-laravel-validation-junit/phpunit-junit.xml',
            $report,
        ));
        $log = $this->temporaryDirectory . '/nix-build.log';
        self::assertNotFalse(file_put_contents(
            $log,
            "note: keeping build directory \"{$first}\"\n"
                . "note: keeping build directory \"{$second}\"\n",
        ));

        $destination = $this->temporaryDirectory . '/collected';
        $process = $this->collect(
            $log,
            $this->temporaryDirectory . '/missing-result',
            $retainedRoot,
            $destination,
        );

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertSame($report, file_get_contents($destination . '/phpunit-junit.xml'));
    }

    private function collect(
        string $log,
        string $output,
        string $retainedRoot,
        string $destination,
    ): Process {
        $process = new Process([
            'bash',
            __DIR__ . '/../scripts/collect-nix-junit-report.bash',
            $log,
            $output,
            $retainedRoot,
            $destination,
        ], __DIR__ . '/..');
        $process->run();

        return $process;
    }

    private function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($iterator as $item) {
            if (!$item instanceof \SplFileInfo) {
                continue;
            }

            if ($item->isLink()) {
                self::assertTrue(unlink($item->getPathname()));
            } elseif ($item->isDir()) {
                self::assertTrue(rmdir($item->getPathname()));
            } else {
                self::assertTrue(unlink($item->getPathname()));
            }
        }

        self::assertTrue(rmdir($directory));
    }
}
