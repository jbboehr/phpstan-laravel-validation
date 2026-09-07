<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/infection/include-interceptor/src/IncludeInterceptor.php';

use Infection\StreamWrapper\IncludeInterceptor;

require __DIR__ . '/patcher.php';

$directory = sys_get_temp_dir() . '/infection-interceptor-' . bin2hex(random_bytes(8));
if (!mkdir($directory, 0700)) {
    throw new RuntimeException('Cannot create the interceptor test directory.');
}

$check = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};
set_error_handler(static function (int $severity, string $message): bool {
    if ((error_reporting() & $severity) !== 0) {
        throw new RuntimeException($message);
    }
    return false;
});

try {
    file_put_contents($directory . '/original.php', '<?php return "original";');
    file_put_contents($directory . '/replacement.php', '<?php return "replacement";');
    symlink('missing', $directory . '/dangling');
    symlink('original.php', $directory . '/linked');

    $nativeDanglingMetadata = lstat($directory . '/dangling');
    $nativeLinkedMetadata = lstat($directory . '/linked');
    $check($nativeDanglingMetadata !== false, 'Native lstat() must inspect the dangling-link fixture.');
    $check($nativeLinkedMetadata !== false, 'Native lstat() must inspect the resolved-link fixture.');

    IncludeInterceptor::intercept($directory . '/original.php', $directory . '/replacement.php');
    IncludeInterceptor::enable();

    clearstatcache(true, $directory . '/dangling');
    clearstatcache(true, $directory . '/linked');
    $check(lstat($directory . '/dangling') === $nativeDanglingMetadata, 'Dangling-link metadata must match the native file wrapper.');
    $check(lstat($directory . '/linked') === $nativeLinkedMetadata, 'Resolved-link metadata must match the native file wrapper.');
    $check(is_link($directory . '/dangling'), 'A dangling symlink must remain visible to is_link().');
    $check((new SplFileInfo($directory . '/dangling'))->isLink(), 'SPL must identify dangling symlinks.');
    $check(!file_exists($directory . '/dangling'), 'A dangling symlink must not acquire a target.');
    $check(!is_link($directory . '/missing'), 'A missing path must return false without a warning.');
    $check(!file_exists($directory . '/missing'), 'Missing target metadata must remain unavailable.');
    $check(is_link($directory . '/linked'), 'A resolved symlink must remain visible.');
    $check(is_file($directory . '/linked'), 'Target metadata must still follow resolved symlinks.');
    $check(!is_link($directory . '/original.php'), 'A regular file must not become a symlink.');
    $check((require $directory . '/original.php') === 'replacement', 'Includes must still load the mutant.');
    $check(file_get_contents($directory . '/original.php') === '<?php return "original";', 'Ordinary reads must retain original contents.');

    file_put_contents($directory . '/bootstrap-original.php', '<?php final class InfectionBootstrapFixture { public const VALUE = "original"; }');
    file_put_contents($directory . '/bootstrap-replacement.php', '<?php final class InfectionBootstrapFixture { public const VALUE = "replacement"; }');
    IncludeInterceptor::intercept($directory . '/bootstrap-original.php', $directory . '/bootstrap-replacement.php');
    $projectRoot = dirname(__DIR__, 3);
    $configuration = simplexml_load_file($projectRoot . '/phpunit.xml.dist');
    $check($configuration !== false, 'Cannot read the PHPUnit configuration.');
    require $projectRoot . '/' . (string) $configuration['bootstrap'];

    // PHPStan restores the native file wrapper after locating an unloaded class.
    PHPStan\Reflection\BetterReflection\SourceLocator\FileReadTrapStreamWrapper::withStreamWrapperOverride(static fn () => null);
    require_once $directory . '/bootstrap-original.php';
    IncludeInterceptor::enable();
    $check(InfectionBootstrapFixture::VALUE === 'replacement', 'PHPStan reflection must not cause tests to execute the original declaration.');
} finally {
    IncludeInterceptor::disable();
    restore_error_handler();
    foreach (['original.php', 'replacement.php', 'dangling', 'linked', 'bootstrap-original.php', 'bootstrap-replacement.php'] as $name) {
        if (file_exists($directory . '/' . $name) || is_link($directory . '/' . $name)) {
            unlink($directory . '/' . $name);
        }
    }
    rmdir($directory);
}

echo "Interceptor filesystem and include checks passed.\n";
