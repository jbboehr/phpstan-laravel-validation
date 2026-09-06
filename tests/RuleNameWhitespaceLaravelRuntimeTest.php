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

use Illuminate\Foundation\Application;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationRuleParser;
use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsLaravelValidation;
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
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

    /** @return iterable<string, array{string}> */
    public static function broaderWhitespaceProvider(): iterable
    {
        foreach ([
            'tab' => "\t", 'LF' => "\n", 'CR' => "\r", 'VT' => "\v", 'FF' => "\f", 'NUL' => "\0",
            'NEL' => "\u{0085}", 'NBSP' => "\u{00a0}", 'OGHAM' => "\u{1680}", 'MVS' => "\u{180e}",
            'EN SPACE' => "\u{2002}", 'EM SPACE' => "\u{2003}", 'ZWSP' => "\u{200b}",
            'LS' => "\u{2028}", 'PS' => "\u{2029}", 'NNBSP' => "\u{202f}",
            'MMSP' => "\u{205f}", 'IDEOGRAPHIC' => "\u{3000}", 'BOM' => "\u{feff}",
        ] as $label => $space) {
            yield $label => [$space];
        }
    }

    #[DataProvider('broaderWhitespaceProvider')]
    public function testBroaderWhitespaceMatchesTheInstalledParser(string $space): void
    {
        $context = new LaravelVersionContext('', Application::VERSION);
        foreach ([$space . 'nullable' . $space, 'required' . $space . 'without_all', $space] as $name) {
            $expected = ValidationRuleParser::parse($name)[0];
            self::assertSame($expected, RuleParser::parseStringRule($name, $context)->getRuleName());
            $arrayRule = RuleParser::parseArrayRule([$name], $context);
            self::assertNotNull($arrayRule);
            self::assertSame($expected, $arrayRule->getRuleName());
        }
    }

    public function testBroaderWhitespacePreservesNullableAndOptionalOutput(): void
    {
        $rules = [
            'value' => ["\u{00a0}nullable\u{00a0}", 'string'],
            'optional' => "\fsometimes\f|required|string",
        ];
        if (ValidationRuleParser::parse("\u{00a0}nullable\u{00a0}")[0] !== 'Nullable') {
            $this->expectException(\BadMethodCallException::class);
        }
        $this->assertLaravelValidationCase('broader modifier whitespace', ['value' => null], $rules, true, ['value' => null]);
    }

    public function testWhitespaceInCustomRuleNamesUsesTheFrameworkParser(): void
    {
        $factory = new Factory(new Translator(new ArrayLoader(), 'en'));
        $factory->extend('custom_value', static fn (string $attribute, mixed $value): bool => is_int($value));
        $name = "custom\u{00a0}value";
        if (ValidationRuleParser::parse($name)[0] !== 'CustomValue') {
            $this->expectException(\BadMethodCallException::class);
        }
        self::assertSame(['value' => 7], $factory->make(['value' => 7], ['value' => ['required', $name]])->validated());
    }
}
