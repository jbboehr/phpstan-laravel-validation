#!/usr/bin/env bash

set -euo pipefail

SCRIPT_PATH="$( cd -- "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"
PHP_WITH_UOPZ="${PHP_WITH_UOPZ:-php}"
LARAVEL_PATH="${LARAVEL_PATH:-../laravel-framework}"

(
    cd "$LARAVEL_PATH"
    LARAVEL_COMMIT=$(git log HEAD -n1 -q --pretty=format:"%h" --no-patch)
    export LARAVEL_COMMIT

    $PHP_WITH_UOPZ -d memory_limit=512M \
        ./vendor/bin/phpunit \
        --prepend "$SCRIPT_PATH/valid-test-extractor.php" \
        tests/Validation/
)

exit 0
