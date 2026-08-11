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

namespace jbboehr\PhpstanLaravelValidation\Extension;

use Illuminate\Foundation\Http\FormRequest;
use jbboehr\PhpstanLaravelValidation\ShouldNotHappenException;
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestTypeRegistry;
use jbboehr\PhpstanLaravelValidation\Validation\InvalidCustomRuleContractException;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

final class FormRequestValidatedExtension implements DynamicMethodReturnTypeExtension
{
    public function __construct(
        private FormRequestTypeRegistry $typeRegistry
    ) {
    }

    public function getClass(): string
    {
        return FormRequest::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'validated';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): ?Type {
        try {
            $arguments = $this->resolveArguments($methodCall);
            if ($arguments === null) {
                return null;
            }

            $keyType = $arguments['key'] === null
                ? new NullType()
                : $scope->getType($arguments['key']->value);
            $keyTypes = $keyType->getConstantScalarTypes();
            if (!$keyType->isConstantScalarValue()->yes() || $keyTypes === [] || count($keyTypes) > 128) {
                return null;
            }

            $defaultType = $this->resolveDefaultType($arguments['default'], $scope);

            $types = [];
            foreach ($scope->getType($methodCall->var)->getObjectClassReflections() as $classReflection) {
                if (!$classReflection->isSubclassOf(FormRequest::class)) {
                    continue;
                }

                $type = $this->typeRegistry->getType($classReflection);
                if ($type === null) {
                    return null;
                }

                foreach ($keyTypes as $constantKeyType) {
                    $constantKey = $constantKeyType->getValue();
                    if ($constantKey === null) {
                        $types[] = $type;
                        continue;
                    }
                    if (!is_string($constantKey) && !is_int($constantKey)) {
                        return null;
                    }

                    $selectedType = $this->selectValidatedType(
                        $type,
                        (string) $constantKey,
                        $defaultType
                    );
                    if ($selectedType === null) {
                        return null;
                    }
                    $types[] = $selectedType;
                }
            }

            if ($types === []) {
                return null;
            }

            return TypeCombinator::union(...$types);
        } catch (InvalidCustomRuleContractException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ShouldNotHappenException($e->getMessage(), $e);
        }
    }

    /** @return array{key: Arg|null, default: Arg|null}|null */
    private function resolveArguments(MethodCall $methodCall): ?array
    {
        $arguments = ['key' => null, 'default' => null];
        $position = 0;
        foreach ($methodCall->getArgs() as $argument) {
            if ($argument->unpack) {
                return null;
            }

            if ($argument->name !== null) {
                $name = strtolower($argument->name->toString());
                if (!array_key_exists($name, $arguments) || $arguments[$name] !== null) {
                    return null;
                }
                $arguments[$name] = $argument;
                continue;
            }

            while ($position < 2 && $arguments[$position === 0 ? 'key' : 'default'] !== null) {
                ++$position;
            }
            if ($position >= 2) {
                return null;
            }

            $arguments[$position === 0 ? 'key' : 'default'] = $argument;
            ++$position;
        }

        return $arguments;
    }

    private function resolveDefaultType(?Arg $default, Scope $scope): Type
    {
        if ($default === null) {
            return new NullType();
        }

        $type = $scope->getType($default->value);
        $closureType = new ObjectType(\Closure::class);
        if (!$closureType->isSuperTypeOf($type)->no()) {
            return new MixedType();
        }

        return $type;
    }

    private function selectValidatedType(Type $payloadType, string $key, Type $defaultType): ?Type
    {
        $segments = explode('.', $key);
        $type = $payloadType;
        $mayUseDefault = false;

        foreach ($segments as $segment) {
            if (in_array($segment, ['*', '\\*', '{first}', '\\{first}', '{last}', '\\{last}'], true)) {
                return null;
            }

            if ($type->isArray()->no() && $type->isObject()->no()) {
                return $defaultType;
            }
            if (!$type->isArray()->yes()) {
                // data_get() traverses arrays and ArrayAccess objects, but not
                // PHP strings even though PHPStan models string offsets. Keep
                // unions and object-property traversal broad rather than
                // confusing native offset access with Laravel's contract.
                return null;
            }

            $offsetType = new ConstantStringType($segment);
            $hasOffset = $type->hasOffsetValueType($offsetType);
            if ($hasOffset->no()) {
                return $defaultType;
            }
            if ($hasOffset->maybe()) {
                $mayUseDefault = true;
            }

            $type = $type->getOffsetValueType($offsetType);
        }

        return $mayUseDefault
            ? TypeCombinator::union($type, $defaultType)
            : $type;
    }
}
