<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Rule;

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
