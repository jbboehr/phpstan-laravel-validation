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
final class FormRequestResultCacheTest extends \PHPUnit\Framework\TestCase
{
    private string $projectDirectory;

    private ?string $symlinkSourceDirectory = null;

    private ?string $outOfTreeSourceDirectory = null;

    protected function setUp(): void
    {
        parent::setUp();

        $directory = tempnam(sys_get_temp_dir(), 'phpstan-form-request-');
        if (!is_string($directory)) {
            self::fail('Unable to allocate a temporary project path.');
        }
        if (!unlink($directory)
            || !mkdir($directory . '/src', 0777, true)
            || !mkdir($directory . '/external', 0777, true)
        ) {
            self::fail('Unable to create the temporary project.');
        }

        $this->projectDirectory = $directory;
        $this->writeProjectFile('composer.json', <<<'JSON'
{
    "autoload": {
        "psr-4": {
            "CacheFixture\\": "src/"
        }
    }
}
JSON);
        $this->writeConfig(false);
        $this->writeProjectFile('src/Controller.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function consume(CacheRequest $request): int
{
    $validated = $request->validated();

    return strlen($validated['value']);
}
PHP);
    }

    private function writeConfig(bool $includeUnvalidatedArrayKeys): void
    {
        $this->writeProjectFile('phpstan.neon', sprintf(
            <<<'NEON'
includes:
    - %s

parameters:
    level: max
    paths:
        - src
    tmpDir: cache
    phpstanLaravelValidation:
        includeUnvalidatedArrayKeys: %s
        formRequests:
            enabled: true
NEON,
            dirname(__DIR__) . '/extension.neon',
            $includeUnvalidatedArrayKeys ? 'true' : 'false'
        ));
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->projectDirectory);
        if ($this->symlinkSourceDirectory !== null) {
            $this->removeDirectory($this->symlinkSourceDirectory);
        }
        if ($this->outOfTreeSourceDirectory !== null) {
            $this->removeDirectory($this->outOfTreeSourceDirectory);
        }

