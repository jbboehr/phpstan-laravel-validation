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
use Illuminate\Validation\Validator;
use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsLaravelValidation;
use jbboehr\Rensei\Parse;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

/**
 * Runtime and inference conformance for accepted and declined token parsing.
 */
#[Group('laravel')]
final class ParseAcceptedDeclinedLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    use AssertsLaravelValidation;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::validatorSupportsSetValue(Validator::class)) {
            self::markTestSkipped('Parsing rules require Validator::setValue().');
        }
    }

    /** @return iterable<string, array{mixed, bool}> */
    public static function acceptedCases(): iterable
    {
        yield 'yes' => ['yes', true];
        yield 'on' => ['on', true];
        yield 'string one' => ['1', true];
        yield 'one' => [1, true];
        yield 'true' => [true, true];
        yield 'string true' => ['true', true];

        yield 'no' => ['no', false];
        yield 'off' => ['off', false];
        yield 'string zero' => ['0', false];
        yield 'zero' => [0, false];
        yield 'false' => [false, false];
        yield 'string false' => ['false', false];
        yield 'float one' => [1.0, false];
        yield 'uppercase yes' => ['YES', false];
        yield 'blank' => ['', false];
        yield 'whitespace' => [' ', false];
        yield 'null' => [null, false];
        yield 'array' => [[], false];
        yield 'object' => [new \stdClass(), false];
    }

    /** @return iterable<string, array{mixed, bool}> */
    public static function declinedCases(): iterable
    {
        yield 'no' => ['no', true];
        yield 'off' => ['off', true];
        yield 'string zero' => ['0', true];
        yield 'zero' => [0, true];
        yield 'false' => [false, true];
        yield 'string false' => ['false', true];

        yield 'yes' => ['yes', false];
        yield 'on' => ['on', false];
        yield 'string one' => ['1', false];
        yield 'one' => [1, false];
        yield 'true' => [true, false];
        yield 'string true' => ['true', false];
        yield 'float zero' => [0.0, false];
        yield 'uppercase no' => ['NO', false];
        yield 'blank' => ['', false];
        yield 'whitespace' => [' ', false];
        yield 'null' => [null, false];
        yield 'array' => [[], false];
        yield 'object' => [new \stdClass(), false];
    }

    #[DataProvider('acceptedCases')]
    public function testAcceptedParserMatchesLaravelAndProducesTrue(mixed $value, bool $expectedPasses): void
    {
        $laravel = self::factory()->make(
            ['terms' => $value],
            ['terms' => ['required', 'accepted']]
        );
        self::assertSame($expectedPasses, $laravel->passes());

        $this->assertLaravelValidationCase(
            'accepted token ' . get_debug_type($value),
            ['terms' => $value],
            ['terms' => ['required', Parse::accepted()]],
            $expectedPasses,
            $expectedPasses ? ['terms' => true] : null
        );
    }

    #[DataProvider('declinedCases')]
    public function testDeclinedParserMatchesLaravelAndProducesFalse(mixed $value, bool $expectedPasses): void
    {
        $laravel = self::factory()->make(
            ['opt_out' => $value],
            ['opt_out' => ['required', 'declined']]
        );
        self::assertSame($expectedPasses, $laravel->passes());

        $this->assertLaravelValidationCase(
            'declined token ' . get_debug_type($value),
            ['opt_out' => $value],
            ['opt_out' => ['required', Parse::declined()]],
            $expectedPasses,
            $expectedPasses ? ['opt_out' => false] : null
        );
    }

    public function testPresenceAndNullabilityRemainExplicit(): void
    {
        $this->assertLaravelValidationCase(
            'optional accepted token',
            [],
            ['terms' => [Parse::accepted()]],
            true,
            []
        );

        $this->assertLaravelValidationCase(
            'required accepted token',
            [],
            ['terms' => ['required', Parse::accepted()]],
            false,
            null
        );

        $this->assertLaravelValidationCase(
            'nullable declined token',
            ['opt_out' => null],
            ['opt_out' => ['nullable', Parse::declined()]],
            true,
            ['opt_out' => null]
        );
    }

    public function testOrdinaryPredicatesSeeTheOriginalTokens(): void
    {
        $this->assertLaravelValidationCase(
            'accepted predicate before write-back',
            ['terms' => 'yes'],
            ['terms' => ['accepted', Parse::accepted()]],
            true,
            ['terms' => true]
        );

        $this->assertLaravelValidationCase(
            'declined predicate after parser',
            ['opt_out' => 'off'],
            ['opt_out' => [Parse::declined(), 'declined']],
            true,
            ['opt_out' => false]
        );
    }

    public function testNestedWildcardAndExcludedValues(): void
    {
        $this->assertLaravelValidationCase(
            'nested and wildcard tokens',
            [
                'settings' => ['terms' => 'on'],
                'users' => [
                    ['opt_out' => 'no'],
                    ['opt_out' => 'false'],
                ],
            ],
            [
                'settings.terms' => ['required', Parse::accepted()],
                'users.*.opt_out' => ['required', Parse::declined()],
            ],
            true,
            [
                'settings' => ['terms' => true],
                'users' => [
                    ['opt_out' => false],
                    ['opt_out' => false],
                ],
            ]
        );

        $this->assertLaravelValidationCase(
            'excluded accepted token',
            ['terms' => 'yes'],
            ['terms' => ['exclude', Parse::accepted()]],
            true,
            []
        );
    }

    public function testACompletedValidatorCannotBeReused(): void
    {
        $validator = self::factory()->make(
            ['terms' => 'yes'],
            ['terms' => ['required', Parse::accepted()]]
        );

        self::assertTrue($validator->passes());
        self::assertTrue($validator->fails());
        self::assertSame(
            ['A validator containing parsing rules cannot be reused.'],
            $validator->errors()->get('terms')
        );
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    /** @param class-string $validatorClass */
    private static function validatorSupportsSetValue(string $validatorClass): bool
    {
        return method_exists($validatorClass, 'setValue');
    }
}
