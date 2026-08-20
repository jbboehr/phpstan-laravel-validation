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

final class InferenceAuditCases
{
    /**
     * @return array<string, array{
     *     rules: array<array-key, mixed>|\Closure(array<mixed, mixed>): array<array-key, mixed>,
     *     data: array<mixed, mixed>|\Closure(): array<mixed, mixed>,
     *     concern: string,
     *     precision?: bool
     * }>
     */
    public static function cases(): array
    {
        $case = static fn (mixed $value, string|array $rules, string $concern = 'native value') => [
            'data' => ['value' => $value],
            'rules' => ['value' => $rules],
            'concern' => $concern,
            'precision' => true,
        ];

        return [
            'accepted.true' => $case(true, 'accepted'),
            'accepted.string' => $case('yes', 'accepted'),
            'accepted_if.inactive' => [
                'data' => ['other' => 'different', 'value' => 42],
                'rules' => ['other' => 'required|string', 'value' => 'required|accepted_if:other,match'],
                'concern' => 'conditional predicate inactive branch',
            ],
            'declined.false' => $case(false, 'declined'),
            'declined_if.inactive' => [
                'data' => ['other' => 'different', 'value' => 42],
                'rules' => ['other' => 'required|string', 'value' => 'required|declined_if:other,match'],
                'concern' => 'conditional predicate inactive branch',
            ],
            'boolean.native' => $case(true, 'required|boolean'),
            'boolean.integer' => $case(1, 'required|boolean'),
            'boolean.string' => $case('1', 'required|boolean'),
            'integer.native' => $case(1, 'required|integer'),
            'integer.float' => $case(1.0, 'required|integer'),
            'integer.non_integral_float' => $case(1.5, 'required|integer', 'expressiveness limit'),
            'integer.true' => $case(true, 'required|integer'),
            'integer.stringable' => [
                'data' => static fn (): array => ['value' => new \Illuminate\Support\Stringable('1')],
                'rules' => ['value' => 'required|integer'],
                'concern' => 'coercive integer predicate',
                'precision' => true,
            ],
            'integer.invalid_stringable' => [
                'data' => static fn (): array => ['value' => new \Illuminate\Support\Stringable('no')],
                'rules' => ['value' => 'required|integer'],
                'concern' => 'expressiveness limit',
                'precision' => true,
            ],
            'integer_strict.string' => $case('1', 'required|integer:strict', 'minor-version strict mode'),
            'integer_strict.float' => $case(1.0, 'required|integer:strict', 'minor-version strict mode'),
            'integer_strict.true' => $case(true, 'required|integer:strict', 'minor-version strict mode'),
            'integer_strict.stringable' => [
                'data' => static fn (): array => ['value' => new \Illuminate\Support\Stringable('1')],
                'rules' => ['value' => 'required|integer:strict'],
                'concern' => 'minor-version strict mode',
                'precision' => true,
            ],
            'numeric.integer' => $case(1, 'required|numeric'),
            'numeric.float' => $case(1.5, 'required|numeric'),
            'numeric.string' => $case('1e2', 'required|numeric'),
            'numeric_path.single' => [
                'data' => [0 => 'legacy', 3 => 'preserved'],
                'rules' => [3 => 'required|string'],
                'concern' => 'major-version numeric rule-key projection',
            ],
            'numeric_path.sparse' => [
                'data' => [0 => 'zero', 1 => 'one', 3 => 'three', 5 => 'five'],
                'rules' => [3 => 'required|string', 5 => 'required|string'],
                'concern' => 'major-version sparse numeric rule-key projection',
            ],
            'numeric_path.mixed' => [
                'data' => [
                    'name' => 'named',
                    0 => 'legacy-three',
                    1 => 'legacy-five',
                    3 => 'preserved-three',
                    5 => 'preserved-five',
                    'email' => 'email@example.com',
                ],
                'rules' => [
                    'name' => 'required|string',
                    3 => 'required|string',
                    'email' => 'required|email',
                    5 => 'required|string',
                ],
                'concern' => 'major-version mixed numeric rule-key projection',
            ],
            'numeric_path.negative' => [
                'data' => [0 => 'legacy', -2 => 'preserved'],
                'rules' => [-2 => 'required|string'],
                'concern' => 'major-version negative numeric rule-key projection',
            ],
            'digits.string' => $case('12', 'required|digits:2'),
            'digits.integer' => $case(12, 'required|digits:2'),
            'digits.float' => $case(1.5, 'required|digits:2', 'expressiveness limit'),
            'digits_between.integer' => $case(12, 'required|digits_between:1,3'),
            'decimal.string' => $case('1.25', 'required|decimal:2'),
            'decimal.integer' => $case(1, 'required|decimal:2', 'predicate precision'),
            'multiple_of.float' => $case(1.5, 'required|multiple_of:0.5'),
            'multiple_of.non_multiple' => $case(1.6, 'required|multiple_of:0.5', 'predicate precision'),
            'max_digits.integer' => $case(12, 'required|max_digits:3'),
            'max_digits.too_many' => $case(1234, 'required|max_digits:3', 'predicate precision'),
            'min_digits.integer' => $case(12, 'required|min_digits:2'),
            'min_digits.too_few' => $case(1, 'required|min_digits:2', 'predicate precision'),
            'min.string' => $case('x', 'required|string|min:1'),
            'min.optional_blank_string' => $case('', 'string|min:1', 'blank-value bypass'),
            'min.array' => $case(['item'], 'required|array|min:1'),
            'min.empty_array' => $case([], 'required|array|min:1', 'predicate precision'),
            'min.array_excluded_child' => [
                'data' => ['items' => ['removed']],
                'rules' => [
                    'items' => 'required|array|min:1',
                    'items.0' => 'exclude',
                ],
                'concern' => 'input size before output projection',
            ],
            'min.parameterized_array_excluded_child' => [
                'data' => ['items' => ['name' => 'removed']],
                'rules' => [
                    'items' => 'required|array:name|min:1',
                    'items.name' => 'exclude',
                ],
                'concern' => 'allowed-key input size before output projection',
            ],
            'alpha.string' => $case('abc', 'required|alpha'),
            'alpha_num.integer' => $case(123, 'required|alpha_num'),
            'alpha_num.negative' => $case(-1, 'required|alpha_num'),
            'alpha_num.float' => $case(1.5, 'required|alpha_num', 'expressiveness limit'),
            'alpha_dash.float' => $case(-1.5, 'required|alpha_dash:ascii'),
            'alpha_dash.invalid_float' => $case(1.5, 'required|alpha_dash:ascii', 'expressiveness limit'),
            'ascii.string' => $case('plain', 'required|ascii'),
            'ascii.integer' => $case(123, 'ascii', 'major-version coercion'),
            'ascii.float' => $case(1.5, 'ascii', 'major-version coercion'),
            'ascii.true' => $case(true, 'ascii', 'major-version coercion'),
            'ascii.false' => $case(false, 'ascii', 'major-version coercion'),
            'ascii.null' => $case(null, 'ascii', 'major-version coercion'),
            'ascii.stringable' => [
                'data' => static fn (): array => ['value' => new \Illuminate\Support\Stringable('plain')],
                'rules' => ['value' => 'ascii'],
                'concern' => 'major-version coercion',
                'precision' => true,
            ],
            'ascii.resource' => [
                'data' => static function (): array {
                    $resource = fopen('php://memory', 'r');
                    if ($resource === false) {
                        throw new \RuntimeException('Could not open audit resource');
                    }
                    return ['value' => $resource];
                },
                'rules' => ['value' => 'ascii'],
                'concern' => 'major-version coercion',
                'precision' => true,
            ],
            'ascii.array' => $case(['key' => 'value'], 'ascii', 'warning-sensitive coercion'),
            'string.native' => $case('plain', 'required|string'),
            'string.integer' => $case(123, 'required|string'),
            'lowercase.string' => $case('plain', 'required|lowercase'),
            'lowercase.integer' => $case(123, 'lowercase'),
            'uppercase.string' => $case('PLAIN', 'required|uppercase'),
            'uppercase.integer' => $case(123, 'uppercase'),
            'json.string' => $case('{"value":1}', 'required|json'),
            'json.integer' => $case(1, 'required|json'),
            'json.true' => $case(true, 'required|json'),
            'json.false' => $case(false, 'json'),
            'json.infinity' => $case(INF, 'required|json', 'expressiveness limit'),
            'json.stringable' => [
                'data' => static fn (): array => [
                    'value' => new \Illuminate\Support\Stringable('{"value":1}'),
                ],
                'rules' => ['value' => 'required|json'],
                'concern' => 'coercive JSON predicate',
                'precision' => true,
            ],
            'json.invalid_stringable' => [
                'data' => static fn (): array => [
                    'value' => new \Illuminate\Support\Stringable('not-json'),
                ],
                'rules' => ['value' => 'required|json'],
                'concern' => 'expressiveness limit',
                'precision' => true,
            ],
            'date.integer' => $case(20240101, 'required|date'),
            'date.invalid_string' => $case('not-a-date', 'required|date', 'predicate precision'),
            'date_format.integer' => $case(20240101, 'required|date_format:Ymd'),
            'date_format.invalid_integer' => $case(42, 'required|date_format:Ymd', 'predicate precision'),
            'date_format.float' => $case(20240101.0, 'required|date_format:Ymd'),
            'date.object' => [
                'data' => static fn (): array => ['value' => new \DateTimeImmutable('2024-01-01')],
                'rules' => ['value' => 'required|date'],
                'concern' => 'date object preservation',
                'precision' => true,
            ],
            'before.integer' => $case(20240101, 'required|date_format:Ymd|before:20250101'),
            'after_or_equal.object' => [
                'data' => static fn (): array => ['value' => new \DateTimeImmutable('2024-01-01')],
                'rules' => ['value' => 'required|after_or_equal:2024-01-01'],
                'concern' => 'date comparison object preservation',
                'precision' => true,
            ],
            'email.string' => $case('person@example.com', 'required|email'),
            'email.invalid' => $case('not-an-email', 'required|email', 'predicate precision'),
            'ip.string' => $case('127.0.0.1', 'required|ip'),
            'ipv4.string' => $case('127.0.0.1', 'required|ipv4'),
            'ipv6.string' => $case('2001:db8::1', 'required|ipv6'),
            'mac.string' => $case('00:11:22:33:44:55', 'required|mac_address'),
            'timezone.string' => $case('UTC', 'required|timezone'),
            'url.string' => $case('https://example.com', 'required|url'),
            'uuid.string' => $case('550e8400-e29b-41d4-a716-446655440000', 'required|uuid'),
            'ulid.string' => $case('01ARZ3NDEKTSV4RRFFQ69G5FAV', 'required|ulid'),
            'regex.integer' => $case(123, ['required', 'regex:/^123$/']),
            'regex.boolean' => $case(true, ['required', 'regex:/^1$/'], 'native-type precision'),
            'regex.rejected_integer' => $case(456, ['required', 'regex:/^123$/'], 'predicate precision'),
            'not_regex.boolean' => $case(true, ['required', 'not_regex:/^false$/']),
            'not_regex.rejected_boolean' => $case(
                true,
                ['required', 'not_regex:/^1$/'],
                'predicate precision'
            ),
            'in.string' => $case('one', 'required|in:one,two'),
            'in.integer' => $case(1, 'required|in:1'),
            'in.float' => $case(1.0, 'required|in:1'),
            'in.true' => $case(true, 'required|in:1'),
            'in.numeric_string' => $case('01', 'required|in:1'),
            'in.other_integer' => $case(2, 'required|in:1', 'predicate precision'),
            'in.other_float' => $case(2.0, 'required|in:1', 'predicate precision'),
            'in.other_numeric_string' => $case('2', 'required|in:1', 'predicate precision'),
            'in.stringable' => [
                'data' => static fn (): array => ['value' => new \Illuminate\Support\Stringable('one')],
                'rules' => ['value' => 'required|in:one'],
                'concern' => 'coercive membership predicate',
                'precision' => true,
            ],
            'in.invalid_stringable' => [
                'data' => static fn (): array => ['value' => new \Illuminate\Support\Stringable('other')],
                'rules' => ['value' => 'required|in:one'],
                'concern' => 'predicate precision',
                'precision' => true,
            ],
            'array.bare' => [
                'data' => ['user' => ['name' => 'Ada', 'admin' => true]],
                'rules' => ['user' => 'required|array'],
                'concern' => 'nested key retention',
            ],
            'array.allowed_keys' => [
                'data' => ['user' => ['name' => 'Ada', 'admin' => true]],
                'rules' => ['user' => 'required|array:name'],
                'concern' => 'allowed-key rejection',
            ],
            'array.child_projection' => [
                'data' => ['user' => ['name' => 'Ada', 'admin' => true]],
                'rules' => ['user' => 'required|array', 'user.name' => 'required|string'],
                'concern' => 'nested projection',
            ],
            'array.parameterized_parent_missing_child' => [
                'data' => ['payload' => ['name' => 'Ada']],
                'rules' => ['payload' => 'required|array:name', 'payload.child' => 'missing'],
                'concern' => 'parameterized array parent preservation',
                'precision' => true,
            ],
            'array.required_keys' => [
                'data' => ['user' => ['name' => 'Ada']],
                'rules' => ['user' => 'required|array|required_array_keys:name'],
                'concern' => 'required array offset inference',
                'precision' => true,
            ],
            'array.required_keys_blank' => [
                'data' => ['user' => ''],
                'rules' => ['user' => 'required_array_keys:name'],
                'concern' => 'required array keys blank bypass',
            ],
            'array.required_keys_numeric' => [
                'data' => ['user' => [0 => 'zero']],
                'rules' => ['user' => 'required|required_array_keys:0'],
                'concern' => 'required numeric array offset inference',
            ],
            'array.required_key_projected' => [
                'data' => ['user' => ['name' => 'Ada', 'extra' => true]],
                'rules' => [
                    'user' => 'required|array|required_array_keys:name',
                    'user.name' => 'string',
                ],
                'concern' => 'required array key nested projection',
            ],
            'array.required_key_unprojected' => [
                'data' => ['user' => ['name' => 'Ada', 'email' => 'ada@example.test']],
                'rules' => [
                    'user' => 'required|array|required_array_keys:name',
                    'user.email' => 'string',
                ],
                'concern' => 'required input key omitted from nested projection',
            ],
            'optional.blank_integer' => $case('', 'integer', 'optional blank bypass'),
            'optional.invalid_integer_string' => $case('abc', 'integer', 'blank-bypass precision'),
            'optional.whitespace_email' => $case('   ', 'email', 'optional blank bypass'),
            'optional.invalid_email_string' => $case('not-an-email', 'email', 'blank-bypass precision'),
            'nullable.required_null' => $case(null, 'required|nullable|string', 'required and nullable interaction'),
            'nullable.required_missing' => [
                'data' => [],
                'rules' => ['value' => 'required|nullable|string'],
                'concern' => 'required and nullable interaction',
            ],
            'nullable.required_after_nullable_null' => $case(
                null,
                'nullable|required|string',
                'required and nullable rule order'
            ),
            'nullable.required_after_nullable_missing' => [
                'data' => [],
                'rules' => ['value' => 'nullable|required|string'],
                'concern' => 'required and nullable rule order',
            ],
            'nullable.optional_null' => $case(null, 'nullable|string', 'nullable output'),
            'present.value' => $case('value', 'present|string', 'unconditional presence rule'),
            'present.missing' => [
                'data' => [],
                'rules' => ['value' => 'present|string'],
                'concern' => 'unconditional presence rule',
            ],
            'present_array.empty_wildcard' => [
                'data' => ['items' => []],
                'rules' => ['items' => 'present|array', 'items.*.id' => 'required|integer'],
                'concern' => 'zero-match wildcard parent preservation',
            ],
            'present_array.blank_wildcard' => [
                'data' => ['items' => ''],
                'rules' => ['items' => 'present|array', 'items.*.id' => 'required|integer'],
                'concern' => 'zero-match wildcard blank bypass',
            ],
            'present_array.deep_wildcard' => [
                'data' => ['payload' => ['extra' => 1]],
                'rules' => ['payload' => 'present|array', 'payload.items.*.id' => 'required|integer'],
                'concern' => 'deeper zero-match wildcard parent preservation',
            ],
            'present_array.deep_wildcard_missing' => [
                'data' => ['payload' => ['items' => [['other' => 'value']]]],
                'rules' => ['payload' => 'present|array', 'payload.items.*.id' => 'missing'],
                'concern' => 'matched deeper missing projection',
            ],
            'missing.absent' => [
                'data' => [],
                'rules' => ['value' => 'missing'],
                'concern' => 'unconditional missing rule',
            ],
            'missing.present' => [
                'data' => ['value' => null],
                'rules' => ['value' => 'missing'],
                'concern' => 'unconditional missing rule',
            ],
            'confirmed.dependency' => [
                'data' => ['value' => 'secret', 'value_confirmation' => 'secret'],
                'rules' => ['value' => 'required|string|confirmed'],
                'concern' => 'validation dependency projection',
            ],
            'required_if.active' => [
                'data' => ['kind' => 'person', 'value' => 'name'],
                'rules' => ['kind' => 'required|string', 'value' => 'required_if:kind,person|string'],
                'concern' => 'cross-field presence',
            ],
            'required_if.inactive' => [
                'data' => ['kind' => 'company'],
                'rules' => ['kind' => 'required|string', 'value' => 'required_if:kind,person|string'],
                'concern' => 'cross-field presence',
            ],
            'exclude_if.active' => [
                'data' => ['kind' => 'guest', 'value' => 'secret'],
                'rules' => ['kind' => 'required|string', 'value' => 'required|string|exclude_if:kind,guest'],
                'concern' => 'conditional output projection',
            ],
            'exclude_if.inactive' => [
                'data' => ['kind' => 'member', 'value' => 'visible'],
                'rules' => ['kind' => 'required|string', 'value' => 'required|string|exclude_if:kind,guest'],
                'concern' => 'conditional output projection',
            ],
            'wildcard.missing_parent' => [
                'data' => [],
                'rules' => ['person.*.email' => 'required|string|email'],
                'concern' => 'zero-match wildcard',
            ],
            'wildcard.string_key' => [
                'data' => ['person' => ['named' => ['email' => 'person@example.com']]],
                'rules' => ['person.*.email' => 'required|string|email'],
                'concern' => 'wildcard key domain',
            ],
            'wildcard.mixed_named' => [
                'data' => ['items' => ['named' => 'value', 0 => 'other']],
                'rules' => ['items.*' => 'required|string', 'items.named' => 'required|string'],
                'concern' => 'overlapping wildcard projection',
            ],
            'parent.scalar_with_child' => [
                'data' => ['foo' => 'value'],
                'rules' => ['foo' => 'required|string', 'foo.bar' => 'sometimes|string'],
                'concern' => 'parent rules with children',
            ],
            'unknown_rule.fallback' => $case('value', 'required|filled', 'non-narrowing fallback'),
        ];
    }

