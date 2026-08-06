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

use jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode;
use PHPStan\Type;
use PHPStan\Type\AcceptsResult;
use PHPStan\Type\CompoundType;
use PHPStan\Type\IsSuperTypeOfResult;
use PHPStan\Type\ObjectType;

final class ValidatorType extends ObjectType
{
    private string $rulesCacheKey;

    public function __construct(
        private RuleTreeNode $validatorRules,
        ?Type\Type $subtractedType = null
    ) {
        $this->rulesCacheKey = hash('sha256', serialize($validatorRules));

        parent::__construct(\Illuminate\Validation\Validator::class, $subtractedType);
    }

    public function getValidatorRules(): RuleTreeNode
    {
        return $this->validatorRules;
    }

    public function accepts(Type\Type $type, bool $strictTypes): AcceptsResult
    {
        if ($type instanceof self) {
            if ($this->rulesCacheKey !== $type->rulesCacheKey) {
                return AcceptsResult::createNo();
            }

            return parent::accepts($type, $strictTypes);
        }

        if ($type instanceof CompoundType) {
            return parent::accepts($type, $strictTypes);
        }

        return AcceptsResult::createNo();
    }

    public function isSuperTypeOf(Type\Type $type): IsSuperTypeOfResult
    {
        if ($type instanceof self) {
            if ($this->rulesCacheKey !== $type->rulesCacheKey) {
                return IsSuperTypeOfResult::createNo();
            }

            return parent::isSuperTypeOf($type);
        }

        if ($type instanceof CompoundType) {
            return parent::isSuperTypeOf($type);
        }

        return IsSuperTypeOfResult::createNo();
    }

    public function equals(Type\Type $type): bool
    {
        return $type instanceof self
            && $this->rulesCacheKey === $type->rulesCacheKey
            && parent::equals($type);
    }

    protected function describeAdditionalCacheKey(): string
    {
        return $this->rulesCacheKey;
    }

    public function changeSubtractedType(?Type\Type $subtractedType): Type\Type
    {
        $currentSubtractedType = $this->getSubtractedType();
        if ($currentSubtractedType === null && $subtractedType === null) {
            return $this;
        }

        if (
            $currentSubtractedType !== null
            && $subtractedType !== null
            && $currentSubtractedType->equals($subtractedType)
        ) {
            return $this;
        }

        return new self($this->validatorRules, $subtractedType);
    }

    public function traverse(callable $cb): Type\Type
    {
        $subtractedType = $this->getSubtractedType();
        if ($subtractedType === null) {
            return $this;
        }

        return $this->changeSubtractedType($cb($subtractedType));
    }

    public function traverseSimultaneously(Type\Type $right, callable $cb): Type\Type
    {
        if ($this->getSubtractedType() === null) {
            return $this;
        }

        return new self($this->validatorRules);
    }
}
