<?php

declare(strict_types=1);

$check = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};
$read = static function (string $path): string {
    $contents = file_get_contents($path);
    if ($contents === false) {
        throw new RuntimeException('Cannot read the patcher test fixture.');
    }

    return $contents;
};
$runPatcher = static function (string $path): void {
    (static function (string $script): void {
        require $script;
    })($path);
};

$installedPath = dirname(__DIR__) . '/vendor/infection/include-interceptor/src/IncludeInterceptor.php';
$patchedSource = $read($installedPath);
$check(
    hash('sha256', $patchedSource) === 'ad1a6069180b922e4bed3beee154e917ed8ca75efc28a7ef60f383861d5b7267',
    'The installed interceptor must have the reviewed patched contents.',
);

$patchedBlock = <<<'PHP'
            if ($flags & STREAM_URL_STAT_LINK) {
                return ($flags & STREAM_URL_STAT_QUIET) ? @lstat($path) : lstat($path);
            }

            if (is_readable($path) === false) {
                return false;
            }
PHP;
$originalBlock = <<<'PHP'
            if (is_readable($path) === false) {
                return false;
            }

            if ($flags & STREAM_URL_STAT_LINK) {
                return lstat($path);
            }
PHP;
$originalSource = str_replace($patchedBlock, $originalBlock, $patchedSource, $replacements);
$check($replacements === 1, 'The patcher fixture must contain one reviewed change.');
$check(
    hash('sha256', $originalSource) === 'f4729bf586295e5dd5e44bd571162ebbc37eca804cb357a9dd73a77158f54efb',
    'The reconstructed interceptor must match the pinned original dependency.',
);

$directory = sys_get_temp_dir() . '/infection-patcher-' . bin2hex(random_bytes(8));
$fixtureDirectory = $directory . '/vendor/infection/include-interceptor/src';
if (!mkdir($fixtureDirectory, 0700, true)) {
    throw new RuntimeException('Cannot create the patcher test directory.');
}
$fixturePath = $fixtureDirectory . '/IncludeInterceptor.php';
$patcherPath = $directory . '/patch-interceptor.php';

try {
    $check(copy(dirname(__DIR__) . '/patch-interceptor.php', $patcherPath), 'Cannot copy the patcher under test.');
    $check(file_put_contents($fixturePath, $originalSource) === strlen($originalSource), 'Cannot create the original-source fixture.');

    $runPatcher($patcherPath);
    $check($read($fixturePath) === $patchedSource, 'The pinned original dependency must be patched exactly.');

    $runPatcher($patcherPath);
    $check($read($fixturePath) === $patchedSource, 'Applying the patch to patched source must be idempotent.');

    $unfamiliarSource = $originalSource . "\n// unfamiliar dependency source\n";
    $check(file_put_contents($fixturePath, $unfamiliarSource) === strlen($unfamiliarSource), 'Cannot create the unfamiliar-source fixture.');
    $rejected = false;
    try {
        $runPatcher($patcherPath);
    } catch (RuntimeException $exception) {
        $rejected = str_contains($exception->getMessage(), 'interceptor changed');
    }
    $check($rejected, 'Unfamiliar dependency source must be rejected explicitly.');
    $check($read($fixturePath) === $unfamiliarSource, 'Rejected dependency source must remain byte-for-byte unchanged.');
} finally {
    foreach ([$fixturePath, $patcherPath] as $path) {
        if (file_exists($path)) {
            unlink($path);
        }
    }
    foreach (
        [
            $fixtureDirectory,
            dirname($fixtureDirectory),
            dirname($fixtureDirectory, 2),
            dirname($fixtureDirectory, 3),
            $directory,
        ] as $path
    ) {
        if (is_dir($path)) {
            rmdir($path);
        }
    }
}

echo "Interceptor patching checks passed.\n";