    /**
     * @return array<string, array{status: string, evidence: list<string>, note?: string}>
     */
    public static function inventory(): array
    {
        return [
            'accepted and declined' => [
                'status' => 'probed',
                'evidence' => ['accepted.true', 'accepted_if.inactive', 'declined.false', 'declined_if.inactive'],
            ],
            'boolean and numeric families' => [
                'status' => 'probed',
                'evidence' => [
                    'boolean.native', 'integer.native', 'integer_strict.string', 'numeric.string',
                    'digits.integer', 'digits_between.integer', 'decimal.string', 'multiple_of.float',
                    'max_digits.integer', 'min_digits.integer', 'min.string', 'min.array',
                    'min.array_excluded_child', 'min.parameterized_array_excluded_child',
                ],
            ],
            'text predicates' => [
                'status' => 'probed',
                'evidence' => [
                    'alpha.string', 'alpha_num.integer', 'alpha_dash.float', 'ascii.integer',
                    'string.integer', 'lowercase.integer', 'uppercase.integer', 'regex.integer',
                    'not_regex.boolean',
                ],
            ],
            'JSON, dates, and membership' => [
                'status' => 'probed',
                'evidence' => [
                    'json.integer', 'date.integer', 'date_format.integer', 'before.integer',
                    'after_or_equal.object', 'in.integer', 'in.stringable',
                ],
            ],
            'network and identifier strings' => [
                'status' => 'probed',
                'evidence' => [
                    'email.string', 'ip.string', 'ipv4.string', 'ipv6.string', 'mac.string',
                    'timezone.string', 'url.string', 'uuid.string', 'ulid.string',
                ],
                'note' => 'active_url is excluded because DNS is environment-dependent',
            ],
            'arrays and projection' => [
                'status' => 'probed',
                'evidence' => [
                    'array.bare', 'array.allowed_keys', 'array.child_projection',
                    'array.parameterized_parent_missing_child', 'array.required_keys',
                    'array.required_keys_blank', 'array.required_keys_numeric',
                    'array.required_key_projected', 'array.required_key_unprojected',
                    'numeric_path.single', 'numeric_path.sparse', 'numeric_path.mixed',
                    'numeric_path.negative',
                    'wildcard.missing_parent', 'wildcard.string_key', 'wildcard.mixed_named',
                    'parent.scalar_with_child',
                ],
            ],
            'presence and conditional behavior' => [
                'status' => 'probed',
                'evidence' => [
                    'optional.blank_integer', 'nullable.required_null', 'nullable.required_missing',
                    'nullable.required_after_nullable_null', 'nullable.required_after_nullable_missing',
                    'nullable.optional_null', 'present.value', 'present.missing',
                    'present_array.empty_wildcard', 'present_array.blank_wildcard',
                    'present_array.deep_wildcard', 'present_array.deep_wildcard_missing',
                    'missing.absent', 'missing.present', 'confirmed.dependency',
                    'required_if.active', 'required_if.inactive', 'exclude_if.active', 'exclude_if.inactive',
                ],
            ],
            'image dimensions' => [
                'status' => 'covered-by-cross-profile-suite',
                'evidence' => [],
                'note' => 'A real one-pixel image and adversarial values are exercised outside the portable runner.',
            ],
            'other files, database, password-rule services, and custom rules' => [
                'status' => 'environment-dependent',
                'evidence' => [],
                'note' => 'Catalogued but excluded from the portable runner; current inference is object or mixed.',
            ],
            'validation entry points' => [
                'status' => 'covered-by-static-suite',
                'evidence' => [],
                'note' => 'Facade, factory, request, controller, helper, validator unions, and prohibited mutations have static fixtures.',
            ],
        ];
    }
}
