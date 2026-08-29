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
final class NixStoreDatabaseWaitTest extends \PHPUnit\Framework\TestCase
{
    private string $temporaryDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->temporaryDirectory = sys_get_temp_dir()
            . '/phpstan-laravel-validation-nix-db-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($this->temporaryDirectory, 0700));
    }

    protected function tearDown(): void
    {
        $iterator = new \FilesystemIterator($this->temporaryDirectory);
        foreach ($iterator as $item) {
            if (!$item instanceof \SplFileInfo) {
                continue;
            }

            self::assertTrue(unlink($item->getPathname()));
        }

        self::assertTrue(rmdir($this->temporaryDirectory));

        parent::tearDown();
    }

    public function testRetriesCommandFailuresAndIncompleteCheckpoints(): void
    {
        $database = $this->temporaryDirectory . '/db.sqlite';
        self::assertNotFalse(file_put_contents($database, ''));
        $attempts = $this->temporaryDirectory . '/attempts';
        self::assertNotFalse(file_put_contents($attempts, '0'));
        $sqlite = $this->createFakeSqlite(<<<'BASH'
case "$attempt" in
    1)
        echo 'Error: in prepare, database is locked (5)' >&2
        exit 5
        ;;
    2)
        echo '1|2|1'
        ;;
    *)
        echo '0|2|2'
        ;;
esac
BASH);

        $process = $this->waitForDatabase($database, $attempts, $sqlite, 2);

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertSame('3', file_get_contents($attempts));
    }

    public function testFailsAfterTheDatabaseRemainsBusy(): void
    {
        $database = $this->temporaryDirectory . '/db.sqlite';
        self::assertNotFalse(file_put_contents($database, ''));
        $attempts = $this->temporaryDirectory . '/attempts';
        self::assertNotFalse(file_put_contents($attempts, '0'));
        $sqlite = $this->createFakeSqlite("echo '1|0|0'");

        $process = $this->waitForDatabase($database, $attempts, $sqlite, 0);

        self::assertSame(1, $process->getExitCode());
        self::assertSame('1', file_get_contents($attempts));
        self::assertStringContainsString('Timed out', $process->getErrorOutput());
    }

    public function testTreatsLeadingZeroTimeoutAsDecimal(): void
    {
        $database = $this->temporaryDirectory . '/db.sqlite';
        self::assertNotFalse(file_put_contents($database, ''));
        $attempts = $this->temporaryDirectory . '/attempts';
        self::assertNotFalse(file_put_contents($attempts, '0'));
        $sqlite = $this->createFakeSqlite(<<<'BASH'
if [[ "$attempt" -eq 1 ]]; then
    echo '1|0|0'
else
    echo '0|0|0'
fi
BASH);

        $process = $this->waitForDatabase($database, $attempts, $sqlite, '08');

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertSame('2', file_get_contents($attempts));
    }

    public function testDoesNotRetryPermanentSqliteErrors(): void
    {
        $database = $this->temporaryDirectory . '/db.sqlite';
        self::assertNotFalse(file_put_contents($database, ''));
        $attempts = $this->temporaryDirectory . '/attempts';
        self::assertNotFalse(file_put_contents($attempts, '0'));
        $sqlite = $this->createFakeSqlite(<<<'BASH'
if [[ "$attempt" -eq 1 ]]; then
    echo 'Error: file is not a database (26)' >&2
    exit 26
fi

echo '0|0|0'
BASH);

        $process = $this->waitForDatabase($database, $attempts, $sqlite, 2);

        self::assertSame(1, $process->getExitCode());
        self::assertSame('1', file_get_contents($attempts));
        self::assertStringContainsString('file is not a database', $process->getErrorOutput());
    }

    public function testRejectsInvalidTimeoutBeforeInvokingSqlite(): void
    {
        $database = $this->temporaryDirectory . '/db.sqlite';
        self::assertNotFalse(file_put_contents($database, ''));
        $attempts = $this->temporaryDirectory . '/attempts';
        self::assertNotFalse(file_put_contents($attempts, '0'));
        $sqlite = $this->createFakeSqlite("echo '0|0|0'");

        $process = $this->waitForDatabase($database, $attempts, $sqlite, 'invalid');

        self::assertSame(2, $process->getExitCode());
        self::assertSame('0', file_get_contents($attempts));
        self::assertStringContainsString('non-negative integer', $process->getErrorOutput());
    }

    public function testRejectsMissingDatabaseBeforeInvokingSqlite(): void
    {
        $database = $this->temporaryDirectory . '/missing.sqlite';
        $attempts = $this->temporaryDirectory . '/attempts';
        self::assertNotFalse(file_put_contents($attempts, '0'));
        $sqlite = $this->createFakeSqlite("echo '0|0|0'");

        $process = $this->waitForDatabase($database, $attempts, $sqlite, 2);

        self::assertSame(1, $process->getExitCode());
        self::assertSame('0', file_get_contents($attempts));
        self::assertStringContainsString('does not exist', $process->getErrorOutput());
    }

    public function testRejectsUnavailableSqliteBeforeAttemptingCheckpoint(): void
    {
        $database = $this->temporaryDirectory . '/db.sqlite';
        self::assertNotFalse(file_put_contents($database, ''));
        $attempts = $this->temporaryDirectory . '/attempts';
        self::assertNotFalse(file_put_contents($attempts, '0'));
        $sqlite = $this->createFakeSqlite("echo '0|0|0'", 127);

        $process = $this->waitForDatabase($database, $attempts, $sqlite, 2);

        self::assertSame(1, $process->getExitCode());
        self::assertSame('0', file_get_contents($attempts));
        self::assertStringContainsString('SQLite is unavailable', $process->getErrorOutput());
    }

    private function createFakeSqlite(string $behavior, int $versionStatus = 0): string
    {
        $sqlite = $this->temporaryDirectory . '/sqlite3';
        $script = str_replace('__VERSION_STATUS__', (string) $versionStatus, <<<'BASH'
#!/usr/bin/env bash

set -euo pipefail

if [[ "$#" -eq 1 && "$1" == '--version' ]]; then
    echo '3.51.2 2025-11-04'
    exit __VERSION_STATUS__
fi

if [[ "$#" -ne 2 || "$1" != "$FAKE_SQLITE_DATABASE" || "$2" != 'PRAGMA wal_checkpoint(TRUNCATE);' ]]; then
    exit 64
fi

attempt="$(cat "$FAKE_SQLITE_ATTEMPTS")"
attempt="$((attempt + 1))"
printf '%s' "$attempt" > "$FAKE_SQLITE_ATTEMPTS"

BASH);
        self::assertNotFalse(file_put_contents($sqlite, $script . $behavior . "\n"));
        self::assertTrue(chmod($sqlite, 0700));

        return $sqlite;
    }

    private function waitForDatabase(
        string $database,
        string $attempts,
        string $sqlite,
        int|string $timeout,
    ): Process {
        $process = new Process([
            'bash',
            __DIR__ . '/../scripts/wait-for-nix-store-database.bash',
            $database,
            (string) $timeout,
        ], __DIR__ . '/..', [
            'FAKE_SQLITE_ATTEMPTS' => $attempts,
            'FAKE_SQLITE_DATABASE' => $database,
            'PATH' => dirname($sqlite) . ':' . (string) getenv('PATH'),
        ]);
        $process->run();

        return $process;
    }
}
