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

use PHPStan\PhpDoc\TypeStringResolver;
use PHPStan\Type\ErrorType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

final class CustomRuleTypeResolver
{
    private const PREDICATE_TYPES = [
        \Closure::class,
        \Illuminate\Contracts\Validation\ImplicitRule::class,
        \Illuminate\Contracts\Validation\InvokableRule::class,
        \Illuminate\Contracts\Validation\Rule::class,
        \Illuminate\Contracts\Validation\ValidationRule::class,
    ];

    /** @var array<string, Type> */
    private array $configuredClasses = [];

    /** @var array<string, Type> */
    private array $configuredNames = [];

    /**
     * @param array<string, string> $configuredClasses
     * @param array<string, string> $configuredNames
     */
    public function __construct(
        private TypeStringResolver $typeStringResolver,
        array $configuredClasses,
        array $configuredNames
    ) {
        foreach ($configuredClasses as $className => $typeString) {
            $className = ltrim($className, '\\');
            if ($className === '') {
                throw new InvalidCustomRuleContractException(
                    'Configured custom validation rule class cannot be empty'
                );
            }
            if (array_key_exists($className, $this->configuredClasses)) {
                throw new InvalidCustomRuleContractException(sprintf(
                    'Configured custom validation rule classes contain a duplicate after normalization: %s',
                    $className
                ));
            }
            $this->configuredClasses[$className] = $this->parseTypeString($typeString, $className);
        }

        foreach ($configuredNames as $ruleName => $typeString) {
            if (trim($ruleName) === '') {
                throw new InvalidCustomRuleContractException('Configured custom validation rule name cannot be empty');
            }
            $normalizedName = RuleParser::normalizeName($ruleName);
            if (TypeResolver::isBuiltInRuleName($normalizedName)) {
                throw new InvalidCustomRuleContractException(sprintf(
                    'Configured custom validation rule name %s collides with a built-in Laravel rule',
                    $ruleName
                ));
            }
            if (array_key_exists($normalizedName, $this->configuredNames)) {
                throw new InvalidCustomRuleContractException(sprintf(
                    'Configured custom validation rule names contain a duplicate after normalization: %s',
                    $ruleName
                ));
            }
            $this->configuredNames[$normalizedName] = $this->parseTypeString($typeString, $ruleName);
        }
    }

    public function resolveRule(Type $type): Rule
    {
        if (!$this->isPredicateType($type)) {
            foreach ($type->getObjectClassNames() as $className) {
                if (array_key_exists($className, $this->configuredClasses)) {
                    throw new InvalidCustomRuleContractException(sprintf(
                        'Configured custom validation rule class %s does not implement a Laravel validation rule contract',
                        $className
                    ));
                }
            }

            return Rule::opaque();
        }

        $classNames = $type->getObjectClassNames();
        if ($classNames === []) {
            return Rule::custom(new MixedType());
        }

        $types = [];
        foreach ($classNames as $className) {
            if (!array_key_exists($className, $this->configuredClasses)) {
                return Rule::custom(new MixedType());
            }
            $types[] = $this->configuredClasses[$className];
        }

        return Rule::custom(TypeCombinator::union(...$types));
    }

    public function resolveName(string $normalizedRuleName): ?Type
    {
        return $this->configuredNames[$normalizedRuleName] ?? null;
    }

    public function isPredicateType(Type $type): bool
    {
        foreach (self::PREDICATE_TYPES as $predicateType) {
            if ((new ObjectType($predicateType))->isSuperTypeOf($type)->yes()) {
                return true;
            }
        }

        return false;
    }

    private function parseTypeString(string $typeString, string $source): Type
    {
        if (trim($typeString) === '') {
            throw new InvalidCustomRuleContractException(sprintf(
                'Custom validation rule type for %s cannot be empty',
                $source
            ));
        }

        try {
            $type = $this->typeStringResolver->resolve($typeString, null);
        } catch (\Throwable $throwable) {
            throw new InvalidCustomRuleContractException(sprintf(
                'Invalid PHPStan type %s for custom validation rule %s: %s',
                $typeString,
                $source,
                $throwable->getMessage()
            ), previous: $throwable);
        }
        if ($type instanceof ErrorType) {
            throw new InvalidCustomRuleContractException(sprintf(
                'Invalid PHPStan type %s for custom validation rule %s',
                $typeString,
                $source
            ));
        }

        return $type;
    }
}
