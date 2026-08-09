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
        if ($profile['exact']) {
            self::assertSame($actual['laravel'], $expected['recordedVersion'] ?? null);
            self::assertSame($actual['laravelReference'], $expected['commit'] ?? null);
        }
        self::assertSame($expected['cases'] ?? null, $actual['cases']);

        foreach ($actual['cases'] as $caseId => $case) {
            self::assertNotContains(
                $case['inference']['classification'],
                ['inference-error', 'observed-unsound', 'runtime-exception'],
                $caseId . ' did not produce a usable conformance result'
            );
            self::assertNotContains(
                $case['precision']['classification'],
                ['candidate-indeterminate', 'inference-error', 'preservation-mismatch', 'runtime-exception'],
                $caseId . ' did not produce a usable precision result'
            );
        }
    }

    public function testEveryAuditProfileHasAProvenancedBaseline(): void
    {
        foreach (InferenceAuditProfiles::all() as $name => $profile) {
            self::assertMatchesRegularExpression('/^8\.[1-5]$/D', $profile['minimumPhp']);

            $baselineFile = __DIR__ . '/fixtures/version-audit/' . $name . '.json';
            self::assertFileExists($baselineFile);
            $baseline = json_decode((string) file_get_contents($baselineFile), true, 512, JSON_THROW_ON_ERROR);

            self::assertIsArray($baseline);
            self::assertSame($name, $baseline['profile'] ?? null);
            self::assertSame($profile['constraint'], $baseline['constraint'] ?? null);
            self::assertIsString($baseline['recordedVersion'] ?? null);
            $commit = $baseline['commit'] ?? null;
            self::assertIsString($commit);
            self::assertMatchesRegularExpression('/^[a-f0-9]{40}$/D', $commit);
            if (!isset($baseline['cases']) || !is_array($baseline['cases'])) {
                self::fail($baselineFile . ' does not contain an audit case map');
            }
            self::assertSame(array_keys(InferenceAuditCases::cases()), array_keys($baseline['cases']));
        }
    }

    public function testVersionDependentPrecisionWitnessesAreExplicit(): void
    {
        $byCase = [];
        foreach (array_keys(InferenceAuditProfiles::all()) as $profile) {
            $baselineFile = __DIR__ . '/fixtures/version-audit/' . $profile . '.json';
            foreach (self::precisionClassifications($baselineFile) as $caseId => $classification) {
                $byCase[$caseId][] = $classification;
            }
        }

        $versionDependent = [];
        foreach ($byCase as $caseId => $classifications) {
            if (count(array_unique($classifications)) > 1) {
                $versionDependent[] = $caseId;
            }
        }

        self::assertSame([
            'integer_strict.string',
            'integer_strict.float',
            'integer_strict.true',
            'integer_strict.stringable',
            'ascii.integer',
            'ascii.float',
            'ascii.true',
            'ascii.false',
            'ascii.null',
            'ascii.stringable',
            'ascii.resource',
            'ascii.array',
        ], $versionDependent);
    }

    /**
     * @return array<string, string>
     */
    private static function precisionClassifications(string $baselineFile): array
    {
        $baseline = json_decode((string) file_get_contents($baselineFile), true, 512, JSON_THROW_ON_ERROR);
        $cases = is_array($baseline) ? ($baseline['cases'] ?? null) : null;
        if (!is_array($cases)) {
            self::fail($baselineFile . ' does not contain an audit case map');
        }

        $classifications = [];
        foreach ($cases as $caseId => $case) {
            $precision = is_array($case) ? ($case['precision'] ?? null) : null;
            $classification = is_array($precision) ? ($precision['classification'] ?? null) : null;
            if (!is_string($caseId) || !is_string($classification)) {
                self::fail($baselineFile . ' contains an invalid precision result');
            }
            $classifications[$caseId] = $classification;
        }

        return $classifications;
    }

    /**
     * @return list<string>
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../extension.neon'];
    }
}
