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
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class ValidatorMutationLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    public function testSetDataRetainsAStaleSuccessfulValidationResult(): void
    {
        $validator = self::validatedStringValidator();

        $validator->setData(['age' => 123]); // @phpstan-ignore laravelValidation.validatorMutation

        self::assertSame(['age' => 123], self::validatedData($validator));
    }

    public function testSetDataRetainsAStaleFailedValidationResult(): void
    {
        $validator = self::factory()->make(
            ['age' => 123],
            ['age' => 'required|string']
        );

        self::assertFalse($validator->passes());
        $validator->setData(['age' => 'now valid']); // @phpstan-ignore laravelValidation.validatorMutation

        $this->expectException(ValidationException::class);
        self::validatedData($validator);
    }

    public function testSetValueRetainsAStaleSuccessfulValidationResult(): void
    {
        if (!self::validatorSupportsSetValue(Validator::class)) {
            self::markTestSkipped('Validator::setValue() requires Laravel 10.7 or newer.');
        }

        $validator = self::validatedStringValidator();

        $validator->setValue('age', 123); // @phpstan-ignore laravelValidation.validatorMutation

        self::assertSame(['age' => 123], self::validatedData($validator));
    }

    public function testSetRulesRetainsAStaleSuccessfulValidationResult(): void
    {
        $validator = self::validatedStringValidator();

        $validator->setRules(['age' => 'required|integer']); // @phpstan-ignore laravelValidation.validatorMutation

        self::assertSame(['age' => 'old'], self::validatedData($validator));
    }

    public function testAddRulesRetainsAStaleSuccessfulValidationResult(): void
    {
        $validator = self::validatedStringValidator();

        $validator->addRules(['age' => 'integer']); // @phpstan-ignore laravelValidation.validatorMutation

        self::assertSame(['age' => 'old'], self::validatedData($validator));
    }

    public function testSometimesRetainsAStaleSuccessfulValidationResult(): void
    {
        $validator = self::validatedStringValidator();

        $validator->sometimes( // @phpstan-ignore laravelValidation.validatorMutation
            'age',
            'integer',
            static fn (): bool => true
        );

        self::assertSame(['age' => 'old'], self::validatedData($validator));
    }

    private static function validatedStringValidator(): Validator
    {
        $validator = self::factory()->make(
            ['age' => 'old'],
            ['age' => 'required|string']
        );

        self::assertTrue($validator->passes());
        self::assertSame(['age' => 'old'], $validator->validated());

        return $validator;
    }

    /** @return array<mixed, mixed> */
    private static function validatedData(Validator $validator): array
    {
        return $validator->validated();
    }

    /** @param class-string $validatorClass */
    private static function validatorSupportsSetValue(string $validatorClass): bool
    {
        return method_exists($validatorClass, 'setValue');
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }
}
