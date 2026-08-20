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
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\BasicRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ContainerInjectedRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\InheritedEmptyWithValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\KeyedValidatedRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\NumericKeyValidatedRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\IntermediateWithValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ThisConstantChildRequest;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\TraitWithValidatorRequest;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantStringType;

final class FormRequestLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    public function testConventionalFormRequestUsesRulesAndPreservesValues(): void
    {
        self::getContainer();

        $request = $this->resolveRequest(BasicRequest::class, [
            'name' => 'Ada',
            'age' => '42',
        ]);

        self::assertSame([
            'name' => 'Ada',
            'age' => '42',
        ], $request->validated());

        $inferred = (new TypeResolver())->evaluate(RuleParser::parse($request->rules()));
        $actualBuilder = ConstantArrayTypeBuilder::createEmpty();
        $actualBuilder->setOffsetValueType(
            new ConstantStringType('name'),
            new ConstantStringType('Ada')
        );
        $actualBuilder->setOffsetValueType(
            new ConstantStringType('age'),
            new ConstantStringType('42')
        );
        self::assertTrue($inferred->accepts($actualBuilder->getArray(), true)->yes());
    }

    public function testRulesMethodDependenciesAreResolvedThroughTheContainer(): void
    {
        $request = $this->resolveRequest(ContainerInjectedRequest::class, [
            'injected' => ['value'],
        ]);

        self::assertSame(['injected' => ['value']], $request->validated());
    }

    public function testInheritedThisConstantUsesTheConcreteRequestClass(): void
    {
        $request = $this->resolveRequest(ThisConstantChildRequest::class, [
            'child' => ['value'],
        ]);

        self::assertSame(['child' => 'required|array'], $request->rules());
        self::assertSame(['child' => ['value']], $request->validated());
    }

    public function testInheritedEmptyWithValidatorLeavesValidatedOutputUnchanged(): void
    {
        $request = $this->resolveRequest(InheritedEmptyWithValidatorRequest::class, [
            'inherited_empty_hook' => 'kept',
        ]);

        self::assertSame(
            ['inherited_empty_hook' => 'kept'],
            $request->validated()
        );
    }

    public function testKeyedValidatedDelegatesToDataGet(): void
    {
        $request = $this->resolveRequest(KeyedValidatedRequest::class, [
            'name' => 'Ada',
            'nickname' => null,
            'profile' => ['email' => 'ada@example.com'],
        ]);

        self::assertSame('Ada', $request->validated('name'));
        self::assertNull($request->validated('nickname', 'fallback'));
        self::assertSame('ada@example.com', $request->validated('profile.email'));
        self::assertSame('fallback', $request->validated('profile.note', 'fallback'));
        self::assertSame('fallback', $request->validated('absent', 'fallback'));
        self::assertSame('lazy', $request->validated('absent', static fn (): string => 'lazy'));
    }

    public function testIntegerKeyedValidatedUsesLaravelArrayKeySemantics(): void
    {
        $request = $this->resolveRequest(NumericKeyValidatedRequest::class, [0 => 'zero']);

        self::assertSame('zero', $request->validated(0));
        self::assertSame('zero', $request->validated('0'));
        self::assertNull($request->validated(1));
    }

    public function testIntermediateWithValidatorHookCanReplaceTheEffectiveRules(): void
    {
        $request = $this->resolveRequest(IntermediateWithValidatorRequest::class, [
            'extra' => 'kept',
        ]);

        self::assertSame(['extra' => 'kept'], $request->validated());
    }

    public function testTraitWithValidatorHookCanReplaceTheEffectiveRules(): void
    {
        $request = $this->resolveRequest(TraitWithValidatorRequest::class, [
            'extra' => 'kept',
        ]);

        self::assertSame(['extra' => 'kept'], $request->validated());
    }

    public function testWithValidatorCanReplaceTheEffectiveRules(): void
    {
        $request = $this->resolveRequest(RuntimeWithValidatorRequest::class, [
            'id' => '42',
            'extra' => 'kept',
        ]);

        self::assertSame([
            'id' => '42',
            'extra' => 'kept',
        ], $request->validated());
    }

    public function testPassedValidationCanReplaceRulesAfterSuccessfulValidation(): void
    {
        $request = $this->resolveRequest(RuntimePassedValidationRequest::class, [
            'id' => '42',
            'extra' => 'not checked by the replacement rules',
        ]);

        self::assertSame([
            'extra' => 'not checked by the replacement rules',
        ], $request->validated());
    }

    public function testCustomValidatorCanIgnoreRulesMethod(): void
    {
        $request = $this->resolveRequest(RuntimeCustomValidatorRequest::class, [
            'id' => 'not an integer',
            'extra' => 'kept',
        ]);

        self::assertSame(['extra' => 'kept'], $request->validated());
    }

    /**
     * @template T of FormRequest
     * @param class-string<T> $requestClass
     * @param array<array-key, mixed> $input
     * @return T
     */
    private function resolveRequest(string $requestClass, array $input): FormRequest
    {
        $container = new Container();
        $factory = new Factory(
            new Translator(new ArrayLoader(), 'en'),
            $container
        );
        $container->instance(ValidationFactoryContract::class, $factory);

        $request = $requestClass::create('/', 'POST', $input);
        $request->setContainer($container);
        $request->validateResolved();

        return $request;
    }
}

final class RuntimeWithValidatorRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['id' => 'required|integer'];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->setRules([ // @phpstan-ignore laravelValidation.validatorMutation
            'id' => 'required|integer',
            'extra' => 'required|string',
        ]);
    }
}

final class RuntimePassedValidationRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['id' => 'required|integer'];
    }

    protected function passedValidation(): void
    {
        $validator = $this->getValidatorInstance();
        if (!$validator instanceof Validator) {
            throw new \LogicException('Expected Laravel validator implementation.');
        }

        $validator->setRules([ // @phpstan-ignore laravelValidation.validatorMutation
            'extra' => 'required|string',
        ]);
    }
}

final class RuntimeCustomValidatorRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['id' => 'required|integer'];
    }

    public function validator(ValidationFactoryContract $factory): ValidatorContract
    {
        return $factory->make($this->all(), [
            'extra' => 'required|string',
        ]);
    }
}
