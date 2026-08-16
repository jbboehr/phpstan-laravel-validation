<?php

/**
 * Copyright (c) anno Domini nostri Jesu Christi MMXXVI John Boehr & contributors
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

/**
 * Fail if generated mdBook HTML contains a broken same-site relative link.
 */

if ($argc !== 2 || !is_string($argv[1] ?? null)) {
    fwrite(STDERR, "Usage: php scripts/check-mdbook-links.php GENERATED-DOCUMENTATION-DIRECTORY\n");
    exit(2);
}

$root = realpath($argv[1]);
if ($root === false || !is_dir($root)) {
    fwrite(STDERR, "Documentation directory does not exist: {$argv[1]}\n");
    exit(1);
}

$errors = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if (!$file->isFile() || strtolower($file->getExtension()) !== 'html') {
        continue;
    }

    $html = file_get_contents($file->getPathname());
    if (!is_string($html)) {
        $errors[] = "Could not read {$file->getPathname()}";
        continue;
    }

    if (preg_match_all('/\b(?:href|src)="([^"]+)"/', $html, $matches) === false) {
        continue;
    }

    $pageDirectory = $file->getPath();
    foreach ($matches[1] as $target) {
        if ($target === ''
            || str_starts_with($target, '#')
            || str_starts_with($target, '/')
            || str_contains($target, '://')
            || str_starts_with($target, 'mailto:')
            || str_starts_with($target, 'data:')
        ) {
            continue;
        }

        $parts = explode('#', $target, 2);
        $path = explode('?', $parts[0], 2)[0];
        $fragment = $parts[1] ?? '';
        if ($path === '') {
            $resolved = $file->getPathname();
        } else {
            $resolved = realpath($pageDirectory . DIRECTORY_SEPARATOR . $path);
        }

        if ($resolved === false || ($resolved !== $root && !str_starts_with($resolved, $root . DIRECTORY_SEPARATOR))) {
            $errors[] = sprintf(
                '%s links to missing target %s',
                substr($file->getPathname(), strlen($root) + 1),
                $target
            );
            continue;
        }

        if ($fragment === '' || strtolower(pathinfo($resolved, PATHINFO_EXTENSION)) !== 'html') {
            continue;
        }

        $htmlTarget = file_get_contents($resolved);
        if (!is_string($htmlTarget)) {
            $errors[] = sprintf(
                '%s links to unreadable target %s',
                substr($file->getPathname(), strlen($root) + 1),
                $target
            );
            continue;
        }

        $quoted = preg_quote($fragment, '/');
        if (
            preg_match('/\\sid="' . $quoted . '"/i', $htmlTarget) !== 1
            && preg_match('/\\sname="' . $quoted . '"/i', $htmlTarget) !== 1
        ) {
            $errors[] = sprintf(
                '%s links to missing fragment %s',
                substr($file->getPathname(), strlen($root) + 1),
                $target
            );
        }
    }
}

if ($errors !== []) {
    fwrite(STDERR, implode("\n", $errors) . "\n");
    exit(1);
}

fwrite(STDOUT, "Generated documentation links are valid.\n");
