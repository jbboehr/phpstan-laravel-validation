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

use Illuminate\Validation\Validator;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\ValidationStringable;
use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsLaravelValidation;
use jbboehr\Rensei\Parse;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use RuntimeException;
use Stringable;
use Throwable;
use TypeError;

/**
 * Runtime and inference conformance for `Parse::string()`.
 */
#[Group('laravel')]
final class ParseStringLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    use AssertsLaravelValidation;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::validatorSupportsSetValue(Validator::class)) {
            self::markTestSkipped('Parsing rules require Validator::setValue().');
        }
    }

    /**
     * @param class-string $validatorClass
     */
    private static function validatorSupportsSetValue(string $validatorClass): bool
    {
        return method_exists($validatorClass, 'setValue');
    }

    /**
     * @return iterable<string, array{mixed, string|null}>
     */
    public static function valueCases(): iterable
    {
        yield 'plain string' => ['plain', 'plain'];
        yield 'empty string' => ['', ''];
        yield 'whitespace string' => [' ', ' '];
        yield 'numeric string' => ['0042', '0042'];
        yield 'zero integer' => [0, '0'];
        yield 'positive integer' => [42, '42'];
        yield 'negative integer' => [-42, '-42'];
        yield 'maximum integer' => [PHP_INT_MAX, (string) PHP_INT_MAX];
        yield 'stringable object' => [new ValidationStringable('object text'), 'object text'];
        yield 'empty stringable object' => [new ValidationStringable(''), ''];

        yield 'integral float' => [42.0, null];
        yield 'fractional float' => [1.5, null];
        yield 'infinity' => [INF, null];
        yield 'true' => [true, null];
        yield 'false' => [false, null];
        yield 'null' => [null, null];
        yield 'array' => [[], null];
        yield 'ordinary object' => [new \stdClass(), null];
    }

    #[DataProvider('valueCases')]
    public function testProducesStringsFromOnlySupportedRepresentations(mixed $value, ?string $expected): void
    {
        $this->assertLaravelValidationCase(
            'string ' . get_debug_type($value),
            ['value' => $value],
            ['value' => [Parse::string()]],
            $expected !== null,
            $expected === null ? null : ['value' => $expected]
        );
    }

    public function testRejectsResources(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        try {
            $this->assertLaravelValidationCase(
                'string resource',
                ['value' => $resource],
                ['value' => [Parse::string()]],
                false,
                null
            );
        } finally {
            fclose($resource);
        }
    }

    /**
     * @return iterable<string, array{Stringable}>
     */
    public static function failedStringableCases(): iterable
    {
        yield 'application exception' => [new class () implements Stringable {
            public function __toString(): string
            {
                throw new RuntimeException('String conversion failed.');
            }
        }];

        yield 'return-contract error' => [new class () implements Stringable {
            public function __toString(): string
            {
                throw new TypeError('String conversion returned the wrong type.');
            }
        }];
    }

    #[DataProvider('failedStringableCases')]
    public function testFailedStringableConversionsBecomeValidationFailures(Stringable $value): void
    {
        try {
            $this->assertLaravelValidationCase(
                'failed Stringable conversion',
                ['value' => $value],
                ['value' => [Parse::string()]],
                false,
                null
            );
        } catch (Throwable $throwable) {
            self::fail(sprintf(
                '%s escaped validation instead of becoming a validation failure: %s',
                $throwable::class,
                $throwable->getMessage()
            ));
        }
    }

    public function testPresenceNullabilityAndNestedPathsRemainSeparate(): void
    {
        $this->assertLaravelValidationCase(
            'absent optional string',
            [],
            ['value' => [Parse::string()]],
            true,
            []
        );

        $this->assertLaravelValidationCase(
            'nullable string',
            ['value' => null],
            ['value' => ['nullable', Parse::string()]],
            true,
            ['value' => null]
        );

        $this->assertLaravelValidationCase(
            'nested string',
            ['profile' => ['id' => 42]],
            ['profile.id' => ['required', Parse::string()]],
            true,
            ['profile' => ['id' => '42']]
        );

        $this->assertLaravelValidationCase(
            'wildcard strings',
            ['users' => [['id' => 1], ['id' => new ValidationStringable('2')]]],
            ['users.*.id' => ['required', Parse::string()]],
            true,
            ['users' => [['id' => '1'], ['id' => '2']]]
        );

        $this->assertLaravelValidationCase(
            'excluded string',
            ['value' => 42],
            ['value' => ['exclude', Parse::string()]],
            true,
            []
        );
    }

    public function testAdjacentLaravelRulesObserveTheOriginalRepresentation(): void
    {
        $this->assertLaravelValidationCase(
            'integer predicate sees native integer',
            ['value' => 42],
            ['value' => ['required', 'integer', Parse::string()]],
            true,
            ['value' => '42']
        );

        $this->assertLaravelValidationCase(
            'integral float is narrower than Laravel integer',
            ['value' => 42.0],
            ['value' => ['required', 'integer', Parse::string()]],
            false,
            null
        );
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function nonCanonicalLaravelIntegerStrings(): iterable
    {
        yield 'leading plus' => ['+42'];
        yield 'leading whitespace' => [' 42'];
        yield 'trailing whitespace' => ['42 '];
    }

    #[DataProvider('nonCanonicalLaravelIntegerStrings')]
    public function testPreservesNonCanonicalStringsAcceptedByLaravelInteger(string $value): void
    {
        $this->assertLaravelValidationCase(
            'non-canonical Laravel integer string',
            ['value' => $value],
            ['value' => ['required', 'integer', Parse::string()]],
            true,
            ['value' => $value]
        );
    }
}
