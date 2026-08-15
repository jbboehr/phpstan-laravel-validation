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

use Composer\InstalledVersions;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus;
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class ArrayRuleBuilderLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    private const ARRAY_RULE_CLASS = 'Illuminate\\Validation\\Rules\\ArrayRule';

    public function testEmptyArrayParameterPermitsOnlyTheEmptyStringKey(): void
    {
        $emptyKey = self::factory()->make(
            ['payload' => ['' => 'value']],
            ['payload' => 'required|array:']
        );
        self::assertTrue($emptyKey->passes());
        self::assertSame(['payload' => ['' => 'value']], $emptyKey->validated());

        self::assertFalse(self::factory()->make(
            ['payload' => ['other' => 'value']],
            ['payload' => 'required|array:']
        )->passes());
    }

    public function testRuntimeContractFollowsItsLaravelIntroductionBoundary(): void
    {
        $supportsBuilder = version_compare(self::frameworkVersion(), '11.7.0', '>=');
        self::assertSame(
            $supportsBuilder,
            (new \ReflectionClass(Rule::class))->hasMethod('array')
        );
        self::assertSame($supportsBuilder, class_exists(self::ARRAY_RULE_CLASS));

        if (!$supportsBuilder) {
            return;
        }

        $bare = $this->arrayRule();
        $empty = $this->arrayRule([]);
        $null = $this->arrayRule(null);
        $false = $this->arrayRule(false);
        $blank = $this->arrayRule('');
        $keyed = $this->arrayRule(['name', 'email']);
        $variadic = $this->arrayRule('name', 'email');
        $enum = $this->arrayRule([PureValidationStatus::Draft]);
        $backedEnum = $this->arrayRule([StringValidationStatus::Draft]);
        $comma = $this->arrayRule(['a,b']);
        $directBare = $this->directArrayRule();
        $directEmpty = $this->directArrayRule([]);
        $directNull = $this->directArrayRule(null);
        $directKeyed = $this->directArrayRule(['name', 'email']);
        $directVariadic = $this->directArrayRule('name', 'email');

        self::assertSame('array', (string) $bare);
        self::assertSame('array', (string) $empty);
        self::assertSame('array:', $this->withoutDeprecations(static fn (): string => (string) $null));
        self::assertSame('array:', $this->withoutDeprecations(static fn (): string => (string) $false));
        self::assertSame('array:', (string) $blank);
        self::assertSame('array:name,email', (string) $keyed);
        self::assertSame('array:name,email', (string) $variadic);
        self::assertSame('array:Draft', (string) $enum);
        self::assertSame('array:draft', (string) $backedEnum);
        self::assertSame('array:a,b', (string) $comma);
        self::assertSame('array', (string) $directBare);
        self::assertSame('array', (string) $directEmpty);
        self::assertSame(
            'array:',
            $this->withoutDeprecations(static fn (): string => (string) $directNull)
        );
        self::assertSame('array:name,email', (string) $directKeyed);
        self::assertSame('array:name,email', (string) $directVariadic);

        $backedEnumKey = self::factory()->make(
            ['payload' => ['draft' => 'value']],
            ['payload' => ['required', $backedEnum]]
        );
        self::assertTrue($backedEnumKey->passes());
        self::assertSame(['payload' => ['draft' => 'value']], $backedEnumKey->validated());

        $commaSplitKeys = self::factory()->make(
            ['payload' => ['a' => 1, 'b' => 2]],
            ['payload' => ['required', $comma]]
        );
        self::assertTrue($commaSplitKeys->passes());
        self::assertSame(['payload' => ['a' => 1, 'b' => 2]], $commaSplitKeys->validated());

        self::assertFalse(self::factory()->make(
            ['payload' => ['a,b' => 1]],
            ['payload' => ['required', $comma]]
        )->passes());

        $directValidator = self::factory()->make(
            ['payload' => ['name' => 'Ada']],
            ['payload' => ['required', $directKeyed]]
        );
        self::assertTrue($directValidator->passes());
        self::assertSame(['payload' => ['name' => 'Ada']], $directValidator->validated());

        $input = ['payload' => ['name' => 'Ada', 'extra' => true]];
        $bareNested = self::factory()->make($input, [
            'payload' => ['required', $bare],
            'payload.name' => 'required|string',
        ]);
        self::assertTrue($bareNested->passes());
        self::assertSame(['payload' => ['name' => 'Ada']], $bareNested->validated());

        $emptyNested = self::factory()->make($input, [
            'payload' => ['required', $empty],
            'payload.name' => 'required|string',
        ]);
        self::assertTrue($emptyNested->passes());
        self::assertSame(['payload' => ['name' => 'Ada']], $emptyNested->validated());

        $keyedInput = ['payload' => ['name' => 'Ada']];
        $keyedMissing = self::factory()->make($keyedInput, [
            'payload' => ['required', $this->arrayRule(['name'])],
            'payload.child' => 'missing',
        ]);
        self::assertTrue($keyedMissing->passes());
        self::assertSame($keyedInput, $keyedMissing->validated());

        $bareMissing = self::factory()->make($keyedInput, [
            'payload' => ['required', $this->arrayRule()],
            'payload.child' => 'missing',
        ]);
        self::assertTrue($bareMissing->passes());
        self::assertSame([], $bareMissing->validated());

        $nullKey = self::factory()->make(
            ['payload' => ['' => 'value']],
            ['payload' => ['required', $null]]
        );
        self::assertTrue($this->withoutDeprecations($nullKey->passes(...)));
        self::assertSame(['payload' => ['' => 'value']], $nullKey->validated());

        $keyedExtra = self::factory()->make($input, [
            'payload' => ['required', $keyed],
        ]);
        self::assertFalse($keyedExtra->passes());
    }

    public function testFloatKeysRemainRuntimePrecisionDependent(): void
    {
        if (version_compare(self::frameworkVersion(), '11.7.0', '<')) {
            self::assertFalse(class_exists(self::ARRAY_RULE_CLASS));
            return;
        }

        $previousPrecision = ini_get('precision');

        try {
            self::assertNotFalse(ini_set('precision', '1'));

            foreach ([
                'factory' => $this->arrayRule([2.5]),
                'direct' => $this->directArrayRule([2.5]),
            ] as $caseId => $rule) {
                self::assertSame('array:2', (string) $rule, $caseId);
                $validator = self::factory()->make(
                    ['payload' => [2 => 'value']],
                    ['payload' => ['required', $rule]]
                );
                self::assertTrue($validator->passes(), $caseId);
                self::assertSame(['payload' => [2 => 'value']], $validator->validated(), $caseId);
            }
        } finally {
            ini_set('precision', $previousPrecision);
        }
    }

    private function arrayRule(mixed ...$arguments): \Stringable
    {
        $rule = (new \ReflectionMethod(Rule::class, 'array'))->invoke(null, ...$arguments);
        self::assertInstanceOf(\Stringable::class, $rule);

        return $rule;
    }

    private function directArrayRule(mixed ...$arguments): \Stringable
    {
        $className = self::ARRAY_RULE_CLASS;
        if (!class_exists($className)) {
            throw new \LogicException('ArrayRule is unavailable.');
        }

        $rule = new $className(...$arguments);
        self::assertInstanceOf(\Stringable::class, $rule);

        return $rule;
    }

    private function withoutDeprecations(\Closure $callback): mixed
    {
        set_error_handler(static fn (int $severity): bool => $severity === E_DEPRECATED);
        try {
            return $callback();
        } finally {
            restore_error_handler();
        }
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    private static function frameworkVersion(): string
    {
        return ltrim((string) InstalledVersions::getPrettyVersion('laravel/framework'), 'v');
    }
}
