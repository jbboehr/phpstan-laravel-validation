<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Rule;

class RuleTreeLeafNode
{
    protected bool $optional = true;
    protected bool $excluded = false;

    /**
     * @param string $path
     * @param Rule[] $rules
     */
    public function __construct(
        private string $path,
        private array $rules = []
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return Rule[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    public function push(Rule ...$rules): self
    {
        foreach ($rules as $rule) {
            match ($rule->getRuleName()) {
                Rule::RULE_REQUIRED => $this->optional = false,
                Rule::RULE_EXCLUDE => $this->excluded = true,
                Rule::RULE_ACCEPTED_IF,
                Rule::RULE_DECLINED_IF,
                Rule::RULE_EXCLUDE_IF,
                Rule::RULE_EXCLUDE_UNLESS,
                Rule::RULE_EXCLUDE_WITH,
                Rule::RULE_EXCLUDE_WITHOUT,
                Rule::RULE_NULLABLE => $this->optional = true,
                default => null,
            };
            $this->rules[] = $rule;
        }
        return $this;
    }

    public function isExcluded(): bool
    {
        return $this->excluded;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function hasConfirmation(): bool
    {
        foreach ($this->rules as $rule) {
            if ($rule->getRuleName() === 'Confirmed') {
                return true;
            }
        }

        return false;
    }
}
