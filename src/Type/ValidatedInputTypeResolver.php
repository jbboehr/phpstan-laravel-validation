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

namespace jbboehr\PhpstanLaravelValidation\Type;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\ValidatedInput;
use jbboehr\PhpstanLaravelValidation\Extension\CallArgumentResolver;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestTypeRegistry;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;

/**
 * @logion [SFA 1:1] Beyond the rain-dark colonnade, seven lamps burned before
 * the silent court.
 */
final class ValidatedInputTypeResolver
{
    private const MAX_PATHS = 128;

    public function __construct(
        private FormRequestTypeRegistry $formRequestTypeRegistry,
        private CallArgumentResolver $callArgumentResolver
    ) {
    }

    public function resolveSafeReturnType(
        Type $receiverType,
        MethodCall $methodCall,
        Scope $scope
    ): ?Type {
        $payloadType = $this->resolvePayloadType($receiverType);
        if ($payloadType === null || $methodCall->isFirstClassCallable()) {
            return null;
        }

        $arguments = $methodCall->getArgs();
        if ($arguments === []) {
            return new ObjectType(ValidatedInput::class);
        }
        if (count($arguments) !== 1 || $arguments[0]->unpack) {
            return null;
        }
        if ($this->callArgumentResolver->expressionMayChangeEvaluationState($arguments[0]->value)) {
            return null;
        }

        $keysType = $scope->getType($arguments[0]->value);
        return $this->resolveSafeKeysType($payloadType, $keysType);
    }

    public function resolveDirectSafePayload(MethodCall $safeCall, Scope $scope): ?Type
    {
        if (
            !$safeCall->name instanceof Identifier
            || strtolower($safeCall->name->toString()) !== 'safe'
            || $safeCall->isFirstClassCallable()
        ) {
            return null;
        }

        $arguments = $safeCall->getArgs();
        if ($arguments !== []) {
            if (count($arguments) !== 1 || $arguments[0]->unpack) {
                return null;
            }
            if (
                $this->callArgumentResolver->expressionMayChangeEvaluationState($arguments[0]->value)
                || !$scope->getType($arguments[0]->value)->isNull()->yes()
            ) {
                return null;
            }
        }

        return $this->resolvePayloadType($scope->getType($safeCall->var));
    }

    public function resolveOnlyReturnType(
        Type $payloadType,
        MethodCall $methodCall,
        Scope $scope
    ): ?Type {
        if ($methodCall->isFirstClassCallable()) {
            return null;
        }

        $arguments = $methodCall->getArgs();
        if ($arguments === [] || $arguments[0]->unpack) {
            return null;
        }

        foreach ($arguments as $argument) {
            if (
                $argument->unpack
                || $this->callArgumentResolver->expressionMayChangeEvaluationState($argument->value)
            ) {
                return null;
            }
        }

        $firstType = $scope->getType($arguments[0]->value);
        if ($firstType->isArray()->yes()) {
            if (count($arguments) !== 1) {
                // ValidatedInput::only() ignores later arguments when its first
                // argument is an array. Declining keeps unusual calls obvious.
                return null;
            }

            return $this->resolveSafeKeysType($payloadType, $firstType, false);
        }

        $paths = [];
        foreach ($arguments as $argument) {
            $pathTypes = $scope->getType($argument->value)->getConstantScalarTypes();
            if (count($pathTypes) !== 1) {
                return null;
            }

            $path = $pathTypes[0]->getValue();
            if (!is_string($path) && !is_int($path)) {
                return null;
            }

            $paths[] = ['path' => (string) $path, 'required' => true];
        }

        return $this->projectOnly($payloadType, $paths);
    }

    private function resolvePayloadType(Type $receiverType): ?Type
    {
        $payloadTypes = [];
        foreach ($receiverType->getObjectClassReflections() as $classReflection) {
            if (
                $classReflection->getName() !== FormRequest::class
                && !$classReflection->isSubclassOf(FormRequest::class)
            ) {
                return null;
            }
            if (
                !$classReflection->hasNativeMethod('safe')
                || $classReflection->getNativeMethod('safe')->getDeclaringClass()->getName()
                    !== FormRequest::class
            ) {
                return null;
            }

            $payloadType = $this->formRequestTypeRegistry->getType($classReflection);
            if ($payloadType === null) {
                return null;
            }

            $payloadTypes[] = $payloadType;
        }

        return $payloadTypes === [] ? null : TypeCombinator::union(...$payloadTypes);
    }

