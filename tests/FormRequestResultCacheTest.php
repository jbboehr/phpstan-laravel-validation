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

    protected function setUp(): void
    {
        parent::setUp();

        $directory = tempnam(sys_get_temp_dir(), 'phpstan-form-request-');
        if (!is_string($directory)) {
            self::fail('Unable to allocate a temporary project path.');
        }
        if (!unlink($directory) || !mkdir($directory . '/src', 0777, true)) {
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
        formRequests:
            enabled: true
NEON,
            dirname(__DIR__) . '/extension.neon'
        ));
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

    protected function tearDown(): void
    {
        $this->removeDirectory($this->projectDirectory);

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

    public function testChangingExternalRuleConstantInvalidatesCachedCaller(): void
    {
        $this->writeRequest('return RuleConstants::RULES;');
        $this->writeRuleConstants('required|string');

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeRuleConstants('required|array');

        $second = $this->analyse();
        self::assertSame(1, $second->getExitCode());
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $second->getErrorOutput() . $second->getOutput()
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

    private function analyse(bool $failWithoutResultCache = false): Process
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

    private function writeRuleConstants(string $rule): void
    {
        $this->writeProjectFile('src/RuleConstants.php', sprintf(
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
        ));
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

            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }

        rmdir($directory);
    }
}
