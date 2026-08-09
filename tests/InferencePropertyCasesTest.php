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

namespace jbboehr\PhpstanLaravelValidation\Test;

use jbboehr\PhpstanLaravelValidation\Test\Support\InferencePropertyCases;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class InferencePropertyCasesTest extends TestCase
{
    /**
     * @return iterable<string, array{array<string, array{data: array<mixed, mixed>, rules: array<string, string>}>, int}>
     */
    public static function catalogProvider(): iterable
    {
        yield 'scalar' => [InferencePropertyCases::scalar(), InferencePropertyCases::SCALAR_COUNT];
        yield 'structural' => [InferencePropertyCases::structural(), InferencePropertyCases::STRUCTURAL_COUNT];
        yield 'conditional' => [InferencePropertyCases::conditional(), InferencePropertyCases::CONDITIONAL_COUNT];
    }

    /**
     * @param array<string, array{data: array<mixed, mixed>, rules: array<string, string>}> $cases
     */
    #[DataProvider('catalogProvider')]
    public function testCatalogHasNamedWellFormedCases(array $cases, int $expectedCount): void
    {
        self::assertCount($expectedCount, $cases);

        foreach ($cases as $caseId => $case) {
            self::assertMatchesRegularExpression('/^[a-z0-9.-]+$/D', $caseId);
            self::assertNotSame([], $case['rules'], $caseId . ' has no rules');
        }
    }
}
