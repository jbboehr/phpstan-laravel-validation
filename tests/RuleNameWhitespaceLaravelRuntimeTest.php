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
final class RuleNameWhitespaceLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    use AssertsLaravelValidation;

    /** @return iterable<string, array{string, bool}> */
    public static function whitespaceProvider(): iterable
    {
        foreach (['none' => '', 'space' => ' ', 'tab' => "\t", 'LF' => "\n", 'CR' => "\r", 'NUL' => "\0", 'VT' => "\v"] as $name => $whitespace) {
            yield $name . ' array' => [$whitespace, false];
            yield $name . ' pipe' => [$whitespace, true];
        }
    }

    #[DataProvider('whitespaceProvider')]
    public function testTrimmedModifiersPreserveNullAndOptionalFields(string $whitespace, bool $pipe): void
    {
        $nullable = [$whitespace . 'nullable' . $whitespace, 'string'];
        $sometimes = [$whitespace . 'sometimes' . $whitespace, 'required', 'string'];
        $required = [$whitespace . 'required' . $whitespace, 'string'];
        $rules = [
            'nullable' => $pipe ? implode('|', $nullable) : $nullable,
            'optional' => $pipe ? implode('|', $sometimes) : $sometimes,
            'required' => $pipe ? implode('|', $required) : $required,
        ];

        $this->assertLaravelValidationCase(
            'null and missing optional field',
            ['nullable' => null, 'required' => 'text'],
            $rules,
            true,
            ['nullable' => null, 'required' => 'text']
        );
        $this->assertLaravelValidationCase(
            'present optional field',
            ['optional' => 'text', 'required' => 'text'],
            $rules,
            true,
            ['optional' => 'text', 'required' => 'text']
        );
        $this->assertLaravelValidationCase(
            'sometimes retains required when present',
            ['optional' => '', 'required' => 'text'],
            $rules,
            false,
            null
        );
        $this->assertLaravelValidationCase('required remains required', [], $rules, false, null);
    }

    public function testTrimmingTheNamePreservesMembershipParameterWhitespace(): void
    {
        $rules = ['value' => ['required', "\tin\n: first,second\t"]];
        $this->assertLaravelValidationCase('leading parameter space', ['value' => ' first'], $rules, true, ['value' => ' first']);
        $this->assertLaravelValidationCase('trailing parameter tab', ['value' => "second\t"], $rules, true, ['value' => "second\t"]);
        $this->assertLaravelValidationCase('parameter whitespace is significant', ['value' => 'first'], $rules, false, null);
    }
}
