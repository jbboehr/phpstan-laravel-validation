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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Process\Process;

#[Group('subprocess')]
#[Group('form-request-value-cache')]
#[Group('form-request-result-cache')]
final class FormRequestValueCacheTest extends \PHPUnit\Framework\TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/form-request-value-cache-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($this->directory . '/src', 0777, true));
        self::assertTrue(mkdir($this->directory . '/external'));
        $this->write('composer.json', '{"autoload":{"psr-4":{"ValueCacheFixture\\\\":"src/"}}}');
        $this->write('phpstan.neon', sprintf(<<<'NEON'
includes:
    - %s/extension.neon
parameters:
    level: max
    inferPrivatePropertyTypeFromConstructor: true
    paths:
        - src
    scanDirectories:
        - external
    tmpDir: cache
    phpstanLaravelValidation:
        assumeHttpInputNormalization: false
        includeUnvalidatedArrayKeys: false
        formRequests:
            enabled: true
NEON, dirname(__DIR__)));
        $this->write('src/Unrelated.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
function unrelated(CacheRequest $request): string
{
    return $request->method();
}
PHP);
    }

    protected function tearDown(): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS),
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
        rmdir($this->directory);
    }

    /** @return array<string, array{string}> */
    public static function payloadCalls(): array
    {
        return [
            'validated' => ['$request->validated()'],
            'safe keys' => ["\$request->safe(['value'])"],
            'safe all' => ['$request->safe()->all()'],
            'safe merge' => ['$request->safe()->merge([])->all()'],
        ];
    }

    #[DataProvider('payloadCalls')]
    public function testOnlyPayloadConsumersAreInvalidated(string $expression): void
    {
        $this->request('required|string');
        $this->consumer($expression);
        $this->analyse(0);
        self::assertStringContainsString('0 files will be reanalysed.', $this->analyse(0));
        $this->request('required|array');
        $output = $this->analyse(1);
        self::assertStringContainsString('strlen expects string, array given.', $output);
        // The request itself and its payload consumer, but not Unrelated.php,
        // even though that file references the same request class.
        self::assertStringContainsString('2 files will be reanalysed.', $output);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);
        self::assertStringContainsString('strlen expects string, array given.', $this->analyse(1, true));
    }

    public function testEquivalentPayloadKeepsConsumerCached(): void
    {
        $this->request('required|string|max:10');
        $this->consumer('$request->validated()');
        $this->analyse(0);
        $this->request('required|string|max:20');
        $output = $this->analyse(0);
        self::assertStringContainsString('1 file will be reanalysed.', $output);
    }

    public function testChangedRequestDoesNotInvalidateAnotherPayloadConsumerWithTheSameInitialType(): void
    {
        $this->request('required|array');
        $this->write('src/OtherRequest.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
final class OtherRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => 'required|array'];
    }
}
PHP);
        $this->consumer('$request->validated()');
        $this->write('src/OtherConsumer.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
function consumeOther(OtherRequest $request): int
{
    return count($request->validated()['value']);
}
PHP);
        $this->analyse(1);

        $this->request('required|string');
        $output = $this->analyse(0);
        self::assertStringContainsString('2 files will be reanalysed.', $output);
        self::assertStringNotContainsString('strlen expects string, array given.', $output);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);
        self::assertStringNotContainsString(
            'strlen expects string, array given.',
            $this->analyse(0, true)
        );
    }

    public function testAllEquivalentPayloadKeysInvalidateOneConsumerOnce(): void
    {
        $this->request('required|string');
        $this->write('src/Consumer.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
function consume(CacheRequest $request): int
{
    $validated = $request->validated();
    $safe = $request->safe()->all();

    return strlen($validated['value']) + strlen($safe['value']);
}
PHP);
        $this->analyse(0);

        $this->request('required|array');
        $output = $this->analyse(1);
        self::assertStringContainsString('2 files will be reanalysed.', $output);
        self::assertSame(2, substr_count($output, 'strlen expects string, array given.'));
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);
    }

    public function testSafeOverrideInExternalChildDoesNotInvalidateValidatedConsumer(): void
    {
        $this->request('required|string');
        $this->write('src/ValidatedConsumer.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
function consumeValidated(CacheRequest $request): int
{
    return strlen($request->validated()['value']);
}
PHP);
        $this->write('src/SafeConsumer.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
function consumeSafe(CacheRequest $request): int
{
    return strlen($request->safe()->all()['value']);
}
PHP);
        $child = <<<'PHP'
<?php
namespace ValueCacheFixture;
final class ChildRequest extends CacheRequest
{
%s
}
PHP;
        $this->write('external/ChildRequest.php', sprintf($child, ''));
        $this->analyse(0);

        $this->write('external/ChildRequest.php', sprintf($child, <<<'PHP'
    public function safe(?array $keys = null)
    {
        return parent::safe($keys);
    }
PHP));
        $output = $this->analyse(1);
        self::assertStringContainsString('1 file will be reanalysed.', $output);
        self::assertStringContainsString('/SafeConsumer.php:', $output);
        self::assertStringNotContainsString('/ValidatedConsumer.php:', $output);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);

        $debugOutput = $this->analyse(1, true);
        self::assertStringContainsString('/SafeConsumer.php:', $debugOutput);
        self::assertStringNotContainsString('/ValidatedConsumer.php:', $debugOutput);

        $this->write('external/ChildRequest.php', sprintf($child, ''));
        $output = $this->analyse(0);
        self::assertStringContainsString('1 file will be reanalysed.', $output);
        self::assertStringNotContainsString('/SafeConsumer.php:', $output);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);
        self::assertStringNotContainsString('/SafeConsumer.php:', $this->analyse(0, true));
    }

    public function testDependencyEmittedWhileInferringPrivatePropertyIsRetained(): void
    {
        $this->request('required|string');
        $this->write('src/Consumer.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
final class Consumer
{
    private $payload;

    public function __construct(CacheRequest $request)
    {
        $this->payload = $request->safe()->all();
    }

    public function consume(): int
    {
        return strlen($this->payload['value']);
    }
}
PHP);
        $this->analyse(0);

        $this->request('required|array');
        $output = $this->analyse(1);
        self::assertStringContainsString('2 files will be reanalysed.', $output);
        self::assertStringContainsString('strlen expects string, array given.', $output);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);
        self::assertStringContainsString('strlen expects string, array given.', $this->analyse(1, true));
    }

    public function testPartiallyDiscoveredHierarchyRecoversWithoutInvalidatingStableConsumer(): void
    {
        $this->request('required|string');
        $this->consumer('$request->validated()');
        $this->write('src/StableRequest.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
final class StableRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => 'required|string'];
    }
}
PHP);
        $this->write('src/StableConsumer.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
function consumeStable(StableRequest $request): int
{
    return strlen($request->validated()['value']);
}
PHP);
        $child = <<<'PHP'
