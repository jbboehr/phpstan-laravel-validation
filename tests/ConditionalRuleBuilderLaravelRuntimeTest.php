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

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\ExcludeIf;
use Illuminate\Validation\Rules\ProhibitedIf;
use Illuminate\Validation\Rules\RequiredIf;
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class ConditionalRuleBuilderLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    public function testWhenSelectsAndFlattensLiteralBranches(): void
    {
        $cases = [
            'true string branch' => [
                ['value' => 'text'],
                ['value' => Rule::when(true, 'required|string', 'required|integer')],
                ['required', 'string'],
            ],
            'false array default' => [
                ['value' => 42],
                ['value' => Rule::when(false, 'required|string', ['required', 'integer'])],
                ['required', 'integer'],
            ],
            'branch inside rule list' => [
                ['value' => 'text'],
                ['value' => ['required', Rule::when(true, ['string']), 'bail']],
                ['required', 'string', 'bail'],
            ],
            'named arguments' => [
                ['value' => 'text'],
                ['value' => Rule::when(
                    rules: ['required', 'string'],
                    defaultRules: ['required', 'integer'],
                    condition: true
                )],
                ['required', 'string'],
            ],
            'unselected callback is not executed' => [
                ['value' => 'text'],
                ['value' => Rule::when(
                    true,
                    ['required', 'string'],
                    static function (): array {
                        throw new \LogicException('unselected branch executed');
                    }
                )],
                ['required', 'string'],
            ],
        ];

        foreach ($cases as $name => [$data, $rules, $expectedRules]) {
            $validator = self::factory()->make($data, $rules);

            self::assertSame($expectedRules, $validator->getRules()['value'], $name);
            self::assertTrue($validator->passes(), $name);
            self::assertSame($data, $validator->validated(), $name);
        }
    }

    public function testWhenEmptyBranchStillMarksParentsForCompleteProjection(): void
    {
        $data = ['payload' => ['name' => 'Ada', 'extra' => 'preserved']];
        foreach ([
            'standalone' => Rule::when(false, 'array'),
            'inside rule list' => [Rule::when(false, 'array')],
        ] as $name => $rule) {
            $validator = self::factory()->make($data, [
                'payload' => $rule,
                'payload.name' => 'required|string',
            ]);

            self::assertSame([], $validator->getRules()['payload'], $name);
            self::assertTrue($validator->passes(), $name);
            self::assertSame($data, $validator->validated(), $name);
        }
    }

    public function testStandaloneWhenBranchesUseLaravelsCompleteFalseyFilter(): void
    {
        foreach ([0, 0.0, '0', false, null, ''] as $falseyRule) {
            $validator = self::factory()->make([], [
                'value' => Rule::when(true, [$falseyRule]),
            ]);

            self::assertSame([], $validator->getRules()['value']);
            self::assertTrue($validator->passes());
            self::assertSame([], $validator->validated());
        }
    }

    public function testWhenDoesNotRecursivelyExpandNestedConditionalWrappers(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('could not be converted to string');

        self::factory()->make(['value' => 42], [
            'value' => Rule::when(true, [
                Rule::when(false, 'string', 'required|integer'),
            ]),
        ]);
    }

    public function testRuleListKeyCollisionsOccurBeforeConditionalFlattening(): void
    {
        $conditionalRules = [0 => 'required'];
        $conditionalRules[0] = Rule::when(true, ['string']);
        $builtInRules = [0 => 'required'];
        $builtInRules[0] = Rule::in(['a']);

        foreach (['conditional' => $conditionalRules, 'built-in' => $builtInRules] as $name => $rules) {
            $validator = self::factory()->make([], ['value' => $rules]);
            $parsedRules = $validator->getRules()['value'];

            self::assertIsArray($parsedRules, $name);
            self::assertCount(1, $parsedRules, $name);
            self::assertTrue($validator->passes(), $name);
            self::assertSame([], $validator->validated(), $name);
        }
    }

    public function testEarlierBuiltInRuleCallsCanChangeALaterNamedCondition(): void
    {
        $condition = true;
        $values = new class ($condition) implements Arrayable {
            public function __construct(private bool &$condition)
            {
            }

            public function toArray(): array
            {
                $wasTrue = $this->condition;
                $this->condition = false;
                return $wasTrue ? ['blocked'] : [];
            }
        };
        $rule = Rule::when(
            rules: [Rule::notIn($values), 'required', 'string'],
            condition: $condition,
            defaultRules: ['required', 'integer']
        );
        $validator = self::factory()->make(['value' => 42], ['value' => $rule]);

        self::assertFalse($condition);
        self::assertSame(['required', 'integer'], $validator->getRules()['value']);
        self::assertTrue($validator->passes());
        self::assertSame(['value' => 42], $validator->validated());
    }

    public function testUnlessInvertsLiteralConditionsAfterItsRuntimeVersionBoundary(): void
    {
        if (version_compare(self::frameworkVersion(), '10.33.0', '<')) {
            self::markTestSkipped('Rule::unless() was introduced in Laravel 10.33.');
        }

        $cases = [
            'false selects rules' => [
                ['value' => 'text'],
                Rule::unless(false, 'required|string', 'required|integer'),
                ['required', 'string'],
            ],
            'true selects default' => [
                ['value' => 42],
                Rule::unless(true, 'required|string', ['required', 'integer']),
                ['required', 'integer'],
            ],
        ];

        foreach ($cases as $name => [$data, $rule, $expectedRules]) {
            $validator = self::factory()->make($data, ['value' => $rule]);

            self::assertSame($expectedRules, $validator->getRules()['value'], $name);
            self::assertTrue($validator->passes(), $name);
            self::assertSame($data, $validator->validated(), $name);
        }
    }

    public function testLiteralConditionsSerializeLikeTheirUnconditionalRules(): void
    {
        foreach ($this->conditionalRules(false) as $name => $rule) {
            self::assertSame('', (string) $rule, $name);
        }

        foreach ($this->conditionalRules(true) as $name => $rule) {
            $expected = match (true) {
                str_starts_with($name, 'required') => 'required',
                str_starts_with($name, 'exclude') => 'exclude',
                default => 'prohibited',
            };
            self::assertSame($expected, (string) $rule, $name);
        }
    }

    public function testFalseConditionsAddNoConstraintAndPreservePresentInput(): void
    {
        foreach ($this->conditionalRules(false) as $name => $rule) {
            $validator = self::factory()->make(['value' => 'text'], ['value' => $rule]);

            self::assertTrue($validator->passes(), $name);
            self::assertSame(['value' => 'text'], $validator->validated(), $name);

            $withString = self::factory()->make(
                ['value' => 'text'],
                ['value' => [$rule, 'string']]
            );
            self::assertTrue($withString->passes(), $name);
            self::assertSame(['value' => 'text'], $withString->validated(), $name);
            self::assertFalse(self::factory()->make(
                ['value' => 123],
                ['value' => [$rule, 'string']]
            )->passes(), $name);
        }
    }

    public function testFalseConditionsStillMarkParentsForCompleteProjection(): void
    {
        $data = ['payload' => ['name' => 'Ada', 'extra' => 'preserved']];

        foreach ($this->conditionalRules(false) as $name => $rule) {
            $validator = self::factory()->make($data, [
                'payload' => $rule,
                'payload.name' => 'required|string',
            ]);

            self::assertSame([''], $validator->getRules()['payload'], $name);
            self::assertTrue($validator->passes(), $name);
            self::assertSame($data, $validator->validated(), $name);
        }
    }

    public function testFalseParentMarkerInteractionsFollowLaravelProjection(): void
    {
        $marker = Rule::requiredIf(false);
        $cases = [
            'wildcard descendants preserve complete elements' => [
                ['payload' => [
                    ['name' => 'Ada', 'extra' => 'kept'],
                    ['name' => 'Lin', 'extra' => 'also kept'],
                ]],
                [
                    'payload' => $marker,
                    'payload.*.name' => 'required|string',
                ],
                ['payload' => [
                    ['name' => 'Ada', 'extra' => 'kept'],
                    ['name' => 'Lin', 'extra' => 'also kept'],
                ]],
            ],
            'excluded descendants mutate the preserved parent' => [
                ['payload' => ['name' => 'Ada', 'secret' => 'gone', 'extra' => 'kept']],
                [
                    'payload' => $marker,
                    'payload.secret' => 'exclude',
                ],
                ['payload' => ['name' => 'Ada', 'extra' => 'kept']],
            ],
            'missing descendants leave unrelated siblings intact' => [
                ['payload' => ['name' => 'Ada', 'extra' => 'kept']],
                [
                    'payload' => $marker,
                    'payload.forbidden' => 'missing',
                ],
                ['payload' => ['name' => 'Ada', 'extra' => 'kept']],
            ],
            'bare array still reconstructs from child rules' => [
                ['payload' => ['name' => 'Ada', 'extra' => 'dropped']],
                [
                    'payload' => [$marker, 'array'],
                    'payload.name' => 'required|string',
                ],
                ['payload' => ['name' => 'Ada']],
            ],
            'true exclusion still removes a parent with child rules' => [
                ['payload' => ['name' => 'Ada', 'extra' => 'gone']],
                [
                    'payload' => Rule::excludeIf(true),
                    'payload.name' => 'required|string',
                ],
                [],
            ],
        ];

        foreach ($cases as $name => [$data, $rules, $expected]) {
            $validator = self::factory()->make($data, $rules);

            self::assertTrue($validator->passes(), $name);
            self::assertSame($expected, $validator->validated(), $name);
        }
    }

    public function testNamedLiteralConditionsUseStableLaravelParameterNames(): void
    {
        $rules = [
            Rule::requiredIf(callback: true),
            Rule::excludeIf(callback: false),
            Rule::prohibitedIf(callback: false),
            new RequiredIf(condition: false),
            new ExcludeIf(condition: false),
            new ProhibitedIf(condition: false),
        ];

        self::assertSame(
            ['required', '', '', '', '', ''],
            array_map(static fn (\Stringable $rule): string => (string) $rule, $rules)
        );
    }

    public function testTrueRequiredConditionsRequireAndPreserveInput(): void
    {
        foreach ([Rule::requiredIf(true), new RequiredIf(true)] as $rule) {
            self::assertFalse(self::factory()->make([], ['value' => $rule])->passes());

            $validator = self::factory()->make(['value' => 'text'], ['value' => $rule]);
            self::assertTrue($validator->passes());
            self::assertSame(['value' => 'text'], $validator->validated());
        }
    }

    public function testTrueExcludeConditionsRemoveInputFromValidatedOutput(): void
    {
        foreach ([Rule::excludeIf(true), new ExcludeIf(true)] as $rule) {
            $validator = self::factory()->make(['value' => 'text'], ['value' => $rule]);

            self::assertTrue($validator->passes());
            self::assertSame([], $validator->validated());
        }
    }

    public function testTrueProhibitedConditionsRejectNonEmptyInput(): void
    {
        foreach ([Rule::prohibitedIf(true), new ProhibitedIf(true)] as $rule) {
            self::assertTrue(self::factory()->make([], ['value' => $rule])->passes());
            self::assertFalse(self::factory()->make(
                ['value' => 'text'],
                ['value' => $rule]
            )->passes());
        }
    }

    /** @return array<string, \Stringable> */
    private function conditionalRules(bool $condition): array
    {
        return [
            'required factory' => Rule::requiredIf($condition),
            'required direct' => new RequiredIf($condition),
            'exclude factory' => Rule::excludeIf($condition),
            'exclude direct' => new ExcludeIf($condition),
            'prohibited factory' => Rule::prohibitedIf($condition),
            'prohibited direct' => new ProhibitedIf($condition),
        ];
    }

    private static function factory(): Factory
    {
        return new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    private static function frameworkVersion(): string
    {
        return \Illuminate\Foundation\Application::VERSION;
    }
}
