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

use Illuminate\Auth\GenericUser;
use Illuminate\Auth\RequestGuard;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Application;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Illuminate\Support\Stringable;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Test\Support\LaravelValueType;
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\Type\VerbosityLevel;
use PHPUnit\Framework\Attributes\DataProvider;

final class CurrentPasswordLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    /** @return iterable<string, array{mixed, string, string|null}> */
    public static function acceptedValueProvider(): iterable
    {
        yield 'integer' => [12345, '12345', null];
        yield 'float' => [12.5, '12.5', null];
        yield 'true' => [true, '1', null];
        yield 'false' => [false, '', null];
        yield 'Stringable' => [new Stringable('fixture-password'), 'fixture-password', null];
        yield 'string' => ['fixture-password', 'fixture-password', null];
        yield 'named guard' => [12345, '12345', 'fixture'];
    }

    #[DataProvider('acceptedValueProvider')]
    public function testCurrentPasswordPreservesAcceptedNativeValues(
        mixed $value,
        string $plainText,
        ?string $guardName
    ): void {
        $hasher = new BcryptHasher(['rounds' => 4]);
        $factory = $this->factory($hasher, $hasher->make($plainText), $guardName);
        $rule = 'required|current_password' . ($guardName === null ? '' : ':' . $guardName);
        $validator = $factory->make(['value' => $value], ['value' => $rule]);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => $value], $validator->validated());
        self::assertInferredTypeAccepts($rule, $validator->validated());
    }

    public function testCurrentPasswordRetainsExplicitStringAndPresenceConstraints(): void
    {
        $hasher = new BcryptHasher(['rounds' => 4]);
        $factory = $this->factory($hasher, $hasher->make('12345'));

        self::assertSame([], $factory->make([], ['value' => 'current_password'])->validated());
        self::assertSame(['value' => ''], $factory->make(
            ['value' => ''],
            ['value' => 'current_password']
        )->validated());
        self::assertSame(['value' => null], $factory->make(
            ['value' => null],
            ['value' => 'nullable|current_password']
        )->validated());
        self::assertFalse($factory->make([], ['value' => 'required|current_password'])->passes());
        self::assertFalse($factory->make(
            ['value' => 12345],
            ['value' => 'required|string|current_password']
        )->passes());
        self::assertSame(['value' => '12345'], $factory->make(
            ['value' => '12345'],
            ['value' => 'required|string|current_password']
        )->validated());
        self::assertFalse($factory->make(
            ['value' => 'different-fixture-password'],
            ['value' => 'required|current_password']
        )->passes());
    }

    public function testCurrentPasswordUsesTheConfiguredHasherWithoutANativeTypeGuard(): void
    {
        $value = ['token' => 7];
        $hasher = $this->createMock(Hasher::class);
        $hasher->expects(self::once())->method('check')
            ->with($value, 'stored-fixture-value')->willReturn(true);
        $factory = $this->factory($hasher, 'stored-fixture-value');
        $validator = $factory->make(['value' => $value], ['value' => 'required|current_password']);

        self::assertSame(['value' => $value], $validator->validated());
        self::assertInferredTypeAccepts('required|current_password', $validator->validated());
    }

    /** @param array<string, mixed> $validated */
    private static function assertInferredTypeAccepts(string $rule, array $validated): void
    {
        self::getContainer();
        $context = new LaravelVersionContext('', Application::VERSION);
        $inferred = (new TypeResolver($context))->evaluate(RuleParser::parse(['value' => $rule], $context));
        $observed = LaravelValueType::fromValue($validated);

        self::assertTrue(
            $inferred->accepts($observed, true)->yes(),
            $inferred->describe(VerbosityLevel::precise()) . ' must accept '
                . $observed->describe(VerbosityLevel::precise())
        );
    }

    private function factory(Hasher $hasher, string $storedPassword, ?string $guardName = null): Factory
    {
        $user = new GenericUser(['id' => 1, 'password' => $storedPassword]);
        $guard = new RequestGuard(static fn (): GenericUser => $user, new Request());
        $auth = $this->createMock(AuthFactory::class);
        $auth->method('guard')->with($guardName)->willReturn($guard);
        $container = new Container();
        $container->instance('auth', $auth);
        $container->instance('hash', $hasher);

        return new Factory(new Translator(new ArrayLoader(), 'en'), $container);
    }
}
