<?php

declare(strict_types=1);

// include-interceptor 1.0.0 rejects unreadable targets before inspecting the
// link itself. Keep this tooling-only patch until upstream preserves lstat.
$path = __DIR__ . '/vendor/infection/include-interceptor/src/IncludeInterceptor.php';
$source = file_get_contents($path);
if ($source === false) {
    throw new RuntimeException('Cannot read the installed Infection interceptor.');
}

$originalHash = 'f4729bf586295e5dd5e44bd571162ebbc37eca804cb357a9dd73a77158f54efb';
$patchedHash = 'ad1a6069180b922e4bed3beee154e917ed8ca75efc28a7ef60f383861d5b7267';
$hash = hash('sha256', $source);
if ($hash === $patchedHash) {
    return;
}
if ($hash !== $originalHash) {
    throw new RuntimeException('The Infection interceptor changed; review or remove patch-interceptor.php.');
}

$before = <<<'PHP'
            if (is_readable($path) === false) {
                return false;
            }

            if ($flags & STREAM_URL_STAT_LINK) {
                return lstat($path);
            }
PHP;
$after = <<<'PHP'
            if ($flags & STREAM_URL_STAT_LINK) {
                return ($flags & STREAM_URL_STAT_QUIET) ? @lstat($path) : lstat($path);
            }

            if (is_readable($path) === false) {
                return false;
            }
PHP;
$patched = str_replace($before, $after, $source);
if (hash('sha256', $patched) !== $patchedHash) {
    throw new RuntimeException('The Infection interceptor patch did not produce the expected source.');
}
if (file_put_contents($path, $patched) !== strlen($patched)) {
    throw new RuntimeException('Cannot write the patched Infection interceptor.');
}
