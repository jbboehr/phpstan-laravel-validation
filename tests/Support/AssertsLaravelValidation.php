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

namespace jbboehr\PhpstanLaravelValidation\Test\Support;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\VerbosityLevel;

trait AssertsLaravelValidation
{
    /**
     * @param array<mixed, mixed> $data
     * @param array<array-key, mixed> $rules
     * @param array<mixed, mixed>|null $expectedValidated
     */
    protected function assertLaravelValidationCase(
        string $caseId,
        array $data,
        array $rules,
        bool $expectedPasses,
        ?array $expectedValidated
    ): void {
        self::getContainer();

        $factory = new Factory(new Translator(new ArrayLoader(), 'en'));
        $validator = $factory->make($data, $rules);
        $context = sprintf(
            "Case: %s\nRules: %s\nInput: %s",
            $caseId,
            var_export($rules, true),
            var_export($data, true),
        );

        self::assertSame($expectedPasses, $validator->passes(), $context);
        if (!$expectedPasses) {
            return;
        }

        $validated = $validator->validated();
        self::assertSame($expectedValidated, $validated, $context);

        $inferred = (new TypeResolver())->evaluate(RuleParser::parse($rules));
        $actual = LaravelValueType::fromValue($validated);
        self::assertTrue($inferred->isSuperTypeOf($actual)->yes(), sprintf(
            "%s\nValidated: %s\nInferred: %s\nActual: %s",
            $context,
            var_export($validated, true),
            $inferred->describe(VerbosityLevel::precise()),
            $actual->describe(VerbosityLevel::precise()),
        ));
    }
}
