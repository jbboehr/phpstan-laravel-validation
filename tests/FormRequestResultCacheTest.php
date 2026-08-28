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

use PHPStan\Collectors\ResultCacheDependencyCollector;
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

    public function testChangingRequestInvalidatesConstantDynamicValidatedCaller(): void
    {
        $this->writeRequest("return ['value' => 'required|string'];");
        $this->writeProjectFile('src/Controller.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function consume(CacheRequest $request): int
{
    $method = 'validated';
    $validated = $request->$method();

    return strlen($validated['value']);
}
PHP);

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeRequest("return ['value' => 'required|array'];");

        $second = $this->analyse(verbose: true);
        $output = $second->getErrorOutput() . $second->getOutput();
        self::assertSame(1, $second->getExitCode(), $output);
        self::assertStringContainsString(
            'Result cache restored. 2 files will be reanalysed.',
            $output
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $output
        );
    }

    public function testChangingRequestInvalidatesFiniteDynamicValidatedCaller(): void
    {
        $this->writeRequest("return ['value' => 'required|string'];");
        $this->writeProjectFile('src/Controller.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function consume(CacheRequest $request, bool $uppercase): int
{
    $method = $uppercase ? 'VALIDATED' : 'validated';
    $validated = $request->$method();

    return strlen($validated['value']);
}
PHP);

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeRequest("return ['value' => 'required|array'];");

        $second = $this->analyse(verbose: true);
        $output = $second->getErrorOutput() . $second->getOutput();
        self::assertSame(1, $second->getExitCode(), $output);
        self::assertStringContainsString(
            'Result cache restored. 2 files will be reanalysed.',
            $output
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $output
        );
    }

    public function testChangingRequestInvalidatesNullsafeValidatedCaller(): void
    {
        $this->writeRequest('return RuleConstants::RULES;');
        $this->writeRuleConstants('required|string');
        $this->writeProjectFile('src/Controller.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function consume(?CacheRequest $request): int
{
    $validated = $request?->validated();
    if ($validated === null) {
        return 0;
    }

    return strlen($validated['value']);
}
PHP);

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeRuleConstants('required|array');

        $second = $this->analyse(verbose: true);
        $output = $second->getErrorOutput() . $second->getOutput();
        self::assertSame(1, $second->getExitCode(), $output);
        self::assertStringContainsString(
            'Result cache restored. 3 files will be reanalysed.',
            $output
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $output
        );
    }

    public function testChangingRequestInvalidatesCallerDefinedInTrait(): void
    {
        $this->writeRequest("return ['value' => 'required|string'];");
        $this->writeProjectFile('src/Controller.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

final class Consumer
{
    use ConsumerTrait;
}
PHP);
        $this->writeProjectFile('src/ConsumerTrait.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

trait ConsumerTrait
{
    public function consume(CacheRequest $request): int
    {
        return strlen($request->validated()['value']);
    }
}
PHP);

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeRequest("return ['value' => 'required|array'];");

        $second = $this->analyse(verbose: true);
        $output = $second->getErrorOutput() . $second->getOutput();
        self::assertSame(1, $second->getExitCode(), $output);
        self::assertStringContainsString(
            'Result cache restored. 2 files will be reanalysed.',
            $output
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $output
        );
    }

    public function testChangingOneRequestInvalidatesOnlyItsConsumer(): void
    {
        $this->writeRequest("return ['value' => 'required|string'];");
        $this->writeProjectFile('src/Controller.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function consume(CacheRequest $request): int
{
    $validated = $request->safe()->all();

    return strlen($validated['value']);
}
PHP);
        $this->writeNamedRequest(
            'OtherRequest',
            "return ['value' => 'required|string'];"
        );
        $this->writeProjectFile('src/OtherController.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

function consumeOther(OtherRequest $request): int
{
    $validated = $request->validated();

    return strlen($validated['value']);
}
PHP);

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $this->writeRequest("return ['value' => 'required|array'];");

        $second = $this->analyse(verbose: true);
        $output = $second->getErrorOutput() . $second->getOutput();
        self::assertSame(1, $second->getExitCode(), $output);
        self::assertStringContainsString(
            'Result cache restored. 2 files will be reanalysed.',
            $output
        );
        self::assertStringContainsString(
            'Parameter #1 $string of function strlen expects string, array given.',
            $output
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

    public function testWarmCacheIsReusableWithoutARegistryManifest(): void
    {
        $this->writeRequest("return ['value' => 'required|string'];");

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $manifests = glob(
            $this->projectDirectory . '/cache/phpstan-laravel-validation/form-requests-*.json'
        );
        self::assertIsArray($manifests);
        self::assertSame([], $manifests);

        $warm = $this->analyse(failWithoutResultCache: true, verbose: true);
        $output = $warm->getErrorOutput() . $warm->getOutput();
        self::assertSame(0, $warm->getExitCode(), $output);
        self::assertStringContainsString(
            'Result cache restored. 0 files will be reanalysed.',
            $output
        );
    }

    public function testHistoricalDependencyKeysRestoreRegardlessOfLookupOrder(): void
    {
        $this->writeRequest("return ['value' => 'required|string'];");

        $first = $this->analyse();
        self::assertSame(0, $first->getExitCode(), $first->getErrorOutput() . $first->getOutput());

        $canonical = $this->readRecordedDependency();
        self::assertSame('CacheFixture\\CacheRequest', $canonical['dependencyKey']);
        $leadingSeparator = $canonical;
        $leadingSeparator['dependencyKey'] = '\\' . $canonical['dependencyKey'];
        $missing = [
            'extensionKey' => $canonical['extensionKey'],
            'dependencyKey' => 'CacheFixture\\RemovedRequest',
            'hash' => '0abc79d3e3cc3cbebc791a95f8643fd79027a112c5f9ac589c8252281f8d082f',
        ];
        $obsolete = [
            'extensionKey' => $canonical['extensionKey'],
            'dependencyKey' => 'cachefixture\\cacherequest',
            'hash' => '8a6207e9a85b241573e5ce4c2225fe6f014ed857d61d99dd0abf4b30810c4376',
        ];

        $orders = [
            'obsolete first' => [$obsolete, $leadingSeparator, $missing, $canonical],
            'canonical first' => [$canonical, $missing, $leadingSeparator, $obsolete],
        ];
        foreach ($orders as $order => $records) {
            $this->writeRecordedDependencies($records);

            $warm = $this->analyse(failWithoutResultCache: true, verbose: true);
            $output = $warm->getErrorOutput() . $warm->getOutput();
            self::assertSame(0, $warm->getExitCode(), $order . "\n" . $output);
            self::assertStringContainsString(
                'Result cache restored. 0 files will be reanalysed.',
                $output,
                $order
            );
        }
    }

    private function analyse(
        bool $failWithoutResultCache = false,
        bool $verbose = false
    ): Process {
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
        if ($verbose) {
            $command[] = '-vv';
        }

        $process = new Process($command, $this->projectDirectory);
        $process->setTimeout(60.0);
        $process->run();

        return $process;
    }

    private function writeRequest(string $returnStatement, string $additionalMethods = ''): void
    {
        $this->writeNamedRequest('CacheRequest', $returnStatement, $additionalMethods);
    }

    private function writeNamedRequest(
        string $className,
        string $returnStatement,
        string $additionalMethods = ''
    ): void {
        $this->writeProjectFile('src/' . $className . '.php', sprintf(
            <<<'PHP'
<?php

declare(strict_types=1);

namespace CacheFixture;

use Illuminate\Foundation\Http\FormRequest;

final class %s extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        %s
    }
%s
}
PHP,
            $className,
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

    /** @return array{extensionKey: string, dependencyKey: string, hash: string} */
    private function readRecordedDependency(): array
    {
        $cacheFile = $this->projectDirectory . '/cache/resultCache.php';
        /** @var array{collectedDataCallback: callable(): array<string, array<string, mixed>>} $cache */
        $cache = require $cacheFile;
        $collectedData = ($cache['collectedDataCallback'])();

        foreach ($collectedData as $perFile) {
            $records = $perFile[ResultCacheDependencyCollector::class] ?? null;
            if (!is_array($records) || !isset($records[0]) || !is_array($records[0])) {
                continue;
            }

            $record = $records[0];
            self::assertIsString($record['extensionKey'] ?? null);
            self::assertIsString($record['dependencyKey'] ?? null);
            self::assertIsString($record['hash'] ?? null);

            return [
                'extensionKey' => $record['extensionKey'],
                'dependencyKey' => $record['dependencyKey'],
                'hash' => $record['hash'],
            ];
        }

        self::fail('No FormRequest result-cache dependency was recorded.');
    }

    /** @param list<array{extensionKey: string, dependencyKey: string, hash: string}> $records */
    private function writeRecordedDependencies(array $records): void
    {
        $cacheFile = $this->projectDirectory . '/cache/resultCache.php';
        $contents = file_get_contents($cacheFile);
        self::assertIsString($contents);

        $callbackPosition = strpos($contents, "'collectedDataCallback' =>");
        self::assertIsInt($callbackPosition);
        $marker = var_export(ResultCacheDependencyCollector::class, true) . ' => ';
        $markerPosition = strpos($contents, $marker, $callbackPosition);
        self::assertIsInt($markerPosition);
        $arrayStart = strpos($contents, 'array (', $markerPosition + strlen($marker));
        self::assertIsInt($arrayStart);

        $depth = 0;
        $arrayEnd = null;
        for ($offset = $arrayStart; $offset < strlen($contents); $offset++) {
            if ($contents[$offset] === '(') {
                $depth++;
            } elseif ($contents[$offset] === ')') {
                $depth--;
                if ($depth === 0) {
                    $arrayEnd = $offset + 1;
                    break;
                }
            }
        }
        self::assertIsInt($arrayEnd);

        $replacement = var_export($records, true);
        $rewritten = substr($contents, 0, $arrayStart)
            . $replacement
            . substr($contents, $arrayEnd);
        self::assertNotFalse(file_put_contents($cacheFile, $rewritten));
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
