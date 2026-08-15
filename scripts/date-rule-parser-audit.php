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

declare(strict_types=1);

use Composer\InstalledVersions;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;

$options = getopt('', ['laravel-autoload:']);
$laravelAutoload = $options['laravel-autoload'] ?? null;
if (!is_string($laravelAutoload) || !is_file($laravelAutoload)) {
    fwrite(STDERR, "--laravel-autoload must name an existing Composer autoload.php\n");
    exit(2);
}

require $laravelAutoload;

$version = InstalledVersions::getPrettyVersion('laravel/framework');
if ($version !== null) {
    $version = ltrim($version, 'v');
}
if ($version === null || version_compare($version, '11.40.0', '<')) {
    fwrite(STDERR, "The Date parser audit requires Laravel 11.40.0 or later\n");
    exit(2);
}

$factory = new Factory(new Translator(new ArrayLoader(), 'en'));
$value = '20240101';
$dateRule = static fn (): object => Rule::date()->format('Ymd');

assertValidationOutcome(
    $factory,
    $value,
    ['value' => ['required', Rule::date()]],
    true,
    'bare Date builder in a rule list'
);
assertValidationOutcome(
    $factory,
    $value,
    ['value' => Rule::date()],
    true,
    'bare standalone Date builder'
);
assertValidationOutcome(
    $factory,
    $value,
    ['value' => ['required', $dateRule()]],
    version_compare($version, '11.41.0', '>='),
    'fluent Date chain in a rule list'
);
assertValidationOutcome(
    $factory,
    $value,
    ['value' => $dateRule()],
    version_compare($version, '11.43.2', '>='),
    'standalone fluent Date chain'
);

fwrite(STDOUT, "Date parser boundaries match Laravel $version\n");

/**
 * @param array<string, mixed> $rules
 */
function assertValidationOutcome(
    Factory $factory,
    string $value,
    array $rules,
    bool $shouldSucceed,
    string $label
): void {
    try {
        $validator = $factory->make(['value' => $value], $rules);
        if (!$validator->passes()) {
            throw new RuntimeException("$label failed validation");
        }

        $validated = $validator->validated();
        if ($validated !== ['value' => $value]) {
            throw new RuntimeException("$label did not preserve its successful input");
        }

        if (!$shouldSucceed) {
            throw new RuntimeException("$label unexpectedly succeeded");
        }
    } catch (BadMethodCallException $exception) {
        if ($shouldSucceed) {
            throw new RuntimeException("$label unexpectedly reached an unknown validator method", 0, $exception);
        }
    }
}
