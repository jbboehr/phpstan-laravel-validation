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

use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type;
use PHPStan\Type\Accessory\AccessoryNumericStringType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Enum\EnumCaseObjectType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\NeverType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;

/**
 * Extracts Laravel's built-in Enum rule only from fresh, statically visible
 * expressions. Mutable variables and runtime-composed rule objects remain
 * opaque rather than receiving guessed state.
 */
final class EnumRuleExpressionResolver
{
    private const ENUM_RULE_CLASS = \Illuminate\Validation\Rules\Enum::class;
    private const RULE_FACTORY_CLASS = \Illuminate\Validation\Rule::class;

    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private LaravelVersionContext $laravelVersionContext
    ) {
    }

    public function resolve(Expr $expression, Scope $scope): ?Rule
    {
        if (!$this->laravelVersionContext->isSupported()) {
            return null;
        }

        $state = $this->resolveState($expression, $scope);
        if ($state === null) {
            return null;
        }

        return Rule::custom($this->resolveAcceptedType(
            $state['enum'],
            $state['only'],
            $state['except']
        ));
    }

    /**
     * @return array{
     *     enum: ClassReflection,
     *     only: list<array{class: string, case: string}>,
     *     except: list<array{class: string, case: string}>
     * }|null
     */
    private function resolveState(Expr $expression, Scope $scope): ?array
    {
        if (
            $expression instanceof Expr\MethodCall
            && $expression->name instanceof Identifier
            && in_array($expression->name->toLowerString(), ['only', 'except'], true)
        ) {
            if (!$this->laravelVersionContext->isAtLeast('10.46.0')) {
                return null;
            }

            $arguments = $expression->getArgs();
            if (count($arguments) !== 1 || $arguments[0]->unpack) {
                return null;
            }

            $state = $this->resolveState($expression->var, $scope);
            $cases = $this->resolveFilterCases($arguments[0]->value, $scope);
            if ($state === null || $cases === null) {
                return null;
            }

            $state[$expression->name->toLowerString()] = $cases;
            return $state;
        }

        $enum = $this->resolveBaseEnum($expression, $scope);
        if ($enum === null) {
            return null;
        }

        return [
            'enum' => $enum,
            'only' => [],
            'except' => [],
        ];
    }

    private function resolveBaseEnum(Expr $expression, Scope $scope): ?ClassReflection
    {
        if ($expression instanceof Expr\New_) {
            if (
                !$expression->class instanceof Name
                || $this->resolveName($expression->class, $scope) !== self::ENUM_RULE_CLASS
            ) {
                return null;
            }
        } elseif ($expression instanceof Expr\StaticCall) {
            if (
                !$expression->class instanceof Name
                || !$expression->name instanceof Identifier
                || $this->resolveName($expression->class, $scope) !== self::RULE_FACTORY_CLASS
                || $expression->name->toLowerString() !== 'enum'
            ) {
                return null;
            }
        } else {
            return null;
        }

        $arguments = $expression->getArgs();
        if (count($arguments) !== 1 || $arguments[0]->unpack) {
            return null;
        }

        $className = $this->resolveEnumClassName($arguments[0]->value, $scope);
        if ($className === null) {
            return null;
        }

        if (!$this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $reflection = $this->reflectionProvider->getClass($className);
        return $reflection->isEnum() ? $reflection : null;
    }

    /**
     * @return list<array{class: string, case: string}>|null
     */
    private function resolveFilterCases(Expr $expression, Scope $scope): ?array
    {
        if ($expression instanceof Expr\Array_) {
            $cases = [];
            foreach ($expression->items as $item) {
                if ($item->unpack) {
                    return null;
                }

                $case = $this->resolveSingleCase($item->value, $scope);
                if ($case === null) {
                    return null;
                }
                $cases[] = $case;
            }
            return $cases;
        }

        $case = $this->resolveSingleCase($expression, $scope);
        return $case === null ? null : [$case];
    }

    /** @return array{class: string, case: string}|null */
    private function resolveSingleCase(Expr $expression, Scope $scope): ?array
    {
        $cases = $scope->getType($expression)->getEnumCases();
        if (count($cases) === 1) {
            return [
                'class' => $cases[0]->getClassName(),
                'case' => $cases[0]->getEnumCaseName(),
            ];
        }

        if (
            !$expression instanceof Expr\ClassConstFetch
            || !$expression->class instanceof Name
            || !$expression->name instanceof Identifier
        ) {
            return null;
        }

        $className = $this->resolveName($expression->class, $scope);
        if (!$this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $enum = $this->reflectionProvider->getClass($className);
        $caseName = $expression->name->toString();
        if (!$enum->isEnum() || !$enum->hasEnumCase($caseName)) {
            return null;
        }

        return [
            'class' => $className,
            'case' => $caseName,
        ];
    }

    private function resolveEnumClassName(Expr $expression, Scope $scope): ?string
    {
        $classStrings = $scope->getType($expression)->getConstantStrings();
        if (count($classStrings) === 1) {
            return $classStrings[0]->getValue();
        }

        if (
            !$expression instanceof Expr\ClassConstFetch
            || !$expression->class instanceof Name
            || !$expression->name instanceof Identifier
            || strtolower($expression->name->toString()) !== 'class'
        ) {
            return null;
        }

        return $this->resolveName($expression->class, $scope);
    }

    private function resolveName(Name $name, Scope $scope): string
    {
        $resolvedName = $name->getAttribute('resolvedName');
        return $resolvedName instanceof Name
            ? $resolvedName->toString()
            : $scope->resolveName($name);
    }

    /**
     * @param list<array{class: string, case: string}> $only
     * @param list<array{class: string, case: string}> $except
     */
    private function resolveAcceptedType(ClassReflection $enum, array $only, array $except): Type\Type
    {
        $cases = $enum->getEnumCases();
        if ($only !== []) {
            $allowed = $this->caseSet($only);
            $cases = array_filter(
                $cases,
                fn ($case): bool => isset($allowed[$this->caseId($enum->getName(), $case->getName())])
            );
        } elseif ($except !== []) {
            $excluded = $this->caseSet($except);
            $cases = array_filter(
                $cases,
                fn ($case): bool => !isset($excluded[$this->caseId($enum->getName(), $case->getName())])
            );
        }

        if ($cases === []) {
            return new NeverType();
        }

        $caseTypes = [];
        foreach ($cases as $case) {
            // Store only the enum and case names so the rule contract does not
            // retain a live ClassReflection instance.
            $caseTypes[] = new EnumCaseObjectType($enum->getName(), $case->getName());
        }

        if (!$enum->isBackedEnum()) {
            return $this->combineCaseAndValueTypes($caseTypes, []);
        }

        $backedType = $enum->getBackedEnumType();
        if ($backedType !== null && $backedType->isInteger()->yes()) {
            return $this->resolveIntegerBackedType($cases, $caseTypes);
        }

        return $this->resolveStringBackedType($cases, $caseTypes);
    }

    /**
     * @param array<\PHPStan\Reflection\EnumCaseReflection> $cases
     * @param list<Type\Type> $caseTypes
     */
    private function resolveIntegerBackedType(array $cases, array $caseTypes): Type\Type
    {
        $valueTypes = [new Type\FloatType(), new IntersectionType([
            new StringType(),
            new AccessoryNumericStringType(),
        ])];

        foreach ($cases as $case) {
            $backingType = $case->getBackingValueType();
            if (!$backingType instanceof ConstantIntegerType) {
                return new Type\MixedType();
            }

            $valueTypes[] = $backingType;
            if ($backingType->getValue() === 0) {
                $valueTypes[] = new ConstantBooleanType(false);
            } elseif ($backingType->getValue() === 1) {
                $valueTypes[] = new ConstantBooleanType(true);
            }
        }

        return $this->combineCaseAndValueTypes($caseTypes, $valueTypes);
    }

    /**
     * @param array<\PHPStan\Reflection\EnumCaseReflection> $cases
     * @param list<Type\Type> $caseTypes
     */
    private function resolveStringBackedType(array $cases, array $caseTypes): Type\Type
    {
        $valueTypes = [new ObjectType(\Stringable::class)];
        $hasIntegerCoercion = false;

        foreach ($cases as $case) {
            $backingType = $case->getBackingValueType();
            $backingStrings = $backingType?->getConstantStrings() ?? [];
            if (count($backingStrings) !== 1) {
                return new Type\MixedType();
            }

            $valueTypes[] = $backingStrings[0];
            $integer = $this->canonicalInteger($backingStrings[0]->getValue());
            if ($integer === null) {
                continue;
            }

            $hasIntegerCoercion = true;
            $valueTypes[] = new ConstantIntegerType($integer);
            if ($integer === 0) {
                $valueTypes[] = new ConstantBooleanType(false);
            } elseif ($integer === 1) {
                $valueTypes[] = new ConstantBooleanType(true);
            }
        }

        if ($hasIntegerCoercion) {
            $valueTypes[] = new Type\FloatType();
        }

        return $this->combineCaseAndValueTypes($caseTypes, $valueTypes);
    }

    /**
     * Keep enum case objects out of TypeCombinator here. Comparing them can
     * populate their internal reflection caches unnecessarily; scalar values
     * can still be normalized separately before the final union is assembled.
     *
     * @param list<Type\Type> $caseTypes
     * @param list<Type\Type> $valueTypes
     */
    private function combineCaseAndValueTypes(array $caseTypes, array $valueTypes): Type\Type
    {
        if ($valueTypes !== []) {
            $valueType = Type\TypeCombinator::union(...$valueTypes);
            array_push(
                $caseTypes,
                ...($valueType instanceof UnionType ? $valueType->getTypes() : [$valueType])
            );
        }

        return count($caseTypes) === 1
            ? $caseTypes[0]
            : new UnionType($caseTypes);
    }

    /**
     * @param list<array{class: string, case: string}> $cases
     * @return array<string, true>
     */
    private function caseSet(array $cases): array
    {
        $result = [];
        foreach ($cases as $case) {
            $result[$this->caseId($case['class'], $case['case'])] = true;
        }
        return $result;
    }

    private function caseId(string $className, string $caseName): string
    {
        return $className . '::' . $caseName;
    }

    private function canonicalInteger(string $value): ?int
    {
        if (preg_match('/^-?(?:0|[1-9][0-9]*)$/D', $value) !== 1) {
            return null;
        }

        $integer = filter_var($value, FILTER_VALIDATE_INT);
        if (!is_int($integer) || (string) $integer !== $value) {
            return null;
        }

        return $integer;
    }
}