        parent::tearDown();
    }

    public function testChangingOnlyRulesMethodBodyInvalidatesCachedCaller(): void
    {
        $this->writeRequest("return ['value' => 'required|string'];");

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeRequest("return ['value' => 'required|array'];");

        $second = $this->analyse();
        self::assertSame(1, $second->getExitCode());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $second->getErrorOutput() . $second->getOutput()
        );
    }

    public function testChangingRuleConstantInOutsidePackageMappingInvalidatesCachedCaller(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalController();
        $this->writeExternalPackageBootstrap();
        $this->writeExternalPackageRequest();
        $this->writeExternalPackageRules('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $warm = $this->analyse(true);
        self::assertSame(0, $warm->getExitCode(), $warm->getErrorOutput() . $warm->getOutput());

        $this->writeExternalPackageRules('required|array');

        $second = $this->analyse();
        self::assertSame(1, $second->getExitCode());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $second->getErrorOutput() . $second->getOutput()
        );
    }

    public function testChangingIncRuleConstantInOutsideClassmapInvalidatesCachedCaller(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalController();
        $this->writeExternalClassmapBootstrap();
        $this->writeExternalPackageRequest();
        $this->writeExternalClassmapRules('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $warm = $this->analyse(true);
        self::assertSame(0, $warm->getExitCode(), $warm->getErrorOutput() . $warm->getOutput());

        $this->writeExternalClassmapRules('required|array');

        $cached = $this->analyse();
        $uncached = $this->analyse(debug: true);
        self::assertSame(
            1,
            $uncached->getExitCode(),
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertSame(1, $cached->getExitCode(), $cached->getErrorOutput() . $cached->getOutput());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $cached->getErrorOutput() . $cached->getOutput()
        );
    }

    public function testChangingRuleConstantInUnrelatedPackageInvalidatesCachedCaller(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalController();
        $this->writeUnrelatedPackagesBootstrap();
        $this->writeRequestUsingUnrelatedPackageRules();
        $this->writeUnrelatedPackageRules('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $warm = $this->analyse(true);
        self::assertSame(0, $warm->getExitCode(), $warm->getErrorOutput() . $warm->getOutput());

        $this->writeUnrelatedPackageRules('required|array');

        $cached = $this->analyse();
        $uncached = $this->analyse(debug: true);
        self::assertSame(
            1,
            $uncached->getExitCode(),
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertSame(1, $cached->getExitCode(), $cached->getErrorOutput() . $cached->getOutput());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $cached->getErrorOutput() . $cached->getOutput()
        );
    }

    public function testChangingInjectedServiceConstantInUnrelatedPackageInvalidatesCachedCaller(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalController();
        $this->writeUnrelatedPackagesBootstrap();
        $this->writeRequestUsingInjectedServiceRules();
        $this->writeInjectedServiceRules('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $warm = $this->analyse(true);
        self::assertSame(0, $warm->getExitCode(), $warm->getErrorOutput() . $warm->getOutput());

        $this->writeInjectedServiceRules('required|array');

        $cached = $this->analyse();
        $uncached = $this->analyse(debug: true);
        self::assertSame(
            1,
            $uncached->getExitCode(),
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertSame(1, $cached->getExitCode(), $cached->getErrorOutput() . $cached->getOutput());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $cached->getErrorOutput() . $cached->getOutput()
        );
    }

    public function testChangingNamespacedConstantInUnrelatedAutoloadFileInvalidatesCachedCaller(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalController();
        $this->writeAutoloadFilePackagesBootstrap();
        $this->writeRequestUsingNamespacedConstantRules();
        $this->writeNamespacedConstantRules('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $warm = $this->analyse(true);
        self::assertSame(0, $warm->getExitCode(), $warm->getErrorOutput() . $warm->getOutput());

        $this->writeNamespacedConstantRules('required|array');

        $cached = $this->analyse();
        $uncached = $this->analyse(debug: true);
        self::assertSame(
            1,
            $uncached->getExitCode(),
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertSame(1, $cached->getExitCode(), $cached->getErrorOutput() . $cached->getOutput());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $cached->getErrorOutput() . $cached->getOutput()
        );
    }

    public function testChangingTransitiveRuleConstantInUnrelatedPackageInvalidatesCachedCaller(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalController();
        $this->writeTransitivePackagesBootstrap();
        $this->writeRequestUsingUnrelatedPackageRules();
        $this->writeDelegatingPackageRules();
        $this->writeTransitivePackageRules('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $warm = $this->analyse(true);
        self::assertSame(0, $warm->getExitCode(), $warm->getErrorOutput() . $warm->getOutput());

        $this->writeTransitivePackageRules('required|array');

        $cached = $this->analyse();
        $uncached = $this->analyse(debug: true);
        self::assertSame(
            1,
            $uncached->getExitCode(),
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertSame(1, $cached->getExitCode(), $cached->getErrorOutput() . $cached->getOutput());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $cached->getErrorOutput() . $cached->getOutput()
        );
    }

    public function testChangingHelperBesideOutOfTreeRequestInvalidatesCachedCaller(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalController();
        $this->writeOutOfTreePackage('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $warm = $this->analyse(true);
        self::assertSame(0, $warm->getExitCode(), $warm->getErrorOutput() . $warm->getOutput());

        $this->writeOutOfTreePackageRules('required|array');

        $cached = $this->analyse();
        $uncached = $this->analyse(debug: true);
        self::assertSame(
            1,
            $uncached->getExitCode(),
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertSame(1, $cached->getExitCode(), $cached->getErrorOutput() . $cached->getOutput());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $cached->getErrorOutput() . $cached->getOutput()
        );
    }

    public function testAddingLifecycleHookInvalidatesCachedCaller(): void
    {
        $this->writeRequest("return ['value' => 'required|string'];");

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeRequest(
            "return ['value' => 'required|string'];",
            <<<'PHP'

    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->setRules(['replacement' => 'required|array']);
    }
PHP
        );

        $second = $this->analyse();
        self::assertSame(1, $second->getExitCode());
        self::assertStringContainsString(
            "Cannot access offset 'value' on mixed.",
            $second->getErrorOutput() . $second->getOutput()
        );
    }

    public function testUnrelatedBodyChangeKeepsResultCacheUsable(): void
    {
        $this->writeRequest("return ['value' => 'required|string'];");
        $this->writeProjectFile('src/Unrelated.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function unrelated(): int
{
    return 1;
}
PHP);

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeProjectFile('src/Unrelated.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function unrelated(): int
{
    return 2;
}
PHP);

        $second = $this->analyse(true);
        self::assertSame(0, $second->getExitCode(), $second->getErrorOutput() . $second->getOutput());
    }

    public function testChangingIncludedArrayKeyAssumptionInvalidatesCachedCaller(): void
    {
        $this->writeRequest(<<<'PHP'
return [
    'payload' => 'required|array',
    'payload.name' => 'required|string',
];
PHP);
        $this->writeProjectFile('src/Controller.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function consume(CacheRequest $request): int
{
    $validated = $request->validated();

    return strlen($validated['payload']['name']);
}
PHP);

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeConfig(true);

        $second = $this->analyse();
        self::assertSame(1, $second->getExitCode());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, mixed given.',
            $second->getErrorOutput() . $second->getOutput()
        );
    }

    public function testFactoryModeDiagnosticFollowsIncludedArrayKeysOption(): void
    {
        $this->writeProjectFile('src/Controller.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function configure(\Illuminate\Validation\Factory $factory): void
{
    $factory->includeUnvalidatedArrayKeys();
}
PHP);

        $first = $this->analyse();
        self::assertSame(1, $first->getExitCode());
        self::assertStringContainsString(
            'Calling includeUnvalidatedArrayKeys() conflicts with '
                . 'phpstanLaravelValidation.includeUnvalidatedArrayKeys: false',
            $first->getErrorOutput() . $first->getOutput()
        );

        $this->writeConfig(true);

        $second = $this->analyse();
        self::assertSame(0, $second->getExitCode(), $second->getErrorOutput() . $second->getOutput());
    }

    public function testRegistryManifestIsWrittenAndCorruptionFallsBackSafely(): void
    {
        $this->writeRequest("return ['value' => 'required|string'];");

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $manifests = glob(
            $this->projectDirectory . '/cache/phpstan-laravel-validation/form-requests-*.json'
        );
        self::assertIsArray($manifests);
        self::assertCount(1, $manifests);
        $contents = file_get_contents($manifests[0]);
        self::assertIsString($contents);
        $manifest = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        self::assertIsArray($manifest);
        self::assertSame(2, $manifest['schema'] ?? null);
        $descriptorHash = $manifest['descriptorHash'] ?? null;
        self::assertIsString($descriptorHash);
        self::assertMatchesRegularExpression('/^[a-f0-9]{64}$/D', $descriptorHash);

        $warm = $this->analyse(true);
        self::assertSame(0, $warm->getExitCode(), $warm->getErrorOutput() . $warm->getOutput());
        self::assertSame($contents, file_get_contents($manifests[0]));

        self::assertNotFalse(file_put_contents($manifests[0], '{corrupt'));
        $second = $this->analyse(true);
        self::assertSame(0, $second->getExitCode(), $second->getErrorOutput() . $second->getOutput());
    }

    public function testChangingHelperInParentPackageInvalidatesCachedCaller(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalController();
        $this->writeCrossPackageBootstrap();
        $this->writeCrossPackageLeaf();
        $this->writeCrossPackageParent();
        $this->writeCrossPackageParentRules('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $warm = $this->analyse(true);
        self::assertSame(0, $warm->getExitCode(), $warm->getErrorOutput() . $warm->getOutput());

        $this->writeCrossPackageParentRules('required|array');

        $second = $this->analyse();
        self::assertSame(1, $second->getExitCode());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $second->getErrorOutput() . $second->getOutput()
        );
    }

    public function testChangingNonPhpAutoloadFileInSymlinkedPackageInvalidatesCachedCaller(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalController();
        $this->writeSymlinkPackage('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $warm = $this->analyse(true);
        self::assertSame(0, $warm->getExitCode(), $warm->getErrorOutput() . $warm->getOutput());

        $this->writeSymlinkPackageRules('required|array');

        $second = $this->analyse();
        self::assertSame(1, $second->getExitCode());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $second->getErrorOutput() . $second->getOutput()
        );
    }

    public function testChangingRecursiveTraitOfExplicitRequestInvalidatesCachedCaller(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalController();
        $this->writeExternalBootstrap();
        $this->writeExternalLeaf();
        $this->writeExternalTraitParent();
        $this->writeExternalTraits('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeExternalTraits('required|array');

        $second = $this->analyse();
        self::assertSame(1, $second->getExitCode());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $second->getErrorOutput() . $second->getOutput()
        );
    }

    public function testChangingInterfaceOfExplicitRequestInvalidatesCachedCaller(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalController();
        $this->writeExternalInterfaceBootstrap();
        $this->writeExternalInterfaceLeaf();
        $this->writeExternalRulesInterface('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeExternalRulesInterface('required|array');

        $second = $this->analyse();
        $uncached = $this->analyse(debug: true);
        self::assertSame(
            1,
            $uncached->getExitCode(),
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $uncached->getErrorOutput() . $uncached->getOutput()
        );
        self::assertSame(1, $second->getExitCode());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $second->getErrorOutput() . $second->getOutput()
        );
    }

    public function testExplicitRequestDependencyFingerprintDoesNotDiscoverSibling(): void
    {
        $this->writeExternalConfig();
        $this->writeExternalControllerWithSibling();
        $this->writeExternalBootstrap();
        $this->writeExternalLeaf();
        $this->writeExternalParentWithSibling();

        $analysis = $this->analyse();
        self::assertSame(1, $analysis->getExitCode());
        self::assertStringContainsString(
            'Parameter #1 $value of function count expects array|Countable, mixed given.',
            $analysis->getErrorOutput() . $analysis->getOutput()
        );
        self::assertStringNotContainsString(
            'Parameter #1 $string of function strlen expects string, mixed given.',
            $analysis->getErrorOutput() . $analysis->getOutput()
        );
    }

    private function analyse(bool $failWithoutResultCache = false, bool $debug = false): Process
    {
        $command = [
            PHP_BINARY,
            dirname(__DIR__) . '/vendor/bin/phpstan',
            'analyse',
            '--configuration',
            $this->projectDirectory . '/phpstan.neon',
            '--no-progress',
            '--error-format=raw',
        ];
        if ($failWithoutResultCache) {
            $command[] = '--fail-without-result-cache';
        }
        if ($debug) {
            $command[] = '--debug';
        }

        $process = new Process($command, $this->projectDirectory);
        $process->setTimeout(60.0);
        $process->run();

        return $process;
    }

    private function writeRequest(string $returnStatement, string $additionalMethods = ''): void
    {
        $this->writeProjectFile('src/CacheRequest.php', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;

final class CacheRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        %s
    }
%s
}
PHP,
            $returnStatement,
            $additionalMethods
        ));
    }

    private function writeExternalConfig(): void
    {
        $this->writeProjectFile('phpstan.neon', sprintf(
            <<<'NEON'
includes:
    - %s

parameters:
    level: max
    paths:
        - src
    bootstrapFiles:
        - external/bootstrap.php
    tmpDir: cache
    phpstanLaravelValidation:
        formRequests:
            enabled: true
            additionalClasses:
                - CacheFixture\ExternalRequest
NEON,
            dirname(__DIR__) . '/extension.neon'
        ));
    }

    private function writeExternalController(): void
    {
        $this->writeProjectFile('src/Controller.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function consume(ExternalRequest $request): int
{
    $validated = $request->validated();

    return strlen($validated['value']);
}
PHP);
    }

    private function writeExternalControllerWithSibling(): void
    {
        $this->writeProjectFile('src/Controller.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function consume(ExternalRequest $request): int
{
    $validated = $request->validated();

    return strlen($validated['value']);
}

function consumeSibling(ExternalSiblingRequest $request): int
{
    $validated = $request->validated();

    return count($validated['sibling']);
}
PHP);
    }

    private function writeExternalBootstrap(): void
    {
        $this->writeProjectFile('external/bootstrap.php', <<<'PHP'
<?php

declare(strict_types=1);

require_once __DIR__ . '/InnerRules.php';
require_once __DIR__ . '/OuterRules.php';
require_once __DIR__ . '/ExternalParentRequest.php';
require_once __DIR__ . '/ExternalRequest.php';
PHP);
        $this->writeProjectFile('external/InnerRules.php', "<?php\n");
        $this->writeProjectFile('external/OuterRules.php', "<?php\n");
    }

    private function writeExternalInterfaceBootstrap(): void
    {
        $this->writeProjectFile('external/bootstrap.php', <<<'PHP'
<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseRulesContract.php';
require_once __DIR__ . '/RulesContract.php';
require_once __DIR__ . '/ExternalRequest.php';
PHP);
    }

    private function writeExternalPackageBootstrap(): void
    {
        self::assertTrue(mkdir($this->projectDirectory . '/external/package/src', 0777, true));
        self::assertTrue(mkdir($this->projectDirectory . '/external/shared', 0777, true));
        $this->writeProjectFile('external/package/composer.json', <<<'JSON'
{
    "name": "cache-fixture/external-package",
    "autoload": {
        "psr-4": {
            "CacheFixture\\": ["src/", "../shared/"]
        }
    }
}
JSON);
        $this->writeProjectFile('external/bootstrap.php', <<<'PHP'
<?php

declare(strict_types=1);

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('CacheFixture\\', [__DIR__ . '/package/src', __DIR__ . '/shared']);
$loader->register();
PHP);
    }

    private function writeExternalClassmapBootstrap(): void
    {
        self::assertTrue(mkdir($this->projectDirectory . '/external/package/src', 0777, true));
        self::assertTrue(mkdir($this->projectDirectory . '/external/shared', 0777, true));
        $this->writeProjectFile('external/package/composer.json', <<<'JSON'
{
    "name": "cache-fixture/external-package",
    "autoload": {
        "psr-4": {
            "CacheFixture\\": "src/"
        },
        "classmap": ["../shared/"]
    }
}
JSON);
        $this->writeProjectFile('external/bootstrap.php', <<<'PHP'
<?php

declare(strict_types=1);

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('CacheFixture\\', __DIR__ . '/package/src');
$loader->addClassMap([
    'CacheFixture\\UserRules' => __DIR__ . '/shared/UserRules.inc',
]);
$loader->register();
PHP);
    }

    private function writeUnrelatedPackagesBootstrap(): void
    {
        self::assertTrue(mkdir($this->projectDirectory . '/external/package-a/src', 0777, true));
        self::assertTrue(mkdir($this->projectDirectory . '/external/package-b/src', 0777, true));
        $this->writeProjectFile('external/package-a/composer.json', <<<'JSON'
{
    "name": "cache-fixture/package-a",
    "autoload": {
        "psr-4": {
            "CacheFixture\\": "src/"
        }
    }
}
JSON);
        $this->writeProjectFile('external/package-b/composer.json', <<<'JSON'
{
    "name": "cache-fixture/package-b",
    "autoload": {
        "psr-4": {
            "PackageB\\": "src/"
        }
    }
}
JSON);
        $this->writeProjectFile('external/bootstrap.php', <<<'PHP'
<?php

declare(strict_types=1);

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('CacheFixture\\', __DIR__ . '/package-a/src');
$loader->addPsr4('PackageB\\', __DIR__ . '/package-b/src');
$loader->register();
PHP);
    }

    private function writeTransitivePackagesBootstrap(): void
    {
        self::assertTrue(mkdir($this->projectDirectory . '/external/package-a/src', 0777, true));
        self::assertTrue(mkdir($this->projectDirectory . '/external/package-b/src', 0777, true));
        self::assertTrue(mkdir($this->projectDirectory . '/external/package-c/src', 0777, true));
        $this->writeProjectFile('external/package-a/composer.json', <<<'JSON'
{
    "name": "cache-fixture/package-a",
    "autoload": {
        "psr-4": {
            "CacheFixture\\": "src/"
        }
    }
}
JSON);
        $this->writeProjectFile('external/package-b/composer.json', <<<'JSON'
{
    "name": "cache-fixture/package-b",
    "autoload": {
        "psr-4": {
            "PackageB\\": "src/"
        }
    }
}
JSON);
        $this->writeProjectFile('external/package-c/composer.json', <<<'JSON'
{
    "name": "cache-fixture/package-c",
    "autoload": {
        "psr-4": {
            "PackageC\\": "src/"
        }
    }
}
JSON);
        $this->writeProjectFile('external/bootstrap.php', <<<'PHP'
<?php

declare(strict_types=1);

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('CacheFixture\\', __DIR__ . '/package-a/src');
$loader->addPsr4('PackageB\\', __DIR__ . '/package-b/src');
$loader->addPsr4('PackageC\\', __DIR__ . '/package-c/src');
$loader->register();
PHP);
    }

    private function writeAutoloadFilePackagesBootstrap(): void
    {
        self::assertTrue(mkdir($this->projectDirectory . '/external/package-a/src', 0777, true));
        self::assertTrue(mkdir($this->projectDirectory . '/external/package-b/src', 0777, true));
        $this->writeProjectFile('external/package-a/composer.json', <<<'JSON'
{
    "name": "cache-fixture/package-a",
    "autoload": {
        "psr-4": {
            "CacheFixture\\": "src/"
        }
    }
}
JSON);
        $this->writeProjectFile('external/package-b/composer.json', <<<'JSON'
{
    "name": "cache-fixture/package-b",
    "autoload": {
        "files": ["src/rules.contract"]
    }
}
JSON);
        $this->writeProjectFile('external/bootstrap.php', <<<'PHP'
<?php

declare(strict_types=1);

require_once __DIR__ . '/package-b/src/rules.contract';

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('CacheFixture\\', __DIR__ . '/package-a/src');
$loader->register();
PHP);
    }

    private function writeRequestUsingUnrelatedPackageRules(): void
    {
        $this->writeProjectFile('external/package-a/src/ExternalRequest.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;
use PackageB\RuleConstants;

final class ExternalRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return RuleConstants::RULES;
    }
}
PHP);
    }

    private function writeRequestUsingInjectedServiceRules(): void
    {
        $this->writeProjectFile('external/package-a/src/ExternalRequest.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;
use PackageB\RuleService;

final class ExternalRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(RuleService $service): array
    {
        return $service::RULES;
    }
}
PHP);
    }

    private function writeRequestUsingNamespacedConstantRules(): void
    {
        $this->writeProjectFile('external/package-a/src/ExternalRequest.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;

use const PackageB\RULES;

final class ExternalRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return RULES;
    }
}
PHP);
    }

    private function writeUnrelatedPackageRules(string $rule): void
    {
        $this->writeProjectFile('external/package-b/src/RuleConstants.php', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

namespace PackageB;

final class RuleConstants
{
    /** @var array<string, string> */
    public const RULES = ['value' => '%s'];
}
PHP,
            $rule
        ));
    }

    private function writeInjectedServiceRules(string $rule): void
    {
        $this->writeProjectFile('external/package-b/src/RuleService.php', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

namespace PackageB;

final class RuleService
{
    /** @var array<string, string> */
    public const RULES = ['value' => '%s'];
}
PHP,
            $rule
        ));
    }

    private function writeNamespacedConstantRules(string $rule): void
    {
        $this->writeProjectFile('external/package-b/src/rules.contract', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

namespace PackageB;

/** @var array<string, string> */
const RULES = ['value' => '%s'];
PHP,
            $rule
        ));
    }

    private function writeDelegatingPackageRules(): void
    {
        $this->writeProjectFile('external/package-b/src/RuleConstants.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace PackageB;

final class RuleConstants
{
    /** @var array<string, string> */
    public const RULES = \PackageC\ActualRules::RULES;
}
PHP);
    }

    private function writeTransitivePackageRules(string $rule): void
    {
        $this->writeProjectFile('external/package-c/src/ActualRules.php', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

namespace PackageC;

final class ActualRules
{
    /** @var array<string, string> */
    public const RULES = ['value' => '%s'];
}
PHP,
            $rule
        ));
    }

    private function writeOutOfTreePackage(string $rule): void
    {
        $directory = tempnam(sys_get_temp_dir(), 'phpstan-form-request-mapping-');
        if (!is_string($directory)) {
            self::fail('Unable to allocate an out-of-tree source path.');
        }
        if (!unlink($directory) || !mkdir($directory)) {
            self::fail('Unable to create the out-of-tree source path.');
        }
        $this->outOfTreeSourceDirectory = $directory;

        self::assertTrue(mkdir($this->projectDirectory . '/external/package'));
        $mapping = json_encode('../../../' . basename($directory), JSON_THROW_ON_ERROR);
        $this->writeProjectFile('external/package/composer.json', sprintf(
            <<<'JSON'
{
    "name": "cache-fixture/out-of-tree-package",
    "autoload": {
        "psr-4": {
            "CacheFixture\\": %s
        }
    }
}
JSON,
            $mapping
        ));
        $this->writeProjectFile('external/bootstrap.php', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('CacheFixture\\', %s);
$loader->register();
PHP,
            var_export($directory, true)
        ));

        $result = file_put_contents($directory . '/ExternalRequest.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;

final class ExternalRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return RuleConstants::RULES;
    }
}
PHP);
        self::assertNotFalse($result);
        $this->writeOutOfTreePackageRules($rule);
    }

    private function writeOutOfTreePackageRules(string $rule): void
    {
        self::assertNotNull($this->outOfTreeSourceDirectory);
        $result = file_put_contents(
            $this->outOfTreeSourceDirectory . '/RuleConstants.php',
            sprintf(
                <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

final class RuleConstants
{
    /** @var array<string, string> */
    public const RULES = ['value' => '%s'];
}
PHP,
                $rule
            )
        );
        self::assertNotFalse($result);
    }

    private function writeExternalPackageRequest(): void
    {
        $this->writeProjectFile('external/package/src/ExternalRequest.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;

final class ExternalRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return UserRules::RULES;
    }
}
PHP);
    }

    private function writeExternalPackageRules(string $rule): void
    {
        $this->writeProjectFile('external/shared/UserRules.php', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

final class UserRules
{
    /** @var array<string, string> */
    public const RULES = ['value' => '%s'];
}
PHP,
            $rule
        ));
    }

    private function writeExternalClassmapRules(string $rule): void
    {
        $this->writeProjectFile('external/shared/UserRules.inc', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

final class UserRules
{
    /** @var array<string, string> */
    public const RULES = ['value' => '%s'];
}
PHP,
            $rule
        ));
    }

    private function writeCrossPackageBootstrap(): void
    {
        self::assertTrue(mkdir($this->projectDirectory . '/external/leaf/src', 0777, true));
        self::assertTrue(mkdir($this->projectDirectory . '/external/parent/src', 0777, true));
        $this->writeProjectFile('external/leaf/composer.json', <<<'JSON'
{
    "name": "cache-fixture/leaf-package",
    "autoload": {
        "psr-4": {
            "CacheFixture\\": "src/"
        }
    }
}
JSON);
        $this->writeProjectFile('external/parent/composer.json', <<<'JSON'
{
    "name": "cache-fixture/parent-package",
    "autoload": {
        "psr-4": {
            "CacheFixture\\": "src/"
        }
    }
}
JSON);
        $this->writeProjectFile('external/bootstrap.php', <<<'PHP'
<?php

declare(strict_types=1);

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('CacheFixture\\', [__DIR__ . '/leaf/src', __DIR__ . '/parent/src']);
$loader->register();
PHP);
    }

    private function writeCrossPackageLeaf(): void
    {
        $this->writeProjectFile('external/leaf/src/ExternalRequest.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

final class ExternalRequest extends ExternalParentRequest
{
}
PHP);
    }

    private function writeCrossPackageParent(): void
    {
        $this->writeProjectFile('external/parent/src/ExternalParentRequest.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;

abstract class ExternalParentRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ParentRules::RULES;
    }
}
PHP);
    }

    private function writeCrossPackageParentRules(string $rule): void
    {
        $this->writeProjectFile('external/parent/src/ParentRules.php', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

final class ParentRules
{
    /** @var array<string, string> */
    public const RULES = ['value' => '%s'];
}
PHP,
            $rule
        ));
    }

    private function writeSymlinkPackage(string $rule): void
    {
        $directory = tempnam(sys_get_temp_dir(), 'phpstan-form-request-source-');
        if (!is_string($directory)) {
            self::fail('Unable to allocate a symlink source path.');
        }
        if (!unlink($directory) || !mkdir($directory)) {
            self::fail('Unable to create the symlink source path.');
        }
        $this->symlinkSourceDirectory = $directory;

        self::assertTrue(mkdir($this->projectDirectory . '/external/symlink-package'));
        self::assertTrue(symlink(
            $this->symlinkSourceDirectory,
            $this->projectDirectory . '/external/symlink-package/src'
        ));
        $this->writeProjectFile('external/symlink-package/composer.json', <<<'JSON'
{
    "name": "cache-fixture/symlink-package",
    "autoload": {
        "psr-4": {
            "CacheFixture\\": "src/"
        },
        "files": ["src/rules.contract"]
    }
}
JSON);
        $this->writeProjectFile('external/bootstrap.php', <<<'PHP'
<?php

declare(strict_types=1);

require_once __DIR__ . '/symlink-package/src/rules.contract';

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('CacheFixture\\', __DIR__ . '/symlink-package/src');
$loader->register();
PHP);
        $result = file_put_contents(
            $this->symlinkSourceDirectory . '/ExternalRequest.php',
            <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;

final class ExternalRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return FileRules::RULES;
    }
}
PHP
        );
        self::assertNotFalse($result);
        $this->writeSymlinkPackageRules($rule);
    }

    private function writeSymlinkPackageRules(string $rule): void
    {
        self::assertNotNull($this->symlinkSourceDirectory);
        $result = file_put_contents(
            $this->symlinkSourceDirectory . '/rules.contract',
            sprintf(
                <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

final class FileRules
{
    /** @var array<string, string> */
    public const RULES = ['value' => '%s'];
}
PHP,
                $rule
            )
        );
        self::assertNotFalse($result);
    }

    private function writeExternalLeaf(): void
    {
        $this->writeProjectFile('external/ExternalRequest.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

final class ExternalRequest extends ExternalParentRequest
{
}
PHP);
    }

    private function writeExternalInterfaceLeaf(): void
    {
        $this->writeProjectFile('external/ExternalRequest.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;

final class ExternalRequest extends FormRequest implements RulesContract
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return self::RULES;
    }
}
PHP);
    }

    private function writeExternalParentWithSibling(): void
    {
        $this->writeProjectFile('external/ExternalParentRequest.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;

abstract class ExternalParentRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => 'required|string'];
    }
}

