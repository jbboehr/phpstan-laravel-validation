<?php
/**
 * Copyright (c) anno Domini nostri Jesu Christi MMXXIV John Boehr & contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once './vendor/autoload.php';

// Composer's generated autoload.php always prepends itself to the SPL
// autoload stack, so simply requiring our own vendor/autoload.php here
// would shadow whichever version of a same-named class (e.g. PHPUnit's
// own classes) the Laravel checkout under test already loaded. Register
// it appended instead, so our extra dependencies (VarExporter, Monolog)
// are resolved without stealing class resolution from the Laravel
// checkout's own dependencies.
$ourLoader = require_once __DIR__ . '/../vendor/autoload.php';
if ($ourLoader instanceof \Composer\Autoload\ClassLoader) {
    spl_autoload_unregister([$ourLoader, 'loadClass']);
    $ourLoader->register(false);
}

require_once __DIR__ . '/valid-test-extractor-functions.php';

use Brick\VarExporter\VarExporter;

$outputDirectory = __DIR__ . '/../tests/laravel';
$testExportFile = __DIR__ . '/../tests/fixtures/laravel-export.php';

$log = new \Monolog\Logger('');
$log->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/valid-test-extractor.log', 'debug'));

if (!is_dir(dirname($testExportFile))) {
    mkdir(dirname($testExportFile)) or die('failed to create output directory');
}

function is_anonymous_class(object|string $instance)
{
    $testInstance = new \ReflectionClass($instance);
    return $testInstance->isAnonymous();
}

function is_exportable(mixed $expr): bool
{
    return match (gettype($expr)) {
        "boolean", "integer", "double", "float", "string", "NULL" => true,
        "array" => count(array_filter($expr, function ($v, $k) {
            return !is_exportable($k) || !is_exportable($v);
        }, ARRAY_FILTER_USE_BOTH)) === 0,
        "object" => match (get_class($expr)) {
            DateTime::class,
            DateTimeImmutable::class,
            "Symfony\\Component\\HttpFoundation\\File\\File",
            "Symfony\\Component\\HttpFoundation\\File\\UploadedFile",
            "Illuminate\\Support\\Carbon",
            "Illuminate\\Http\\Testing\\File" => !is_anonymous_class($expr),
            default => false,
        },
        default => false,
    };
}

function is_constant_expression(mixed $expr): bool
{
    if (is_array($expr)) {
        foreach ($expr as $k => $v) {
            if (!is_constant_expression($k) || !is_constant_expression($v)) {
                return false;
            }
        }
        return true;
    }
    return match (gettype($expr)) {
        "boolean", "integer", "double", "float", "string", "NULL" => true,
        "object" => false,
        default => false,
    };
}

function all_tests(): ArrayObject
{
    static $allTests;
    if (null === $allTests) {
        $allTests = new ArrayObject();
    }
    return $allTests;
}

register_shutdown_function(function () use ($testExportFile) {
    file_put_contents(
        $testExportFile,
        '<?php /* laravel commit ' . getenv('LARAVEL_COMMIT') . ' */ return ' . VarExporter::export(all_tests()->getArrayCopy()) . ';'
    );
});

uopz_set_return(\Illuminate\Validation\Validator::class, 'passes', function () use ($log, $outputDirectory) {
    if (!isset($this)) { // @phpstan-ignore-line
        return false;
    }
    /** @var Illuminate\Validation\Validator $this */

    // this may cause tests to fail, unfortunately
    $passes = $this->passes();
    if (!$passes) {
        return $passes;
    }

    $allTests = all_tests();

    // get data
    $data = $this->getData();
    $rules = $this->initialRules;
    $expandedRules = $this->getRules();
    $validated = $this->validated();
    $placeholders = get_validator_placeholders($this);

    // extract the test name
    $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $testName = 'unknown';
    $lastTrace = null;
    foreach ($bt as $trace) {
        if (
            str_contains($trace['class'], 'Illuminate\\Tests\\Validation') &&
            str_starts_with($trace['function'], 'test')
        ) {
            $testName = $trace['class'] . '::' . $trace['function'] . ':' . ($lastTrace['line'] ?? $trace['line']);
            break;
        } else {
            $lastTrace = $trace;
        }
    }
    $log = $log->withName($testName);

    $rules = revert_validator_placeholders($rules, $placeholders);
    $expandedRules = revert_validator_placeholders($expandedRules, $placeholders);
    $data = revert_validator_placeholders($data, $placeholders);
    $validated = revert_validator_placeholders($validated, $placeholders);

    $isRulesConstExpr = is_constant_expression($rules);
    $isExpandedRulesConstExpr = is_constant_expression($expandedRules);
    $isDataExportable = is_exportable($data);
    $isValidatedExportable = is_exportable($validated);

    $log->debug('test ' . $testName);
    $log->debug('rules const expr ' . $isRulesConstExpr);
    $log->debug('data exportable ' . $isDataExportable);
    $log->debug('validated exportable ' . $isValidatedExportable);
    $log->debug('dot placeholder ' . $placeholders['dot']);

    if (!$isRulesConstExpr || !$isDataExportable || !$isValidatedExportable || !$isExpandedRulesConstExpr) {
        $log->info('skipping, not const expr');
        return $passes;
    }

    if (validator_rules_were_mutated($this)) {
        $log->info('skipping, effective rules differ from source rules');
        return $passes;
    }

    // skip empty tests?
//    if (empty($data) || empty($rules) || empty($validated)) {
//        $log->info('skipping, empty rules data or validated');
//        return $passes;
//    }

    if (!empty($this->replacers)) {
        $log->info('skipping, has replacers', $this->replacers);
        return $passes;
    } elseif (!empty($this->after)) {
        $log->info('skipping, has after', $this->after);
        return $passes;
    }

    $dataExported = VarExporter::export($data, true);
    $rulesExported = VarExporter::export($rules, true);
    $validatedExported = VarExporter::export($validated, true);

    $hash = sodium_bin2base64(sodium_hex2bin(md5(join([
        $testName,
        $rulesExported,
        $dataExported,
        $validatedExported
    ]))), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);

    $log->debug('data ' . $dataExported);
    $log->debug('rules ' . $rulesExported);
    $log->debug('validated ' . $validatedExported);
    $log->debug('hash ' . $hash);

    $allTests[$hash] = [
        'location' => $testName,
        'data' => $data,
        'validated' => $validated,
        'rules' => $rules,
        'expandedRules' => $expandedRules,
    ];

    return $passes;
}, true);
