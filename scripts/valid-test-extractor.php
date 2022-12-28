<?php

require_once './vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Brick\VarExporter\VarExporter;

$outputDirectory = __DIR__ . '/../tests/laravel';
$testExportFile = __DIR__ . '/../tests/fixtures/laravel-export.php';

$log = new \Monolog\Logger('');
$log->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/valid-test-extractor.log', 'debug'));

if (!is_dir(dirname($testExportFile))) {
    mkdir(dirname($testExportFile)) or die('failed to create output directory');
}

function is_exportable(mixed $expr): bool
{
    return match (gettype($expr)) {
        "boolean", "integer", "double", "float", "string", "NULL" => true,
        "array" => !empty(array_filter(array_map(function ($v) {
            return is_exportable($v);
        }, $expr))),
        "object" => match (get_class($expr)) {
            DateTime::class,
            DateTimeImmutable::class,
            "Symfony\\Component\\HttpFoundation\\File\\File",
            "Symfony\\Component\\HttpFoundation\\File\\UploadedFile",
            "Illuminate\\Support\\Carbon",
            "Illuminate\\Http\\Testing\\File" => true,
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

function revert_dot_placeholder(mixed $data, string $dotPlaceholder): mixed
{
    if (is_array($data)) {
        $newData = [];
        foreach ($data as $k => $v) {
            $newKey = str_replace($dotPlaceholder, '\.', $k);
            $newData[$newKey] = revert_dot_placeholder($v, $dotPlaceholder);
        }
        return $newData;
    }
    return $data;
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
    $dotPlaceholder = $this->dotPlaceholder;

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

    $rules = revert_dot_placeholder($rules, $dotPlaceholder);
    $data = revert_dot_placeholder($data, $dotPlaceholder);
    $validated = revert_dot_placeholder($validated, $dotPlaceholder);

    $isRulesConstExpr = is_constant_expression($rules);
    $isExpandedRulesConstExpr = is_constant_expression($expandedRules);
    $isDataExportable = is_exportable($data);
    $isValidatedExportable = is_exportable($validated);

    $log->debug('test ' . $testName);
    $log->debug('rules const expr ' . $isRulesConstExpr);
    $log->debug('data exportable ' . $isDataExportable);
    $log->debug('validated exportable ' . $isValidatedExportable);
    $log->debug('dot placeholder ' . $dotPlaceholder);

    if (!$isRulesConstExpr || !$isDataExportable || !$isValidatedExportable || !$isExpandedRulesConstExpr) {
        $log->info('skipping, not const expr');
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
