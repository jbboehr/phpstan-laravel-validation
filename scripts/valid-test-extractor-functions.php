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

use Illuminate\Validation\ValidationRuleParser;
use Illuminate\Validation\Validator;

/**
 * Determine whether rules were added to or otherwise changed on a validator
 * after its source rules were installed by Validator::setRules().
 *
 * The source rule array cannot faithfully represent calls to addRules() or
 * sometimes(). Exporting such a validator would therefore test an incomplete
 * rule set. Re-expand the source rules with Laravel's own parser and compare
 * them with the rules that actually ran.
 */
function validator_rules_were_mutated(Validator $validator): bool
{
    $reflection = new ReflectionClass($validator);
    $initialRulesProperty = $reflection->getProperty('initialRules');
    $initialRulesProperty->setAccessible(true);

    /** @var array<mixed, mixed> $initialRules */
    $initialRules = $initialRulesProperty->getValue($validator);
    $data = $validator->getData();

    $expectedRules = (new ValidationRuleParser($data))->explode(
        ValidationRuleParser::filterConditionalRules($initialRules, $data)
    )->rules;

    return $expectedRules !== $validator->getRules();
}
