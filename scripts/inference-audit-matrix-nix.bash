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
    nix develop ".#${php_shell}" -c \
        php scripts/inference-audit-matrix.php \
        "--profile=${profile}" \
        "${execution_args[@]}"
done <<< "$audit_plan"
