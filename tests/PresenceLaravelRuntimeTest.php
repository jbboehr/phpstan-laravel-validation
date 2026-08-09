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
final class PresenceLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    use AssertsLaravelValidation;

    /**
     * @return iterable<string, array{string, array<mixed, mixed>, array<string, string>, bool, array<mixed, mixed>|null}>
     */
    public static function namedPresenceCases(): iterable
    {
        yield from self::namedCases([
            'present null' => [['value' => null], ['value' => 'present'], true, ['value' => null]],
            'present blank bypass' => [['value' => ''], ['value' => 'present|integer'], true, ['value' => '']],
            'present absent' => [[], ['value' => 'present'], false, null],
            'sometimes present absent' => [[], ['value' => 'sometimes|present|string'], true, []],
            'present nullable integer' => [
                ['value' => null],
                ['value' => 'present|nullable|integer'],
                true,
                ['value' => null],
            ],
            'present nested' => [
                ['payload' => ['name' => null]],
                ['payload.name' => 'present'],
                true,
                ['payload' => ['name' => null]],
            ],
        ]);
    }

    /**
     * @return iterable<string, array{string, array<mixed, mixed>, array<string, string>, bool, array<mixed, mixed>|null}>
     */
    public static function wildcardProjectionCases(): iterable
    {
        yield from self::namedCases([
            'present wildcard without matches' => [[], ['items.*.value' => 'present'], true, []],
            'present wildcard match' => [
                ['items' => [['value' => null]]],
                ['items.*.value' => 'present'],
                true,
                ['items' => [['value' => null]]],
            ],
            'present array with empty wildcard collection' => [
                ['items' => []],
                ['items' => 'present|array', 'items.*.id' => 'required|integer'],
                true,
                ['items' => []],
            ],
            'present array blank bypass with zero wildcard matches' => [
                ['items' => ''],
                ['items' => 'present|array', 'items.*.id' => 'required|integer'],
                true,
                ['items' => ''],
            ],
            'present array whitespace bypass with zero wildcard matches' => [
                ['items' => " \t"],
                ['items' => 'present|array', 'items.*.id' => 'required|integer'],
                true,
                ['items' => " \t"],
            ],
            'optional array blank bypass with zero wildcard matches' => [
                ['items' => ''],
                ['items' => 'array', 'items.*.id' => 'required|integer'],
                true,
                ['items' => ''],
            ],
            'required array rejects empty collection before wildcard projection' => [
                ['items' => []],
                ['items' => 'required|array', 'items.*.id' => 'required|integer'],
                false,
                null,
            ],
            'required array projects matched wildcard children' => [
                ['items' => [['id' => 1, 'extra' => true]]],
                ['items' => 'required|array', 'items.*.id' => 'required|integer'],
                true,
                ['items' => [['id' => 1]]],
            ],
            'present array preserves unrelated keys before a deeper wildcard' => [
                ['payload' => ['extra' => 1]],
                ['payload' => 'present|array', 'payload.items.*.id' => 'required|integer'],
                true,
                ['payload' => ['extra' => 1]],
            ],
            'present array can be projected away by a matched deeper missing rule' => [
                ['payload' => ['items' => [['other' => 'value']]]],
                ['payload' => 'present|array', 'payload.items.*.id' => 'missing'],
                true,
                [],
            ],
            'one deep wildcard can project away a parent while another has no matches' => [
                ['payload' => ['optional_items' => [['other' => 'value']]]],
                [
                    'payload' => 'present|array',
                    'payload.required_items.*.id' => 'required|string',
                    'payload.optional_items.*.name' => 'string',
                ],
                true,
                [],
            ],
            'literal descendant prevents raw wildcard parent preservation' => [
                ['payload' => ['extra' => 1]],
                [
                    'payload' => 'present|array',
                    'payload.name' => 'sometimes|string',
                    'payload.items.*.id' => 'required|integer',
                ],
                true,
                [],
            ],
            'literal sibling after wildcard prevents raw blank parent preservation' => [
                ['payload' => ''],
                [
                    'payload' => 'present|array',
                    'payload.*.id' => 'required|integer',
                    'payload.name' => 'sometimes|string',
                ],
                true,
                [],
            ],
        ]);
    }

    /**
     * @return iterable<string, array{string, array<mixed, mixed>, array<string, string>, bool, array<mixed, mixed>|null}>
     */
    public static function missingProjectionCases(): iterable
    {
        yield from self::namedCases([
            'missing absent' => [[], ['value' => 'missing'], true, []],
            'missing rejects present null' => [['value' => null], ['value' => 'missing'], false, null],
            'missing nested' => [['payload' => []], ['payload.name' => 'missing'], true, []],
            'missing wildcard with unmatched child' => [
                ['items' => [['other' => 'value']]],
                ['items.*.value' => 'missing'],
                true,
                [],
            ],
            'missing wildcard rejects matched child' => [
                ['items' => [['value' => 'present']]],
                ['items.*.value' => 'missing'],
                false,
                null,
            ],
            'missing wildcard preserves empty array parent before expansion' => [
                ['items' => []],
                ['items' => 'present|array', 'items.*.id' => 'missing'],
                true,
                ['items' => []],
            ],
            'missing wildcard preserves blank array parent before expansion' => [
                ['items' => ''],
                ['items' => 'present|array', 'items.*.id' => 'missing'],
                true,
                ['items' => ''],
            ],
            'missing wildcard projects matched empty child away' => [
                ['items' => [['other' => 'value']]],
                ['items' => 'present|array', 'items.*.id' => 'missing'],
                true,
                [],
            ],
            'missing child projects array parent away' => [
                ['payload' => ['extra' => 1]],
                ['payload' => 'array', 'payload.name' => 'missing'],
                true,
                [],
            ],
            'missing child preserves scalar parent' => [
                ['payload' => 'value'],
                ['payload' => 'string', 'payload.name' => 'missing'],
                true,
                ['payload' => 'value'],
            ],
            'parameterized array parent is preserved around missing child' => [
                ['payload' => ['name' => 'Ada']],
                ['payload' => 'required|array:name', 'payload.child' => 'missing'],
                true,
                ['payload' => ['name' => 'Ada']],
            ],
            'parameterized array parent preserves allowed sibling during child projection' => [
                ['payload' => ['name' => 'Ada', 'child' => 'value']],
                ['payload' => 'required|array:name,child', 'payload.child' => 'required|string'],
                true,
                ['payload' => ['name' => 'Ada', 'child' => 'value']],
            ],
        ]);
    }

    /**
     * @param array<mixed, mixed> $data
     * @param array<string, string> $rules
     * @param array<mixed, mixed>|null $expectedValidated
     */
    #[DataProvider('namedPresenceCases')]
    #[DataProvider('wildcardProjectionCases')]
    #[DataProvider('missingProjectionCases')]
    public function testRuntimeProjection(
        string $caseId,
        array $data,
        array $rules,
        bool $expectedPasses,
        ?array $expectedValidated
    ): void {
        $this->assertLaravelValidationCase($caseId, $data, $rules, $expectedPasses, $expectedValidated);
    }

    /**
     * @param array<string, array{array<mixed, mixed>, array<string, string>, bool, array<mixed, mixed>|null}> $cases
     * @return iterable<string, array{string, array<mixed, mixed>, array<string, string>, bool, array<mixed, mixed>|null}>
     */
    private static function namedCases(array $cases): iterable
    {
        foreach ($cases as $caseId => $case) {
            yield $caseId => [$caseId, ...$case];
        }
    }
}
