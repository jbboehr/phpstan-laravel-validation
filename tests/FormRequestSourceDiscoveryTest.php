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

use Illuminate\Foundation\Http\FormRequest;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestRuleTypeResolver;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestTypeRegistry;
use PHPStan\File\FileHelper;
use PHPStan\Parser\Parser;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\VerbosityLevel;
use PHPUnit\Framework\Attributes\DataProvider;

final class FormRequestSourceDiscoveryTest extends \PHPStan\Testing\TypeInferenceTestCase
{
    private string $directory;

    protected function setUp(): void
    {
        parent::setUp();
        $directory = tempnam(sys_get_temp_dir(), 'form-request-discovery-');
        self::assertIsString($directory);
        self::assertTrue(unlink($directory));
        self::assertTrue(mkdir($directory . '/source', 0777, true));
        $this->directory = $directory;
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
            if ($file->isDir() && !$file->isLink()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($this->directory);
        parent::tearDown();
    }

    /** @return array<string, array{string}> */
    public static function discoveryModes(): array
    {
        return [
            'analysed directory' => ['analysed'],
            'scan directory' => ['scan'],
            'Composer mapping' => ['composer'],
        ];
    }

    #[DataProvider('discoveryModes')]
    public function testEachResolvedDirectoryIsVisitedOnce(string $mode): void
    {
        $file = $this->directory . '/source/Request.php';
        self::assertNotFalse(file_put_contents($file, "<?php\n"));
        // A single self-link keeps the regression bounded even without the guard.
        self::assertTrue(symlink('.', $this->directory . '/source/loop'));
        $files = [];
        $parser = $this->createMock(Parser::class);
        $parser->method('parseFile')->willReturnCallback(static function (string $path) use (&$files): array {
            $files[] = $path;
            return [];
        });

        $this->registry($mode, $parser)->getHash();

        self::assertSame([$file], $files);
    }

    #[DataProvider('discoveryModes')]
    public function testLinkedDirectoriesPreserveFiltersAndLexicalPaths(string $mode): void
    {
        self::assertTrue(mkdir($this->directory . '/external/.git', 0777, true));
        foreach (['Included.inc', 'Included.php', 'Upper.PHP', '.git/Ignored.inc', '.git/Ignored.php'] as $file) {
            self::assertNotFalse(file_put_contents($this->directory . '/external/' . $file, "<?php\n"));
        }
        // An excluded alias must not claim the identity before the included alias.
        self::assertTrue(symlink('../external', $this->directory . '/source/vendor'));
        self::assertTrue(symlink('../external', $this->directory . '/source/linked'));
        $files = [];
        $parser = $this->createMock(Parser::class);
        $parser->method('parseFile')->willReturnCallback(static function (string $path) use (&$files): array {
            $files[] = $path;
            return [];
        });

        $this->registry($mode, $parser, ['inc'])->getHash();

        self::assertSame($mode === 'composer'
            ? [$this->directory . '/source/linked/Included.php', $this->directory . '/source/linked/Upper.PHP']
            : [$this->directory . '/source/linked/Included.inc'], $files);
    }

    public function testParseFailureDoesNotMakeManifestDependOnCallOrder(): void
    {
        self::assertNotFalse(file_put_contents($this->directory . '/source/Broken.php', '<?php class'));
        $parser = self::getContainer()->getService('currentPhpVersionSimpleDirectParser');
        self::assertInstanceOf(Parser::class, $parser);
        $hashFirst = $this->registry('scan', $parser);
        $hash = $hashFirst->getHash();

        // Initializing the receiver first must preserve the same manifest key,
        // even though parsing has now marked class discovery incomplete.
        $typeFirst = $this->registry('scan', $parser);
        $typeFirst->getType(self::getContainer()->getByType(ReflectionProvider::class)->getClass(FormRequest::class));
        self::assertSame($hash, $typeFirst->getHash());

        $cachedParser = $this->createMock(Parser::class);
        $cachedParser->expects(self::never())->method('parseFile');
        self::assertSame($hash, $this->registry('scan', $cachedParser)->getHash());
    }

    public function testCacheOnlyTraversalFailurePreservesReceiverAndRefreshesManifest(): void
    {
        $brokenLink = $this->directory . '/cache-only-broken-link';
        self::assertFalse(is_link($brokenLink));
        self::assertTrue(symlink('missing-fingerprint-target', $brokenLink));

        try {
            $parser = self::getContainer()->getService('currentPhpVersionSimpleDirectParser');
            self::assertInstanceOf(Parser::class, $parser);
            $sourceFile = $this->directory . '/source/Observed.php';
            $namespace = 'CacheOnlyTraversalFixture' . bin2hex(random_bytes(6));
            self::assertNotFalse(file_put_contents($sourceFile, sprintf(
                <<<'PHP'
<?php

namespace %s;

class ParentRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => 'required|string'];
    }
}

final class ChildRequest extends ParentRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => 'required|array'];
    }
}
PHP,
                $namespace
            )));
            require $sourceFile;
            $parentClass = $namespace . '\\ParentRequest';
            $childClass = $namespace . '\\ChildRequest';
            $reflection = self::getContainer()->getByType(ReflectionProvider::class)
                ->getClass($parentClass);
            $classes = [$parentClass, $childClass];
            $expectedType = 'array{value: array}|array{value: string}';

            $typeFirst = $this->registry('scan', $parser, ['php'], $classes);
            self::assertSame(
                $expectedType,
                $typeFirst->getType($reflection)?->describe(VerbosityLevel::precise())
            );
            $expectedHash = $typeFirst->getHash();

            $hashFirst = $this->registry('scan', $parser, ['php'], $classes);
            self::assertSame($expectedHash, $hashFirst->getHash());
            self::assertSame(
                $expectedType,
                $hashFirst->getType($reflection)?->describe(VerbosityLevel::precise())
            );
            self::assertSame($expectedHash, $hashFirst->getHash());

            self::assertTrue(unlink($brokenLink));
            $reparsedFiles = [];
            $recoveredParser = $this->createMock(Parser::class);
            $recoveredParser->method('parseFile')->willReturnCallback(
                static function (string $path) use (&$reparsedFiles, $parser): array {
                    $reparsedFiles[] = $path;
                    return $parser->parseFile($path);
                }
            );
            $recovered = $this->registry('scan', $recoveredParser, ['php'], $classes);
            self::assertSame($expectedHash, $recovered->getHash());
            self::assertContains($sourceFile, $reparsedFiles);

            $cachedParser = $this->createMock(Parser::class);
            $cachedParser->expects(self::never())->method('parseFile');
            self::assertSame(
                $expectedHash,
                $this->registry('scan', $cachedParser, ['php'], $classes)->getHash()
            );
        } finally {
            if (is_link($brokenLink)) {
                self::assertTrue(unlink($brokenLink));
            }
        }
    }

    /**
     * @param non-empty-list<string> $extensions
     * @param list<string> $additionalClasses
     */
    private function registry(
        string $mode,
        Parser $parser,
        array $extensions = ['php'],
        array $additionalClasses = []
    ): FormRequestTypeRegistry {
        self::assertNotFalse(file_put_contents($this->directory . '/composer.json', $mode === 'composer'
            ? '{"autoload": {"classmap": ["source/"]}}'
            : '{}'));
        $container = self::getContainer();

        return new FormRequestTypeRegistry(
            reflectionProvider: $container->getByType(ReflectionProvider::class),
            parser: $parser,
            fileHelper: $container->getByType(FileHelper::class),
            ruleTypeResolver: $container->getByType(FormRequestRuleTypeResolver::class),
            workingDirectory: $this->directory,
            tmpDirectory: $this->directory . '/cache',
            enabled: true,
            additionalClasses: $additionalClasses,
            trustedClasses: [],
            analysedPaths: $mode === 'analysed' ? [$this->directory . '/source'] : [],
            analysedPathsFromConfig: [],
            composerAutoloaderProjectPaths: [$this->directory],
            scanFiles: [],
            scanDirectories: $mode === 'scan' ? [$this->directory . '/source'] : [],
            fileExtensions: $extensions
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../extension.neon', __DIR__ . '/form-request/phpstan.neon'];
    }
}
