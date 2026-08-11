# BookStack performance benchmark

This report measures the incremental PHPStan cost of loading
`phpstan-laravel-validation` in a substantial existing Laravel application. It
is a reproducible development benchmark, not a performance guarantee for other
applications, machines, PHPStan releases, or extension configurations.

Benchmark date: 2026-08-10.

## Result

On BookStack's complete configured level-4 scan, loading the extension added
0.11 seconds to the median cold serial run and 0.7 MiB to GNU `time`'s median
maximum-RSS measurement:

| Cache | Workers | Configuration | n | Median | Mean | Max RSS |
| --- | --- | --- | ---: | ---: | ---: | ---: |
| Cold | Default | Baseline | 5 | 5.18 s | 5.236 s | 307.2 MiB |
| Cold | Default | Extension | 5 | 5.20 s | 5.284 s | 311.3 MiB |
| Cold | One | Baseline | 5 | 17.49 s | 17.592 s | 422.0 MiB |
| Cold | One | Extension | 5 | 17.60 s | 17.660 s | 422.7 MiB |
| Warm | Default | Baseline | 5 | 0.30 s | 0.306 s | 148.1 MiB |
| Warm | Default | Extension | 5 | 0.31 s | 0.310 s | 149.8 MiB |

The corresponding median comparisons are:

- cold serial: **+0.6% wall time and +0.7 MiB reported maximum RSS**;
- cold default workers: **+0.4% wall time and +4.1 MiB reported maximum
  RSS**; and
- warm result cache: **+10 ms wall time and +1.8 MiB reported maximum RSS**.

The default-worker cold scan was 3.38 times faster than the serial scan both
with and without the extension. On this workload, the extension did not
materially change PHPStan's parallel scaling.

The parallel wall-time difference is smaller than the variation among its
individual samples. The warm comparison is close to GNU `time`'s displayed
resolution, so its 3.3% relative figure should not be mistaken for a meaningful
regression. The cold serial comparison is the cleanest incremental result:
both distributions were stable, and the extension added roughly six-tenths of
one percent.

Every measured run completed with BookStack's expected zero diagnostics at
its configured level.

## Target and environment

The benchmark reused the pinned application from the
[BookStack compatibility investigation](bookstack-compatibility-investigation.md)
and refreshed only the mirrored local extension package.
The [development report index](README.md) distinguishes these recorded results
from current feature documentation.

| Component | Revision or value |
| --- | --- |
| BookStack | v26.05.3 at `e1cd3229966d` |
| Analyzed application size | 476 PHP files and 43,304 lines under `app` |
| `phpstan-laravel-validation` | `develop` at `0b8b764f1e3c` |
| PHPStan | 2.2.8 at `e285254e60f3` |
| Larastan | v3.10.0 at `2970f8339815` |
| Laravel | v12.64.0 at `727a8ea2949c` |
| PHP | 8.4.23 |
| Processor | AMD Ryzen 9 9950X3D, 16 cores and 32 logical CPUs |
| Operating system | NixOS, Linux 7.1.5-xanmod1, x86-64 |

BookStack's native configuration analyzes `app` at level 4, loads Larastan,
and bootstraps the application through `bootstrap/phpstan.php`. Its source,
dependency lock, PHPStan version, Larastan configuration, analyzed paths, and
PHP runtime were identical between each baseline/extension pair. The only
configuration difference was inclusion of this project's `extension.neon`.

The extension was installed into BookStack as a mirrored Composer path
package. Merely having its classes in Composer's autoloader was therefore
common to both configurations; only the extension-enabled runs registered its
PHPStan services.

## Method

The benchmark harness generated isolated PHPStan configurations and ran this
matrix five times:

1. Baseline, one worker, fresh result cache.
1. Extension, one worker, fresh result cache.
1. Baseline, PHPStan's default workers, fresh result cache.
1. Extension, PHPStan's default workers, fresh result cache.
1. Baseline, PHPStan's default workers, primed result cache.
1. Extension, PHPStan's default workers, primed result cache.

