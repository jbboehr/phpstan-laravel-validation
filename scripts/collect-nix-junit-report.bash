#!/usr/bin/env bash

set -euo pipefail

if [[ "$#" -ne 4 ]]; then
    echo "Usage: $0 <nix-build-log> <nix-result> <retained-build-root> <destination>" >&2
    exit 2
fi

build_log="$1"
nix_result="$2"
retained_build_root="$3"
destination="$4"
report_name="phpunit-junit.xml"

canonical_directory() {
    local directory="$1"

    (cd -P -- "$directory" 2>/dev/null && pwd -P)
}

copy_report() {
    local report="$1"
    local allowed_root="$2"
    local canonical_root
    local canonical_report_directory

    if [[ ! -f "$report" || -L "$report" ]]; then
        echo "Refusing a non-regular or symlinked PHPUnit report: $report" >&2
        return 1
    fi

    canonical_root="$(canonical_directory "$allowed_root")" || return 1
    canonical_report_directory="$(canonical_directory "$(dirname "$report")")" || return 1
    report="$canonical_report_directory/$report_name"
    if [[ "$report" != "$canonical_root"/* ]]; then
        echo "Refusing a PHPUnit report outside its expected root: $report" >&2
        return 1
    fi

    mkdir -p "$destination"
    cp "$report" "$destination/$report_name"
    echo "Collected PHPUnit JUnit report from: $report"
}

successful_report="$nix_result/reports/$report_name"
if [[ -e "$successful_report" || -L "$successful_report" ]]; then
    if copy_report "$successful_report" "$nix_result"; then
        exit 0
    fi
fi

if [[ ! -f "$build_log" ]]; then
    echo "No Nix build log was available for PHPUnit report recovery." >&2
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

    retained_report="$retained_directory/phpstan-laravel-validation-junit/$report_name"
    if [[ -e "$retained_report" || -L "$retained_report" ]]; then
        if copy_report "$retained_report" "$retained_directory"; then
            exit 0
        fi
    fi
done < <(sed -nE 's/.*keeping build directory "([^"]+)".*/\1/p' "$build_log")

echo "No PHPUnit JUnit report was produced by the Nix build." >&2
