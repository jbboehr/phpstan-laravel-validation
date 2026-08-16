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
final class HeliogenesisAssetsTest extends TestCase
{
    private const COPIED_FILES = [
        'heliogenesis.css',
        'heliogenesis-document.css',
        'heliogenesis-options.js',
        'heliogenesis-scene.js',
        'heliogenesis.js',
        'vendor/THREE-LICENSE.txt',
        'vendor/three.core.min.js',
        'vendor/three.module.min.js',
    ];

    public function testPublicRuntimeMatchesTheComposerInstalledIntegration(): void
    {
        $projectRoot = dirname(__DIR__, 2);
        $upstreamRoot = $projectRoot
            . '/vendor/jbboehr/doctrine-of-the-second-sun/integrations/web/heliogenesis';
        $publicRoot = $projectRoot . '/docs/pages/assets/heliogenesis';

        foreach (self::COPIED_FILES as $relativePath) {
            self::assertFileEquals(
                $upstreamRoot . '/' . $relativePath,
                $publicRoot . '/' . $relativePath,
                $relativePath . ' must remain an unmodified copy of the installed Heliogenesis runtime.',
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

        $notice = file_get_contents($projectRoot . '/docs/pages/assets/heliogenesis/NOTICE.txt');
        self::assertNotFalse($notice);
        self::assertStringContainsString('revision ' . $reference, $notice);
    }

    public function testMdBookThemeMountsTheOptInRuntimeWithDocumentTomography(): void
    {
        $projectRoot = dirname(__DIR__, 2);
        $script = file_get_contents($projectRoot . '/docs/theme/phpstan-laravel-validation.js');
        self::assertNotFalse($script);

        self::assertStringContainsString('assets/heliogenesis/', $script);
        self::assertStringContainsString('aria-label", "Dawn the Second Sun"', $script);
        self::assertStringContainsString('new module.Heliogenesis({ trigger })', $script);
        self::assertStringContainsString('document.querySelector("#mdbook-page-wrapper") ?? document.body', $script);
        self::assertStringContainsString('mark(world, "data-heliogenesis-world")', $script);
        self::assertStringContainsString('data-heliogenesis-surface', $script);
        self::assertStringContainsString('data-heliogenesis-callout', $script);
        self::assertStringContainsString('data-heliogenesis-code', $script);
        self::assertStringContainsString('data-heliogenesis-rule', $script);
        self::assertStringContainsString('classList.add("expanded")', $script);
        self::assertStringContainsString('headingsByChapter', $script);
        self::assertStringContainsString('injectInactivePageOutlines', $script);
        self::assertStringContainsString('page-outline', $script);
        self::assertStringContainsString('mountWideNavigation', $script);
        self::assertStringContainsString('wide-navigation-title', $script);
        self::assertStringContainsString('wide-navigation-mounted', $script);
        self::assertStringContainsString('trigger.hidden = true', $script);
        self::assertStringContainsString('trigger.hidden = false', $script);
        self::assertStringContainsString('stylesheet.element.remove()', $script);
        self::assertStringContainsString('unmarkDocument()', $script);
        self::assertStringNotContainsString('window.documentLooksBack = {', $script);

        $stylesheet = file_get_contents($projectRoot . '/docs/theme/phpstan-laravel-validation.css');
        self::assertNotFalse($stylesheet);
        self::assertStringContainsString('#mdbook-menu-bar .menu-title', $stylesheet);
        self::assertStringContainsString('display: flex', $stylesheet);
        self::assertStringContainsString('.nav-wide-wrapper', $stylesheet);
        self::assertStringContainsString('.wide-navigation-title', $stylesheet);
        self::assertStringContainsString('position: static', $stylesheet);
        self::assertStringContainsString('margin-left: auto', $stylesheet);
        self::assertStringContainsString('[data-heliogenesis-state="idle"]', $stylesheet);
        self::assertStringContainsString('transition-duration: 0s', $stylesheet);
    }
}
