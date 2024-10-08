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

use ArrayIterator;
use IteratorAggregate;

/**
 * @implements \IteratorAggregate<int|string, RuleTreeNode>
 */
final class RuleTreeNode implements IteratorAggregate, \Countable
{
    private bool $optional = true;
    private bool $excluded = false;
    private bool $sometimes = false;
    private bool $hasRequiredChild = false;
    private bool $nullable = false;
    private bool $isArray = false;

    /**
     * @param string $path
     * @param array<RuleTreeNode> $children
     * @param array<Rule> $rules
     */
    public function __construct(
        private string $path,
        private array $children = [],
        private array $rules = []
    ) {
    }

    /**
     * @return ArrayIterator<int|string, RuleTreeNode>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->children);
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

    /**
     * @throws InvalidRuleException
     */
    public function insert(string $key, Rule $rule): self
    {
        $leaf = $this->resolvePath($key);
        $leaf->push($rule);
        return $this;
    }

    public function push(Rule ...$rules): self
    {
        foreach ($rules as $rule) {
            match ($rule->getRuleName()) {
                // These imply the node is not optional
                Rule::RULE_REQUIRED => $this->optional = false,

                // This removes the node completely
                Rule::RULE_EXCLUDE => $this->excluded = true,

                // These force the node to be optional
                Rule::RULE_ACCEPTED_IF,
                Rule::RULE_DECLINED_IF,
                Rule::RULE_EXCLUDE_IF,
                Rule::RULE_EXCLUDE_UNLESS,
                Rule::RULE_EXCLUDE_WITH,
                Rule::RULE_EXCLUDE_WITHOUT,
                Rule::RULE_SOMETIMES => $this->sometimes = true,

                Rule::RULE_NULLABLE => $this->nullable = $this->optional = true,

                Rule::RULE_ARRAY => $this->isArray = true,

                default => null,
            };
            $this->rules[] = $rule;
        }
        return $this;
    }

    /**
     * @throws InvalidRuleException
     */
    public function resolvePath(string $key): RuleTreeNode
    {
        $matches = null;
        $pos = false;
        if (preg_match('/[^\\\]\./', $key, $matches, PREG_OFFSET_CAPTURE) > 0) {
            assert(isset($matches[0][1]));
            $pos = $matches[0][1] + 1;
        }

        // simple
        if (false === $pos) {
            $key = str_replace('\.', '.', $key);

            if (isset($this->children[$key])) {
                return $this->children[$key];
            }

            $path = $this->getPath() . ($this->getPath() !== '' ? '.' : '') . $key;
            return $this->children[$key] = new RuleTreeNode($path);
        }

        $subKey = str_replace('\.', '.', substr($key, 0, $pos));
        $remainder = substr($key, $pos + 1);
        $path = $this->getPath() . ($this->getPath() !== '' ? '.' : '') . $subKey;

        if (!isset($this->children[$subKey])) {
            $mapNode = $this->children[$subKey] = new RuleTreeNode($path);
        } else {
            $mapNode = $this->children[$subKey];
        }

        return $mapNode->resolvePath($remainder);
    }

    public function resolveOptional(): bool
    {
        if (count($this->children) <= 0) {
            return $this->optional || $this->sometimes;
        }

        $isRequired = false;
        foreach ($this->children as $child) {
            $isRequired = $isRequired || !$child->resolveOptional();
        }
        $this->hasRequiredChild = $isRequired;
        return $this->isOptional();
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
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

    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function isExcluded(): bool
    {
        return $this->excluded;
    }

    public function isOptional(): bool
    {
        return ($this->optional || $this->sometimes) && !$this->hasRequiredChild;
    }

    public function isWildcard(): bool
    {
        return isset($this->children['*']);
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function count(): int
    {
        return count($this->children);
    }
}
