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

use Composer\Autoload\ClassLoader;
use Composer\InstalledVersions;
use Illuminate\Foundation\Http\FormRequest;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\NodeFinder;
use PHPStan\Analyser\ResultCache\ResultCacheMetaExtension;
use PHPStan\File\FileHelper;
use PHPStan\Parser\Parser;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

final class FormRequestTypeRegistry implements ResultCacheMetaExtension
{
    private const MANIFEST_SCHEMA = 3;

    /** @var array<string, true> */
    private const PHPSTAN_IGNORED_NON_DOT_DIRECTORIES = [
        'CVS' => true,
        '_darcs' => true,
        '_svn' => true,
    ];

    /** @var array<string, Type|null> */
    private array $types = [];

    /** @var array<string, string> */
    private array $globalCacheDescriptors = [];

    /** @var array<string, true> */
    private array $globalCacheDependencyFiles = [];

    private bool $initialized = false;

    /** @var list<string>|null */
    private ?array $sourceFiles = null;

    /** @var list<string>|null */
    private ?array $fingerprintSourceFiles = null;

    /** @var array<string, true>|null */
    private ?array $analysedSourceFiles = null;

    /** @var array<string, string|null> */
    private array $fileDigests = [];

    private ?string $extensionContractHash = null;

    /**
     * @param list<string> $additionalClasses
     * @param list<string> $trustedClasses
     * @param list<string> $analysedPaths
     * @param list<string> $analysedPathsFromConfig
     * @param list<string> $composerAutoloaderProjectPaths
     * @param list<string> $scanFiles
     * @param list<string> $scanDirectories
     * @param array{analyse?: list<string>, analyseAndScan?: list<string>} $excludePaths
     * @param non-empty-list<string> $fileExtensions
     */
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private Parser $parser,
        private FileHelper $fileHelper,
        private FormRequestRuleTypeResolver $ruleTypeResolver,
        private string $workingDirectory,
        private string $tmpDirectory,
        private bool $enabled,
        private array $additionalClasses,
        private array $trustedClasses,
        private array $analysedPaths,
        private array $analysedPathsFromConfig,
        private array $composerAutoloaderProjectPaths,
        private array $scanFiles,
        private array $scanDirectories,
        private array $excludePaths = [],
        private array $fileExtensions = ['php']
    ) {
        $this->additionalClasses = self::normalizeClassNames($this->additionalClasses);
        $this->trustedClasses = self::normalizeClassNames($this->trustedClasses);
        $this->fileExtensions = array_values(array_unique($this->fileExtensions));
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
        if (!$this->enabled || !$this->reflectionProvider->hasClass(FormRequest::class)) {
            return hash('sha256', 'inactive:' . self::MANIFEST_SCHEMA);
        }

        $sourceFingerprint = $this->sourceFingerprint();
        if (!$this->initialized) {
            $cachedHash = $this->readManifest($sourceFingerprint);
            if ($cachedHash !== null) {
                return $cachedHash;
            }
        }

        $this->initialize();
        $descriptorHash = $this->descriptorHash();
        $this->writeManifest($sourceFingerprint, $descriptorHash);

        return $descriptorHash;
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

        $classNames = array_fill_keys(array_merge(
            $this->additionalClasses,
            $this->trustedClasses
        ), true);
        foreach ($this->sourceFiles() as $fileName) {
            foreach ($this->discoverClassNames($fileName) as $className) {
                $classNames[$className] = true;
            }
        }

        ksort($classNames);
        $baseClass = $this->reflectionProvider->getClass(FormRequest::class);

        foreach (array_keys($classNames) as $className) {
            $classReflection = null;
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
                    if (!$this->canUseExportedFingerprint($classReflection)) {
                        $this->globalCacheDescriptors[$className] = $eligibility;
                        $this->recordGlobalCacheDependencies($classReflection);
                    }
                    continue;
                }

                $type = $this->ruleTypeResolver->resolve($classReflection);
                $this->types[$className] = $type;
                $descriptor = $type === null
                    ? 'unresolved'
                    : 'inferred:' . $type->describe(VerbosityLevel::precise());
                if (!$this->canUseExportedFingerprint($classReflection)) {
                    $this->globalCacheDescriptors[$className] = $descriptor;
                    $this->recordGlobalCacheDependencies($classReflection);
                }
            } catch (InvalidCustomRuleContractException $e) {
                throw $e;
            } catch (\Throwable) {
                $this->types[$className] = null;
                $this->globalCacheDescriptors[$className] = 'unresolved';
                if ($classReflection !== null) {
                    $this->recordGlobalCacheDependencies($classReflection);
                }
            }
        }

        ksort($this->types);
        ksort($this->globalCacheDescriptors);
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
        $method = $this->findNativeMethodNode($classReflection, 'withValidator');
        if ($method === null || $method->stmts === null) {
            return false;
        }

        foreach ($method->stmts as $statement) {
            if (!$statement instanceof Nop) {
                return false;
            }
        }

        return true;
    }

    private function findNativeMethodNode(
        ClassReflection $classReflection,
        string $methodName
    ): ?ClassMethod {
        $nativeClass = $classReflection->getNativeReflection();
        if (!$nativeClass->hasMethod($methodName)) {
            return null;
        }

        $method = $nativeClass->getMethod($methodName);
        $fileName = $method->getFileName();
        if (!is_string($fileName)) {
            return null;
        }

        try {
            $nodes = $this->parser->parseFile($fileName);
        } catch (\Throwable) {
            return null;
        }

        $methodNode = (new NodeFinder())->findFirst(
            $nodes,
            static fn (Node $node): bool => $node instanceof ClassMethod
                && strcasecmp($node->name->toString(), $methodName) === 0
                && $node->getStartLine() <= $method->getStartLine()
                && $node->getEndLine() >= $method->getEndLine()
        );

        return $methodNode instanceof ClassMethod ? $methodNode : null;
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
    private function sourceFiles(): array
    {
        return $this->sourceFiles ??= $this->discoverSourceFiles();
    }

    /** @return list<string> */
    private function fingerprintSourceFiles(): array
    {
        if ($this->fingerprintSourceFiles !== null) {
            return $this->fingerprintSourceFiles;
        }

        $files = array_fill_keys($this->sourceFiles(), true);
        $packageDirectories = [];
        foreach (array_merge($this->additionalClasses, $this->trustedClasses) as $className) {
            try {
                if (!$this->reflectionProvider->hasClass($className)) {
                    continue;
                }

                $visited = [];
                $classReflection = $this->reflectionProvider->getClass($className);
                $this->collectClassSourceFiles(
                    $classReflection,
                    $files,
                    $visited,
                    $packageDirectories
                );
                foreach ($this->ruleTypeResolver->sourceDependencyClassNames($classReflection) as $dependencyClassName) {
                    if (!$this->reflectionProvider->hasClass($dependencyClassName)) {
                        continue;
                    }

                    $this->collectClassSourceFiles(
                        $this->reflectionProvider->getClass($dependencyClassName),
                        $files,
                        $visited,
                        $packageDirectories
                    );
                }
                foreach ($this->ruleTypeResolver->sourceDependencyFiles($classReflection) as $dependencyFile) {
                    $files[$dependencyFile] = true;
                }
                $this->collectRuleConstantDependencySourceFiles(
                    $classReflection,
                    $files,
                    $visited,
                    $packageDirectories
                );
            } catch (\Throwable) {
                continue;
            }
        }

        $this->fingerprintSourceFiles = array_keys($files);
        sort($this->fingerprintSourceFiles);

        return $this->fingerprintSourceFiles;
    }

    /**
     * @param array<string, true> $files
     * @param array<string, true> $visitedClasses
     * @param array<string, true> $packageDirectories
     */
    private function collectRuleConstantDependencySourceFiles(
        ClassReflection $classReflection,
        array &$files,
        array &$visitedClasses,
        array &$packageDirectories
    ): void {
        $queue = $this->ruleTypeResolver
            ->sourceDependencyClassConstantReferences($classReflection);
        $visitedReferences = [];

        for ($offset = 0; isset($queue[$offset]); ++$offset) {
            $reference = $queue[$offset];
            $referenceKey = strtolower($reference['className']) . '::' . $reference['constantName'];
            if (isset($visitedReferences[$referenceKey])) {
                continue;
            }
            $visitedReferences[$referenceKey] = true;

            if (!$this->reflectionProvider->hasClass($reference['className'])) {
                continue;
            }

            $dependencyClass = $this->reflectionProvider->getClass($reference['className']);
            $this->collectClassSourceFiles(
                $dependencyClass,
                $files,
                $visitedClasses,
                $packageDirectories
            );
            if (strtolower($reference['constantName']) === 'class') {
                continue;
            }

            array_push(
                $queue,
                ...$this->ruleTypeResolver->classConstantSourceDependencyReferences(
                    $dependencyClass,
                    $reference['constantName']
                )
            );
        }
    }

    /**
     * @param array<string, true> $files
     * @param array<string, true> $visited
     * @param array<string, true> $packageDirectories
     */
    private function collectClassSourceFiles(
        ClassReflection $classReflection,
        array &$files,
        array &$visited,
        array &$packageDirectories
    ): void {
        $className = $classReflection->getName();
        if (isset($visited[$className])) {
            return;
        }
        $visited[$className] = true;

        foreach ($this->classSourceFiles($classReflection) as $fileName) {
            $files[$fileName] = true;
            $this->collectPackageSourceFiles($fileName, $files, $packageDirectories);
        }

        foreach ($classReflection->getTraits() as $traitReflection) {
            $this->collectClassSourceFiles(
                $traitReflection,
                $files,
                $visited,
                $packageDirectories
            );
        }

        foreach ($classReflection->getInterfaces() as $interfaceReflection) {
            $this->collectClassSourceFiles(
                $interfaceReflection,
                $files,
                $visited,
                $packageDirectories
            );
        }

        $parentReflection = $classReflection->getParentClass();
        if ($parentReflection !== null) {
            $this->collectClassSourceFiles(
                $parentReflection,
                $files,
                $visited,
                $packageDirectories
            );
        }
    }

    /**
     * @param array<string, true> $files
     * @param array<string, true> $packageDirectories
     */
    private function collectPackageSourceFiles(
        string $fileName,
        array &$files,
        array &$packageDirectories
    ): void {
        if (str_starts_with($fileName, 'phar://')) {
            return;
        }

        $directory = dirname($fileName);
        while (true) {
            $composerJson = $directory . DIRECTORY_SEPARATOR . 'composer.json';
            if (is_file($composerJson)) {
                if (isset($packageDirectories[$directory])) {
                    return;
                }
                $packageDirectories[$directory] = true;
                $files[$composerJson] = true;
                $this->collectPhpFiles($directory, $files);
                $this->collectComposerAutoloadSources($directory, $composerJson, $files);

                return;
            }

            $parent = dirname($directory);
            if ($parent === $directory) {
                return;
            }
            $directory = $parent;
        }
    }

    /** @return list<string> */
    private function classSourceFiles(ClassReflection $classReflection): array
    {
        $files = [];
        $nativeFile = $classReflection->getNativeReflection()->getFileName();
        if (is_string($nativeFile)) {
            $files[$nativeFile] = true;
        }

        foreach (spl_autoload_functions() as $autoloader) {
            if (!is_array($autoloader)
                || !$autoloader[0] instanceof ClassLoader
            ) {
                continue;
            }

            $autoloadFile = $autoloader[0]->findFile($classReflection->getName());
            if (is_string($autoloadFile)) {
                $files[$autoloadFile] = true;
            }
        }

        return array_keys($files);
    }

    /** @param array<string, true> $files */
    private function collectComposerAutoloadSources(
        string $packageDirectory,
        string $composerJson,
        array &$files
    ): void {
        $contents = @file_get_contents($composerJson);
        if (!is_string($contents)) {
            return;
        }

        try {
            $composer = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return;
        }
        if (!is_array($composer)) {
            return;
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
                    foreach (is_array($mapping) ? $mapping : [$mapping] as $path) {
                        if (!is_string($path)) {
                            continue;
                        }

                        $this->collectPhpFiles(
                            $this->absolutizeComposerPath($packageDirectory, $path),
                            $files,
                            $autoloadType === 'classmap' ? ['php', 'inc', 'hh'] : ['php']
                        );
                    }
                }
            }

            $autoloadFiles = $section['files'] ?? null;
            if (!is_array($autoloadFiles)) {
                continue;
            }

            foreach ($autoloadFiles as $path) {
                if (!is_string($path)) {
                    continue;
                }

                $files[$this->absolutizeComposerPath($packageDirectory, $path)] = true;
            }
        }
    }

    private function absolutizeComposerPath(string $packageDirectory, string $path): string
    {
        $path = $this->isAbsolutePath($path)
            ? $path
            : $packageDirectory . DIRECTORY_SEPARATOR . $path;

        $realPath = realpath($path);

        return is_string($realPath) ? $realPath : $path;
    }

    /**
     * @param array<string, true> $files
     * @param non-empty-list<string> $extensions
     */
    private function collectPhpFiles(
        string $path,
        array &$files,
        array $extensions = ['php']
    ): void {
        if (is_file($path)) {
            if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $extensions, true)) {
                $files[$path] = true;
            }

            return;
        }
        if (!is_dir($path)) {
            return;
        }

        try {
            $iterator = new \RecursiveCallbackFilterIterator(
                new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
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

            foreach (new \RecursiveIteratorIterator($iterator) as $file) {
                if (!$file instanceof \SplFileInfo
                    || !$file->isFile()
                    || !in_array(strtolower($file->getExtension()), $extensions, true)
                ) {
                    continue;
                }

                $files[$file->getPathname()] = true;
            }
        } catch (\UnexpectedValueException) {
        }
    }

    private function descriptorHash(): string
    {
        return hash('sha256', serialize([
            'extensionContract' => $this->extensionContractHash(),
            'globalDescriptors' => $this->globalCacheDescriptors,
        ]));
    }

    private function canUseExportedFingerprint(ClassReflection $classReflection): bool
    {
        $className = $classReflection->getName();
        if (in_array($className, $this->trustedClasses, true)
            || !$this->ruleTypeResolver->hasExportableLiteralRulesMethodBody($classReflection)
            || !$this->hasExportableLifecycleFingerprints($classReflection)
        ) {
            return false;
        }

        $visited = [];
        return $this->areFormRequestSourcesAnalysed($classReflection, $visited);
    }

    private function hasExportableLifecycleFingerprints(ClassReflection $classReflection): bool
    {
        foreach (['after', 'validator', 'withValidator'] as $methodName) {
            if (!$classReflection->hasNativeMethod($methodName)) {
                continue;
            }

            $method = $this->findNativeMethodNode($classReflection, $methodName);
            if ($method === null
                || (!$method->isAbstract() && !$method->isFinal() && $method->isPrivate())
            ) {
                return false;
            }
        }

        return true;
    }

    private function recordGlobalCacheDependencies(ClassReflection $classReflection): void
    {
        try {
            $visited = [];
            $this->recordClassHierarchySourceFiles($classReflection, $visited);

            foreach ($this->ruleTypeResolver->sourceDependencyClassNames($classReflection) as $className) {
                if (!$this->reflectionProvider->hasClass($className)) {
                    continue;
                }

                $this->recordClassHierarchySourceFiles(
                    $this->reflectionProvider->getClass($className),
                    $visited
                );
            }
            foreach ($this->ruleTypeResolver->sourceDependencyFiles($classReflection) as $fileName) {
                $this->globalCacheDependencyFiles[$fileName] = true;
            }

            $queue = $this->ruleTypeResolver
                ->sourceDependencyClassConstantReferences($classReflection);
            $visitedReferences = [];
            for ($offset = 0; isset($queue[$offset]); ++$offset) {
                $reference = $queue[$offset];
                $referenceKey = strtolower($reference['className']) . '::' . $reference['constantName'];
                if (isset($visitedReferences[$referenceKey])
                    || !$this->reflectionProvider->hasClass($reference['className'])
                ) {
                    continue;
                }
                $visitedReferences[$referenceKey] = true;

                $dependencyClass = $this->reflectionProvider->getClass($reference['className']);
                $this->recordClassHierarchySourceFiles($dependencyClass, $visited);
                if (strtolower($reference['constantName']) !== 'class') {
                    array_push(
                        $queue,
                        ...$this->ruleTypeResolver->classConstantSourceDependencyReferences(
                            $dependencyClass,
                            $reference['constantName']
                        )
                    );
                }
            }
        } catch (\Throwable) {
            // The existing project/package scan remains the conservative fallback.
        }
    }

    /** @param array<string, true> $visited */
    private function recordClassHierarchySourceFiles(
        ClassReflection $classReflection,
        array &$visited
    ): void {
        $className = $classReflection->getName();
        if ($className === FormRequest::class || isset($visited[$className])) {
            return;
        }
        $visited[$className] = true;

        foreach ($this->classSourceFiles($classReflection) as $fileName) {
            $this->globalCacheDependencyFiles[$fileName] = true;
        }
        foreach ($classReflection->getTraits() as $traitReflection) {
            $this->recordClassHierarchySourceFiles($traitReflection, $visited);
        }
        foreach ($classReflection->getInterfaces() as $interfaceReflection) {
            $this->recordClassHierarchySourceFiles($interfaceReflection, $visited);
        }

        $parentReflection = $classReflection->getParentClass();
        if ($parentReflection !== null) {
            $this->recordClassHierarchySourceFiles($parentReflection, $visited);
        }
    }

    /** @param array<string, true> $visited */
    private function areFormRequestSourcesAnalysed(
        ClassReflection $classReflection,
        array &$visited
    ): bool {
        $className = $classReflection->getName();
        if ($className === FormRequest::class || isset($visited[$className])) {
            return true;
        }
        $visited[$className] = true;

        $fileName = $classReflection->getNativeReflection()->getFileName();
        if (!is_string($fileName) || !$this->isAnalysedSourceFile($fileName)) {
            return false;
        }

        foreach ($classReflection->getTraits() as $traitReflection) {
            if (!$this->areFormRequestSourcesAnalysed($traitReflection, $visited)) {
                return false;
            }
        }

        $parentReflection = $classReflection->getParentClass();
        return $parentReflection === null
            || $this->areFormRequestSourcesAnalysed($parentReflection, $visited);
    }

    private function isAnalysedSourceFile(string $fileName): bool
    {
        if (str_starts_with($fileName, 'phar://')) {
            return false;
        }

        return isset($this->analysedSourceFiles()[$this->absolutizePath($fileName)]);
    }

    /** @return array<string, true> */
    private function analysedSourceFiles(): array
    {
        if ($this->analysedSourceFiles !== null) {
            return $this->analysedSourceFiles;
        }

        $files = [];
        foreach ($this->analysedPaths as $path) {
            $path = $this->absolutizePath($path);
            if (is_file($path)) {
                if (!$this->isExcludedFromAnalysing($path)) {
                    $files[$path] = true;
                }
                continue;
            }
            if (!is_dir($path)) {
                continue;
            }

            try {
                $iterator = new \RecursiveCallbackFilterIterator(
                    new \RecursiveDirectoryIterator(
                        $path,
                        \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS
                    ),
                    function (\SplFileInfo $file): bool {
                        if (str_starts_with($file->getFilename(), '.')
                            || isset(self::PHPSTAN_IGNORED_NON_DOT_DIRECTORIES[$file->getFilename()])
                        ) {
                            return false;
                        }

                        return $file->isDir()
                            || in_array($file->getExtension(), $this->fileExtensions, true);
                    }
                );
                foreach (new \RecursiveIteratorIterator($iterator) as $file) {
                    if (!$file instanceof \SplFileInfo || !$file->isFile()) {
                        continue;
                    }

                    $fileName = $file->getPathname();
                    if (!$this->isExcludedFromAnalysing($fileName)) {
                        $files[$fileName] = true;
                    }
                }
            } catch (\UnexpectedValueException) {
                // An uncertain selection retains global invalidation.
            }
        }

        return $this->analysedSourceFiles = $files;
    }

    private function isExcludedFromAnalysing(string $fileName): bool
    {
        $fileName = $this->fileHelper->normalizePath($fileName);
        $excludes = array_merge(
            $this->excludePaths['analyse'] ?? [],
            $this->excludePaths['analyseAndScan'] ?? []
        );
        foreach (array_unique($excludes) as $exclude) {
            // PHPStan deliberately leaves leading-star patterns relative so
            // they can match analysed paths outside the working directory.
            if (!str_starts_with($exclude, '*')) {
                $exclude = $this->absolutizePath($exclude);
            }
            $exclude = $this->fileHelper->normalizePath($exclude);
            if (preg_match('~[*?[\]]~', $exclude) === 1) {
                $flags = DIRECTORY_SEPARATOR === '\\' ? FNM_CASEFOLD | FNM_NOESCAPE : 0;
                if (fnmatch($exclude, $fileName, $flags)) {
                    return true;
                }
                continue;
            }

            if ($fileName === rtrim($exclude, DIRECTORY_SEPARATOR)
                || str_starts_with(
                    $fileName,
                    rtrim($exclude, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
                )
            ) {
                return true;
            }
        }

        return false;
    }

    private function sourceFingerprint(): string
    {
        $context = hash_init('sha256');
        hash_update($context, 'manifest-schema:' . self::MANIFEST_SCHEMA . "\0");
        hash_update($context, 'working-directory:' . $this->workingDirectory . "\0");
        hash_update($context, 'additional:' . serialize($this->additionalClasses) . "\0");
        hash_update($context, 'trusted:' . serialize($this->trustedClasses) . "\0");
        hash_update($context, 'excluded:' . serialize($this->excludePaths) . "\0");
        hash_update($context, 'file-extensions:' . serialize($this->fileExtensions) . "\0");
        hash_update($context, 'extension-contract:' . $this->extensionContractHash() . "\0");

        foreach ($this->fingerprintSourceFiles() as $fileName) {
            hash_update($context, 'source:' . $fileName . "\0");
            hash_update($context, $this->fingerprintFile($fileName, 'unreadable'));
            hash_update($context, "\0");
        }

        foreach ($this->composerMetadataFiles() as $fileName) {
            hash_update($context, 'composer:' . $fileName . "\0");
            hash_update($context, $this->fingerprintFile($fileName, 'missing'));
            hash_update($context, "\0");
        }

        return hash_final($context);
    }

    private function extensionContractHash(): string
    {
        if ($this->extensionContractHash !== null) {
            return $this->extensionContractHash;
        }

        $context = hash_init('sha256');
        foreach ($this->extensionContractFiles() as $relativePath => $fileName) {
            hash_update($context, $relativePath . "\0");
            hash_update($context, $this->fingerprintFile($fileName, 'unreadable'));
            hash_update($context, "\0");
        }

        return $this->extensionContractHash = hash_final($context);
    }

    /** @return array<string, string> */
    private function extensionContractFiles(): array
    {
        $sourceDirectory = dirname(__DIR__);
        $files = [];

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($sourceDirectory, \FilesystemIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                if (!$file instanceof \SplFileInfo
                    || !$file->isFile()
                    || strtolower($file->getExtension()) !== 'php'
                ) {
                    continue;
                }

                $fileName = $file->getPathname();
                $files[substr($fileName, strlen($sourceDirectory) + 1)] = $fileName;
            }
        } catch (\UnexpectedValueException) {
            $files['src'] = $sourceDirectory;
        }

        $extensionConfiguration = dirname($sourceDirectory) . DIRECTORY_SEPARATOR . 'extension.neon';
        $files['../extension.neon'] = $extensionConfiguration;
        ksort($files);

        return $files;
    }

    /** @return list<string> */
    private function composerMetadataFiles(): array
    {
        $projectPaths = $this->composerAutoloaderProjectPaths;
        if ($projectPaths === []) {
            foreach (InstalledVersions::getAllRawData() as $installed) {
                $installPath = self::findRootInstallPath($installed);
                if ($installPath !== null && !str_starts_with($installPath, 'phar://')) {
                    $projectPaths[] = $installPath;
                }
            }
        }

        $files = [];
        foreach (array_unique($projectPaths) as $projectPath) {
            $projectPath = $this->absolutizePath($projectPath);
            foreach (['composer.json', 'composer.lock', 'vendor/composer/installed.php'] as $relativePath) {
                $files[] = $projectPath . DIRECTORY_SEPARATOR . $relativePath;
            }
        }
        sort($files);

        return $files;
    }

    private function readManifest(string $sourceFingerprint): ?string
    {
        $contents = @file_get_contents($this->manifestFile());
        if (!is_string($contents)) {
            return null;
        }

        try {
            $manifest = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }

        if (!is_array($manifest)
            || ($manifest['schema'] ?? null) !== self::MANIFEST_SCHEMA
            || ($manifest['sourceFingerprint'] ?? null) !== $sourceFingerprint
            || !$this->dependencyFingerprintsMatch($manifest['dependencyFingerprints'] ?? null)
        ) {
            return null;
        }

        $descriptorHash = $manifest['descriptorHash'] ?? null;
        return is_string($descriptorHash) && preg_match('/^[a-f0-9]{64}$/D', $descriptorHash) === 1
            ? $descriptorHash
            : null;
    }

    private function writeManifest(string $sourceFingerprint, string $descriptorHash): void
    {
        $fileName = $this->manifestFile();
        $directory = dirname($fileName);
        if (!is_dir($directory) && !@mkdir($directory, 0777, true) && !is_dir($directory)) {
            return;
        }

        try {
            $contents = json_encode([
                'schema' => self::MANIFEST_SCHEMA,
                'sourceFingerprint' => $sourceFingerprint,
                'descriptorHash' => $descriptorHash,
                'dependencyFingerprints' => $this->globalCacheDependencyFingerprints(),
            ], JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return;
        }

        $temporary = @tempnam($directory, 'form-request-');
        if (!is_string($temporary)) {
            return;
        }
        if (@file_put_contents($temporary, $contents) === false || !@rename($temporary, $fileName)) {
            @unlink($temporary);
        }
    }

    /** @return array<string, string> */
    private function globalCacheDependencyFingerprints(): array
    {
        $fingerprints = [];
        $fileNames = array_keys($this->globalCacheDependencyFiles);
        sort($fileNames);
        foreach ($fileNames as $fileName) {
            $fingerprints[$fileName] = $this->fingerprintFile($fileName, 'missing');
        }

        return $fingerprints;
    }

    private function dependencyFingerprintsMatch(mixed $fingerprints): bool
    {
        if (!is_array($fingerprints)) {
            return false;
        }

        foreach ($fingerprints as $fileName => $expectedFingerprint) {
            if (!is_string($fileName) || !is_string($expectedFingerprint)) {
                return false;
            }

            $actualFingerprint = $this->fingerprintFile($fileName, 'missing');
            if (!hash_equals($expectedFingerprint, $actualFingerprint)) {
                return false;
            }
        }

        return true;
    }

    private function fingerprintFile(string $fileName, string $unreadable): string
    {
        if (!array_key_exists($fileName, $this->fileDigests)) {
            $contents = @file_get_contents($fileName);
            $this->fileDigests[$fileName] = is_string($contents)
                ? hash('sha256', $contents)
                : null;
        }

        return $this->fileDigests[$fileName] ?? $unreadable;
    }

    private function manifestFile(): string
    {
        $identity = hash('sha256', serialize([
            self::MANIFEST_SCHEMA,
            $this->workingDirectory,
            $this->additionalClasses,
            $this->trustedClasses,
            $this->analysedPaths,
            $this->analysedPathsFromConfig,
            $this->composerAutoloaderProjectPaths,
            $this->scanFiles,
            $this->scanDirectories,
            $this->excludePaths,
            $this->fileExtensions,
        ]));

        return rtrim($this->tmpDirectory, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . 'phpstan-laravel-validation'
            . DIRECTORY_SEPARATOR
            . 'form-requests-' . $identity . '.json';
    }

    /**
     * @param list<string> $classNames
     *
     * @return list<string>
     */
    private static function normalizeClassNames(array $classNames): array
    {
        return array_values(array_unique(array_map(
            static fn (string $className): string => ltrim($className, '\\'),
            $classNames
        )));
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
        return $this->fileHelper->normalizePath(
            $this->fileHelper->absolutizePath($path)
        );
    }

    private function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, '/')
            || preg_match('~^[A-Za-z]:[\\\\/]~D', $path) === 1;
    }
}
