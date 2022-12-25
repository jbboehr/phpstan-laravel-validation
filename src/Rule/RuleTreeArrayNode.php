<?php
declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Rule;

final class RuleTreeArrayNode extends RuleTreeLeafNode
{
    private RuleTreeMapNode $child;

    /**
     * @param string $path
     * @param ?RuleTreeMapNode $child
     * @param array<Rule> $rules
     */
    public function __construct(
        string $path,
        ?RuleTreeMapNode $child = null,
        array $rules = []
    ) {
        parent::__construct($path, $rules);
        $this->child = $child ?? new RuleTreeMapNode($path);
        // @TODO technically, it's only required if *any* of it's children are marked required
        $this->optional = false;
    }

    public function getChild(): RuleTreeMapNode
    {
        return $this->child;
    }

    /**
     * @throws InvalidRuleException
     */
    public function resolvePath(string $key): RuleTreeLeafNode
    {
        return $this->child->resolvePath(substr($key, 2));
    }
}
