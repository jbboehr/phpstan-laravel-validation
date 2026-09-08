<?php

declare(strict_types=1);

use Composer\InstalledVersions;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use jbboehr\Rensei\Parse;

// Run in a separate process against a fresh consumer's Composer autoloader.
// Never load this repository's development dependencies as a fallback.
$autoload = isset($argv[1]) ? realpath($argv[1]) : false;
if ($autoload === false || !is_file($autoload)) {
    fwrite(STDERR, "Usage: php scripts/check-runtime-installation.php /consumer/vendor/autoload.php\n");
    exit(2);
}

$loader = require $autoload;

$package = 'jbboehr/phpstan-laravel-validation';
if (!InstalledVersions::isInstalled($package, false)) {
    fwrite(STDERR, "Runtime package is unavailable as a production dependency.\n");
    exit(1);
}

function checkRuntimeInstallation(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$vendor = dirname($autoload);
$metadata = json_decode(file_get_contents($vendor . '/composer/installed.json'), true, 512, JSON_THROW_ON_ERROR);
checkRuntimeInstallation($metadata['dev'] === false, 'Install the consumer with --no-dev.');
checkRuntimeInstallation($loader->isClassMapAuthoritative(), 'Install with --classmap-authoritative.');
$installation = InstalledVersions::getInstallPath($package);
$installedPath = $installation === null ? false : realpath($installation);
checkRuntimeInstallation(
    $installedPath !== false && str_starts_with($installedPath, $vendor . DIRECTORY_SEPARATOR),
    'The package must be installed inside the consumer vendor directory, not linked to a checkout.'
);
checkRuntimeInstallation(
    !InstalledVersions::isInstalled('phpunit/phpunit'),
    'The production consumer must not provide PHPUnit.'
);
checkRuntimeInstallation(
    (new ReflectionClass(Parse::class))->getFileName() === $installedPath . '/runtime/Parse.php',
    'Parse must load from the installed package.'
);

$factory = new Factory(new Translator(new ArrayLoader(), 'en'));
$input = ['age' => '42', 'page' => '2'];
$validator = $factory->make($input, [
    'age' => ['required', Parse::integer()],
    'page' => ['required', 'integer'],
]);
$expected = ['age' => 42, 'page' => '2'];
checkRuntimeInstallation($validator->validated() === $expected, 'Validated output must parse only age.');
checkRuntimeInstallation($validator->safe()->all() === $expected, 'Safe output must contain the parsed value.');
checkRuntimeInstallation($input === ['age' => '42', 'page' => '2'], 'Caller input must remain unchanged.');

// No native integer rule here: rejection must come from the installed parser.
$invalid = $factory->make(['age' => '042'], ['age' => ['required', Parse::integer()]]);
checkRuntimeInstallation($invalid->fails(), 'The installed parser must reject a noncanonical integer.');
checkRuntimeInstallation($invalid->errors()->has('age'), 'The parser must report the rejected attribute.');

echo json_encode([
    'result' => 'PASS',
    'package' => InstalledVersions::getPrettyVersion($package),
    'illuminate/validation' => InstalledVersions::getPrettyVersion('illuminate/validation'),
    'phpstan/phpstan' => InstalledVersions::getPrettyVersion('phpstan/phpstan'),
    'nikic/php-parser' => InstalledVersions::getPrettyVersion('nikic/php-parser'),
    'php' => PHP_VERSION,
], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n";
