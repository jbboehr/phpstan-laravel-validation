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

const CONDITIONAL_PRESENT_RULES_INTRODUCED = '10.32.0';

$options = getopt('', ['laravel-autoload:']);
$laravelAutoload = $options['laravel-autoload'] ?? null;
if (!is_string($laravelAutoload) || !is_file($laravelAutoload)) {
    fwrite(STDERR, "--laravel-autoload must name an existing Composer autoload.php\n");
    exit(2);
}

require $laravelAutoload;

$version = InstalledVersions::getPrettyVersion('laravel/framework');
$version = $version === null
    ? Illuminate\Foundation\Application::VERSION
    : ltrim($version, 'v');
$supportsConditionalPresent = version_compare(
    $version,
    CONDITIONAL_PRESENT_RULES_INTRODUCED,
    '>='
);
$factory = new Factory(new Translator(new ArrayLoader(), 'en'));

$cases = [
    'present_if' => ['mode' => 'create'],
    'present_unless' => ['mode' => 'update'],
];

foreach ($cases as $rule => $data) {
    $validator = $factory->make($data, [
        'mode' => 'required|string|in:' . $data['mode'],
        'value' => $rule . ':mode,create|string',
    ]);
    $passes = $validator->passes();

    if ($supportsConditionalPresent && $passes) {
        throw new RuntimeException(sprintf(
            '%s unexpectedly accepted an absent value on Laravel %s',
            $rule,
            $version
        ));
    }

    if (!$supportsConditionalPresent) {
        if (!$passes) {
            throw new RuntimeException(sprintf(
                '%s unexpectedly rejected an absent value on Laravel %s',
                $rule,
                $version
            ));
        }

        if ($validator->validated() !== $data) {
            throw new RuntimeException(sprintf(
                '%s unexpectedly changed validated output on Laravel %s',
                $rule,
                $version
            ));
        }
    }
}

foreach (['missing_if', 'missing_unless'] as $rule) {
    $mode = $rule === 'missing_if' ? 'create' : 'update';
    $validator = $factory->make(['mode' => $mode], [
        'mode' => 'required|string|in:' . $mode,
        'value' => $rule . ':mode,create|string',
    ]);

    if (!$validator->passes() || $validator->validated() !== ['mode' => $mode]) {
        throw new RuntimeException(sprintf(
            '%s boundary witness failed on Laravel %s',
            $rule,
            $version
        ));
    }
}

fwrite(STDOUT, sprintf(
    "Conditional presence rule boundary matches Laravel %s\n",
    $version
));
