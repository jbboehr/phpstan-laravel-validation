#!/usr/bin/env bash
set -euo pipefail

# Preserve the consumers and logs for inspection. This check needs network
# access for Composer resolution and does not modify the repository's vendor.
repository_root="$(git rev-parse --show-toplevel)"
source_commit="$(git rev-parse --verify "${1:-HEAD}^{commit}")"
illuminate_constraint="${2:-^13.0}"
check_dir="$(mktemp -d "${TMPDIR:-/tmp}/plv-runtime-installation.XXXXXX")"
echo "Runtime installation evidence: $check_dir"

git archive --format=zip --output="$check_dir/package.zip" "$source_commit"
git show "$source_commit:composer.json" > "$check_dir/package-composer.json"

php /dev/stdin "$check_dir" "$source_commit" "$illuminate_constraint" <<'PHP'
<?php
$directory = $argv[1];
$package = json_decode(file_get_contents($directory . '/package-composer.json'), true, 512, JSON_THROW_ON_ERROR);
// Supply only the repository metadata that an unpublished Git export lacks.
$package['version'] = 'dev-runtime-installation';
$package['dist'] = [
    'type' => 'zip',
    'url' => 'file://' . $directory . '/package.zip',
    'reference' => $argv[2],
    'shasum' => sha1_file($directory . '/package.zip'),
];
foreach (['production', 'dev-only-control'] as $mode) {
    $consumer = [
        'name' => 'release-check/' . $mode,
        'type' => 'project',
        'license' => 'proprietary',
        'require' => ['illuminate/validation' => $argv[3]],
        'repositories' => [['type' => 'package', 'package' => $package]],
        'config' => ['allow-plugins' => false, 'preferred-install' => 'dist'],
    ];
    $section = $mode === 'production' ? 'require' : 'require-dev';
    $consumer[$section][$package['name']] = $package['version'];
    mkdir($directory . '/' . $mode);
    file_put_contents($directory . '/' . $mode . '/composer.json', json_encode($consumer, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n");
}
PHP

for mode in production dev-only-control; do
    consumer_dir="$check_dir/$mode"
    composer --working-dir="$consumer_dir" update --no-install --no-interaction --no-progress > "$consumer_dir/resolve.log" 2>&1
    composer --working-dir="$consumer_dir" install --no-dev --classmap-authoritative --no-interaction --no-progress > "$consumer_dir/install.log" 2>&1
done

php "$repository_root/scripts/check-runtime-installation.php" "$check_dir/production/vendor/autoload.php" > "$check_dir/production/result.json"
cat "$check_dir/production/result.json"

control_status=0
php "$repository_root/scripts/check-runtime-installation.php" "$check_dir/dev-only-control/vendor/autoload.php" \
    > "$check_dir/dev-only-control/result.stdout" 2> "$check_dir/dev-only-control/result.stderr" || control_status=$?
if [[ "$control_status" -ne 1 ]]; then
    echo "The development-only control must fail with exit 1; got $control_status." >&2
    exit 1
fi
printf '%s\n' 'Runtime package is unavailable as a production dependency.' \
    | cmp - "$check_dir/dev-only-control/result.stderr"
echo 'PASS: the development-only control reports the missing runtime dependency.'
