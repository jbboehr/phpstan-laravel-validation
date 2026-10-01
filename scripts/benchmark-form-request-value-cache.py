#!/usr/bin/env python3
"""Compare isolated visitor and value-dependency checkouts on PHPStan 2.3."""

import argparse
import json
from pathlib import Path
import re
import statistics
import subprocess
import tempfile
import time

parser = argparse.ArgumentParser(description=__doc__)
parser.add_argument("visitor", type=Path)
parser.add_argument("value", type=Path)
parser.add_argument("--requests", type=int, default=100)
parser.add_argument("--runs", type=int, default=5)
args = parser.parse_args()
if args.requests < 1 or args.runs < 1:
    parser.error("--requests and --runs must be positive")


def measure(checkout):
    checkout = checkout.resolve()
    project = Path(tempfile.mkdtemp(prefix="form-request-value-benchmark-"))
    (project / "src").mkdir()
    (project / "phpstan.neon").write_text(
        f"includes:\n    - {checkout}/extension.neon\n"
        "parameters:\n    level: max\n    paths: [src]\n    tmpDir: cache\n"
        "    phpstanLaravelValidation:\n        formRequests:\n            enabled: true\n"
    )

    def request(index, rule):
        return (
            "<?php\nnamespace ValueBenchmark;\n"
            f"final class Request{index} extends \\Illuminate\\Foundation\\Http\\FormRequest {{\n"
            "/** @return array<string, string> */\n"
            f"public function rules(): array {{ return ['value' => '{rule}']; }}\n}}\n"
        )

    for index in range(args.requests):
        (project / f"src/Request{index}.php").write_text(
            request(index, "required|string|max:10")
        )
        (project / f"src/Consumer{index}.php").write_text(
            "<?php\nnamespace ValueBenchmark;\n"
            f"function consume{index}(Request{index} $r): int {{ "
            "return strlen($r->validated()['value']); }\n"
        )
        (project / f"src/Reference{index}.php").write_text(
            "<?php\nnamespace ValueBenchmark;\n"
            f"function reference{index}(Request{index} $r): string {{ "
            "return $r->method(); }\n"
        )

    command = [
        "php", str(checkout / "vendor/bin/phpstan"), "analyse",
        "-c", str(project / "phpstan.neon"), "--no-progress", "--error-format=raw", "-vv",
    ]

    def run(expected_exit):
        start = time.perf_counter()
        result = subprocess.run(command, cwd=project, capture_output=True, text=True)
        elapsed = time.perf_counter() - start
        output = result.stdout + result.stderr
        if result.returncode != expected_exit:
            raise RuntimeError(output)
        match = re.search(r"(\d+) files? will be reanalysed", output)
        return {
            "seconds": round(elapsed, 3),
            "reanalysed": int(match[1]) if match else None,
        }

    cold = run(0)
    warm = [run(0) for _ in range(args.runs)]
    (project / "src/Request0.php").write_text(request(0, "required|string|max:20"))
    equivalent = run(0)
    (project / "src/Request0.php").write_text(request(0, "required|array"))
    changed = run(1)
    return {
        "checkout": str(checkout),
        "phpstan": subprocess.check_output(
            ["php", str(checkout / "vendor/bin/phpstan"), "--version"], text=True
        ).strip(),
        "project": str(project),
        "cold": cold,
        "warm": warm,
        "warm_median_seconds": statistics.median(run["seconds"] for run in warm),
        "equivalent_payload_edit": equivalent,
        "changed_payload_edit": changed,
    }


print(json.dumps({"requests": args.requests, "visitor": measure(args.visitor),
                  "value": measure(args.value)}, indent=2))
