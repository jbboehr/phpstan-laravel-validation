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
use jbboehr\Rensei\Parse;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

/**
 * The parsing contract, asserted at runtime and against inference at once.
 *
 * Every case runs a real Laravel validator and then requires the inferred
 * type to contain the value that validator actually returned. A parser whose
 * grammar and whose declared type disagreed would fail here rather than in
 * somebody's application.
 */
#[Group('laravel')]
final class ParseIntegerLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    use AssertsLaravelValidation;

    /**
     * The presence, null, and blank matrix.
     *
     * @return iterable<string, array{string, array<mixed, mixed>, array<string, mixed>, bool, array<mixed, mixed>|null}>
     */
    public static function presenceCases(): iterable
    {
        $sets = [
            'bare' => [],
            'required' => ['required'],
            'sometimes' => ['sometimes'],
            'nullable' => ['nullable'],
            'sometimes nullable' => ['sometimes', 'nullable'],
            'required nullable' => ['required', 'nullable'],
            'present' => ['present'],
            'filled' => ['filled'],
            'bail required' => ['bail', 'required'],
        ];

        // passes() for absent, blank, whitespace-only, and null input.
        $expectations = [
            //                    absent  blank  spaces  null
            'bare' => [true, false, false, false],
            'required' => [false, false, false, false],
            'sometimes' => [true, false, false, false],
            'nullable' => [true, false, false, true],
            'sometimes nullable' => [true, false, false, true],
            'required nullable' => [false, false, false, false],
            'present' => [false, false, false, false],
            'filled' => [true, false, false, false],
            'bail required' => [false, false, false, false],
        ];

        foreach ($sets as $label => $prefix) {
            [$absent, $blank, $spaces, $null] = $expectations[$label];

            yield $label . ': absent' => [
                $label . ': absent',
                [],
                ['age' => [...$prefix, Parse::integer()]],
                $absent,
                $absent ? [] : null,
            ];

            yield $label . ': blank' => [
                $label . ': blank',
                ['age' => ''],
                ['age' => [...$prefix, Parse::integer()]],
                $blank,
                null,
            ];

            yield $label . ': whitespace' => [
                $label . ': whitespace',
                ['age' => '   '],
                ['age' => [...$prefix, Parse::integer()]],
                $spaces,
                null,
            ];

            yield $label . ': null' => [
                $label . ': null',
                ['age' => null],
                ['age' => [...$prefix, Parse::integer()]],
                $null,
                $null ? ['age' => null] : null,
            ];

            // Every rule set accepts a parseable value and produces an int.
            yield $label . ': string digits' => [
                $label . ': string digits',
                ['age' => '42'],
                ['age' => [...$prefix, Parse::integer()]],
                true,
                ['age' => 42],
            ];

            yield $label . ': int' => [
                $label . ': int',
                ['age' => 42],
                ['age' => [...$prefix, Parse::integer()]],
                true,
                ['age' => 42],
            ];
        }
    }

    /**
     * Grammar rejections, seen through the validator rather than the parser.
     *
     * @return iterable<string, array{string, array<mixed, mixed>, array<string, mixed>, bool, array<mixed, mixed>|null}>
     */
    public static function grammarCases(): iterable
    {
        $rejected = [
            'leading zero' => '042',
            'leading plus' => '+42',
            'leading space' => ' 42',
            'trailing space' => '42 ',
            'trailing newline' => "42\n",
            'integral decimal string' => '42.0',
            'scientific notation' => '1e3',
            'hexadecimal' => '0x1A',
            'trailing garbage' => '1foo',
            'words' => 'abc',
            'integral float' => 42.0,
            'true' => true,
            'false' => false,
            'array' => ['42'],
        ];

        foreach ($rejected as $label => $value) {
            yield 'rejects ' . $label => [
                'rejects ' . $label,
                ['age' => $value],
                ['age' => ['required', Parse::integer()]],
                false,
                null,
            ];
        }

        yield 'accepts negative' => [
            'accepts negative',
            ['age' => '-42'],
            ['age' => ['required', Parse::integer()]],
            true,
            ['age' => -42],
        ];

        yield 'accepts zero' => [
            'accepts zero',
            ['age' => '0'],
            ['age' => ['required', Parse::integer()]],
            true,
            ['age' => 0],
        ];
    }

    /**
     * Structure and interaction with ordinary rules.
     *
     * @return iterable<string, array{string, array<mixed, mixed>, array<string, mixed>, bool, array<mixed, mixed>|null}>
     */
    public static function interactionCases(): iterable
    {
        yield 'same compares the original representation' => [
            'same compares the original representation',
            ['a' => '42', 'b' => '42'],
            ['a' => [Parse::integer()], 'b' => ['same:a']],
            true,
            ['a' => 42, 'b' => '42'],
        ];

        yield 'gte compares the original representation' => [
            'gte compares the original representation',
            ['start' => '5', 'end' => '10'],
            ['start' => [Parse::integer()], 'end' => ['gte:start']],
            true,
            ['start' => 5, 'end' => '10'],
        ];

        yield 'paired with the integer predicate' => [
            'paired with the integer predicate',
            ['age' => '42'],
            ['age' => ['required', 'integer', Parse::integer(), 'min:18']],
            true,
            ['age' => 42],
        ];

        yield 'nested attribute' => [
            'nested attribute',
            ['profile' => ['age' => '42']],
            ['profile.age' => ['required', Parse::integer()]],
            true,
            ['profile' => ['age' => 42]],
        ];

        yield 'wildcard elements' => [
            'wildcard elements',
            ['users' => [['age' => '12'], ['age' => '34']]],
            ['users.*.age' => ['required', Parse::integer()]],
            true,
            ['users' => [['age' => 12], ['age' => 34]]],
        ];

        yield 'wildcard with a nullable element' => [
            'wildcard with a nullable element',
            ['users' => [['age' => '12'], ['age' => null]]],
            ['users.*.age' => ['nullable', Parse::integer()]],
            true,
            ['users' => [['age' => 12], ['age' => null]]],
        ];

        yield 'excluded attribute' => [
            'excluded attribute',
            ['age' => '42', 'mode' => 'skip'],
            ['age' => [Parse::integer(), 'exclude_if:mode,skip'], 'mode' => ['required']],
            true,
            ['mode' => 'skip'],
        ];
    }

    /**
     * @param array<mixed, mixed> $data
     * @param array<string, mixed> $rules
     * @param array<mixed, mixed>|null $expectedValidated
     */
    #[DataProvider('presenceCases')]
    #[DataProvider('grammarCases')]
    #[DataProvider('interactionCases')]
    public function testRuntimeOutputStaysWithinTheInferredType(
        string $caseId,
        array $data,
        array $rules,
        bool $expectedPasses,
        ?array $expectedValidated
    ): void {
        $this->assertLaravelValidationCase($caseId, $data, $rules, $expectedPasses, $expectedValidated);
    }
}
