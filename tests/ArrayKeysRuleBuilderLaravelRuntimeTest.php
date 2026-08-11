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
final class ArrayKeysRuleBuilderLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    public function testRuntimeContractFollowsItsLaravelIntroductionBoundary(): void
    {
        $supportsBuilder = version_compare(self::frameworkVersion(), '13.24.0', '>=');
        self::assertSame(
            $supportsBuilder,
            (new \ReflectionClass(Rule::class))->hasMethod('arrayKeys')
        );

        if (!$supportsBuilder) {
            return;
        }

        $empty = $this->arrayKeysRule([]);
        $blank = $this->arrayKeysRule('');
        $null = $this->arrayKeysRule(null);
        $false = $this->arrayKeysRule(false);
        $keyed = $this->arrayKeysRule(['name', 'email']);
        $variadic = $this->arrayKeysRule('name', 'email');
        $enum = $this->arrayKeysRule([PureValidationStatus::Draft]);
        $backedEnum = $this->arrayKeysRule([StringValidationStatus::Draft]);
        $comma = $this->arrayKeysRule(['a,b']);
        $numeric = $this->arrayKeysRule([0, '01']);

        self::assertSame('array_keys:', (string) $empty);
        self::assertSame('array_keys:', (string) $blank);
        self::assertSame('array_keys:', $this->withoutDeprecations(static fn (): string => (string) $null));
        self::assertSame('array_keys:', $this->withoutDeprecations(static fn (): string => (string) $false));
        self::assertSame('array_keys:name,email', (string) $keyed);
        self::assertSame('array_keys:name,email', (string) $variadic);
        self::assertSame('array_keys:Draft', (string) $enum);
        self::assertSame('array_keys:draft', (string) $backedEnum);
        self::assertSame('array_keys:a,b', (string) $comma);
        self::assertSame('array_keys:0,01', (string) $numeric);

        $keyedValidator = self::factory()->make(
            ['payload' => ['name' => 'Ada']],
            ['payload' => ['required', $keyed]]
        );
        self::assertTrue($keyedValidator->passes());
        self::assertSame(['payload' => ['name' => 'Ada']], $keyedValidator->validated());

        $backedEnumValidator = self::factory()->make(
            ['payload' => ['draft' => 'value']],
            ['payload' => ['required', $backedEnum]]
        );
        self::assertTrue($backedEnumValidator->passes());
        self::assertSame(['payload' => ['draft' => 'value']], $backedEnumValidator->validated());

        foreach (
            [
                'empty array' => [$empty, false],
                'blank string' => [$blank, false],
                'null' => [$null, true],
                'false' => [$false, true],
            ] as $caseId => [$emptyKeyBuilder, $expectDeprecation]
        ) {
            $emptyKeyValidator = self::factory()->make(
                ['payload' => ['' => 'value']],
                ['payload' => ['required', $emptyKeyBuilder]]
            );
            $passes = $expectDeprecation
                ? $this->withoutDeprecations($emptyKeyValidator->passes(...))
                : $emptyKeyValidator->passes();
            self::assertTrue($passes, $caseId);
            self::assertSame(
                ['payload' => ['' => 'value']],
                $emptyKeyValidator->validated(),
                $caseId
            );
        }

        $commaValidator = self::factory()->make(
            ['payload' => ['a' => 1, 'b' => 2]],
            ['payload' => ['required', $comma]]
        );
        self::assertTrue($commaValidator->passes());
        self::assertSame(['payload' => ['a' => 1, 'b' => 2]], $commaValidator->validated());
        self::assertFalse(self::factory()->make(
            ['payload' => ['a,b' => 1]],
            ['payload' => ['required', $comma]]
        )->passes());

        $numericValidator = self::factory()->make(
            ['payload' => [0 => 'zero', '01' => 'leading']],
            ['payload' => ['required', $numeric]]
        );
        self::assertTrue($numericValidator->passes());
        self::assertSame(
            ['payload' => [0 => 'zero', '01' => 'leading']],
            $numericValidator->validated()
        );

        $nestedValidator = self::factory()->make(
            ['payload' => ['name' => 'Ada', 'email' => 'ada@example.test']],
            [
                'payload' => ['required', $keyed],
                'payload.name' => 'required|string',
            ]
        );
        self::assertTrue($nestedValidator->passes());
        self::assertSame(
            ['payload' => ['name' => 'Ada', 'email' => 'ada@example.test']],
            $nestedValidator->validated()
        );
    }

    private function arrayKeysRule(mixed ...$arguments): \Stringable
    {
        $rule = (new \ReflectionMethod(Rule::class, 'arrayKeys'))->invoke(null, ...$arguments);
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
