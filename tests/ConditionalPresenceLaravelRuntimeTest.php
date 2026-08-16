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
use jbboehr\PhpstanLaravelValidation\Test\Support\LaravelValueType;
use jbboehr\PhpstanLaravelValidation\Validation\LaravelVersionContext;
use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use jbboehr\PhpstanLaravelValidation\Validation\TypeResolver;
use PHPStan\Type\VerbosityLevel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class ConditionalPresenceLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    private const CONDITIONAL_PRESENT_RULES_INTRODUCED = '10.32.0';

    /**
     * @return iterable<string, array{string, string, array<string, string>, array<string, string>}>
     */
    public static function conditionalPresenceProvider(): iterable
    {
        yield 'present_if matching' => [
            'present_if',
            'create',
            ['mode' => 'create', 'value' => ''],
            ['mode' => 'create', 'value' => ''],
        ];
        yield 'present_if non-matching' => [
            'present_if',
            'update',
            ['mode' => 'update'],
            ['mode' => 'update'],
        ];
        yield 'present_unless matching' => [
            'present_unless',
            'create',
            ['mode' => 'create'],
            ['mode' => 'create'],
        ];
        yield 'present_unless non-matching' => [
            'present_unless',
            'update',
            ['mode' => 'update', 'value' => ''],
            ['mode' => 'update', 'value' => ''],
        ];
        yield 'missing_if matching' => [
            'missing_if',
            'create',
            ['mode' => 'create'],
            ['mode' => 'create'],
        ];
        yield 'missing_if non-matching' => [
            'missing_if',
            'update',
            ['mode' => 'update', 'value' => ''],
            ['mode' => 'update', 'value' => ''],
        ];
        yield 'missing_unless matching' => [
            'missing_unless',
            'create',
            ['mode' => 'create', 'value' => ''],
            ['mode' => 'create', 'value' => ''],
        ];
        yield 'missing_unless non-matching' => [
            'missing_unless',
            'update',
            ['mode' => 'update'],
            ['mode' => 'update'],
        ];
    }

    /**
     * @param array<string, string> $data
     * @param array<string, string> $expectedValidated
     */
    #[DataProvider('conditionalPresenceProvider')]
    public function testConditionalPresenceMatchesExperimentalInference(
        string $rule,
        string $mode,
        array $data,
        array $expectedValidated
    ): void {
        if (self::isConditionalPresentRule($rule) && !self::supportsConditionalPresentRules()) {
            self::markTestSkipped('Conditional present rules require Laravel 10.32 or later');
        }

        self::getContainer();

        $rules = [
            'mode' => 'required|string|in:' . $mode,
            'value' => $rule . ':mode,create|string',
        ];
        $validator = self::factory()->make($data, $rules);

        self::assertTrue($validator->passes());
        self::assertSame($expectedValidated, $validator->validated());

        $context = new LaravelVersionContext('', self::frameworkVersion());
        $inferred = (new TypeResolver(
            laravelVersionContext: $context,
            experimentalConditionalPresenceInference: true
        ))->evaluate(RuleParser::parse($rules, $context));
        $actual = LaravelValueType::fromValue($validator->validated());
        self::assertTrue($inferred->isSuperTypeOf($actual)->yes(), sprintf(
            "Inferred: %s\nActual: %s",
            $inferred->describe(VerbosityLevel::precise()),
            $actual->describe(VerbosityLevel::precise())
        ));
    }

    /**
     * @return iterable<string, array{string, string, array<string, string>}>
     */
    public static function activeConditionalPresenceFailureProvider(): iterable
    {
        yield 'present_if rejects a missing value' => [
            'present_if',
            'create',
            ['mode' => 'create'],
        ];
        yield 'present_unless rejects a missing value' => [
            'present_unless',
            'update',
            ['mode' => 'update'],
        ];
        yield 'missing_if rejects a present value' => [
            'missing_if',
            'create',
            ['mode' => 'create', 'value' => 'present'],
        ];
        yield 'missing_unless rejects a present value' => [
            'missing_unless',
            'update',
            ['mode' => 'update', 'value' => 'present'],
        ];
    }

    /** @param array<string, string> $data */
    #[DataProvider('activeConditionalPresenceFailureProvider')]
    public function testActiveConditionalPresenceRulesRejectTheOppositeShape(
        string $rule,
        string $mode,
        array $data
    ): void {
        if (self::isConditionalPresentRule($rule) && !self::supportsConditionalPresentRules()) {
            self::markTestSkipped('Conditional present rules require Laravel 10.32 or later');
        }

        $validator = self::factory()->make($data, [
            'mode' => 'required|string|in:' . $mode,
            'value' => $rule . ':mode,create|string',
        ]);

        self::assertFalse($validator->passes());
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function conditionalPresentBoundaryProvider(): iterable
    {
        yield 'present_if' => ['present_if', 'create'];
        yield 'present_unless' => ['present_unless', 'update'];
    }

    #[DataProvider('conditionalPresentBoundaryProvider')]
    public function testConditionalPresentRulesFollowRuntimeAndInferenceBoundary(
        string $rule,
        string $mode
    ): void {
        self::getContainer();

        $rules = [
            'mode' => 'required|string|in:' . $mode,
            'value' => $rule . ':mode,create|string',
        ];
        $validator = self::factory()->make(['mode' => $mode], $rules);
        $context = new LaravelVersionContext('', self::frameworkVersion());
        $inferred = (new TypeResolver(
            laravelVersionContext: $context,
            experimentalConditionalPresenceInference: true
        ))->evaluate(RuleParser::parse($rules, $context));

        if (!self::supportsConditionalPresentRules()) {
            self::assertTrue($validator->passes());
            self::assertSame(['mode' => $mode], $validator->validated());
            self::assertSame(
                "array{mode: '" . $mode . "', value?: string}",
                $inferred->describe(VerbosityLevel::precise())
            );
            return;
        }

        self::assertFalse($validator->passes());
        self::assertSame(
            "array{mode: '" . $mode . "', value: string}",
            $inferred->describe(VerbosityLevel::precise())
        );
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    private static function isConditionalPresentRule(string $rule): bool
    {
        return $rule === 'present_if' || $rule === 'present_unless';
    }

    private static function supportsConditionalPresentRules(): bool
    {
        return version_compare(
            self::frameworkVersion(),
            self::CONDITIONAL_PRESENT_RULES_INTRODUCED,
            '>='
        );
    }

    private static function frameworkVersion(): string
    {
        $version = InstalledVersions::getPrettyVersion('laravel/framework');

        return $version === null
            ? \Illuminate\Foundation\Application::VERSION
            : ltrim($version, 'v');
    }
}
