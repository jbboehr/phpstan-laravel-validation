#!/usr/bin/env bash

set -euo pipefail

profile_args=()
execution_args=()

for argument in "$@"; do
    case "$argument" in
        --profile=*) profile_args+=("$argument") ;;
        --update|--reinstall|--composer=*) execution_args+=("$argument") ;;
        *)
            echo "Unknown option: $argument" >&2
            exit 2
            ;;
    esac
done

audit_plan="$(php scripts/inference-audit-matrix.php --list "${profile_args[@]}")"

while IFS=$'\t' read -r profile minimum_php; do
    php_shell="php${minimum_php/./}"
    # Composer wrappers may export PHP-version-specific runtime paths. The
    # selected Nix shell must supply those unless --composer overrides it.
    nix develop ".#${php_shell}" -c \
        env \
        -u COMPOSER_BINARY \
        -u PHPRC \
        -u PHP_INI_SCAN_DIR \
        php scripts/inference-audit-matrix.php \
        "--profile=${profile}" \
        "${execution_args[@]}"
done <<< "$audit_plan"
