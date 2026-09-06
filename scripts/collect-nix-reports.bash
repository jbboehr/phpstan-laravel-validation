#!/usr/bin/env bash

set -euo pipefail

if [[ "$#" -ne 5 ]]; then
    echo "Usage: $0 <junit|infection> <nix-build-log> <nix-result> <retained-build-root> <destination>" >&2
    exit 2
fi

report_kind="$1"
build_log="$2"
nix_result="$3"
retained_build_root="$4"
destination="$5"

case "$report_kind" in
    junit)
        report_names=(phpunit-junit.xml)
        retained_report_directory="phpstan-laravel-validation-junit"
        ;;
    infection)
        report_names=(infection.log infection-summary.json infection-summary.log)
        retained_report_directory="project"
        ;;
    *)
        echo "Unknown Nix report kind: $report_kind" >&2
        exit 2
        ;;
esac

canonical_directory() {
    local directory="$1"

    (cd -P -- "$directory" 2>/dev/null && pwd -P)
}

copy_report() {
    local report="$1"
    local allowed_root="$2"
    local report_name="${report##*/}"
    local canonical_root
    local canonical_report_directory

    if [[ ! -f "$report" || -L "$report" ]]; then
        echo "Refusing a non-regular or symlinked $report_kind report: $report" >&2
        return 1
    fi

    canonical_root="$(canonical_directory "$allowed_root")" || return 1
    canonical_report_directory="$(canonical_directory "$(dirname "$report")")" || return 1
    report="$canonical_report_directory/$report_name"
    if [[ "$report" != "$canonical_root"/* ]]; then
        echo "Refusing a $report_kind report outside its expected root: $report" >&2
        return 1
    fi

    mkdir -p "$destination" || return 1
    cp "$report" "$destination/$report_name" || return 1
    echo "Collected $report_kind report from: $report"
}

copy_reports() {
    local directory="$1"
    local allowed_root="$2"
    local report_name
    local copied=false

    for report_name in "${report_names[@]}"; do
        if [[ -e "$directory/$report_name" || -L "$directory/$report_name" ]]; then
            if copy_report "$directory/$report_name" "$allowed_root"; then
                copied=true
            fi
        fi
    done

    [[ "$copied" == true ]]
}

if copy_reports "$nix_result/reports" "$nix_result"; then
    exit 0
fi

if [[ ! -f "$build_log" ]]; then
    echo "No Nix build log was available for $report_kind report recovery." >&2
    exit 0
fi

if [[ ! -d "$retained_build_root" ]]; then
    echo "The configured Nix build directory is unavailable; no failed-build report can be recovered." >&2
    exit 0
fi

retained_build_root="$(canonical_directory "$retained_build_root")"

while IFS= read -r retained_directory; do
    if [[ ! -d "$retained_directory" ]]; then
        continue
    fi

    retained_directory="$(canonical_directory "$retained_directory")" || continue
    if [[ "$retained_directory" != "$retained_build_root"/* ]]; then
        continue
    fi

    if copy_reports "$retained_directory/$retained_report_directory" "$retained_directory"; then
        exit 0
    fi
done < <(sed -nE 's/.*keeping build directory "([^"]+)".*/\1/p' "$build_log")

echo "No $report_kind report was produced by the Nix build." >&2
