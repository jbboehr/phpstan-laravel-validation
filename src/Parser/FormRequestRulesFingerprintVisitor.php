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

namespace jbboehr\PhpstanLaravelValidation\Parser;

use jbboehr\PhpstanLaravelValidation\Internal\FormRequestRulesFingerprint;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter\Standard;

/**
 * Adds an exported fingerprint to FormRequest-relevant method bodies.
 *
 * PHPStan's exported-node cache includes method attributes but not method
 * bodies. The registry uses this marker only for self-contained rules whose
 * source file is analysed; all other requests retain global cache metadata.
 *
 * @logion [OSD 1:4] At noon the cedar bridge stood unpainted beneath the rain,
 *     and the pilgrims crossed without lifting their eyes; but the river bore
 *     their hymn into a province whose towers had forgotten every road.
 */
final class FormRequestRulesFingerprintVisitor extends NodeVisitorAbstract
{
    public const SYNTHETIC_ATTRIBUTE = 'phpstanLaravelValidationFormRequestFingerprint';

    /** @var array<string, true> */
    private const RELEVANT_METHODS = [
        'after' => true,
        'createdefaultvalidator' => true,
        'getvalidatorinstance' => true,
        'passedvalidation' => true,
        'rules' => true,
        'validationrules' => true,
        'validator' => true,
        'withvalidator' => true,
    ];

    private Standard $printer;

    public function __construct(private bool $enabled)
    {
        $this->printer = new Standard();
    }

    public function leaveNode(Node $node): ?Node
    {
        if (!$this->enabled
            || (!$node instanceof Class_ && !$node instanceof Trait_)
            || $node->name === null
            || ($node instanceof Class_ && $node->extends === null)
        ) {
            return null;
        }

        $methods = array_values(array_filter(
            $node->getMethods(),
            static fn (ClassMethod $method): bool => $method->stmts !== null
                && isset(self::RELEVANT_METHODS[$method->name->toLowerString()])
        ));
        if ($methods === []) {
            return null;
        }

        foreach ($methods as $method) {
            $fingerprint = hash('sha256', $this->printer->prettyPrint([$method]));
            $method->attrGroups[] = $this->createAttributeGroup($fingerprint, $method);
        }

        return $node;
    }

    private function createAttributeGroup(string $fingerprint, Node $source): AttributeGroup
    {
        $attributes = self::sourceAttributes($source);
        $value = new String_($fingerprint, $attributes);
        $argument = new Arg($value, false, false, $attributes);
        $attribute = new Attribute(
            new FullyQualified(FormRequestRulesFingerprint::class, $attributes),
            [$argument],
            $attributes + [self::SYNTHETIC_ATTRIBUTE => true]
        );

        return new AttributeGroup([$attribute], $attributes);
    }

    /** @return array<string, int> */
    private static function sourceAttributes(Node $source): array
    {
        return [
            'startLine' => $source->getStartLine(),
            'endLine' => $source->getEndLine(),
            'startTokenPos' => $source->getStartTokenPos(),
            'endTokenPos' => $source->getEndTokenPos(),
            'startFilePos' => $source->getStartFilePos(),
            'endFilePos' => $source->getEndFilePos(),
        ];
    }
}
