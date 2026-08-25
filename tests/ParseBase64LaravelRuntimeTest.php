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

/**
 * Runtime and inference conformance for canonical Base64 decoding.
 */
#[Group('laravel')]
final class ParseBase64LaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    use AssertsLaravelValidation;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::validatorSupportsSetValue(Validator::class)) {
            self::markTestSkipped('Parsing rules require Validator::setValue().');
        }
    }

    /** @return iterable<string, array{string, non-empty-string}> */
    public static function canonicalBase64Cases(): iterable
    {
        yield 'one byte' => ['YQ==', 'a'];
        yield 'two bytes' => ['YWI=', 'ab'];
        yield 'three bytes need no padding' => ['TWFu', 'Man'];
        yield 'text' => ['aGk=', 'hi'];
        yield 'NUL byte' => ['AA==', "\0"];
        yield 'non-UTF-8 bytes' => ['//79', "\xff\xfe\xfd"];
    }

    #[DataProvider('canonicalBase64Cases')]
    public function testDecodesCanonicalBase64IntoBytes(string $encoded, string $decoded): void
    {
        $this->assertLaravelValidationCase(
            'canonical Base64',
            ['payload' => $encoded],
            ['payload' => ['required', Parse::base64()]],
            true,
            ['payload' => $decoded]
        );
    }

    /** @return iterable<string, array{mixed}> */
    public static function invalidBase64Cases(): iterable
    {
        yield 'empty string' => [''];
        yield 'missing required padding' => ['aGk'];
        yield 'extra padding' => ['aGk==='];
        yield 'trailing newline' => ["aGk=\n"];
        yield 'embedded whitespace' => ['a Gk='];
        yield 'URL-safe alphabet' => ['--__'];
        yield 'invalid characters' => ['***'];
        yield 'padding alone' => ['='];
        yield 'integer' => [42];
        yield 'true' => [true];
        yield 'false' => [false];
        yield 'null' => [null];
        yield 'array' => [[]];
        yield 'ordinary object' => [new \stdClass()];
        yield 'Stringable object' => [new ValidationStringable('aGk=')];
    }

    #[DataProvider('invalidBase64Cases')]
    public function testRejectsInvalidOrNonCanonicalBase64(mixed $value): void
    {
        $this->assertLaravelValidationCase(
            'invalid Base64',
            ['payload' => $value],
            ['payload' => [Parse::base64()]],
            false,
            null
        );
    }

    public function testRejectsResources(): void
    {
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        try {
            $this->assertLaravelValidationCase(
                'Base64 resource',
                ['payload' => $resource],
                ['payload' => [Parse::base64()]],
                false,
                null
            );
        } finally {
            fclose($resource);
        }
    }

    public function testPresenceNullabilityNestedPathsAndExclusionsRemainSeparate(): void
    {
        $this->assertLaravelValidationCase(
            'absent optional Base64',
            [],
            ['payload' => [Parse::base64()]],
            true,
            []
        );

        $this->assertLaravelValidationCase(
            'nullable Base64',
            ['payload' => null],
            ['payload' => ['nullable', Parse::base64()]],
            true,
            ['payload' => null]
        );

        $this->assertLaravelValidationCase(
            'nested Base64',
            ['document' => ['payload' => 'aGk=']],
            ['document.payload' => ['required', Parse::base64()]],
            true,
            ['document' => ['payload' => 'hi']]
        );

        $this->assertLaravelValidationCase(
            'wildcard Base64',
            ['documents' => [['payload' => 'YQ=='], ['payload' => 'YWI=']]],
            ['documents.*.payload' => ['required', Parse::base64()]],
            true,
            ['documents' => [['payload' => 'a'], ['payload' => 'ab']]]
        );

        $this->assertLaravelValidationCase(
            'excluded Base64',
            ['payload' => 'aGk='],
            ['payload' => ['exclude', Parse::base64()]],
            true,
            []
        );
    }

    /** @param class-string $validatorClass */
    private static function validatorSupportsSetValue(string $validatorClass): bool
    {
        return method_exists($validatorClass, 'setValue');
    }
}
