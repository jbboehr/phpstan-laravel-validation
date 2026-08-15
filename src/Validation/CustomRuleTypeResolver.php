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

use jbboehr\PhpstanLaravelValidation\Attribute\ValidationRuleType;
use PHPStan\PhpDoc\TypeStringResolver;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ErrorType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

final class CustomRuleTypeResolver
{
    private const PHPDOC_TAG = '@laravel-validation-type';

    private const PREDICATE_TYPES = [
        \Closure::class,
        \Illuminate\Contracts\Validation\ImplicitRule::class,
        \Illuminate\Contracts\Validation\InvokableRule::class,
        \Illuminate\Contracts\Validation\Rule::class,
        \Illuminate\Contracts\Validation\ValidationRule::class,
    ];

    /**
     * Type resolution may invoke third-party reflection extensions. Keep
     * configured strings unresolved until analysis, after bootstrap files
     * have run, rather than resolving them while PHPStan builds its container.
     *
     * @var array<string, string>
     */
    private array $configuredClasses = [];

    /** @var array<string, array{type: string, source: string}> */
    private array $configuredNames = [];

    /** @var array<string, Type|null> */
    private array $classCache = [];

    /** @var array<string, Type> */
    private array $nameCache = [];

    /**
     * @param array<string, string> $configuredClasses
     * @param array<string, string> $configuredNames
     */
    public function __construct(
        private TypeStringResolver $typeStringResolver,
        private ReflectionProvider $reflectionProvider,
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
            $this->assertNonBlankTypeString($typeString, $className);
            $this->configuredClasses[$className] = $typeString;
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
            $this->assertNonBlankTypeString($typeString, $ruleName);
            $this->configuredNames[$normalizedName] = [
                'type' => $typeString,
                'source' => $ruleName,
            ];
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
            $resolved = $this->resolveClass($className);
            if ($resolved === null) {
                return Rule::custom(new MixedType());
            }
            $types[] = $resolved;
        }

        return Rule::custom(TypeCombinator::union(...$types));
    }

    public function resolveName(string $normalizedRuleName): ?Type
    {
        if (array_key_exists($normalizedRuleName, $this->nameCache)) {
            return $this->nameCache[$normalizedRuleName];
        }

        if (!array_key_exists($normalizedRuleName, $this->configuredNames)) {
            return null;
        }

        return $this->nameCache[$normalizedRuleName] = $this->parseTypeString(
            $this->configuredNames[$normalizedRuleName]['type'],
            $this->configuredNames[$normalizedRuleName]['source']
        );
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

    private function resolveClass(string $className): ?Type
    {
        if (array_key_exists($className, $this->classCache)) {
            return $this->classCache[$className];
        }

        if (array_key_exists($className, $this->configuredClasses)) {
            return $this->classCache[$className] = $this->parseTypeString(
                $this->configuredClasses[$className],
                $className
            );
        }

        $reflection = $this->reflectionProvider->getClass($className);
        $attributeType = $this->resolveAttributeType($reflection);
        $phpDocType = $this->resolvePhpDocType($reflection);

        return $this->classCache[$className] = $attributeType ?? $phpDocType;
    }

    private function resolveAttributeType(ClassReflection $reflection): ?Type
    {
        $matches = array_values(array_filter(
            $reflection->getAttributes(),
            static fn ($attribute): bool => $attribute->getName() === ValidationRuleType::class
        ));

        if (count($matches) > 1) {
            throw new InvalidCustomRuleContractException(sprintf(
                'Custom validation rule %s has more than one %s attribute',
                $reflection->getName(),
                ValidationRuleType::class
            ));
        }
        if ($matches === []) {
            return null;
        }

        $arguments = array_values($matches[0]->getArgumentTypes());
        $constantStrings = count($arguments) === 1 ? $arguments[0]->getConstantStrings() : [];
        if (count($constantStrings) !== 1) {
            throw new InvalidCustomRuleContractException(sprintf(
                '%s on custom validation rule %s requires exactly one constant type string',
                ValidationRuleType::class,
                $reflection->getName()
            ));
        }

        return $this->parseTypeString($constantStrings[0]->getValue(), $reflection->getName());
    }

    private function resolvePhpDocType(ClassReflection $reflection): ?Type
    {
        $resolvedPhpDoc = $reflection->getResolvedPhpDoc();
        if ($resolvedPhpDoc === null || !$resolvedPhpDoc->hasPhpDocString()) {
            return null;
        }
        $phpDocString = $resolvedPhpDoc->getPhpDocString();

        preg_match_all(
            '/' . self::PHPDOC_TAG . '\h+([^\r\n*]*\S)(?=\h*(?:\*\/)?(?:\r?\n|$))/',
            $phpDocString,
            $matches
        );
        $typeStrings = $matches[1];

        if (count($typeStrings) > 1) {
            throw new InvalidCustomRuleContractException(sprintf(
                'Custom validation rule %s has more than one %s tag',
                $reflection->getName(),
                self::PHPDOC_TAG
            ));
        }
        if ($typeStrings === []) {
            return null;
        }

        return $this->parseTypeString(trim($typeStrings[0]), $reflection->getName());
    }

    private function parseTypeString(string $typeString, string $source): Type
    {
        $this->assertNonBlankTypeString($typeString, $source);

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

    private function assertNonBlankTypeString(string $typeString, string $source): void
    {
        if (trim($typeString) === '') {
            throw new InvalidCustomRuleContractException(sprintf(
                'Custom validation rule type for %s cannot be empty',
                $source
            ));
        }
    }
}
