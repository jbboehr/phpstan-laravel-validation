<?php

/**
 * Copyright (c) anno Domini nostri Jesu Christi MMXXVI John Boehr & contributors
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

namespace jbboehr\PhpstanLaravelValidation\Test\Documentation;

use PHPUnit\Framework\TestCase;

/**
 * @group documentation
 */
final class DocumentLooksBackAssetsTest extends TestCase
{
    private const COPIED_FILES = [
        'document-looks-back.css',
        'document-looks-back.js',
        'vendor/THREE-LICENSE.txt',
        'vendor/three.core.min.js',
        'vendor/three.module.min.js',
    ];

    public function testPublicRuntimeMatchesTheComposerInstalledIntegration(): void
    {
        $projectRoot = dirname(__DIR__, 2);
        $upstreamRoot = $projectRoot
            . '/vendor/jbboehr/doctrine-of-the-second-sun/integrations/web/document-looks-back';
        $publicRoot = $projectRoot . '/docs/pages/assets/document-looks-back';

        foreach (self::COPIED_FILES as $relativePath) {
            self::assertFileEquals(
                $upstreamRoot . '/' . $relativePath,
                $publicRoot . '/' . $relativePath,
                $relativePath . ' must remain an unmodified copy of the installed Document Looks Back runtime.',
            );
        }

        self::assertFileEquals(
            $projectRoot . '/vendor/jbboehr/doctrine-of-the-second-sun/LICENSE.md',
            $publicRoot . '/DOCTRINE-LICENSE.txt',
        );
    }

    public function testProvenanceNoticeRecordsTheLockedDoctrineRevision(): void
    {
        $projectRoot = dirname(__DIR__, 2);
        $lock = json_decode(
            (string) file_get_contents($projectRoot . '/composer.lock'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        if (!is_array($lock)) {
            self::fail('composer.lock must decode to an object.');
        }

        $packages = $lock['packages-dev'] ?? null;
        if (!is_array($packages)) {
            self::fail('composer.lock must contain development packages.');
        }

        $reference = null;
        foreach ($packages as $package) {
            if (!is_array($package)) {
                continue;
            }

            if ('jbboehr/doctrine-of-the-second-sun' === ($package['name'] ?? null)) {
                $source = $package['source'] ?? null;
                if (is_array($source)) {
                    $reference = $source['reference'] ?? null;
                }
                break;
            }
        }

        self::assertIsString($reference);
        self::assertMatchesRegularExpression('/^[a-f0-9]{40}$/', $reference);

        $notice = file_get_contents($projectRoot . '/docs/pages/assets/document-looks-back/NOTICE.txt');
        self::assertNotFalse($notice);
        self::assertStringContainsString('revision ' . $reference, $notice);
    }

    public function testMdBookThemeExposesTheControllerSummonApi(): void
    {
        $projectRoot = dirname(__DIR__, 2);
        $script = file_get_contents($projectRoot . '/docs/theme/phpstan-laravel-validation.js');
        self::assertNotFalse($script);

        self::assertStringContainsString('assets/document-looks-back/', $script);
        self::assertStringContainsString('new module.DocumentLooksBack({', $script);
        self::assertStringContainsString('controller.mount()', $script);
        self::assertStringContainsString('window.documentLooksBack = controller', $script);
        self::assertStringContainsString('selector: "p, li"', $script);
    }
}