<?php
namespace ValueCacheFixture;
final class ChildRequest extends MissingMiddle
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => 'required|array'];
    }
}
PHP;
        $this->write('external/ChildRequest.php', $child);
        $this->analyse(0);

        $this->write('external/ChildRequest.php', str_replace(
            'final class ChildRequest',
            "class MissingMiddle extends CacheRequest {}\nfinal class ChildRequest",
            $child
        ));
        $output = $this->analyse(1);
        self::assertStringContainsString('1 file will be reanalysed.', $output);
        self::assertStringContainsString('strlen expects string, array|string given.', $output);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);
        $debugOutput = $this->analyse(1, true);
        self::assertStringContainsString('strlen expects string, array|string given.', $debugOutput);
    }

    public function testAddingAndRemovingChildUpdatesParentConsumer(): void
    {
        $this->request('required|string');
        $this->consumer('$request->validated()');
        $this->analyse(0);
        $this->write('src/ChildRequest.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
final class ChildRequest extends CacheRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => 'required|array'];
    }
}
PHP);
        $output = $this->analyse(1);
        self::assertStringContainsString('strlen expects string, array|string given.', $output);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);
        self::assertTrue(unlink($this->directory . '/src/ChildRequest.php'));
        $output = $this->analyse(0);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);
    }

    #[DataProvider('payloadCalls')]
    public function testUnresolvedPayloadRecoversWhenHookBecomesEmpty(string $expression): void
    {
        $this->request('required|string', 'public function withValidator(\\Illuminate\\Validation\\Validator $validator): void { $validator->setRules([]); }');
        $this->consumer($expression);
        $this->analyse(1);
        $this->request('required|string', 'public function withValidator(\\Illuminate\\Validation\\Validator $validator): void {}');
        $output = $this->analyse(0);
        self::assertStringContainsString('2 files will be reanalysed.', $output);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);
    }

    public function testExternalRulesAvoidGlobalInvalidation(): void
    {
        $this->request('required|string');
        $this->write('src/CacheRequest.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
class CacheRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return RuleProvider::RULES;
    }
}
PHP);
        $rules = <<<'PHP'
