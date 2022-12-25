<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Rule;

use ArrayIterator;
use IteratorAggregate;

/**
 * @implements \IteratorAggregate<string, RuleTreeMapNode|RuleTreeArrayNode|RuleTreeLeafNode>
 */
final class RuleTreeMapNode extends RuleTreeLeafNode implements IteratorAggregate
{
    /**
     * @var array<string, RuleTreeMapNode|RuleTreeArrayNode|RuleTreeLeafNode>
     */
    private array $children;

    /**
     * @param string $path
     * @param array<RuleTreeMapNode|RuleTreeArrayNode|RuleTreeLeafNode> $children
     * @param array<Rule> $rules
     */
    public function __construct(
        string $path,
        array $children = [],
        array $rules = []
    ) {
        parent::__construct($path, $rules);
        $this->children = $children;
        // @TODO technically, it's only required if *any* of it's children are marked required
        $this->optional = false;
    }

    /**
     * @return ArrayIterator<string, RuleTreeMapNode|RuleTreeArrayNode|RuleTreeLeafNode>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->children);
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

    /**
     * @throws InvalidRuleException
     */
    public function resolvePath(string $key): RuleTreeLeafNode
    {
        $path = $this->getPath() . ($this->getPath() ? '.' : '') . $key;
        $pos = strpos($key, '.');

        // simple
        if (false === $pos) {
            if (isset($this->children[$key])) {
                return $this->children[$key];
            }

            return $this->children[$key] = new RuleTreeLeafNode($path);
        }

        $subKey = substr($key, 0, $pos);
        $remainder = substr($key, $pos + 1);
        $isNextArray = strlen($remainder) >= 3 && $remainder[0] === '*' && $remainder[1] === '.';

        // array
        if ($isNextArray) {
            if (!isset($this->children[$subKey])) {
                $arrayNode = $this->children[$subKey] = new RuleTreeArrayNode($subKey);
            } else {
                $arrayNode = $this->children[$subKey];
            }

            if (!($arrayNode instanceof RuleTreeArrayNode)) {
                throw new InvalidRuleException('Cannot have both string and integer keys');
            }

            return $arrayNode->resolvePath($remainder);
        }

        // map
        if (!isset($this->children[$subKey])) {
            $mapNode = $this->children[$subKey] = new RuleTreeMapNode($subKey);
        } else {
            $mapNode = new RuleTreeMapNode($remainder);
        }

        if (!($mapNode instanceof RuleTreeMapNode)) {
            throw new InvalidRuleException('Cannot have both string and integer keys');
        }

        return $mapNode->resolvePath($remainder);
    }
}
