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

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsLaravelValidation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use ReflectionMethod;

#[Group('laravel')]
final class RequiredArrayKeysLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    use AssertsLaravelValidation;

    /**
     * @return iterable<string, array{string, array<mixed, mixed>, array<string, string>, bool, array<mixed, mixed>|null}>
     */
    public static function cases(): iterable
    {
        yield from self::namedCases([
            'absent field bypasses the non-implicit rule' => [
                [],
                ['user' => 'required_array_keys:name'],
                true,
                [],
            ],
            'blank field bypasses the non-implicit rule' => [
                ['user' => ''],
                ['user' => 'required_array_keys:name'],
                true,
                ['user' => ''],
            ],
            'present null fails the array predicate' => [
                ['user' => null],
                ['user' => 'required_array_keys:name'],
                false,
                null,
            ],
            'required offsets may contain null and extra keys are preserved' => [
                ['user' => ['name' => null, 'extra' => true]],
                ['user' => 'required|required_array_keys:name'],
                true,
                ['user' => ['name' => null, 'extra' => true]],
            ],
            'missing required offset fails' => [
                ['user' => ['name' => 'Ada']],
                ['user' => 'required|required_array_keys:name,email'],
                false,
                null,
            ],
            'numeric offset is interpreted as a PHP integer array key' => [
                ['user' => [0 => 'zero']],
                ['user' => 'required|required_array_keys:0'],
                true,
                ['user' => [0 => 'zero']],
            ],
            'no parameters still requires an array when evaluated' => [
                ['user' => []],
                ['user' => 'required_array_keys'],
                true,
                ['user' => []],
            ],
            'bare array projects a matching required child' => [
                ['user' => ['name' => 'Ada', 'extra' => true]],
                ['user' => 'required|array|required_array_keys:name', 'user.name' => 'string'],
                true,
                ['user' => ['name' => 'Ada']],
            ],
            'bare array projects a matching numeric required child' => [
                ['user' => [0 => 'zero', 'extra' => true]],
                ['user' => 'required|array|required_array_keys:0', 'user.0' => 'string'],
                true,
                ['user' => [0 => 'zero']],
            ],
            'required input key without a child rule is not projected' => [
                ['user' => ['name' => 'Ada', 'email' => 'ada@example.test']],
                ['user' => 'required|array|required_array_keys:name', 'user.email' => 'string'],
                true,
                ['user' => ['email' => 'ada@example.test']],
            ],
            'required key with only a grandchild rule can still disappear' => [
                ['user' => ['name' => '']],
                ['user' => 'required|array|required_array_keys:name', 'user.name.first' => 'string'],
                true,
                [],
            ],
            'required array keys alone preserve the complete parent' => [
                ['user' => ['name' => 'Ada', 'extra' => true]],
                ['user' => 'required|required_array_keys:name', 'user.name' => 'string'],
                true,
                ['user' => ['name' => 'Ada', 'extra' => true]],
            ],
            'allowed-key array preserves its complete permitted parent' => [
                ['user' => ['name' => 'Ada', 'email' => 'ada@example.test']],
                ['user' => 'required|array:name,email|required_array_keys:name'],
                true,
                ['user' => ['name' => 'Ada', 'email' => 'ada@example.test']],
            ],
            'blank present bare array can disappear during projection' => [
                ['user' => ''],
                ['user' => 'present|array|required_array_keys:name', 'user.name' => 'string'],
                true,
                [],
            ],
        ]);
    }

    /**
     * @param array<mixed, mixed> $data
     * @param array<string, string> $rules
     * @param array<mixed, mixed>|null $expectedValidated
     */
    #[DataProvider('cases')]
    public function testRuntimeBehavior(
        string $caseId,
        array $data,
        array $rules,
        bool $expectedPasses,
        ?array $expectedValidated
    ): void {
        $this->assertLaravelValidationCase($caseId, $data, $rules, $expectedPasses, $expectedValidated);
    }

    public function testFloatParametersUseLaravelArrayKeyCoercion(): void
    {
        $factory = new Factory(new Translator(new ArrayLoader(), 'en'));
        $validator = $factory->make([], []);
        $method = new ReflectionMethod($validator, 'validateRequiredArrayKeys');

        self::assertTrue($method->invoke($validator, 'user', ['1.5' => 'value'], [1.5]) === true);
        self::assertTrue($method->invoke($validator, 'user', [1 => 'value'], [1.5]) === false);
        self::assertTrue($method->invoke($validator, 'user', [1 => 'value'], [1.0]) === true);
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
