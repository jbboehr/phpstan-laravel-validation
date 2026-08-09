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

namespace jbboehr\PhpstanLaravelValidation\Test\Support;

final class InferencePropertyCases
{
    public const SCALAR_COUNT = 1620;
    public const STRUCTURAL_COUNT = 140;
    public const CONDITIONAL_COUNT = 280;

    /**
     * @return array<string, array{data: array<mixed, mixed>, rules: array<string, string>}>
     */
    public static function scalar(): array
    {
        $rules = [
            'string' => 'string',
            'integer' => 'integer',
            'numeric' => 'numeric',
            'boolean' => 'boolean',
            'array' => 'array',
            'in-zero-or-one' => 'in:0,1',
        ];
        $modifiers = [
            'none' => [],
            'required' => ['required'],
            'nullable' => ['nullable'],
            'sometimes' => ['sometimes'],
            'filled' => ['filled'],
            'required-nullable' => ['required', 'nullable'],
            'sometimes-nullable' => ['sometimes', 'nullable'],
            'present' => ['present'],
            'missing' => ['missing'],
        ];
        $inputs = [
            'absent' => ['present' => false],
            'null' => ['present' => true, 'value' => null],
            'empty-string' => ['present' => true, 'value' => ''],
            'space' => ['present' => true, 'value' => ' '],
            'numeric-string-zero' => ['present' => true, 'value' => '0'],
            'integer-zero' => ['present' => true, 'value' => 0],
            'numeric-string-one' => ['present' => true, 'value' => '1'],
            'integer-one' => ['present' => true, 'value' => 1],
            'integral-float' => ['present' => true, 'value' => 1.0],
            'non-integral-float' => ['present' => true, 'value' => 1.5],
            'true' => ['present' => true, 'value' => true],
            'false' => ['present' => true, 'value' => false],
            'plain-string' => ['present' => true, 'value' => 'plain'],
            'empty-array' => ['present' => true, 'value' => []],
            'non-empty-array' => ['present' => true, 'value' => ['item']],
        ];
        $orders = ['rule-first' => false, 'modifiers-first' => true];
        $cases = [];

        foreach ($rules as $ruleName => $rule) {
            foreach ($modifiers as $modifierName => $modifierRules) {
                foreach ($inputs as $inputName => $input) {
                    foreach ($orders as $orderName => $modifiersFirst) {
                        $parts = $modifierRules;
                        if ($modifiersFirst) {
                            $parts[] = $rule;
                        } else {
                            array_unshift($parts, $rule);
                        }

                        $cases[implode('.', [$ruleName, $modifierName, $inputName, $orderName])] = [
                            'data' => $input['present'] ? ['value' => $input['value']] : [],
                            'rules' => ['value' => implode('|', $parts)],
                        ];
                    }
                }
            }
        }

        return $cases;
    }

