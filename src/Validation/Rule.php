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

final class Rule
{
    public const RULE_ACCEPTED_IF = "AcceptedIf";
    public const RULE_DECLINED_IF = "DeclinedIf";
    public const RULE_REQUIRED = "Required";
    public const RULE_EXCLUDE = "Exclude";
    public const RULE_EXCLUDE_IF = "ExcludeIf";
    public const RULE_EXCLUDE_UNLESS = "ExcludeUnless";
    public const RULE_EXCLUDE_WITH = "ExcludeWith";
    public const RULE_EXCLUDE_WITHOUT = "ExcludeWithout";
    public const RULE_NULLABLE = "Nullable";
    public const RULE_SOMETIMES = "Sometimes";
    public const RULE_ARRAY = "Array";
    public const RULE_NUMERIC = "Numeric";
    public const RULE_PHPSTANTYPE = "PHPStanType";

    public const RULES = [
        self::RULE_ACCEPTED_IF => self::RULE_ACCEPTED_IF,
        self::RULE_DECLINED_IF => self::RULE_DECLINED_IF,
        self::RULE_REQUIRED => self::RULE_REQUIRED,
        self::RULE_EXCLUDE => self::RULE_EXCLUDE,
        self::RULE_EXCLUDE_IF => self::RULE_EXCLUDE_IF,
        self::RULE_EXCLUDE_UNLESS => self::RULE_EXCLUDE_UNLESS,
        self::RULE_EXCLUDE_WITH => self::RULE_EXCLUDE_WITH,
        self::RULE_EXCLUDE_WITHOUT => self::RULE_EXCLUDE_WITHOUT,
        self::RULE_NULLABLE => self::RULE_NULLABLE,
        self::RULE_SOMETIMES => self::RULE_SOMETIMES,
        self::RULE_ARRAY => self::RULE_ARRAY,
        self::RULE_NUMERIC => self::RULE_NUMERIC,
    ];

    /**
     * @param string $ruleName
     * @param array<int, mixed> $parameters
     * @return static
     */
    public static function create(
        string $ruleName,
        array $parameters = []
    ): self {
        return new self($ruleName, $parameters);
    }

    /**
     * @param string $ruleName
     * @param array<int, mixed> $parameters
     */
    public function __construct(
        private string $ruleName,
        private array $parameters = []
    ) {
    }

    public function getRuleName(): string
    {
        return $this->ruleName;
    }

    /**
     * @return array<int, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
