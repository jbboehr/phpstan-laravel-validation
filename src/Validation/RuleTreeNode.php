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
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

/**
 * @implements \IteratorAggregate<int|string, RuleTreeNode>
 */
final class RuleTreeNode implements IteratorAggregate, \Countable
{
    private bool $optional = true;
    private bool $excluded = false;
    private bool $sometimes = false;
    private bool $hasRequiredChild = false;
    private bool $missing = false;
    private bool $nullable = false;
    private bool $present = false;
    private bool $required = false;
    private bool $isArray = false;
    private bool $opaque = false;

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
            if (in_array(
                $rule->getRuleName(),
                [Rule::RULE_ACCEPTED, Rule::RULE_DECLINED, Rule::RULE_REQUIRED],
                true
            )) {
                $this->required = true;
            }

            if ($rule->getRuleName() === Rule::RULE_PRESENT) {
                $this->present = true;
            }

            match ($rule->getRuleName()) {
                // These imply the node is not optional
                Rule::RULE_ACCEPTED,
                Rule::RULE_DECLINED,
                Rule::RULE_PRESENT,
                Rule::RULE_REQUIRED => $this->optional = false,

                // This removes the node completely
                Rule::RULE_EXCLUDE => $this->excluded = true,

                // These force the node to be optional
                Rule::RULE_EXCLUDE_IF,
                Rule::RULE_EXCLUDE_UNLESS,
                Rule::RULE_EXCLUDE_WITH,
                Rule::RULE_EXCLUDE_WITHOUT,
                Rule::RULE_SOMETIMES => $this->sometimes = true,

                // A successful unconditional missing rule guarantees that
                // this path is absent from validated output.
                Rule::RULE_MISSING => $this->missing = true,

                // Nullable lets an otherwise optional value be null, but it
                // does not override an unconditional required rule.
                Rule::RULE_NULLABLE => $this->nullable = true,

                Rule::RULE_ARRAY => $this->isArray = true,

                Rule::RULE_OPAQUE => $this->opaque = true,

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
        foreach ($this->children as $key => $child) {
            $childOptional = $child->resolveOptional();
            // A required wildcard applies only to elements that exist; it does
            // not require the collection itself to be present.
            if ($key !== '*') {
                $isRequired = $isRequired || !$childOptional;
            }
        }
        $this->hasRequiredChild = $isRequired;
        return $this->isOptional();
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function hasBareArrayRule(): bool
    {
        return $this->hasBareRule(Rule::RULE_ARRAY);
    }

    public function hasBareListRule(): bool
    {
        return $this->hasBareRule(Rule::RULE_LIST);
    }

    private function hasBareRule(string $ruleName): bool
    {
        foreach ($this->rules as $rule) {
            if ($rule->getRuleName() === $ruleName && $rule->getParameters() === []) {
                return true;
            }
        }

        return false;
    }

    public function isExcluded(): bool
    {
        return $this->excluded;
    }

    public function isMissing(): bool
    {
        return $this->missing;
    }

    public function isOpaque(): bool
    {
        return $this->opaque;
    }

    public function isOptional(): bool
    {
        return $this->opaque || (($this->optional || $this->sometimes) && !$this->hasRequiredChild);
    }

    public function isWildcard(): bool
    {
        return isset($this->children['*']);
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function allowsNull(): bool
    {
        return $this->nullable && !$this->required;
    }

    public function requiresNonBlankValue(): bool
    {
        return $this->required;
    }

    /**
     * The type a parsing rule on this node produces, if any.
     *
     * Two parsing rules on one attribute is a union rather than a choice: a
     * value has to satisfy both to validate at all, and the attribute then
     * holds whichever wrote back first -- the second finds a value that is no
     * longer what it parsed and skips. Which one that is cannot be read off
     * this node: write-backs run in the order the rule instances registered
     * them, across the whole validator, so with `['x' => [A, B],
     * 'y' => [B, A]]` instance A wins both attributes. The union covers
     * either outcome, and collapses when the two agree.
     *
     * Rejecting two parsers on one attribute outright is the better answer
     * and is deferred with the other diagnostics.
     */
    public function getProducedType(): ?Type
    {
        $types = [];

        foreach ($this->rules as $rule) {
            $produced = $rule->getProducedType();
            if ($rule->getRuleName() === Rule::RULE_PARSE && $produced !== null) {
                $types[] = $produced;
            }
        }

        return $types === [] ? null : TypeCombinator::union(...$types);
    }

    public function hasParsingRule(): bool
    {
        foreach ($this->rules as $rule) {
            if ($rule->getRuleName() === Rule::RULE_PARSE) {
                return true;
            }
        }

        return false;
    }

    /**
     * Laravel skips non-implicit rules for blank strings. Since PHPStan has no
     * whitespace-only string type, a node that permits this bypass must include
     * the general string type in its result.
     */
    public function allowsBlankStringBypass(): bool
    {
        // A present rule requires the key but does not make non-implicit rules
        // run for blank strings. Other causes of non-optionality retain the
        // existing non-blank behavior.
        if (!$this->isOptional() && !$this->present) {
            return false;
        }

        foreach ($this->rules as $rule) {
            if (
                in_array($rule->getRuleName(), [
                Rule::RULE_ACCEPTED,
                Rule::RULE_DECLINED,
                Rule::RULE_FILLED,
                Rule::RULE_MISSING,
                Rule::RULE_PARSE,
                Rule::RULE_REQUIRED,
                ], true)
            ) {
                return false;
            }
        }

        return true;
    }

    public function count(): int
    {
        return count($this->children);
    }

    public function getCacheKey(): string
    {
        $children = [];
        foreach ($this->children as $key => $child) {
            $children[] = [get_debug_type($key), $key, $child->getCacheKey()];
        }

        return hash('sha256', serialize([
            $this->path,
            array_map(static fn (Rule $rule): string => $rule->getCacheKey(), $this->rules),
            $children,
            $this->optional,
            $this->excluded,
            $this->sometimes,
            $this->hasRequiredChild,
            $this->missing,
            $this->nullable,
            $this->present,
            $this->required,
            $this->isArray,
            $this->opaque,
        ]));
    }
}
