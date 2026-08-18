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

namespace jbboehr\PhpstanLaravelValidation\Test\Support;

use jbboehr\PhpstanLaravelValidation\Validation\ParsingRuleTypeResolver;
use jbboehr\Rensei\ParsingRule;
use PHPStan\Type\ObjectType;

/**
 * Prepares a live rule array for the analyzer.
 *
 * The runtime cross-check validates a real rule array and then infers from
 * the same array. RuleParser accepts strings and rule descriptions, not live
 * rule objects, and teaching it otherwise would mean giving a static utility
 * a reflection provider, or hard-coding a class-to-type table -- the very
 * per-class branching the generic discovery exists to avoid.
 *
 * Substituting here keeps the derivation mechanical and routes it through the
 * production resolver, so the cross-check still proves that the parser's
 * produced type is discoverable rather than assuming it.
 */
trait SubstitutesParsingRules
{
    /**
     * @param array<mixed, mixed> $rules
     *
     * @return array<mixed, mixed>
     */
    private static function analyzerRules(array $rules): array
    {
        $resolver = new ParsingRuleTypeResolver(self::createReflectionProvider());
        $substitute = static function (mixed $rule) use ($resolver): mixed {
            if (!$rule instanceof ParsingRule) {
                return $rule;
            }

            return $resolver->resolveRule(new ObjectType($rule::class))
                ?? throw new \LogicException(sprintf(
                    'No produced type is discoverable for %s.',
                    $rule::class
                ));
        };

        $result = [];
        foreach ($rules as $attribute => $definition) {
            $result[$attribute] = is_array($definition)
                ? array_map($substitute, $definition)
                : $substitute($definition);
        }

        return $result;
    }
}