    /**
     * @return array<string, array{data: array<mixed, mixed>, rules: array<string, string>}>
     */
    public static function structural(): array
    {
        $cases = [];

        self::addCartesianCases($cases, 'payload', [
            'bare-array' => ['payload' => 'array'],
            'allowed-keys' => ['payload' => 'array:name,email'],
            'required-name' => ['payload' => 'array', 'payload.name' => 'required|string'],
            'optional-nullable-name' => ['payload' => 'array', 'payload.name' => 'sometimes|nullable|string'],
            'allowed-keys-missing-child' => [
                'payload' => 'array:name,email',
                'payload.child' => 'missing',
            ],
            'required-array-keys' => ['payload' => 'required_array_keys:name'],
            'required-array-keys-required' => [
                'payload' => 'required|required_array_keys:name',
            ],
            'required-key-projected-child' => [
                'payload' => 'required|array|required_array_keys:name',
                'payload.name' => 'string',
            ],
            'required-key-unprojected-child' => [
                'payload' => 'required|array|required_array_keys:name',
                'payload.email' => 'string',
            ],
            'required-key-allowed-parent' => [
                'payload' => 'required|array:name,email|required_array_keys:name',
            ],
        ], [
            'absent' => [],
            'empty-array' => ['payload' => []],
            'name' => ['payload' => ['name' => 'Alice']],
            'name-and-extra' => ['payload' => ['name' => 'Alice', 'extra' => 1]],
            'email' => ['payload' => ['email' => 'alice@example.test']],
            'blank-name' => ['payload' => ['name' => '']],
            'scalar' => ['payload' => 'scalar'],
            'null' => ['payload' => null],
        ]);

        self::addCartesianCases($cases, 'items', [
            'wildcard-only' => ['items.*.value' => 'required|string'],
            'array-parent' => ['items' => 'array', 'items.*.value' => 'required|string'],
            'present-array-parent' => ['items' => 'present|array', 'items.*.value' => 'required|string'],
            'present-array-missing' => ['items' => 'present|array', 'items.*.value' => 'missing'],
        ], [
            'absent' => [],
            'empty-array' => ['items' => []],
            'list-value' => ['items' => [['value' => 'first']]],
            'named-value' => ['items' => ['named' => ['value' => 'named']]],
            'mixed-keys' => ['items' => [0 => ['value' => 'first'], 'named' => ['value' => 'named']]],
            'unvalidated-child' => ['items' => [['other' => 'unvalidated']]],
            'blank-child' => ['items' => [['value' => '']]],
            'scalar' => ['items' => 'scalar'],
            'empty-string' => ['items' => ''],
            'whitespace' => ['items' => " \t"],
        ]);

        self::addCartesianCases($cases, 'deep-wildcard', [
            'present-array-parent' => [
                'payload' => 'present|array',
                'payload.items.*.value' => 'required|string',
            ],
            'present-array-missing' => [
                'payload' => 'present|array',
                'payload.items.*.value' => 'missing',
            ],
        ], [
            'absent' => [],
            'empty-array' => ['payload' => []],
            'unrelated-key' => ['payload' => ['extra' => 1]],
            'empty-items' => ['payload' => ['items' => []]],
            'list-value' => ['payload' => ['items' => [['value' => 'first']]]],
            'unvalidated-child' => ['payload' => ['items' => [['other' => 'unvalidated']]]],
            'named-value' => ['payload' => ['items' => ['named' => ['value' => 'named']]]],
            'empty-string' => ['payload' => ''],
            'whitespace' => ['payload' => " \t"],
            'scalar' => ['payload' => 'scalar'],
        ]);

        return $cases;
    }

    /**
     * @return array<string, array{data: array<mixed, mixed>, rules: array<string, string>}>
     */
    public static function conditional(): array
    {
        $conditionalRules = [
            'required-if' => 'required_if:mode,member|string',
            'required-unless' => 'required_unless:mode,guest|string',
            'required-with' => 'required_with:trigger|string',
            'exclude-if' => 'exclude_if:mode,guest|string',
            'exclude-unless' => 'exclude_unless:mode,member|string',
            'exclude-with' => 'exclude_with:trigger|string',
            'exclude-without' => 'exclude_without:trigger|string',
        ];
        $modes = [
            'absent' => [],
            'guest' => ['mode' => 'guest'],
            'member' => ['mode' => 'member'],
            'other' => ['mode' => 'other'],
        ];
        $triggers = ['trigger-absent' => [], 'trigger-present' => ['trigger' => 'present']];
        $values = [
            'value-absent' => [],
            'null' => ['value' => null],
            'empty-string' => ['value' => ''],
            'string' => ['value' => 'value'],
            'integer-zero' => ['value' => 0],
        ];
        $cases = [];

        foreach ($conditionalRules as $ruleName => $valueRule) {
            foreach ($modes as $modeName => $modeData) {
                foreach ($triggers as $triggerName => $triggerData) {
                    foreach ($values as $valueName => $valueData) {
                        $cases[implode('.', [$ruleName, $modeName, $triggerName, $valueName])] = [
                            'data' => $modeData + $triggerData + $valueData,
                            'rules' => [
                                'mode' => 'sometimes|string',
                                'trigger' => 'sometimes|string',
                                'value' => $valueRule,
                            ],
                        ];
                    }
                }
            }
        }

        return $cases;
    }

    /**
     * @param array<string, array{data: array<mixed, mixed>, rules: array<string, string>}> $cases
     * @param array<string, array<string, string>> $ruleSets
     * @param array<string, array<mixed, mixed>> $inputs
     */
    private static function addCartesianCases(array &$cases, string $family, array $ruleSets, array $inputs): void
    {
        foreach ($ruleSets as $ruleName => $rules) {
            foreach ($inputs as $inputName => $data) {
                $cases[implode('.', [$family, $ruleName, $inputName])] = [
                    'data' => $data,
                    'rules' => $rules,
                ];
            }
        }
    }
}