<?php
namespace ValueCacheFixture;
final class RuleProvider
{
    /** @var array<string, string> */
    public const RULES = ['value' => '%s'];
}
PHP;
        $this->write('external/RuleProvider.php', sprintf($rules, 'required|string'));
        $this->consumer('$request->validated()');
        $this->analyse(0);
        $this->write('external/RuleProvider.php', sprintf($rules, 'required|array'));
        $output = $this->analyse(1);
        self::assertStringContainsString('strlen expects string, array given.', $output);
        // PHPStan also rechecks the request's ordinary constant dependency.
        self::assertStringContainsString('2 files will be reanalysed.', $output);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);
    }

    public function testInferenceConfigurationChangeRefreshesPersistedPayload(): void
    {
        $this->write('src/CacheRequest.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
final class CacheRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return [
            'payload' => 'required|array',
            'payload.name' => 'required|string',
        ];
    }
}
PHP);
        $this->write('src/Consumer.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
function consume(CacheRequest $request): int
{
    return strlen($request->validated()['payload']['name']);
}
PHP);
        $this->analyse(0);

        $config = file_get_contents($this->directory . '/phpstan.neon');
        self::assertIsString($config);
        $this->write('phpstan.neon', str_replace(
            'includeUnvalidatedArrayKeys: false',
            'includeUnvalidatedArrayKeys: true',
            $config
        ));
        $included = $this->analyse(1);
        self::assertStringContainsString('strlen expects string, mixed given.', $included);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $included);
        self::assertStringContainsString('strlen expects string, mixed given.', $this->analyse(1, true));

        $config = file_get_contents($this->directory . '/phpstan.neon');
        self::assertIsString($config);
        $this->write('phpstan.neon', str_replace(
            'includeUnvalidatedArrayKeys: true',
            'includeUnvalidatedArrayKeys: false',
            $config
        ));
        $excludedAgain = $this->analyse(0);
        self::assertStringNotContainsString('strlen expects string, mixed given.', $excludedAgain);
        self::assertStringNotContainsString('strlen expects string, mixed given.', $this->analyse(0, true));
    }

    public function testExternalDependencyRecoversAfterTemporarilyBecomingUnresolved(): void
    {
        $this->write('src/CacheRequest.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
final class CacheRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return RuleProvider::RULES;
    }
}
PHP);
        $rules = <<<'PHP'
