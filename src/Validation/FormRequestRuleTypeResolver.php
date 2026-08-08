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

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Analyser\ScopeFactory;
use PHPStan\Parser\Parser;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\Generic\TemplateTypeMap;

final class FormRequestRuleTypeResolver
{
    /** @var array<string, Type|null> */
    private array $types = [];

    /** @var array<string, true> */
    private array $resolving = [];

    public function __construct(
        private Parser $parser,
        private ScopeFactory $scopeFactory,
        private RuleSetResolver $ruleSetResolver,
        private TypeResolver $typeResolver,
        private bool $assumeHttpInputNormalization
    ) {
    }

    public function resolve(ClassReflection $classReflection): ?Type
    {
        $className = $classReflection->getName();
        if (array_key_exists($className, $this->types)) {
            return $this->types[$className];
        }
        if (isset($this->resolving[$className])) {
            return null;
        }

        $this->resolving[$className] = true;
        try {
            return $this->types[$className] = $this->resolveUncached($classReflection);
        } finally {
            unset($this->resolving[$className]);
        }
    }

    private function resolveUncached(ClassReflection $classReflection): ?Type
    {
        $nativeClass = $classReflection->getNativeReflection();
        if (!$nativeClass->hasMethod('rules')) {
            return null;
        }

        $method = $nativeClass->getMethod('rules');
        $fileName = $method->getFileName();
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();
        if (!is_string($fileName) || !is_int($startLine) || !is_int($endLine)) {
            return null;
        }

        try {
            $nodes = $this->parser->parseFile($fileName);
        } catch (\Throwable) {
            return null;
        }

        $classMethod = $this->findClassMethod(
            $nodes,
            $method->getName(),
            $startLine,
            $endLine
        );
        if ($classMethod === null) {
            return null;
        }

        $declaringClassName = $method->getDeclaringClass()->getName();
        $analysisClass = $declaringClassName === $classReflection->getName()
            ? $classReflection
            : $classReflection->getAncestorWithClassName($declaringClassName);
        if ($analysisClass === null) {
            return null;
        }
        if ($analysisClass !== $classReflection && $this->containsLateBoundReference($classMethod)) {
            return null;
        }

        $scope = $this->scopeFactory
            ->create(ScopeContext::create($fileName))
            ->enterClass($analysisClass)
            ->enterClassMethod(
                $classMethod,
                TemplateTypeMap::createEmpty(),
                [],
                null,
                null,
                null,
                false,
                false,
                false
            );

        $returnNodes = $this->collectReturnNodes($classMethod->stmts ?? []);
        $types = [];

        try {
            foreach ($returnNodes as $returnNode) {
                if ($returnNode->expr === null) {
                    return null;
                }

                $ruleTrees = $this->ruleSetResolver->resolve($returnNode->expr, $scope);
                if ($ruleTrees === []) {
                    return null;
                }

                foreach ($ruleTrees as $ruleTree) {
                    $types[] = $this->typeResolver->evaluate(
                        $ruleTree,
                        $this->assumeHttpInputNormalization
                    );
                }
            }
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable) {
            return null;
        }

        if ($types === []) {
            return null;
        }

        return TypeCombinator::union(...$types);
    }

    /** @param array<Node> $nodes */
    private function findClassMethod(
        array $nodes,
        string $methodName,
        int $startLine,
        int $endLine
    ): ?ClassMethod {
        $node = (new NodeFinder())->findFirst(
            $nodes,
            static fn (Node $node): bool => $node instanceof ClassMethod
                && strcasecmp($node->name->toString(), $methodName) === 0
                && $node->getStartLine() <= $startLine
                && $node->getEndLine() === $endLine
        );

        return $node instanceof ClassMethod ? $node : null;
    }

    /**
     * @param array<Node> $nodes
     * @return list<Return_>
     */
    private function collectReturnNodes(array $nodes): array
    {
        $visitor = new class () extends NodeVisitorAbstract {
            private int $nestedFunctionDepth = 0;

            /** @var list<Return_> */
            private array $returnNodes = [];

            /** @return null */
            public function enterNode(Node $node)
            {
                if ($node instanceof FunctionLike) {
                    ++$this->nestedFunctionDepth;
                } elseif ($node instanceof Return_ && $this->nestedFunctionDepth === 0) {
                    $this->returnNodes[] = $node;
                }

                return null;
            }

            /** @return null */
            public function leaveNode(Node $node)
            {
                if ($node instanceof FunctionLike) {
                    --$this->nestedFunctionDepth;
                }

                return null;
            }

            /** @return list<Return_> */
            public function getReturnNodes(): array
            {
                return $this->returnNodes;
            }
        };

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);
        $traverser->traverse($nodes);

        return $visitor->getReturnNodes();
    }

    private function containsLateBoundReference(ClassMethod $classMethod): bool
    {
        return (new NodeFinder())->findFirst(
            $classMethod->stmts ?? [],
            static function (Node $node): bool {
                if (!$node instanceof ClassConstFetch
                    && !$node instanceof StaticCall
                    && !$node instanceof StaticPropertyFetch
                ) {
                    return false;
                }

                if ($node->class instanceof Name) {
                    return strtolower($node->class->toString()) === 'static';
                }

                return $node->class instanceof Variable
                    && $node->class->name === 'this';
            }
        ) !== null;
    }
}
