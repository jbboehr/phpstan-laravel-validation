#!/usr/bin/env bash

set -euo pipefail

database="${1:-/nix/var/nix/db/db.sqlite}"
timeout="${2:-30}"

if [[ ! "$timeout" =~ ^[0-9]+$ ]]; then
    echo "The Nix store database timeout must be a non-negative integer." >&2
    exit 2
fi
timeout=$((10#$timeout))

if [[ ! -f "$database" ]]; then
    echo "The Nix store database does not exist: $database" >&2
    exit 1
fi

sqlite=(sqlite3)
if [[ ! -w "$database" || ! -w "$(dirname "$database")" ]]; then
    if ! command -v sudo >/dev/null 2>&1; then
        echo "The Nix store database is not writable and sudo is unavailable: $database" >&2
        exit 1
    fi

    sqlite=(sudo sqlite3)
fi

if ! "${sqlite[@]}" --version >/dev/null 2>&1; then
    echo "SQLite is unavailable for the Nix store database checkpoint." >&2
    exit 1
fi

deadline=$((SECONDS + timeout))
checkpoint=""

while true; do
    set +e
    checkpoint="$("${sqlite[@]}" "$database" 'PRAGMA wal_checkpoint(TRUNCATE);' 2>&1)"
    checkpoint_status=$?
    set -e

    if [[ "$checkpoint_status" -eq 0 && "$checkpoint" == 0\|* ]]; then
        echo "The Nix store database checkpoint completed."
        exit 0
    fi

    if [[ "$checkpoint_status" -ne 0 && "$checkpoint_status" -ne 5 && "$checkpoint_status" -ne 6 ]]; then
        echo "The Nix store database checkpoint failed permanently." >&2
        if [[ -n "$checkpoint" ]]; then
            echo "$checkpoint" >&2
        fi
        exit 1
    fi

    if [[ "$checkpoint_status" -eq 0 && "$checkpoint" != 1\|* ]]; then
        echo "The Nix store database checkpoint returned an unexpected result." >&2
        if [[ -n "$checkpoint" ]]; then
            echo "$checkpoint" >&2
        fi
        exit 1
    fi

    if ((SECONDS >= deadline)); then
        echo "Timed out waiting for the Nix store database checkpoint." >&2
        if [[ -n "$checkpoint" ]]; then
            echo "$checkpoint" >&2
        fi
        exit 1
    fi

    sleep 0.25
done
