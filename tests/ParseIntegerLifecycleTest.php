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

use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Factory as ValidationFactoryContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\ValidatedInput;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use jbboehr\Rensei\Parse;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

/**
 * Lifecycle behavior of a delayed-write-back parsing rule against real Laravel.
 *
 * These cases pin the hazards found during the validation parsing
 * investigation. Several assert behavior that is currently correct but
 * fragile, so that a future Laravel change fails loudly here rather than
 * silently producing a value the inferred type disagrees with.
 */
#[Group('laravel')]
final class ParseIntegerLifecycleTest extends TestCase
{
    public function testTransformsValidatedOutput(): void
    {
        $validator = self::factory()->make(
            ['age' => '42'],
            ['age' => ['required', Parse::integer()]]
        );

        self::assertTrue($validator->passes());
        self::assertSame(['age' => 42], $validator->validated());
        self::assertSame(['age' => 42], $validator->valid());

        $safe = $validator->safe();
        self::assertInstanceOf(ValidatedInput::class, $safe);
        self::assertSame(['age' => 42], $safe->all());
    }

    public function testOrdinaryRulesObserveTheOriginalRepresentation(): void
    {
        // Immediate write-back would make `same:a` compare int(42) to '42'
        // and fail. Delaying the write keeps every ordinary rule's semantics.
        $validator = self::factory()->make(
            ['a' => '42', 'b' => '42'],
            ['a' => [Parse::integer()], 'b' => ['same:a']]
        );

        self::assertTrue($validator->passes());
        self::assertSame(['a' => 42, 'b' => '42'], $validator->validated());
    }

    public function testASharedInstanceKeepsAttributesApart(): void
    {
        $shared = Parse::integer();
        $validator = self::factory()->make(
            ['a' => '1', 'b' => '2'],
            ['a' => [$shared], 'b' => [$shared]]
        );

        self::assertSame(['a' => 1, 'b' => 2], $validator->validated());
    }

    public function testASharedInstanceKeepsValidatorsApart(): void
    {
        $shared = Parse::integer();
        $factory = self::factory();

        $first = $factory->make(['a' => '1'], ['a' => [$shared]]);
        $second = $factory->make(['a' => '2'], ['a' => [$shared]]);

        self::assertSame(['a' => 1], $first->validated());
        self::assertSame(['a' => 2], $second->validated());
    }

    public function testTransformsWildcardElements(): void
    {
        $validator = self::factory()->make(
            ['users' => [['age' => '12'], ['age' => '34']]],
            ['users.*.age' => ['required', Parse::integer()]]
        );

        self::assertSame(
            ['users' => [['age' => 12], ['age' => 34]]],
            $validator->validated()
        );
    }

    public function testTransformsAssociativeWildcardElements(): void
    {
        $validator = self::factory()->make(
            ['users' => ['a' => ['age' => '12'], 'b' => ['age' => '34']]],
            ['users.*.age' => ['required', Parse::integer()]]
        );

        self::assertSame(
            ['users' => ['a' => ['age' => 12], 'b' => ['age' => 34]]],
            $validator->validated()
        );
    }

    public function testTransformsNestedWildcardElements(): void
    {
        $validator = self::factory()->make(
            ['groups' => [['users' => [['age' => '1'], ['age' => '2']]], ['users' => [['age' => '3']]]]],
            ['groups.*.users.*.age' => ['required', Parse::integer()]]
        );

        self::assertSame(
            ['groups' => [['users' => [['age' => 1], ['age' => 2]]], ['users' => [['age' => 3]]]]],
            $validator->validated()
        );
    }

    public function testLeavesAnAbsentWildcardElementAbsent(): void
    {
        $validator = self::factory()->make(
            ['users' => [['age' => '12'], ['name' => 'x']]],
            ['users.*.age' => ['sometimes', Parse::integer()]]
        );

        self::assertTrue($validator->passes());
        self::assertSame(['users' => [['age' => 12]]], $validator->validated());
    }

    public function testPreservesANullWildcardElementWhenNullable(): void
    {
        $validator = self::factory()->make(
            ['users' => [['age' => '12'], ['age' => null]]],
            ['users.*.age' => ['nullable', Parse::integer()]]
        );

        self::assertTrue($validator->passes());
        self::assertSame(
            ['users' => [['age' => 12], ['age' => null]]],
            $validator->validated()
        );
    }

    public function testOneUnparsableWildcardElementFailsWithoutDisturbingSiblings(): void
    {
        $validator = self::factory()->make(
            ['users' => [['age' => '12'], ['age' => 'nope']]],
            ['users.*.age' => ['required', Parse::integer()]]
        );

        self::assertFalse($validator->passes());
        self::assertSame(['users.1.age'], $validator->errors()->keys());
    }

    public function testTransformsNestedAttributes(): void
    {
        $validator = self::factory()->make(
            ['profile' => ['age' => '42']],
            ['profile.age' => ['required', Parse::integer()]]
        );

        self::assertSame(['profile' => ['age' => 42]], $validator->validated());
    }

    public function testAMissingNestedParentStaysAbsent(): void
    {
        $validator = self::factory()->make([], ['profile.age' => [Parse::integer()]]);

        self::assertTrue($validator->passes());
        self::assertSame([], $validator->validated());
    }

    public function testTransformsUnderAnArrayParent(): void
    {
        $validator = self::factory()->make(
            ['payload' => ['age' => '42']],
            ['payload' => ['required', 'array'], 'payload.age' => ['required', Parse::integer()]]
        );

        self::assertSame(['payload' => ['age' => 42]], $validator->validated());
    }

