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

namespace jbboehr\PhpstanLaravelValidation\Rule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Extension\CallArgumentResolver;
use jbboehr\PhpstanLaravelValidation\Validation\InvalidCustomRuleContractException;
use jbboehr\PhpstanLaravelValidation\Validation\Rule as ValidationRule;
use jbboehr\PhpstanLaravelValidation\Validation\RuleSetResolver;
use jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NeverType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\TypeUtils;

/**
 * @implements Rule<Node>
 *
 * @logion [AWC 1:3] Above the northern cliffs a pale orchard flowered beneath
 *     the moon, and its keeper slept beside the gate until the returning cranes
 *     covered the branches like snow.
 */
final class ParsingNumericSizeRule implements Rule
{
    public const IDENTIFIER = 'laravelValidation.parsingNumericSize';

    /** @var list<string> */
    private const SIZE_RULES = ['Between', 'Max', 'Min', 'Size'];

    /** @var list<string> */
    private const NUMERIC_RULES = ['Decimal', 'Integer', 'Numeric'];

    public function __construct(
        private RuleSetResolver $ruleSetResolver,
        private CallArgumentResolver $callArgumentResolver,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function getNodeType(): string
    {
        return Node::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $rulesExpression = $this->resolveRulesExpression($node, $scope);
        if ($rulesExpression === null) {
            return [];
        }

        try {
            $trees = $this->ruleSetResolver->resolve($rulesExpression, $scope);
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable) {
            // This diagnostic must not make a rule expression less analyzable
            // than the return-type extension that owns the actual inference.
            return [];
        }

        /** @var array<string, array{rules: array<string, true>, integerMarker: bool}> $hazards */
        $hazards = [];
        foreach ($trees as $tree) {
            $this->collectHazards($tree, $hazards);
        }

        $errors = [];
        foreach ($hazards as $path => $hazard) {
            $rules = array_keys($hazard['rules']);
            sort($rules);
            $displayRules = array_map(strtolower(...), $rules);
            $ruleDescription = count($displayRules) === 1
                ? sprintf('size rule `%s`', $displayRules[0])
                : sprintf('size rules `%s`', implode('`, `', $displayRules));
            $singleBranch = count($trees) === 1;
            $subject = $singleBranch
                ? sprintf('The rules for `%s` combine', $path)
                : sprintf('A resolved rule branch for `%s` combines', $path);
            $declaration = $singleBranch ? 'but declare no' : 'but declares no';
            $markerDescription = $hazard['integerMarker']
                ? '`integer`, `numeric`, or `decimal`'
                : '`numeric` or `decimal`';
            $tip = $hazard['integerMarker']
                ? 'Add `integer`, `numeric`, or `decimal` for numeric size semantics. Leave the rule list unchanged only if measuring the original representation is intentional.'
                : 'Add `numeric` or an appropriate `decimal` rule for numeric size semantics. Leave the rule list unchanged only if measuring the original representation is intentional.';

            $errors[] = RuleErrorBuilder::message(sprintf(
                '%s a numeric parsing rule with Laravel %s %s %s rule. Laravel therefore measures the original input representation rather than the parsed numeric value.',
                $subject,
                $ruleDescription,
                $declaration,
                $markerDescription
            ))
                ->identifier(self::IDENTIFIER)
                ->line($this->findPathLine($rulesExpression, $path, $scope))
                ->tip($tip)
                ->build();
        }

        return $errors;
    }

    private function resolveRulesExpression(Node $node, Scope $scope): ?Expr
    {
        if ($node instanceof Return_) {
            return $this->isFormRequestRulesReturn($scope) ? $node->expr : null;
        }

        if (!$node instanceof CallLike || $node->isFirstClassCallable()) {
            return null;
        }

        if ($node instanceof FuncCall) {
            if (!$this->isLaravelValidatorFunction($node, $scope)) {
                return null;
            }

            return $this->findArgument($node, 'rules', 1);
        }

        if ($node instanceof StaticCall) {
            if (!$node->name instanceof Node\Identifier
                || !in_array($node->name->toLowerString(), ['make', 'validate'], true)
                || !$node->class instanceof Node\Name
                || $node->class->isSpecialClassName()
                || !$this->isSubtypeOf(
                    new ObjectType($scope->resolveName($node->class)),
                    ValidatorFacade::class
                )
            ) {
                return null;
            }

            return $this->findArgument($node, 'rules', 1);
        }

        if (!$node instanceof MethodCall && !$node instanceof NullsafeMethodCall) {
            return null;
        }
        if (!$node->name instanceof Node\Identifier) {
            return null;
        }

        $method = $node->name->toLowerString();
        $receiverType = $scope->getType($node->var);
        if (in_array($method, ['make', 'validate'], true)
            && $this->isSubtypeOf($receiverType, Factory::class)
        ) {
            return $this->findArgument($node, 'rules', 1);
        }
        if ($method === 'validate' && $this->isSubtypeOf($receiverType, Request::class)) {
            return $this->findArgument($node, 'rules', 0);
        }
        if (!$this->usesValidatesRequests($receiverType)) {
            return null;
        }

        return match ($method) {
            'validate' => $this->findArgument($node, 'rules', 1),
            'validatewith' => $this->findArgument($node, 'validator', 0),
            'validatewithbag' => $this->findArgument($node, 'rules', 2),
            default => null,
        };
    }

    private function findArgument(CallLike $call, string $name, int $position): ?Expr
    {
        return $this->callArgumentResolver->find($call->getArgs(), $name, $position)?->value;
    }

    private function isLaravelValidatorFunction(FuncCall $call, Scope $scope): bool
    {
        if (!$call->name instanceof Node\Name) {
            return false;
        }

        // NameResolver records the namespaced candidate even though PHP falls
        // back to a global function when that candidate does not exist. Check
        // a real namespaced declaration before accepting the global helper.
        $namespacedName = $call->name->getAttribute('namespacedName');
        if (!$namespacedName instanceof Node\Name
            && !$call->name->isFullyQualified()
            && $scope->getNamespace() !== null
        ) {
            $namespacedName = new Node\Name\FullyQualified(
                $scope->getNamespace() . '\\' . $call->name->toString()
            );
        }
        if ($namespacedName instanceof Node\Name
            && $this->reflectionProvider->hasFunction($namespacedName, $scope)
            && strtolower($this->reflectionProvider
                ->getFunction($namespacedName, $scope)
                ->getName()) !== 'validator'
        ) {
            return false;
        }

        return $this->reflectionProvider->hasFunction($call->name, $scope)
            && strtolower($this->reflectionProvider
                ->getFunction($call->name, $scope)
                ->getName()) === 'validator';
    }

    /**
     * @param array<string, array{rules: array<string, true>, integerMarker: bool}> $hazards
     */
    private function collectHazards(RuleTreeNode $node, array &$hazards): void
    {
        $producedType = $node->getProducedType();
        $ruleNames = array_map(
            static fn (ValidationRule $rule): string => $rule->getRuleName(),
            $node->getRules()
        );
        $sizeRules = array_intersect(self::SIZE_RULES, $ruleNames);

        if ($producedType !== null
            && $this->isNumericType($producedType)
            && $sizeRules !== []
            && array_intersect(self::NUMERIC_RULES, $ruleNames) === []
        ) {
            $path = $node->getPath();
            $integerMarker = (new IntegerType())->isSuperTypeOf($producedType)->yes();
            if (!isset($hazards[$path])) {
                $hazards[$path] = [
                    'rules' => [],
                    'integerMarker' => $integerMarker,
                ];
            } else {
                // Advice aggregated across conditional branches must remain
                // valid for every branch. One float-producing branch makes
                // Laravel's integer predicate an inappropriate suggestion.
                $hazards[$path]['integerMarker'] = $hazards[$path]['integerMarker']
                    && $integerMarker;
            }

            foreach ($sizeRules as $sizeRule) {
                $hazards[$path]['rules'][$sizeRule] = true;
            }
        }

        foreach ($node as $child) {
            $this->collectHazards($child, $hazards);
        }
    }

    private function isNumericType(Type $type): bool
    {
        if ($type instanceof NeverType) {
            return false;
        }

        return TypeCombinator::union(new IntegerType(), new FloatType())
            ->isSuperTypeOf($type)
            ->yes();
    }

    private function isFormRequestRulesReturn(Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        $functionReflection = $scope->getFunction();

        return $classReflection !== null
            && $functionReflection instanceof MethodReflection
            && !$scope->isInAnonymousFunction()
            && strtolower($functionReflection->getName()) === 'rules'
            && ($classReflection->getName() === FormRequest::class
                || $classReflection->getAncestorWithClassName(FormRequest::class) !== null);
    }

    /** @param class-string $parentClass */
    private function isSubtypeOf(Type $type, string $parentClass): bool
    {
        return $this->allObjectAlternativesMatch(
            $type,
            static fn ($classReflection): bool => $classReflection->getName() === $parentClass
                || $classReflection->getAncestorWithClassName($parentClass) !== null
        );
    }

    private function usesValidatesRequests(Type $type): bool
    {
        return $this->allObjectAlternativesMatch(
            $type,
            static fn ($classReflection): bool => $classReflection
                ->hasTraitUse(ValidatesRequests::class)
        );
    }

    /**
     * @param callable(\PHPStan\Reflection\ClassReflection): bool $predicate
     */
    private function allObjectAlternativesMatch(Type $type, callable $predicate): bool
    {
        $type = TypeCombinator::removeNull($type);
        if ($type instanceof NeverType) {
            return false;
        }

        $alternatives = TypeUtils::flattenTypes($type);
        if ($alternatives === []) {
            return false;
        }

        foreach ($alternatives as $alternative) {
            $matches = false;
            foreach ($alternative->getObjectClassReflections() as $classReflection) {
                if ($predicate($classReflection)) {
                    $matches = true;
                    break;
                }
            }

            if (!$matches) {
                return false;
            }
        }

        return true;
    }

    private function findPathLine(Expr $expression, string $path, Scope $scope): int
    {
        if (!$expression instanceof Expr\Array_) {
            return $expression->getStartLine();
        }

        foreach ($expression->items as $item) {
            if ($item->unpack || $item->key === null) {
                continue;
            }

            $keys = $scope->getType($item->key)->getConstantScalarValues();
            if (count($keys) !== 1 || (!is_int($keys[0]) && !is_string($keys[0]))) {
                continue;
            }

            if (str_replace('\\.', '.', (string) $keys[0]) === $path) {
                return $item->getStartLine();
            }
        }

        return $expression->getStartLine();
    }
}
