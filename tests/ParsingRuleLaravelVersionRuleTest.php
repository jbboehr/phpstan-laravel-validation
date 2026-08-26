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

use jbboehr\PhpstanLaravelValidation\Rule\ParsingRuleLaravelVersionRule;
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use jbboehr\PhpstanLaravelValidation\Validation\ValidationRulesExpressionResolver;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

require_once __DIR__ . '/fixtures/parsing-rule-laravel-version.php';

/** @extends RuleTestCase<ParsingRuleLaravelVersionRule> */
final class ParsingRuleLaravelVersionRuleTest extends RuleTestCase
{
    private string $laravelVersion = '10.6.2';

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

        return new ParsingRuleLaravelVersionRule(
            new LaravelVersionContext('', $this->laravelVersion),
            $container->getByType(RuleSetResolver::class),
            $container->getByType(ValidationRulesExpressionResolver::class)
        );
    }

    public function testReportsParsingRulesBelowTheRuntimeFloor(): void
    {
        $message = 'Parsing rule for `%s` requires laravel/framework >= 10.7.0 '
            . 'because parsed values are written through Validator::setValue(); '
            . 'detected 10.6.2.';
        $tip = 'Upgrade laravel/framework to 10.7.0 or newer, or remove the parsing rule. '
            . 'Ordinary Laravel validation inference remains supported on this version.';

        $this->analyse(
            [__DIR__ . '/fixtures/parsing-rule-laravel-version.php'],
            [
                [sprintf($message, 'age'), 21, $tip],
                [sprintf($message, 'amount'), 22, $tip],
                [sprintf($message, 'count'), 23, $tip],
                [sprintf($message, 'enabled'), 27, $tip],
                [sprintf($message, 'replacement'), 31, $tip],
                [sprintf($message, 'facade_replacement'), 35, $tip],
                [sprintf($message, 'helper_replacement'), 39, $tip],
                [sprintf($message, 'identifier'), 43, $tip],
                [sprintf($message, 'unknown'), 44, $tip],
                [sprintf($message, 'timezone'), 67, $tip],
            ]
        );

        $errors = $this->gatherAnalyserErrors([
            __DIR__ . '/fixtures/parsing-rule-laravel-version.php',
        ]);
        self::assertCount(10, $errors);
        foreach ($errors as $error) {
            self::assertSame(ParsingRuleLaravelVersionRule::IDENTIFIER, $error->getIdentifier());
        }
    }

    public function testStaysSilentAtTheRuntimeFloor(): void
    {
        $this->laravelVersion = '10.7.0';

        $this->analyse([__DIR__ . '/fixtures/parsing-rule-laravel-version.php'], []);
    }

    public function testStaysSilentWhenTheLaravelVersionIsUnknown(): void
    {
        $this->laravelVersion = 'auto';

        $this->analyse([__DIR__ . '/fixtures/parsing-rule-laravel-version.php'], []);
    }
}
