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
    LARAVEL_COMMIT=$(git log HEAD -n1 -q --pretty=format:"%h" --no-patch)
    export LARAVEL_COMMIT

    $PHP_WITH_UOPZ -d memory_limit=512M \
        ./vendor/bin/phpunit \
        --prepend "$SCRIPT_PATH/valid-test-extractor.php" \
        tests/Validation/
)

exit 0
