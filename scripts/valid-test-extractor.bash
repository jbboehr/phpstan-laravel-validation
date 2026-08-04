#!/usr/bin/env bash
# Copyright (c) anno Domini nostri Jesu Christi MMXXIV John Boehr & contributors
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

set -euo pipefail

SCRIPT_PATH="$( cd -- "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"
PHP_WITH_UOPZ="${PHP_WITH_UOPZ:-php}"
LARAVEL_PATH="${LARAVEL_PATH:-../laravel-framework}"

(
    cd "$LARAVEL_PATH"

    if [[ -n "$(git status --porcelain)" ]]; then
        echo "Laravel checkout must be clean before exporting validation fixtures" >&2
        exit 1
    fi

    LARAVEL_COMMIT=$(git rev-parse HEAD)
    LARAVEL_VERSION=$("$PHP_WITH_UOPZ" -r 'require "vendor/autoload.php"; echo Illuminate\Foundation\Application::VERSION;')

    if [[ ! "$LARAVEL_VERSION" =~ ^([0-9]+)\. ]]; then
        echo "Unable to determine Laravel major version from: $LARAVEL_VERSION" >&2
        exit 1
    fi

    LARAVEL_MAJOR="${BASH_REMATCH[1]}"
    # The dollar-prefixed expressions below are PHP variables, not shell variables.
    # shellcheck disable=SC2016
    LATEST_LARAVEL_VERSION=$(
        composer show laravel/framework --all --format=json |
            "$PHP_WITH_UOPZ" -r '
                require $argv[1];
                $metadata = json_decode(stream_get_contents(STDIN), true, 512, JSON_THROW_ON_ERROR);
                $versions = $metadata["versions"] ?? null;
                if (!is_array($versions)) {
                    fwrite(STDERR, "Unable to read available Laravel versions\n");
                    exit(1);
                }
                $latest = latest_stable_laravel_version($versions, (int) $argv[2]);
                if ($latest === null) {
                    fwrite(STDERR, "Unable to find the latest Laravel release\n");
                    exit(1);
                }
                echo $latest;
            ' "$SCRIPT_PATH/valid-test-extractor-functions.php" "$LARAVEL_MAJOR"
    )
    if [[ "$LARAVEL_VERSION" != "$LATEST_LARAVEL_VERSION" ]]; then
        echo "Laravel $LARAVEL_VERSION is stale; checkout $LATEST_LARAVEL_VERSION before exporting" >&2
        exit 1
    fi

    EXPORT_DIRECTORY="${LARAVEL_EXPORT_DIRECTORY:-$SCRIPT_PATH/../tests/fixtures}"
    EXPORT_DIRECTORY=$(cd -- "$EXPORT_DIRECTORY" && pwd -P)
    FINAL_EXPORT_FILE="$EXPORT_DIRECTORY/laravel-export-v$LARAVEL_MAJOR.php"
    TEMPORARY_EXPORT_FILE=$(mktemp "$EXPORT_DIRECTORY/.laravel-export-v$LARAVEL_MAJOR.XXXXXX")

    cleanup() {
        if [[ -n "$TEMPORARY_EXPORT_FILE" && -e "$TEMPORARY_EXPORT_FILE" ]]; then
            rm -f -- "$TEMPORARY_EXPORT_FILE"
        fi
    }
    trap cleanup EXIT

    export LARAVEL_COMMIT LARAVEL_VERSION
    export LARAVEL_EXPORT_FILE="$TEMPORARY_EXPORT_FILE"

    "$PHP_WITH_UOPZ" -d memory_limit=512M \
        ./vendor/bin/phpunit \
        --bootstrap "$SCRIPT_PATH/valid-test-extractor.php" \
        tests/Validation/

    # The dollar-prefixed expressions below are PHP variables, not shell variables.
    # shellcheck disable=SC2016
    "$PHP_WITH_UOPZ" -r '
        require "vendor/autoload.php";
        $fixtures = require $argv[1];
        exit(is_array($fixtures) && $fixtures !== [] ? 0 : 1);
    ' "$TEMPORARY_EXPORT_FILE"

    mv -- "$TEMPORARY_EXPORT_FILE" "$FINAL_EXPORT_FILE"
    TEMPORARY_EXPORT_FILE=""
    cleanup
    trap - EXIT
    echo "Exported Laravel $LARAVEL_VERSION validation fixtures to $FINAL_EXPORT_FILE"
)

exit 0
