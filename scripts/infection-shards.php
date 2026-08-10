<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$manifestPath = $projectRoot . '/.github/infection-shards.json';

try {
    $manifestContents = file_get_contents($manifestPath);
    if ($manifestContents === false) {
        throw new RuntimeException(sprintf('Could not read %s.', $manifestPath));
    }

    $shards = json_decode($manifestContents, true, flags: JSON_THROW_ON_ERROR);
    if (!is_array($shards) || array_is_list($shards)) {
        throw new RuntimeException('The Infection shard manifest must contain a non-empty object.');
    }

    $ownersByFile = [];
    $matrix = [];

    foreach ($shards as $shard => $configuration) {
        if (!is_string($shard) || preg_match('/^[a-z0-9-]+$/', $shard) !== 1) {
            throw new RuntimeException('Infection shard names may contain only lowercase letters, numbers, and hyphens.');
        }
        if (!is_array($configuration) || array_is_list($configuration)) {
            throw new RuntimeException(sprintf('Infection shard %s must contain paths and threads.', $shard));
        }
        if (array_diff(array_keys($configuration), ['paths', 'threads']) !== []) {
            throw new RuntimeException(sprintf('Infection shard %s contains an unsupported setting.', $shard));
        }

        $paths = $configuration['paths'] ?? null;
        $threads = $configuration['threads'] ?? null;
        if (!is_array($paths) || !array_is_list($paths) || $paths === []) {
            throw new RuntimeException('Each Infection shard must have a name and at least one path.');
        }
        if (!is_int($threads) || $threads < 1) {
            throw new RuntimeException(sprintf('Infection shard %s must use at least one thread.', $shard));
        }

        foreach ($paths as $path) {
            if (!is_string($path) || preg_match('/^src\/[A-Za-z0-9_.\/-]+$/', $path) !== 1) {
                throw new RuntimeException(sprintf('Invalid path in Infection shard %s.', $shard));
            }

            $absolutePath = $projectRoot . '/' . $path;
            if (is_file($absolutePath)) {
                $files = str_ends_with($path, '.php') ? [$path] : [];
            } elseif (is_dir($absolutePath)) {
                $files = phpFilesBelow($projectRoot, $absolutePath);
            } else {
                throw new RuntimeException(sprintf('Infection shard path does not exist: %s.', $path));
            }
            if ($files === []) {
                throw new RuntimeException(sprintf('Infection shard path contains no PHP files: %s.', $path));
            }

            foreach ($files as $file) {
                $ownersByFile[$file][] = $shard;
            }
        }

        $matrix[] = [
            'shard' => $shard,
            'paths' => implode(' ', $paths),
            'threads' => $threads,
        ];
    }

    $sourceFiles = phpFilesBelow($projectRoot, $projectRoot . '/src');
    $coveredFiles = array_keys($ownersByFile);
    sort($coveredFiles);

    $missingFiles = array_values(array_diff($sourceFiles, $coveredFiles));
    $unexpectedFiles = array_values(array_diff($coveredFiles, $sourceFiles));
    $duplicateFiles = array_filter(
        $ownersByFile,
        static fn (array $owners): bool => count($owners) !== 1,
    );

    if ($missingFiles !== [] || $unexpectedFiles !== [] || $duplicateFiles !== []) {
        $problems = [];
        if ($missingFiles !== []) {
            $problems[] = 'Missing: ' . implode(', ', $missingFiles);
        }
        if ($unexpectedFiles !== []) {
            $problems[] = 'Unexpected: ' . implode(', ', $unexpectedFiles);
        }
        foreach ($duplicateFiles as $file => $owners) {
            $problems[] = sprintf('Duplicate: %s (%s)', $file, implode(', ', $owners));
        }

        throw new RuntimeException(implode(PHP_EOL, $problems));
    }

    echo json_encode(['include' => $matrix], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES), PHP_EOL;
} catch (Throwable $throwable) {
    fwrite(STDERR, $throwable->getMessage() . PHP_EOL);
    exit(1);
}

/**
 * @return list<string>
 */
function phpFilesBelow(string $projectRoot, string $directory): array
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
    );

    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo) {
            throw new RuntimeException('Unexpected directory entry while reading Infection shard paths.');
        }

        if ($file->isFile() && $file->getExtension() === 'php') {
            $files[] = substr($file->getPathname(), strlen($projectRoot) + 1);
        }
    }

    sort($files);

    return $files;
}
