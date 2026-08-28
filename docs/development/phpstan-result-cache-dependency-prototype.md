# PHPStan result-cache dependency prototype

This document records the experimental integration between FormRequest
inference and PHPStan's per-file semantic result-cache dependency prototype.
It is implementation evidence for the `result-cache-dependencies` branch, not
a promise that a released PHPStan version exposes this API.

Prototype date: 2026-08-27. Latest upstream retest: 2026-08-27.

## Upstream dependency

The implementation was exercised against
[`jbboehr/phpstan-src` branch `prototype/result-cache-dependencies`](https://github.com/jbboehr/phpstan-src/tree/prototype/result-cache-dependencies)
at commit `0ab81728a02b22ec7d1c3e53bf17d4dda99f35ea`. The tested PHAR
identifies itself as `2.2.x-dev@0ab8172`. This supersedes the initial
`de9a96a00d175ceb67feb999522b3a2745aa0d58` prototype build.

That source package is PHPStan's unscoped development tree. Installing it
directly into this Laravel test project's Composer graph is not viable: its
development dependency versions conflict with Laravel and PHPUnit. The local
prototype therefore used a PHAR compiled from that exact source commit in
place of the locked PHPStan 2.2.7 PHAR. No Composer dependency change is
committed by this experiment.

The branch is not release-ready until the API is available through a normal
`phpstan/phpstan` release. At that point, the package's PHPStan constraint and
all minimum-version checks must be updated together.

The current upstream source example also needs one PHAR-specific correction.
PHPStan's compiler downgrades the native
`Scope&NodeCallbackInvoker&CollectedDataEmitter` parameter on `Rule` to
`Scope` so the PHAR remains compatible with PHP 7.4. An external rule that
copies the source-only native intersection then fatals against the compiled
PHAR. This extension already uses the compatible `Scope` declaration, and its
compiled-PHAR tests pass. The upstream example should use a PHPDoc
intersection or otherwise receive compiled-PHAR coverage before the API is
released.

## Architecture

PHPStan's prototype separates two operations:

1. During analysis, a rule emits an extension key and an opaque semantic
   dependency key for the current file.
1. During result-cache save and restoration, PHPStan asks the owning extension
   for the current hash of that dependency key in the main process.

For this extension, the semantic key is the concrete FormRequest class name.
`FormRequestResultCacheDependencyRule` observes supported `validated()` and
`safe()` calls and associates their files with those class names.
`FormRequestTypeRegistry` resolves one requested class on demand and hashes its
lifecycle eligibility plus its inferred structural type.

The updated upstream contract treats dependency keys as opaque historical
data: a key may have been written by an older extension version, and its hash
must remain deterministic even when it is no longer emitted. This extension
therefore emits canonical reflection names, strips a legacy leading namespace
separator, and maps missing or non-canonical obsolete keys to a stable
fail-closed descriptor. A regression test verifies that such a key's hash does
not depend on whether PHPStan previously resolved the canonical class name.

This removes two costs from the previous design:

- no recursive scan and reflection pass over every project PHP file is needed;
- one changed FormRequest contract no longer invalidates unrelated callers.

The registry still enforces the existing source boundary. Untrusted classes
must live under PHPStan's analyzed or scan paths or the root Composer
`autoload` or `autoload-dev` mappings. An exact `trustedClasses` entry may opt
another class into resolution while retaining the documented soundness risk of
bypassing lifecycle checks. Broad source roots retain the previous scanner's
pruning of nested `.git`, `.phpunit.cache`, `node_modules`, and `vendor`
directories.

## API boundary discovered during integration

Dynamic return-type extensions receive a `Scope`, but they do not execute with
an active collected-data callback in every analysis harness. Attempting to
emit a dependency while resolving the return type caused PHPStan's
`TypeInferenceTestCase` to fail with `Node callback is not present in this
scope`.

Dependency emission therefore belongs in an ordinary PHPStan rule, whose
scope contract includes `CollectedDataEmitter`. The return-type extensions
remain responsible only for type inference. This separation also makes cache
dependency emission independently testable.

## Conformance evidence

`FormRequestResultCacheTest` uses two independent requests and consumers. It
first records a clean cache, changes one request from `required|string` to
`required|array`, and runs PHPStan again.

Before the prototype integration, the test fails because the global
`ResultCacheMetaExtension` hash changes and PHPStan discards the complete
cache. With per-file dependencies, PHPStan reports that exactly two files are
reanalyzed: the changed request and the caller whose `safe()->all()` result
depends on it. The unrelated request and caller stay cached, while the changed
caller reports the expected `strlen(array)` diagnostic.

The existing cache tests continue to prove that changes in a `rules()` body,
an external rule constant, and lifecycle eligibility invalidate affected
callers. The nullsafe-call case changes only an external rule constant: PHPStan
reanalyzes that file, the FormRequest, and the dependent caller, confirming
that PHPStan's virtual ordinary-method-call lowering emits the semantic
dependency. A warm unchanged run restores the cache with zero files reanalyzed
and creates no extension-specific manifest.

## Remaining verification

The focused cache and FormRequest inference suites pass against the compiled
`0ab8172` prototype PHAR, including constant-dynamic, finite-dynamic, and
nullsafe call sites. Repository-wide PHPStan analysis also passes. Before this
design can replace the released implementation:

1. run the full supported PHP and Laravel matrices against a distributable
   PHPStan build containing the API;
1. rerun the Koel and Pterodactyl benchmarks to measure cold and warm behavior
   after removing global discovery;
1. confirm the upstream API's final names and cache-record compatibility;
1. make the upstream rule example compatible with the compiled PHAR and add a
   PHAR-level extension test;
1. replace the prototype dependency procedure with an ordinary released
   Composer constraint.
