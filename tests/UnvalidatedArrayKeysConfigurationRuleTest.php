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

namespace jbboehr\PhpstanLaravelValidation\Test;

use jbboehr\PhpstanLaravelValidation\Rule\UnvalidatedArrayKeysConfigurationRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/** @extends RuleTestCase<UnvalidatedArrayKeysConfigurationRule> */
final class UnvalidatedArrayKeysConfigurationRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new UnvalidatedArrayKeysConfigurationRule(false);
    }

    public function testReportsCallsThatEnableIncludedKeys(): void
    {
        $message = 'Calling includeUnvalidatedArrayKeys() conflicts with '
            . 'phpstanLaravelValidation.includeUnvalidatedArrayKeys: false and may make '
            . 'inferred validated array shapes unsound.';
        $tip = 'Set phpstanLaravelValidation.includeUnvalidatedArrayKeys to true if '
            . 'this factory mode applies to inferred validation output.';

        $this->analyse(
            [__DIR__ . '/fixtures/unvalidated-array-keys-configuration.php'],
            [
                [$message, 20, $tip],
                [$message, 22, $tip],
                [$message, 23, $tip],
                [$message, 29, $tip],
                [$message, 31, $tip],
            ]
        );

        $errors = $this->gatherAnalyserErrors([
            __DIR__ . '/fixtures/unvalidated-array-keys-configuration.php',
        ]);
        self::assertNotSame([], $errors);
        foreach ($errors as $error) {
            self::assertSame(
                UnvalidatedArrayKeysConfigurationRule::IDENTIFIER,
                $error->getIdentifier()
            );
        }
    }
}
