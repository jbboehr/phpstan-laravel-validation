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

use jbboehr\PhpstanLaravelValidation\Rule\ValidatorMutationRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/** @extends RuleTestCase<ValidatorMutationRule> */
final class ValidatorMutationRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ValidatorMutationRule();
    }

    public function testRejectsStaticallyIdentifiableLaravelValidatorMutations(): void
    {
        $tip = 'Construct a new validator with the complete data and rules instead.';
        $errors = [];
        foreach ([
            23 => 'setData',
            24 => 'setData',
            25 => 'setData',
            26 => 'setData',
            27 => 'setValue',
            28 => 'setRules',
            29 => 'addRules',
            30 => 'sometimes',
            31 => 'sometimes',
            33 => 'addRules',
            35 => ['setData', 'setRules'],
            36 => 'setData',
            37 => 'setData',
            64 => 'setValue',
        ] as $line => $methods) {
            $methods = (array) $methods;
            $displayMethods = array_map(
                static fn (string $method): string => $method . '()',
                $methods
            );
            $methodDescription = count($displayMethods) === 1
                ? 'method ' . $displayMethods[0]
                : 'methods ' . implode(', ', array_slice($displayMethods, 0, -1))
                    . ' or ' . $displayMethods[count($displayMethods) - 1];
            $errors[] = [
                sprintf(
                    'Do not call Laravel validator mutation %s. Mutating a validator invalidates its inferred output contract and can reuse stale validation state.',
                    $methodDescription
                ),
                $line,
                $tip,
            ];
        }

        $fixture = __DIR__ . '/fixtures/validator-mutations.php';
        $this->analyse([$fixture], $errors);

        $analyserErrors = $this->gatherAnalyserErrors([$fixture]);
        self::assertNotSame([], $analyserErrors);
        foreach ($analyserErrors as $error) {
            self::assertSame(ValidatorMutationRule::IDENTIFIER, $error->getIdentifier());
        }
    }

    public function testAllowsThePackageOwnedParsingWriteBack(): void
    {
        $this->analyse([__DIR__ . '/../runtime/Rules/BaseParsingRule.php'], []);
    }
}
