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

use jbboehr\PhpstanLaravelValidation\Test\Support\InferenceAudit;
use jbboehr\PhpstanLaravelValidation\Test\Support\InferenceAuditCases;
use jbboehr\PhpstanLaravelValidation\Test\Support\InferenceAuditProfiles;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Process\Process;

#[Group('subprocess')]
final class InferenceAuditCliTest extends \PHPUnit\Framework\TestCase
{
    public function testListsNamedCasesWithoutRunningTheAudit(): void
    {
        $process = $this->runScript('inference-audit.php', '--list-cases');

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertSame(array_keys(InferenceAuditCases::cases()), self::outputLines($process));
    }

    public function testListsProfilesWithConstraintsAndPhpFloors(): void
    {
        $process = $this->runScript('inference-audit.php', '--list-profiles');

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        $expected = [];
        foreach (InferenceAuditProfiles::all() as $name => $profile) {
            $expected[] = implode("\t", [$name, $profile['constraint'], $profile['minimumPhp']]);
        }
        self::assertSame($expected, self::outputLines($process));
    }

    public function testChecksOnlySelectedCasesAgainstABaseline(): void
    {
        $baseline = getenv('LARAVEL_AUDIT_BASELINE');
        if (!is_string($baseline) || $baseline === '') {
            $baseline = explode('.', InferenceAudit::frameworkVersion())[0] . '-latest';
        }
        $process = $this->runScript(
            'inference-audit.php',
            '--baseline=' . $baseline,
            '--case=present.value',
            '--case=missing.absent',
        );

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertStringContainsString('audit baseline matches Laravel', $process->getOutput());
    }

    public function testPrintsOnlySelectedCases(): void
    {
        $process = $this->runScript(
            'inference-audit.php',
            '--case=present.value',
            '--case=missing.absent',
        );

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        $result = json_decode($process->getOutput(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($result);
        $cases = $result['cases'] ?? null;
        self::assertIsArray($cases);
        self::assertSame(['present.value', 'missing.absent'], array_keys($cases));
    }

    public function testRejectsPartialBaselineUpdates(): void
    {
        $process = $this->runScript(
            'inference-audit.php',
            '--baseline=10-latest',
            '--case=present.value',
            '--update',
        );

        self::assertSame(2, $process->getExitCode());
        self::assertStringContainsString('baselines are always complete', $process->getErrorOutput());
    }

    public function testRejectsUnknownCases(): void
    {
        $process = $this->runScript('inference-audit.php', '--case=not-a-real-case');

        self::assertSame(2, $process->getExitCode());
        self::assertStringContainsString('Unknown audit case', $process->getErrorOutput());
    }

    public function testMatrixListsSelectedProfilesWithoutInstallingAnything(): void
    {
        $process = $this->runScript(
            'inference-audit-matrix.php',
            '--list',
            '--profile=10.0.0',
            '--profile=13-latest',
        );

        self::assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        self::assertSame(['10.0.0' . "\t" . '8.1', '13-latest' . "\t" . '8.3'], self::outputLines($process));
    }

    public function testMatrixRejectsUnknownProfiles(): void
    {
        $process = $this->runScript('inference-audit-matrix.php', '--list', '--profile=not-a-real-profile');

        self::assertSame(2, $process->getExitCode());
        self::assertStringContainsString('Unknown audit profile', $process->getErrorOutput());
    }

    private function runScript(string $script, string ...$arguments): Process
    {
        $process = new Process(
            [PHP_BINARY, __DIR__ . '/../scripts/' . $script, ...$arguments],
            __DIR__ . '/..',
        );
        $process->run();

        return $process;
    }

    /** @return list<string> */
    private static function outputLines(Process $process): array
    {
        return explode("\n", rtrim($process->getOutput(), "\n"));
    }
}
