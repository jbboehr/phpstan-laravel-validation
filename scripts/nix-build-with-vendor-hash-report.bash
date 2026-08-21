#!/usr/bin/env bash

set -euo pipefail

if [[ "$#" -lt 2 ]]; then
    echo "Usage: $0 <log-file> <command> [argument ...]" >&2
    exit 2
fi

log_file="$1"
shift

set +e
"$@" 2>&1 | tee "$log_file"
pipeline_status=("${PIPESTATUS[@]}")
set -e

command_status="${pipeline_status[0]}"
tee_status="${pipeline_status[1]}"

if [[ "$command_status" -ne 0 ]]; then
    mapfile -t replacement_hashes < <(
        sed -nE 's/^[[:space:]]*got:[[:space:]]+(sha256-[^[:space:]]+).*$/\1/p' \
            "$log_file" | sort -u
    )

    if [[ "${#replacement_hashes[@]}" -gt 0 ]]; then
        report_vendor_hashes() {
            echo "## Nix Composer vendor hash update"
            echo
            echo "Nix reported the following authoritative replacement hash values:"
            echo
            for replacement_hash in "${replacement_hashes[@]}"; do
                echo "- \`$replacement_hash\`"
            done
            echo
            echo "Update the matching entry in \`nix/vendor-hashes.nix\`, then rerun the failed target."
        }

        if [[ -n "${GITHUB_STEP_SUMMARY:-}" ]]; then
            report_vendor_hashes | tee -a "$GITHUB_STEP_SUMMARY"
        else
            report_vendor_hashes
        fi
    fi

    exit "$command_status"
fi

exit "$tee_status"
