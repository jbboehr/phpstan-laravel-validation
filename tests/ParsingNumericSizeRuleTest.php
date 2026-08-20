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

use jbboehr\PhpstanLaravelValidation\Extension\CallArgumentResolver;
use jbboehr\PhpstanLaravelValidation\Rule\ParsingNumericSizeRule;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

require_once __DIR__ . '/fixtures/parsing-numeric-size-controller.php';
require_once __DIR__ . '/fixtures/parsing-numeric-size-form-request.php';

/** @extends RuleTestCase<ParsingNumericSizeRule> */
final class ParsingNumericSizeRuleTest extends RuleTestCase
{
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../extension.neon',
            __DIR__ . '/phpstan.neon',
        ];
    }

    protected function getRule(): Rule
    {
        $container = self::getContainer();

        return new ParsingNumericSizeRule(
            $container->getByType(RuleSetResolver::class),
            $container->getByType(CallArgumentResolver::class),
            $container->getByType(ReflectionProvider::class)
        );
    }

    public function testReportsRepresentationSensitiveNumericSizeRules(): void
    {
        $tip = 'Add `integer`, `numeric`, or `decimal` for numeric size semantics. '
            . 'Leave the rule list unchanged only if measuring the original representation is intentional.';
        $single = static fn (string $path, string $rule): string => sprintf(
            'The rules for `%s` combine a numeric parsing rule with Laravel size rule `%s` but declare no `integer`, `numeric`, or `decimal` rule. Laravel therefore measures the original input representation rather than the parsed numeric value.',
            $path,
            $rule
        );

        $fixture = __DIR__ . '/fixtures/parsing-numeric-size.php';
        $controllerFixture = __DIR__ . '/fixtures/parsing-numeric-size-controller.php';
        $formRequestFixture = __DIR__ . '/fixtures/parsing-numeric-size-form-request.php';

        $this->analyse(
            [$fixture, $controllerFixture, $formRequestFixture],
            [
                [$single('minimum', 'min'), 16, $tip],
                [$single('maximum', 'max'), 17, $tip],
                [$single('range', 'between'), 18, $tip],
                [$single('exact', 'size'), 19, $tip],
                [
                    'The rules for `several` combine a numeric parsing rule with Laravel size rules `max`, `min` but declare no `integer`, `numeric`, or `decimal` rule. Laravel therefore measures the original input representation rather than the parsed numeric value.',
                    20,
                    $tip,
                ],
                [$single('profile.age', 'min'), 21, $tip],
                [$single('users.*.age', 'min'), 22, $tip],
                [$single('field.name', 'min'), 23, $tip],
                [$single('named', 'max'), 33, $tip],
                [$single('custom', 'size'), 37, $tip],
                [$single('variable', 'min'), 40, $tip],
                [$single('request', 'min'), 48, $tip],
                [$single('facade_make', 'min'), 52, $tip],
                [$single('facade_validate', 'min'), 56, $tip],
                [$single('helper', 'min'), 60, $tip],
                [$single('controller', 'min'), 19, $tip],
                [$single('validate_with', 'min'), 23, $tip],
                [$single('validate_with_bag', 'min'), 27, $tip],
                [$single('trait_only_controller', 'min'), 39, $tip],
                [$single('form_request', 'min'), 20, $tip],
            ]
        );

        $errors = $this->gatherAnalyserErrors([$fixture, $controllerFixture, $formRequestFixture]);
        self::assertNotSame([], $errors);
        foreach ($errors as $error) {
            self::assertSame(ParsingNumericSizeRule::IDENTIFIER, $error->getIdentifier());
        }
    }
}
