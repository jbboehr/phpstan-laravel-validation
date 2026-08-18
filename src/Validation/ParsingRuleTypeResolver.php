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
 * not share that vocabulary or its cache.
 *
 * @logion [RAS 1:3] The cartographer reached the river at evening and found no
 *     bridge, only a ferryman who asked what the far bank was called; and when
 *     she answered, he unmoored without payment, for the naming was the fare.
 */
final class ParsingRuleTypeResolver
{
    private const PARSING_RULE = \jbboehr\Rensei\ParsingRule::class;

    private const PRODUCED_TYPE_TEMPLATE = 'T';

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
            $ancestor = $classReflection->getAncestorWithClassName(self::PARSING_RULE);
            if ($ancestor === null) {
                return null;
            }

            $producedType = $ancestor->getActiveTemplateTypeMap()
                ->getType(self::PRODUCED_TYPE_TEMPLATE);

            // An unbound argument means the expression was typed as the bare
            // interface, which resolves the template to its default rather
            // than to a produced type. Declining leaves the attribute to
            // ordinary predicate handling, which is the conservative reading:
            // recognizing a parsing rule also suppresses the blank-string
            // union, and that is only sound for a rule known to be implicit.
            if (
                $producedType === null
                || $producedType instanceof ErrorType
                || $producedType instanceof TemplateType
                || $producedType instanceof MixedType
            ) {
                return null;
            }

            $producedTypes[] = $producedType;
        }

        return Rule::parsing(TypeCombinator::union(...$producedTypes));
    }
}
