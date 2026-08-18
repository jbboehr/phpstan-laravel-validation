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

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ErrorType;
use PHPStan\Type\Generic\TemplateType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

/**
 * Recovers the type a parsing rule produces.
 *
 * The type is read from the `ParsingRule<T>` binding rather than from a table
 * of known rule classes, so a parser defined outside this package is
 * understood without a release here. It is kept separate from
 * {@see CustomRuleTypeResolver}, whose metadata describes an original value
 * that survives a predicate; a produced value is a different claim and must
 * not share that vocabulary.
 *
 * Answers are deliberately not memoized by class name. A generic parser binds
 * its produced type at the use site, so one class answers differently for
 * `EnumRule<Status>` and `EnumRule<Role>`, and a refusal recorded for an
 * unbound form would spread to every bound form reached afterwards. Should
 * this ever measure as hot, the key is the incoming type --
 * `describe(VerbosityLevel::cache())` -- and never the class.
 *
 * @logion [RAS 1:3] The cartographer reached the river at evening and found no
 *     bridge, only a ferryman who asked what the far bank was called; and when
 *     she answered, he unmoored without payment, for the naming was the fare.
 */
final class ParsingRuleTypeResolver
{
    private const PARSING_RULE = \jbboehr\Rensei\ParsingRule::class;

    private const PRODUCED_TYPE_TEMPLATE = 'T';

    private const IMPLICIT_PROPERTY = 'implicit';

    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * The parsing rule this type describes, or null when it describes none.
     *
     * Returning null means "not a parsing rule", which lets the caller fall
     * through to ordinary predicate handling. Every undiscoverable case
     * returns null rather than guessing: an unbound template argument, an
     * erroneous binding, or a union that is not entirely parsing rules.
     */
    public function resolveRule(Type $type): ?Rule
    {
        // The runtime component is optional. An analysed project that never
        // parses need not have it installed.
        if (!$this->reflectionProvider->hasClass(self::PARSING_RULE)) {
            return null;
        }

        $classReflections = $type->getObjectClassReflections();
        if ($classReflections === []) {
            return null;
        }

        $producedTypes = [];

        foreach ($classReflections as $classReflection) {
            $producedType = $this->discover($classReflection);
            if ($producedType === null) {
                return null;
            }

            $producedTypes[] = $producedType;
        }

        return Rule::parsing(TypeCombinator::union(...$producedTypes));
    }

    private function discover(ClassReflection $classReflection): ?Type
    {
        $ancestor = $classReflection->getAncestorWithClassName(self::PARSING_RULE);
        if ($ancestor === null) {
            return null;
        }

        // Recognizing a parsing rule suppresses the blank-string union, and
        // that is sound only for a rule Laravel actually treats as implicit.
        // A non-implicit rule is skipped for a blank or whitespace-only
        // string, so the raw string would survive into the validated output
        // while the produced type promised otherwise. Read implicitness the
        // same way InvokableValidationRule::make() does.
        if (!$this->isImplicit($classReflection)) {
            return null;
        }

        $producedType = $ancestor->getActiveTemplateTypeMap()
            ->getType(self::PRODUCED_TYPE_TEMPLATE);

        // An unbound argument means the expression was typed as the bare
        // interface, which resolves the template to its default rather than to
        // a produced type. Declining leaves the attribute to ordinary
        // predicate handling, which is the conservative reading.
        if (
            $producedType === null
            || $producedType instanceof ErrorType
            || $producedType instanceof TemplateType
            || $producedType instanceof MixedType
        ) {
            return null;
        }

        return $producedType;
    }

    /**
     * Whether Laravel will treat this rule as implicit.
     *
     * A concrete class must carry the property, exactly as the framework
     * requires of it. An abstract type names no runtime class, and PHP cannot
     * make an interface require a property, so there the requirement is not
     * merely unverified but unverifiable. Trusting it would make this check
     * decorative: an expression declared as `ParsingRule<int>` would be
     * believed whatever it returns, and a non-implicit rule behind that
     * declaration is skipped for a blank string, leaving the raw string in
     * the validated output.
     *
     * Declining costs precision for a parser whose concrete class is erased
     * behind the interface. It never costs soundness.
     */
    private function isImplicit(ClassReflection $classReflection): bool
    {
        if ($classReflection->isInterface() || $classReflection->isAbstract()) {
            return false;
        }

        $defaults = $classReflection->getNativeReflection()->getDefaultProperties();

        return ($defaults[self::IMPLICIT_PROPERTY] ?? false) === true;
    }
}