    public function testRepeatedValidationIsIdempotent(): void
    {
        // Laravel calls passes() more than once on ordinary paths. The second
        // run re-parses an already-parsed int, which the grammar accepts.
        $validator = self::factory()->make(['age' => '42'], ['age' => [Parse::integer()]]);

        self::assertTrue($validator->passes());
        self::assertSame(['age' => 42], $validator->getData());

        self::assertTrue($validator->passes());
        self::assertSame(['age' => 42], $validator->getData());
        self::assertSame(['age' => 42], $validator->validated());
    }

    public function testValidatedIsStableAcrossCalls(): void
    {
        $validator = self::factory()->make(['age' => '42'], ['age' => [Parse::integer()]]);

        self::assertFalse($validator->fails());
        self::assertSame(['age' => 42], $validator->validated());
        self::assertSame(['age' => 42], $validator->validated());
    }

    public function testValidateReturnsParsedOutput(): void
    {
        $validator = self::factory()->make(['age' => '42'], ['age' => [Parse::integer()]]);

        self::assertSame(['age' => 42], $validator->validate());
    }

    public function testAnExcludedAttributeIsNotResurrected(): void
    {
        $validator = self::factory()->make(
            ['age' => '42', 'mode' => 'skip'],
            ['age' => [Parse::integer(), 'exclude_if:mode,skip'], 'mode' => ['required']]
        );

        self::assertTrue($validator->passes());
        self::assertSame(['mode' => 'skip'], $validator->validated());

        // Without the write-back guard the attribute reappears here, even
        // though validated() is driven by the rules and stays correct.
        self::assertSame(['mode' => 'skip'], $validator->getData());
    }

    public function testAnUnconditionallyExcludedAttributeIsNotResurrected(): void
    {
        $validator = self::factory()->make(
            ['age' => '42'],
            ['age' => [Parse::integer(), 'exclude']]
        );

        self::assertTrue($validator->passes());
        self::assertArrayNotHasKey('age', $validator->validated());
        self::assertArrayNotHasKey('age', $validator->getData());
    }

    public function testRejectsAnEscapedDotAttribute(): void
    {
        // Laravel hands rules the decoded name while keying data by an
        // internal placeholder, so the parsed value cannot be written back.
        $validator = self::factory()->make(
            ['a.b' => '42'],
            ['a\.b' => ['required', Parse::integer()]]
        );

        self::assertFalse($validator->passes());
        self::assertStringContainsString('escaped dots', $validator->errors()->first('a.b'));

        // The data must not gain a spurious nested branch either.
        self::assertArrayNotHasKey('a', $validator->getData());
    }

    public function testAnAfterCallbackRegisteredBeforeValidationObservesRawValues(): void
    {
        // A known limitation: a parsing rule can only register its write-back
        // during validation, so it always runs after callbacks registered
        // beforehand. Assert the raw value explicitly, so a future Laravel
        // ordering change fails here rather than silently changing the
        // contract.
        $observed = [];
        $validator = self::factory()->make(['age' => '42'], ['age' => [Parse::integer()]]);
        $validator->after(static function (Validator $validator) use (&$observed): void {
            $observed = $validator->validated();
        });

        self::assertTrue($validator->passes());
        self::assertSame(['age' => '42'], $observed);
        self::assertSame(['age' => 42], $validator->validated());
    }

    public function testStopOnFirstFailureReportsTheParseFailure(): void
    {
        $validator = self::factory()->make(
            ['age' => 'nope', 'name' => ''],
            ['age' => [Parse::integer()], 'name' => ['required']]
        );
        $validator->stopOnFirstFailure();

        self::assertFalse($validator->passes());
        self::assertSame(['age'], $validator->errors()->keys());
    }

    public function testOneFailingParserDoesNotBlockAnother(): void
    {
        $validator = self::factory()->make(
            ['a' => '1', 'b' => 'nope'],
            ['a' => [Parse::integer()], 'b' => [Parse::integer()]]
        );

        self::assertFalse($validator->passes());
        self::assertSame(['b'], $validator->errors()->keys());
        self::assertSame(['a' => 1, 'b' => 'nope'], $validator->getData());
    }

    public function testFormRequestTransformsValidatedOutputOnly(): void
    {
        $request = self::resolveRequest(['age' => '42']);

        self::assertSame(['age' => 42], $request->validated());
        self::assertSame(42, $request->validated('age'));

        $safe = $request->safe();
        self::assertInstanceOf(ValidatedInput::class, $safe);
        self::assertSame(['age' => 42], $safe->all());

        // The request itself is never rewritten.
        self::assertSame(['age' => '42'], $request->all());
        self::assertSame('42', $request->input('age'));
    }

    public function testFormRequestRejectsAnUnparsableValue(): void
    {
        $this->expectException(ValidationException::class);

        self::resolveRequest(['age' => 'nope']);
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    /**
     * @param array<string, mixed> $input
     */
    private static function resolveRequest(array $input): ParseIntegerRequest
    {
        $container = new Container();
        $container->instance(
            ValidationFactoryContract::class,
            new Factory(new Translator(new ArrayLoader(), 'en'), $container)
        );

        $request = ParseIntegerRequest::create('/', 'POST', $input);
        $request->setContainer($container);
        $request->validateResolved();

        return $request;
    }
}

final class ParseIntegerRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return ['age' => ['required', Parse::integer()]];
    }

    /**
     * Report the failure directly. The default redirects, which needs routing
     * infrastructure this test does not build.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function failedValidation($validator): never
    {
        throw new ValidationException($validator);
    }
}