    private function resolveSafeKeysType(
        Type $payloadType,
        Type $keysType,
        bool $allowNull = true
    ): ?Type {
        $types = $keysType instanceof UnionType ? $keysType->getTypes() : [$keysType];
        $returnTypes = [];

        foreach ($types as $type) {
            if ($allowNull && $type->isNull()->yes()) {
                $returnTypes[] = new ObjectType(ValidatedInput::class);
                continue;
            }
            if (!$type->isConstantArray()->yes()) {
                return null;
            }

            $constantArrays = $type->getConstantArrays();
            if ($constantArrays === []) {
                return null;
            }

            foreach ($constantArrays as $constantArray) {
                $paths = $this->extractPaths($constantArray);
                if ($paths === null) {
                    return null;
                }

                $projectedType = $this->projectOnly($payloadType, $paths);
                if ($projectedType === null) {
                    return null;
                }

                $returnTypes[] = $projectedType;
            }
        }

        return $returnTypes === [] ? null : TypeCombinator::union(...$returnTypes);
    }

    /**
     * @return list<array{path: string, required: bool}>|null
     */
    private function extractPaths(ConstantArrayType $keys): ?array
    {
        $paths = [];
        foreach ($keys->getValueTypes() as $index => $valueType) {
            $pathTypes = $valueType->getConstantScalarTypes();
            if (
                !$valueType->isConstantScalarValue()->yes()
                || $pathTypes === []
                || count($paths) + count($pathTypes) > self::MAX_PATHS
            ) {
                return null;
            }

            foreach ($pathTypes as $pathType) {
                $path = $pathType->getValue();
                if (!is_string($path) && !is_int($path)) {
                    return null;
                }

                $paths[] = [
                    'path' => (string) $path,
                    'required' => !$keys->isOptionalKey($index) && count($pathTypes) === 1,
                ];
            }
        }

        return $paths;
    }

    /**
     * @param list<array{path: string, required: bool}> $paths
     */
    private function projectOnly(Type $payloadType, array $paths): ?Type
    {
        $payloadTypes = $payloadType instanceof UnionType
            ? $payloadType->getTypes()
            : [$payloadType];
        $projectedTypes = [];

        foreach ($payloadTypes as $type) {
            $root = new ValidatedInputProjectionNode();

            foreach ($paths as $path) {
                $segments = explode('.', $path['path']);
                if (array_intersect(
                    $segments,
                    ['*', '\\*', '{first}', '\\{first}', '{last}', '\\{last}']
                ) !== []) {
                    return null;
                }

                $selectedType = $type;
                $required = $path['required'];
                foreach ($segments as $segment) {
                    if (!$selectedType->isArray()->yes()) {
                        return null;
                    }

                    $offsetType = new ConstantStringType($segment);
                    $hasOffset = $selectedType->hasOffsetValueType($offsetType);
                    if ($hasOffset->no()) {
                        continue 2;
                    }
                    if (!$hasOffset->yes()) {
                        $required = false;
                    }

                    $selectedType = $selectedType->getOffsetValueType($offsetType);
                }

                $this->addProjection($root, $segments, $selectedType, $required);
            }

            $projectedTypes[] = $this->buildProjection($root);
        }

        return $projectedTypes === [] ? null : TypeCombinator::union(...$projectedTypes);
    }

    /**
     * @param non-empty-list<string> $segments
     */
    private function addProjection(
        ValidatedInputProjectionNode $root,
        array $segments,
        Type $valueType,
        bool $required
    ): void {
        $node = $root;
        foreach ($segments as $segment) {
            if (!isset($node->children[$segment])) {
                $node->children[$segment] = new ValidatedInputProjectionNode();
            }

            $child = $node->children[$segment];
            $child->required = $child->required || $required;
            $node = $child;
        }

        $node->value = $node->value === null
            ? $valueType
            : TypeCombinator::union($node->value, $valueType);
    }

    private function buildProjection(ValidatedInputProjectionNode $node): Type
    {
        if ($node->value !== null) {
            return $node->value;
        }

        $builder = ConstantArrayTypeBuilder::createEmpty();
        foreach ($node->children as $key => $child) {
            $builder->setOffsetValueType(
                is_int($key)
                    ? new ConstantIntegerType($key)
                    : new ConstantStringType($key),
                $this->buildProjection($child),
                !$child->required
            );
        }

        return $builder->getArray();
    }
}
