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

namespace jbboehr\PhpstanLaravelValidation\Validation;

use Composer\InstalledVersions;
use Illuminate\Foundation\Http\FormRequest;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\ResultCache\ResultCacheMetaExtension;
use PHPStan\Parser\Parser;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

final class FormRequestTypeRegistry implements ResultCacheMetaExtension
{
    /** @var array<string, Type|null> */
    private array $types = [];

    /** @var array<string, string> */
    private array $descriptors = [];

    private bool $initialized = false;

    /**
     * @param list<string> $trustedClasses
     * @param list<string> $analysedPaths
     * @param list<string> $analysedPathsFromConfig
     * @param list<string> $composerAutoloaderProjectPaths
     * @param list<string> $scanFiles
     * @param list<string> $scanDirectories
     */
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private Parser $parser,
        private FormRequestRuleTypeResolver $ruleTypeResolver,
        private string $workingDirectory,
        private bool $enabled,
        private array $trustedClasses,
        private array $analysedPaths,
        private array $analysedPathsFromConfig,
        private array $composerAutoloaderProjectPaths,
        private array $scanFiles,
        private array $scanDirectories
    ) {
        $this->trustedClasses = array_values(array_unique(array_map(
            static fn (string $className): string => ltrim($className, '\\'),
            $this->trustedClasses
        )));
    }

    public function getType(ClassReflection $classReflection): ?Type
    {
        $this->initialize();

        return $this->types[$classReflection->getName()] ?? null;
    }

    public function getKey(): string
    {
        return 'phpstan-laravel-validation.form-requests';
    }

    public function getHash(): string
    {
        $this->initialize();

        return hash('sha256', serialize($this->descriptors));
    }

    private function initialize(): void
    {
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;
        if (!$this->enabled || !$this->reflectionProvider->hasClass(FormRequest::class)) {
            return;
        }

        $classNames = array_fill_keys($this->trustedClasses, true);
        foreach ($this->discoverSourceFiles() as $fileName) {
            foreach ($this->discoverClassNames($fileName) as $className) {
                $classNames[$className] = true;
            }
        }

        ksort($classNames);
        $baseClass = $this->reflectionProvider->getClass(FormRequest::class);

        foreach (array_keys($classNames) as $className) {
            try {
                if (!$this->reflectionProvider->hasClass($className)) {
                    continue;
                }

                $classReflection = $this->reflectionProvider->getClass($className);
                if ($classReflection->isAbstract() || !$classReflection->isSubclassOf(FormRequest::class)) {
                    continue;
                }

                $trusted = in_array($className, $this->trustedClasses, true);
                $eligibility = $this->determineEligibility($classReflection, $baseClass, $trusted);
                if ($eligibility !== 'eligible') {
                    $this->types[$className] = null;
                    $this->descriptors[$className] = $eligibility;
                    continue;
                }

                $type = $this->ruleTypeResolver->resolve($classReflection);
                $this->types[$className] = $type;
                $this->descriptors[$className] = $type === null
                    ? 'unresolved'
                    : 'inferred:' . $type->describe(VerbosityLevel::precise());
            } catch (InvalidCustomRuleContractException $e) {
                throw $e;
            } catch (\Throwable) {
                $this->types[$className] = null;
                $this->descriptors[$className] = 'unresolved';
            }
        }

        ksort($this->types);
        ksort($this->descriptors);
    }

    private function determineEligibility(
        ClassReflection $classReflection,
        ClassReflection $baseClass,
        bool $trusted
    ): string {
        if (!$this->hasSameNativeMethod($classReflection, $baseClass, 'validated')) {
            return 'overridden-validated';
        }

        if ($trusted) {
            return 'eligible';
        }

        foreach (['getValidatorInstance', 'createDefaultValidator', 'validationRules', 'passedValidation'] as $methodName) {
            if ($baseClass->hasNativeMethod($methodName)
                && !$this->hasSameNativeMethod($classReflection, $baseClass, $methodName)
            ) {
                return 'unsafe-' . $methodName;
            }
        }

        foreach (['validator', 'withValidator', 'after'] as $methodName) {
            if ($classReflection->hasNativeMethod($methodName)) {
                return 'unsafe-' . $methodName;
            }
        }

        return 'eligible';
    }

    private function hasSameNativeMethod(
        ClassReflection $classReflection,
        ClassReflection $baseClass,
        string $methodName
    ): bool {
        $nativeClass = $classReflection->getNativeReflection();
        $nativeBase = $baseClass->getNativeReflection();
        if (!$nativeClass->hasMethod($methodName) || !$nativeBase->hasMethod($methodName)) {
            return false;
        }

        $method = $nativeClass->getMethod($methodName);
        $baseMethod = $nativeBase->getMethod($methodName);

        return $method->getFileName() === $baseMethod->getFileName()
            && $method->getStartLine() === $baseMethod->getStartLine()
            && $method->getEndLine() === $baseMethod->getEndLine();
    }

    /** @return list<string> */
    private function discoverSourceFiles(): array
    {
        $paths = array_merge(
            $this->analysedPaths,
            $this->analysedPathsFromConfig,
            $this->scanFiles,
            $this->scanDirectories,
            $this->discoverComposerSourcePaths()
        );

        $files = [];
        foreach (array_unique($paths) as $path) {
            $path = $this->absolutizePath($path);
            if (is_file($path)) {
                if (str_ends_with(strtolower($path), '.php')) {
                    $files[$path] = true;
                }
                continue;
            }
            if (!is_dir($path)) {
                continue;
            }

            try {
                $directory = new \RecursiveDirectoryIterator(
                    $path,
                    \FilesystemIterator::SKIP_DOTS
                );
                $filter = new \RecursiveCallbackFilterIterator(
                    $directory,
                    static function (\SplFileInfo $file): bool {
                        if (!$file->isDir()) {
                            return true;
                        }

                        return !in_array($file->getFilename(), [
                            '.git',
                            '.phpunit.cache',
                            'node_modules',
                            'vendor',
                        ], true);
                    }
                );

                foreach (new \RecursiveIteratorIterator($filter) as $file) {
                    if (!$file instanceof \SplFileInfo || !$file->isFile()) {
                        continue;
                    }
                    $fileName = $file->getPathname();
                    if (!str_ends_with(strtolower($fileName), '.php')) {
                        continue;
                    }

                    $files[$fileName] = true;
                }
            } catch (\UnexpectedValueException) {
                continue;
            }
        }

        $fileNames = array_keys($files);
        sort($fileNames);

        return $fileNames;
    }

    /** @return list<string> */
    private function discoverComposerSourcePaths(): array
    {
        $paths = [];
        $projectPaths = $this->composerAutoloaderProjectPaths;
        if ($projectPaths === []) {
            foreach (InstalledVersions::getAllRawData() as $installed) {
                $installPath = self::findRootInstallPath($installed);
                if ($installPath !== null && !str_starts_with($installPath, 'phar://')) {
                    $projectPaths[] = $installPath;
                }
            }
        }

        foreach (array_unique($projectPaths) as $projectPath) {
            $projectPath = $this->absolutizePath($projectPath);
            $composerJson = @file_get_contents($projectPath . DIRECTORY_SEPARATOR . 'composer.json');
            if (!is_string($composerJson)) {
                continue;
            }

            try {
                $composer = json_decode($composerJson, true, flags: JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                continue;
            }
            if (!is_array($composer)) {
                continue;
            }

            foreach (['autoload', 'autoload-dev'] as $sectionName) {
                $section = $composer[$sectionName] ?? null;
                if (!is_array($section)) {
                    continue;
                }

                foreach (['psr-0', 'psr-4', 'classmap'] as $autoloadType) {
                    $mappings = $section[$autoloadType] ?? null;
                    if (!is_array($mappings)) {
                        continue;
                    }

                    foreach ($mappings as $mapping) {
                        foreach (is_array($mapping) ? $mapping : [$mapping] as $relativePath) {
                            if (!is_string($relativePath)) {
                                continue;
                            }
                            $paths[] = $this->isAbsolutePath($relativePath)
                                ? $relativePath
                                : $projectPath . DIRECTORY_SEPARATOR . $relativePath;
                        }
                    }
                }
            }
        }

        return $paths;
    }

    private static function findRootInstallPath(mixed $installed): ?string
    {
        if (!is_array($installed)) {
            return null;
        }

        $root = $installed['root'] ?? null;
        if (!is_array($root)) {
            return null;
        }

        $installPath = $root['install_path'] ?? null;
        return is_string($installPath) ? $installPath : null;
    }

    /** @return list<string> */
    private function discoverClassNames(string $fileName): array
    {
        try {
            $nodes = $this->parser->parseFile($fileName);
        } catch (\Throwable) {
            return [];
        }

        $classNames = [];
        $this->collectClassNames($nodes, '', $classNames);

        return array_values(array_unique($classNames));
    }

    /**
     * @param array<Node> $nodes
     * @param list<string> $classNames
     */
    private function collectClassNames(array $nodes, string $namespace, array &$classNames): void
    {
        $nodeFinder = new NodeFinder();
        foreach ($nodes as $node) {
            if ($node instanceof Namespace_) {
                $this->collectClassNames(
                    $node->stmts,
                    $node->name?->toString() ?? '',
                    $classNames
                );
                continue;
            }

            foreach ($nodeFinder->findInstanceOf([$node], Class_::class) as $classNode) {
                if ($classNode->name === null) {
                    continue;
                }

                $classNames[] = ltrim(
                    $namespace . '\\' . $classNode->name->toString(),
                    '\\'
                );
            }
        }
    }

    private function absolutizePath(string $path): string
    {
        if ($this->isAbsolutePath($path)) {
            return $path;
        }

        return rtrim($this->workingDirectory, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . $path;
    }

    private function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, '/')
            || preg_match('~^[A-Za-z]:[\\\\/]~D', $path) === 1;
    }
}
