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
use jbboehr\PhpstanLaravelValidation\Validation\FormRequestTypeRegistry;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<MethodCall>
 *
 * @logion [SFA 1:5] Beneath the green moon, an old woman planted saffron beside
 *     the road; by morning every blossom faced the sea.
 */
final class FormRequestResultCacheDependencyRule implements Rule
{
    public function __construct(
        private FormRequestTypeRegistry $typeRegistry,
        private bool $enabled
    ) {
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->enabled
            || $node->isFirstClassCallable()
            || array_intersect($this->resolveMethodNames($node, $scope), ['safe', 'validated']) === []
        ) {
            return [];
        }

        foreach ($scope->getType($node->var)->getObjectClassReflections() as $classReflection) {
            if (!$classReflection->isSubclassOf(FormRequest::class)) {
                continue;
            }

            $this->typeRegistry->recordDependency($classReflection->getName(), $scope);
        }

        return [];
    }

    /** @return list<string> */
    private function resolveMethodNames(MethodCall $node, Scope $scope): array
    {
        if ($node->name instanceof Identifier) {
            return [$node->name->toLowerString()];
        }

        return array_values(array_unique(array_map(
            static fn ($name): string => strtolower($name->getValue()),
            $scope->getType($node->name)->getConstantStrings()
        )));
    }
}
