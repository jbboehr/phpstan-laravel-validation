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

use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsLaravelValidation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class NullRuleLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    use AssertsLaravelValidation;

    /**
     * @return iterable<string, array{string, array<mixed, mixed>, array<string, list<string|null>>, bool, array<mixed, mixed>|null}>
     */
    public static function cases(): iterable
    {
        yield 'present value is preserved' => [
            'present value is preserved',
            ['value' => 'text'],
            ['value' => ['required', null, 'string']],
            true,
            ['value' => 'text'],
        ];
        yield 'missing optional value remains absent' => [
            'missing optional value remains absent',
            [],
            ['value' => ['string', null]],
            true,
            [],
        ];
        yield 'null entry does not disable required' => [
            'null entry does not disable required',
            ['value' => ''],
            ['value' => [null, 'required', 'string']],
            false,
            null,
        ];
    }

    /**
     * @param array<mixed, mixed> $data
     * @param array<string, list<string|null>> $rules
     * @param array<mixed, mixed>|null $expectedValidated
     */
    #[DataProvider('cases')]
    public function testNullRuleListElementsAreNoOps(
        string $caseId,
        array $data,
        array $rules,
        bool $expectedPasses,
        ?array $expectedValidated
    ): void {
        // Laravel passes null through string functions while normalizing it
        // to an empty rule name. PHP 8.4+ deprecates that coercion, but every
        // supported Laravel major still skips the resulting empty rule.
        set_error_handler(static function (int $severity, string $message, string $file): bool {
            return $severity === E_DEPRECATED
                && str_ends_with(
                    str_replace('\\', '/', $file),
                    '/Illuminate/Validation/ValidationRuleParser.php'
                )
                && str_contains($message, 'Passing null to parameter #1');
        });
        try {
            $this->assertLaravelValidationCase(
                $caseId,
                $data,
                $rules,
                $expectedPasses,
                $expectedValidated
            );
        } finally {
            restore_error_handler();
        }
    }
}
