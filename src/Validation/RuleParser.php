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
    public static function parse(
        mixed $rules,
        ?LaravelVersionContext $laravelVersionContext = null
    ): RuleTreeNode {
        $node = new RuleTreeNode('');

        if (!is_array($rules)) {
            return $node;
        }

        $legacyNumericIndex = 0;
        foreach ($rules as $path => $ruleDef) {
            if (is_int($path)) {
                if ($laravelVersionContext === null || !$laravelVersionContext->isSupported()) {
                    // Laravel 10 and 11 reindex top-level numeric rule keys,
                    // while Laravel 12 and later preserve them. Without a
                    // supported version, retain only a conservative unknown
                    // output key and value rather than guessing either shape.
                    $node->resolvePath('*');
                    continue;
                }

                $path = $laravelVersionContext->isAtLeast('12.0.0')
                    ? (string) $path
                    : (string) $legacyNumericIndex++;
            }

            $child = $node->resolvePath($path);
            $child->push(...self::explodeRules($ruleDef, $laravelVersionContext));
        }

        $node->resolveOptional();

        return $node;
    }

    /**
     * @param mixed $rules
     * @return Rule[]
     * @throws InvalidRuleException
     */
    public static function explodeRules(mixed $rules, ?LaravelVersionContext $laravelVersionContext = null): array
    {
        if ($rules instanceof Rule) {
            return [$rules];
        }

        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        if (!is_array($rules)) {
            throw new InvalidRuleException('Invalid rule definition: ' . var_export($rules, true));
        }

        return array_filter(array_map(function ($rule) use ($laravelVersionContext) {
            return self::parseRule($rule, $laravelVersionContext);
        }, $rules));
    }

    /**
     * @throws InvalidRuleException
     */
    public static function parseRule(mixed $rule, ?LaravelVersionContext $laravelVersionContext = null): ?Rule
    {
        if ($rule === null) {
            // Within an array rule list, Laravel normalizes null to an empty
            // rule name and skips it. A direct null rule definition takes a
            // different upstream path and remains invalid here.
            return null;
        } elseif ($rule instanceof Rule) {
            return $rule;
        } elseif (is_array($rule)) {
            return self::parseArrayRule(array_values($rule), $laravelVersionContext);
        } elseif (is_string($rule)) {
            return self::parseStringRule($rule, $laravelVersionContext);
        }

        throw new InvalidRuleException('Invalid rule type: ' . gettype($rule) . ' ' . var_export($rule, true));
    }

    /**
     * @param array<int, mixed> $rule
     * @return Rule
     */
    public static function parseArrayRule(array $rule, ?LaravelVersionContext $laravelVersionContext = null): ?Rule
    {
        if (count($rule) <= 0) {
            return null;
        }

        $ruleName = $rule[0];

        if (!is_string($ruleName)) {
            return null;
        }

        return self::createRule($ruleName, array_slice($rule, 1), $laravelVersionContext);
    }

    public static function parseStringRule(string $rule, ?LaravelVersionContext $laravelVersionContext = null): Rule
    {
        if (str_contains($rule, ':')) {
            [$rule, $parameter] = explode(':', $rule, 2);

            $parameters = match (strtolower($rule)) {
                "regex", "not_regex", "notregex" => [$parameter],
                default => str_getcsv($parameter, ",", '"', "\\"),
            };
        } else {
            $parameters = [];
        }

        return self::createRule($rule, $parameters, $laravelVersionContext);
    }

    /** @param array<int, mixed> $parameters */
    private static function createRule(string $name, array $parameters, ?LaravelVersionContext $laravelVersionContext): Rule
    {
        $normalizedName = self::normalizeName($name, $laravelVersionContext);

        return $normalizedName === null ? Rule::opaque() : Rule::create($normalizedName, $parameters);
    }

    /** Returns null when the framework's whitespace normalization is unknown. */
    public static function normalizeName(string $str, ?LaravelVersionContext $laravelVersionContext = null): ?string
    {
        if (
            ($laravelVersionContext === null || !$laravelVersionContext->hasFrameworkVersion())
            && preg_match('/[^\S ]/u', trim($str)) !== 0
        ) {
            // Supported versions disagree about these word boundaries, so an
            // unknown version cannot establish the normalized rule name. A
            // standalone Validation component can use another Support version.
            return null;
        }

        $str = str_replace(['-', '_'], ' ', trim($str));
        $unicodeWhitespace = $laravelVersionContext !== null && $laravelVersionContext->hasFrameworkVersion() && (
            $laravelVersionContext->isAtLeast('12.21.0')
            || ($laravelVersionContext->isAtLeast('11.45.2') && !$laravelVersionContext->isAtLeast('12.0.0'))
        );

        if ($unicodeWhitespace) {
            // mb_split() leaves U+180E intact; Laravel 13.9 switched to PCRE,
            // whose whitespace class includes it. No mbstring dependency is
            // needed to model either set of separators.
            $pattern = $laravelVersionContext->isAtLeast('13.9.0') ? '/\s+/u' : '/[^\S\x{180e}]+/u';
            $words = preg_split($pattern, $str);
            if ($words === false) {
                $words = [$str];
            }
        } else {
            $words = explode(' ', $str);
        }

        $normalized = implode(array_map(function (string $word) {
            return ucfirst($word);
        }, $words));

        // Laravel rewrites these aliases after normalizing names to StudlyCase.
        return match ($normalized) {
            'Int' => 'Integer',
            'Bool' => 'Boolean',
            default => $normalized,
        };
    }
}
