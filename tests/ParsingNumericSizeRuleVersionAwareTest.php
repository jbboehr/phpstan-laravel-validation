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

use jbboehr\PhpstanLaravelValidation\Rule\ParsingNumericSizeRule;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use jbboehr\PhpstanLaravelValidation\Validation\ValidationRulesExpressionResolver;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/** @extends RuleTestCase<ParsingNumericSizeRule> */
final class ParsingNumericSizeRuleVersionAwareTest extends RuleTestCase
{
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../extension.neon',
            __DIR__ . '/version-aware/parsing-numeric-size.neon',
        ];
    }

    protected function getRule(): Rule
    {
        $container = self::getContainer();

        return new ParsingNumericSizeRule(
            $container->getByType(RuleSetResolver::class),
            $container->getByType(ValidationRulesExpressionResolver::class)
        );
    }

    public function testRecognizesNumericBuildersAtTheirSupportedVersion(): void
    {
        $message = 'The rules for `hazard` combine a numeric parsing rule with '
            . 'Laravel size rule `min` but declare no `integer`, `numeric`, or '
            . '`decimal` rule. Laravel therefore measures the original input '
            . 'representation rather than the parsed numeric value.';
        $tip = 'Add `integer`, `numeric`, or `decimal` for numeric size semantics. '
            . 'Leave the rule list unchanged only if measuring the original representation is intentional.';

        $this->analyse(
            [__DIR__ . '/version-aware/parsing-numeric-size.php'],
            [[$message, 13, $tip]]
        );
    }
}
