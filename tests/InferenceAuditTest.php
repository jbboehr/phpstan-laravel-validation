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

final class InferenceAuditTest extends \PHPStan\Testing\PHPStanTestCase
{
    public function testCaseInventoryReferencesExistingCases(): void
    {
        $cases = InferenceAuditCases::cases();

        foreach (InferenceAuditCases::inventory() as $name => $entry) {
            foreach ($entry['evidence'] as $caseId) {
                self::assertArrayHasKey($caseId, $cases, $name . ' references an unknown case');
            }
        }
    }

    public function testInstalledLaravelMatchesRecordedAuditContract(): void
    {
        self::getContainer();

        $actual = InferenceAudit::run(InferenceAuditCases::cases());
        $baselineName = getenv('LARAVEL_AUDIT_BASELINE');
        if (!is_string($baselineName) || $baselineName === '') {
            $major = explode('.', $actual['laravel'])[0];
            $baselineName = $major . '-latest';
        }

        $profiles = InferenceAuditProfiles::all();
        self::assertArrayHasKey($baselineName, $profiles);
        $profile = $profiles[$baselineName];
        if ($profile['exact']) {
            self::assertSame($profile['expected'], $actual['laravel']);
        } else {
            self::assertStringStartsWith($profile['expected'] . '.', $actual['laravel']);
        }

        $baselineFile = __DIR__ . '/fixtures/version-audit/' . $baselineName . '.json';
        self::assertFileExists($baselineFile);
        $expected = json_decode((string) file_get_contents($baselineFile), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($expected);
        self::assertSame($expected['cases'] ?? null, $actual['cases']);

        foreach ($actual['cases'] as $caseId => $case) {
            self::assertNotContains(
                $case['inference']['classification'],
                ['inference-error', 'observed-unsound', 'runtime-exception'],
                $caseId . ' did not produce a usable conformance result'
            );
        }
    }

    public function testEveryAuditProfileHasAProvenancedBaseline(): void
    {
        foreach (InferenceAuditProfiles::all() as $name => $profile) {
            self::assertMatchesRegularExpression('/^[a-f0-9]{40}$/D', $profile['commit']);
            self::assertMatchesRegularExpression('/^8\.[1-5]$/D', $profile['minimumPhp']);

            $baselineFile = __DIR__ . '/fixtures/version-audit/' . $name . '.json';
            self::assertFileExists($baselineFile);
            $baseline = json_decode((string) file_get_contents($baselineFile), true, 512, JSON_THROW_ON_ERROR);

            self::assertIsArray($baseline);
            self::assertSame($name, $baseline['profile'] ?? null);
            self::assertSame($profile['constraint'], $baseline['constraint'] ?? null);
            self::assertSame($profile['commit'], $baseline['commit'] ?? null);
            if (!isset($baseline['cases']) || !is_array($baseline['cases'])) {
                self::fail($baselineFile . ' does not contain an audit case map');
            }
            self::assertSame(array_keys(InferenceAuditCases::cases()), array_keys($baseline['cases']));
        }
    }

    /**
     * @return list<string>
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../extension.neon'];
    }
}
