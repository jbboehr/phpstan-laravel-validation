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

namespace jbboehr\PhpstanLaravelValidation\Validation;

final class RuleParser
{
    /**
     * @param mixed $rules
     * @return RuleTreeNode
     * @throws InvalidRuleException
     */
    public static function parse(mixed $rules): RuleTreeNode
    {
        $node = new RuleTreeNode('');

        if (!is_array($rules)) {
            return $node;
        }

        foreach ($rules as $path => $ruleDef) {
            if (!is_string($path)) {
                throw new InvalidRuleException("Invalid rule path: " . var_export($path, true));
            }

            $child = $node->resolvePath($path);
            $child->push(...self::explodeRules($ruleDef));
        }

        $node->resolveOptional();

        return $node;
    }

    /**
     * @param mixed $rules
     * @return Rule[]
     * @throws InvalidRuleException
     */
    public static function explodeRules(mixed $rules): array
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        if (!is_array($rules)) {
            throw new InvalidRuleException('Invalid rule definition: ' . var_export($rules, true));
        }

        return array_filter(array_map(function ($rule) {
            return self::parseRule($rule);
        }, $rules));
    }

    /**
     * @throws InvalidRuleException
     */
    public static function parseRule(mixed $rule): ?Rule
    {
        if (is_array($rule)) {
            return self::parseArrayRule(array_values($rule));
        } elseif (is_string($rule)) {
            return self::parseStringRule($rule);
        }

        throw new InvalidRuleException('Invalid rule type: ' . gettype($rule) . ' ' . var_export($rule, true));
    }

    /**
     * @param array<int, mixed> $rule
     * @return Rule
     */
    public static function parseArrayRule(array $rule): ?Rule
    {
        if (count($rule) <= 0) {
            return null;
        }

        $ruleName = $rule[0];

        if (!is_string($ruleName)) {
            return null;
        }

        return Rule::create(self::normalizeName($ruleName), array_slice($rule, 1));
    }

    /**
     * @psalm-suppress PossiblyUndefinedArrayOffset
     */
    public static function parseStringRule(string $rule): Rule
    {
        if (str_contains($rule, ':')) {
            [$rule, $parameter] = explode(':', $rule, 2);

            $parameters = match (strtolower($rule)) {
                "regex", "not_regex", "notregex" => [$parameter],
                default => str_getcsv($parameter),
            };
        } else {
            $parameters = [];
        }

        return Rule::create(self::normalizeName($rule), $parameters);
    }

    public static function normalizeName(string $str): string
    {
        return implode(array_map(function (string $word) {
            return ucfirst($word);
        }, explode(' ', str_replace(['-', '_'], ' ', $str))));
    }
}