<?php
namespace ValueCacheFixture;
final class RuleProvider
{
    /** @var array<string, string> */
    public const RULES = ['value' => '%s'];
}
PHP;
        $this->write('external/RuleProvider.php', sprintf($rules, 'required|string'));
        $this->consumer('$request->validated()');
        $this->analyse(0);

        $this->write('external/RuleProvider.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
PHP);
        $unresolved = $this->analyse(1);
        self::assertStringContainsString('unknown class ValueCacheFixture\RuleProvider', $unresolved);

        $this->write('external/RuleProvider.php', sprintf($rules, 'required|array'));
        $resolved = $this->analyse(1);
        self::assertStringContainsString('strlen expects string, array given.', $resolved);
        self::assertStringNotContainsString('unknown class ValueCacheFixture\RuleProvider', $resolved);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $resolved);
        $debugOutput = $this->analyse(1, true);
        self::assertStringContainsString('strlen expects string, array given.', $debugOutput);
        self::assertStringNotContainsString('unknown class ValueCacheFixture\RuleProvider', $debugOutput);
    }

    public function testExternalReceiverRecoversAfterDisappearingFromDiscovery(): void
    {
        $this->request('required|string');
        $request = <<<'PHP'
<?php
namespace ValueCacheFixture;
final class ExternalRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => '%s'];
    }
}
PHP;
        $this->write('external/ExternalRequest.php', sprintf($request, 'required|string'));
        $this->write('src/Consumer.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
function consumeExternal(ExternalRequest $request): int
{
    return strlen($request->validated()['value']);
}
PHP);
        $this->analyse(0);

        self::assertTrue(unlink($this->directory . '/external/ExternalRequest.php'));
        $missing = $this->analyse(1);
        self::assertStringContainsString('unknown class ValueCacheFixture\ExternalRequest', $missing);

        $this->write('external/ExternalRequest.php', sprintf($request, 'required|array'));
        $resolved = $this->analyse(1);
        self::assertStringContainsString('strlen expects string, array given.', $resolved);
        self::assertStringNotContainsString('unknown class ValueCacheFixture\ExternalRequest', $resolved);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $resolved);
        $debugOutput = $this->analyse(1, true);
        self::assertStringContainsString('strlen expects string, array given.', $debugOutput);
        self::assertStringNotContainsString('unknown class ValueCacheFixture\ExternalRequest', $debugOutput);
    }

    public function testConsumerInsideTraitIsInvalidated(): void
    {
        $this->request('required|string');
        $this->write('src/ConsumerTrait.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
trait ConsumerTrait
{
    public function consume(CacheRequest $request): int
    {
        return strlen($request->safe()->all()['value']);
    }
}
PHP);
        $this->write('src/Consumer.php', <<<'PHP'
<?php
namespace ValueCacheFixture;
final class Consumer
{
    use ConsumerTrait;
}
PHP);
        $this->analyse(0);
        $this->request('required|array');
        $output = $this->analyse(1);
        self::assertStringContainsString('strlen expects string, array given.', $output);
        self::assertStringNotContainsString('metadata do not match: metaExtensions', $output);
        self::assertStringContainsString('strlen expects string, array given.', $this->analyse(1, true));
    }

    private function request(string $rule, string $hook = ''): void
    {
        $this->write('src/CacheRequest.php', sprintf(<<<'PHP'
<?php
namespace ValueCacheFixture;
class CacheRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => '%s'];
    }
    %s
}
PHP, $rule, $hook));
    }

    private function consumer(string $expression): void
    {
        $this->write('src/Consumer.php', sprintf(<<<'PHP'
<?php
namespace ValueCacheFixture;
function consume(CacheRequest $request): int
{
    return strlen((%s)['value']);
}
PHP, $expression));
    }

    private function write(string $path, string $contents): void
    {
        self::assertNotFalse(file_put_contents($this->directory . '/' . $path, $contents));
    }

    private function analyse(int $exitCode, bool $debug = false): string
    {
        $command = [PHP_BINARY, dirname(__DIR__) . '/vendor/bin/phpstan', 'analyse',
            '-c', $this->directory . '/phpstan.neon', '--no-progress', '--error-format=raw', '-vv'];
        if ($debug) {
            $command[] = '--debug';
        }
        $process = new Process($command, $this->directory);
        $process->setTimeout(60.0);
        $process->run();
        $output = $process->getOutput() . $process->getErrorOutput();
        self::assertSame($exitCode, $process->getExitCode(), $output);

        return $output;
    }
}
