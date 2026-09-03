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

use jbboehr\PhpstanLaravelValidation\Internal\FormRequestRulesFingerprint;
use jbboehr\PhpstanLaravelValidation\Parser\FormRequestRulesFingerprintVisitor;
use jbboehr\PhpstanLaravelValidation\Rule\FormRequestRulesFingerprintRule;
use PhpParser\Node\Attribute;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Analyser\ScopeFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/** @extends RuleTestCase<FormRequestRulesFingerprintRule> */
final class FormRequestRulesFingerprintRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new FormRequestRulesFingerprintRule();
    }

    public function testRejectsAnExplicitMarkerAttribute(): void
    {
        $this->analyse(
            [__DIR__ . '/fixtures/form-request-rules-fingerprint-attribute.php'],
            [[
                'The FormRequest rules fingerprint attribute is reserved for PHPStan cache invalidation and must not be used.',
                28,
            ]]
        );
    }

    public function testIgnoresSyntheticMarkerAttribute(): void
    {
        $attribute = new Attribute(new FullyQualified(FormRequestRulesFingerprint::class));
        $attribute->setAttribute(FormRequestRulesFingerprintVisitor::SYNTHETIC_ATTRIBUTE, true);
        $scope = self::getContainer()
            ->getByType(ScopeFactory::class)
            ->create(ScopeContext::create(__FILE__));

        self::assertSame([], (new FormRequestRulesFingerprintRule())->processNode($attribute, $scope));
    }
}
