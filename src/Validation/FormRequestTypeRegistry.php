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
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Nop;
use PhpParser\NodeFinder;
use PHPStan\Analyser\CollectedDataEmitter;
use PHPStan\Analyser\ResultCache\ResultCacheDependencyExtension;
use PHPStan\Collectors\ResultCacheDependencyCollector;
use PHPStan\Parser\Parser;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

final class FormRequestTypeRegistry implements ResultCacheDependencyExtension
{
    private const DESCRIPTOR_SCHEMA = 1;

    private const PRUNED_SOURCE_DIRECTORIES = [
        '.git',
        '.phpunit.cache',
        'node_modules',
        'vendor',
    ];

    /** @var array<string, Type|null> */
    private array $types = [];

    /** @var array<string, string> */
    private array $descriptors = [];

    /** @var list<string>|null */
    private ?array $sourcePaths = null;

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
            static fn (string $className): string => strtolower(ltrim($className, '\\')),
            $this->trustedClasses
        )));
    }

    public function getType(ClassReflection $classReflection): ?Type
    {
        $className = $classReflection->getName();
        $this->resolveClass($classReflection);

        return $this->types[$className];
    }

    public function getKey(): string
    {
        return 'phpstan-laravel-validation.form-requests';
    }

    public function getHash(string $dependencyKey): string
    {
        $className = ltrim($dependencyKey, '\\');
        if (!array_key_exists($className, $this->descriptors)) {
            $className = $this->resolveClassName($className);
        }

        return hash('sha256', serialize([
            self::DESCRIPTOR_SCHEMA,
            $className,
            $this->descriptors[$className],
        ]));
    }

    public function recordDependency(
        string $className,
        CollectedDataEmitter $collectedDataEmitter
    ): void {
        $collectedDataEmitter->emitCollectedData(
            ResultCacheDependencyCollector::class,
            ResultCacheDependencyCollector::createData($this, $className)
        );
    }

    private function resolveClassName(string $className): string
    {
        if (!$this->enabled) {
            $this->store($className, null, 'disabled');

            return $className;
        }

        try {
            if (!$this->reflectionProvider->hasClass($className)) {
                $this->store($className, null, 'missing-or-obsolete');

                return $className;
            }

            $classReflection = $this->reflectionProvider->getClass($className);
            if ($classReflection->getName() !== $className) {
                $this->store($className, null, 'missing-or-obsolete');

                return $className;
            }

            $this->resolveClass($classReflection);

            return $className;
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable) {
            $this->store($className, null, 'unresolved');

            return $className;
        }
    }

    private function resolveClass(ClassReflection $classReflection): void
    {
        $className = $classReflection->getName();
        if (array_key_exists($className, $this->types)) {
            return;
        }

        if (!$this->enabled || !$this->reflectionProvider->hasClass(FormRequest::class)) {
            $this->store($className, null, 'disabled');

            return;
        }

        if ($classReflection->isAbstract() || !$classReflection->isSubclassOf(FormRequest::class)) {
            $this->store($className, null, 'not-concrete-form-request');

            return;
        }

        $trusted = in_array(strtolower($className), $this->trustedClasses, true);
        if (!$trusted && !$this->isProjectClass($classReflection)) {
            $this->store($className, null, 'not-discovered');

            return;
        }

        try {
            $baseClass = $this->reflectionProvider->getClass(FormRequest::class);
            $eligibility = $this->determineEligibility($classReflection, $baseClass, $trusted);
            if ($eligibility !== 'eligible') {
                $this->store($className, null, $eligibility);

                return;
            }

            $type = $this->ruleTypeResolver->resolve($classReflection);
            $this->store(
                $className,
                $type,
                $type === null
                    ? 'unresolved'
                    : 'inferred:' . $type->describe(VerbosityLevel::precise())
            );
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable) {
            $this->store($className, null, 'unresolved');
        }
    }

    private function store(string $className, ?Type $type, string $descriptor): void
    {
        $this->types[$className] = $type;
        $this->descriptors[$className] = $descriptor;
    }

    private function isProjectClass(ClassReflection $classReflection): bool
    {
        $fileName = $classReflection->getFileName();
        if ($fileName === null) {
            return false;
        }

        $fileName = $this->normalizePath($fileName);
        foreach ($this->sourcePaths() as $path) {
            if (is_file($path) && $fileName === $path) {
                return true;
            }
            if (is_dir($path)
                && str_starts_with($fileName, rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR)
            ) {
                if ($this->isBelowPrunedSourceDirectory($fileName, $path)) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    private function isBelowPrunedSourceDirectory(string $fileName, string $sourceDirectory): bool
    {
        $prefix = rtrim($sourceDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $relativeSegments = explode(DIRECTORY_SEPARATOR, substr($fileName, strlen($prefix)));
        array_pop($relativeSegments);

        foreach ($relativeSegments as $segment) {
            if (in_array($segment, self::PRUNED_SOURCE_DIRECTORIES, true)) {
                return true;
            }
        }

        return false;
    }

    /** @return list<string> */
    private function sourcePaths(): array
    {
        if ($this->sourcePaths !== null) {
            return $this->sourcePaths;
        }

        $paths = array_merge(
            $this->analysedPaths,
            $this->analysedPathsFromConfig,
            $this->scanFiles,
            $this->scanDirectories,
            $this->discoverComposerSourcePaths()
        );

        $normalized = [];
        foreach ($paths as $path) {
            $normalized[$this->normalizePath($this->absolutizePath($path))] = true;
        }

        return $this->sourcePaths = array_keys($normalized);
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

        foreach (['validator', 'after'] as $methodName) {
            if ($classReflection->hasNativeMethod($methodName)) {
                return 'unsafe-' . $methodName;
            }
        }

        if ($classReflection->hasNativeMethod('withValidator')
            && !$this->hasProvablyEmptyWithValidator($classReflection)
        ) {
            return 'unsafe-withValidator';
        }

        return 'eligible';
    }

    private function hasProvablyEmptyWithValidator(ClassReflection $classReflection): bool
    {
        $nativeClass = $classReflection->getNativeReflection();
        if (!$nativeClass->hasMethod('withValidator')) {
            return false;
        }

        $method = $nativeClass->getMethod('withValidator');
        $fileName = $method->getFileName();
        if (!is_string($fileName)) {
            return false;
        }

        try {
            $nodes = $this->parser->parseFile($fileName);
        } catch (\Throwable) {
            return false;
        }

        $nodeFinder = new NodeFinder();
        foreach ($nodeFinder->findInstanceOf($nodes, ClassMethod::class) as $methodNode) {
            if ($methodNode->name->toLowerString() !== 'withvalidator'
                || $methodNode->getStartLine() > $method->getStartLine()
                || $methodNode->getEndLine() < $method->getEndLine()
                || $methodNode->stmts === null
            ) {
                continue;
            }

            foreach ($methodNode->stmts as $statement) {
                if (!$statement instanceof Nop) {
                    return false;
                }
            }

            return true;
        }

        return false;
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

    private function normalizePath(string $path): string
    {
        $realPath = realpath($path);

        return is_string($realPath) ? $realPath : $path;
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