Cold runs received a unique `tmpDir` for every sample. "Cold" therefore means
that PHPStan could not reuse its result cache; it does not mean that the
operating-system filesystem cache was cleared or that the machine was rebooted.
Warm configurations were each run once before measurement to populate their
independent result caches.

Cold variant order rotated between iterations, and warm baseline/extension
order alternated. This reduces systematic first-run, temperature, and scheduler
bias, although it cannot eliminate normal workstation noise. No other known
CPU-intensive process was intentionally run during the benchmark.

The harness invoked PHPStan with JSON output and no progress display. GNU
`time` 1.10 recorded wall, user, system, exit status, and maximum resident-set
size. Medians are the primary comparison; means are retained to expose obvious
outliers.

### Memory qualification

GNU `time` reports maximum RSS for the command it observes. During PHPStan's
default parallel execution, that is not a reliable aggregate peak for the
whole process tree. The default-worker memory figures remain useful as matched
observations, but they must not be read as BookStack's total parallel-analysis
memory requirement.

The serial runs avoid that ambiguity and provide the clearest incremental
memory comparison. Their median changed from 422.0 to 422.7 MiB, approximately
0.2%.

## Raw wall-time samples

| Result cache | Workers | Configuration | Wall-time samples in seconds |
| --- | --- | --- | --- |
| Cold | One | Baseline | 17.38, 17.49, 17.49, 17.60, 18.00 |
| Cold | One | Extension | 17.49, 17.60, 17.70, 17.60, 17.91 |
| Cold | Default | Baseline | 5.08, 5.48, 5.18, 5.07, 5.37 |
| Cold | Default | Extension | 5.18, 5.18, 5.48, 5.20, 5.38 |
| Warm | Default | Baseline | 0.33, 0.30, 0.30, 0.30, 0.30 |
| Warm | Default | Extension | 0.33, 0.31, 0.30, 0.30, 0.31 |

The harness can additionally emit machine-readable JSON containing every wall,
user, system, RSS, and diagnostic-count sample. The raw JSON used for this
report was retained only as local benchmark output rather than committed as a
stable fixture.

## Reproduction

Install the current extension as a development dependency in a BookStack
checkout, then run from this repository:

```sh
php scripts/benchmark-bookstack.php /path/to/bookstack 5
```

The script uses the calling PHP binary and does not require Nix. It requires
BookStack's dependencies, the extension at
`vendor/jbboehr/phpstan-laravel-validation`, and GNU `time`. On systems where
the executable is not at a conventional path, set
`BOOKSTACK_BENCHMARK_TIME`.

The repository's PHP 8.4 development shell is an optional convenience:

```sh
nix develop .#php84 --command \
    php scripts/benchmark-bookstack.php /path/to/bookstack 5
```

Set `BOOKSTACK_BENCHMARK_JSON=/path/to/results.json` to preserve all samples as
JSON. Set `BOOKSTACK_BENCHMARK_KEEP=1` only when inspecting the generated NEON
files, result caches, and command output is useful; otherwise the temporary
benchmark directory is removed automatically.

The script records the exact BookStack and Composer package revisions in its
output. Results should not be compared across revisions as if they measured
only extension performance.

## Limitations

- This is one Laravel 12 application. BookStack uses controller and request
  validation heavily but has no FormRequest classes. The opt-in FormRequest
  integration remained at its default disabled setting, so this benchmark
  measures neither registry discovery nor FormRequest rule resolution.
- Five alternating samples are adequate to identify a large regression, not
  to resolve sub-percent changes with statistical confidence.
- Results from a 32-thread workstation do not predict GitHub-hosted runner
  times or scaling.
- BookStack's configured level is the representative workload. A level-`max`
  differential would produce thousands of diagnostics and answer a different
  question.
- The benchmark measures end-to-end PHPStan cost. It does not attribute time
  among the extension's parser, evaluator, version context, and dynamic return
  type extensions.

A future performance investigation would be most valuable if it adds a second
application that uses many FormRequests, or if a profiler identifies a concrete
hot path worth isolating. This benchmark is suitable as a manual before/after
check when such a change is proposed; it is intentionally not part of CI.