final class ExternalSiblingRequest extends ExternalParentRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['sibling' => 'required|array'];
    }
}
PHP);
    }

    private function writeExternalTraitParent(): void
    {
        $this->writeProjectFile('external/ExternalParentRequest.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;

abstract class ExternalParentRequest extends FormRequest
{
    use OuterRules;
}
PHP);
    }

    private function writeExternalTraits(string $rule): void
    {
        $this->writeProjectFile('external/InnerRules.php', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

trait InnerRules
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => '%s'];
    }
}
PHP,
            $rule
        ));
        $this->writeProjectFile('external/OuterRules.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

trait OuterRules
{
    use InnerRules;
}
PHP);
    }

    private function writeExternalRulesInterface(string $rule): void
    {
        $this->writeProjectFile('external/BaseRulesContract.php', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

interface BaseRulesContract
{
    /** @var array<string, string> */
    public const RULES = ['value' => '%s'];
}
PHP,
            $rule
        ));
        $this->writeProjectFile('external/RulesContract.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

interface RulesContract extends BaseRulesContract
{
}
PHP);
    }

    private function writeProjectFile(string $relativePath, string $contents): void
    {
        $result = file_put_contents(
            $this->projectDirectory . '/' . $relativePath,
            $contents
        );
        if ($result === false) {
            self::fail('Unable to write temporary project file ' . $relativePath . '.');
        }
    }

    private function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $file) {
            if (!$file instanceof \SplFileInfo) {
                continue;
            }

            if ($file->isLink()) {
                unlink($file->getPathname());
            } elseif ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }

        rmdir($directory);
    }
}
