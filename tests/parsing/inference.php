<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\MoneyParsingRule;
use jbboehr\Rensei\Parse;
use jbboehr\Rensei\Rules\IntegerRule;

use function PHPStan\Testing\assertType;

// A parsing rule produces its declared type. Presence is still decided by the
// ordinary presence rules.
assertType('array{age?: int}', Validator::make([], [
    'age' => [Parse::integer()],
])->validated());

assertType('array{age: int}', Validator::make([], [
    'age' => ['required', Parse::integer()],
])->validated());

assertType('array{age?: int}', Validator::make([], [
    'age' => ['sometimes', Parse::integer()],
])->validated());

assertType('array{age?: int|null}', Validator::make([], [
    'age' => ['nullable', Parse::integer()],
])->validated());

// required rejects null, so nullable cannot widen the result here.
assertType('array{age: int}', Validator::make([], [
    'age' => ['required', 'nullable', Parse::integer()],
])->validated());

// The produced type replaces the predicates rather than intersecting with
// them. Intersecting would infer never for this rule set.
assertType('array{age?: int}', Validator::make([], [
    'age' => ['string', Parse::integer()],
])->validated());

assertType('array{age: int}', Validator::make([], [
    'age' => ['required', 'integer', Parse::integer(), 'min:18'],
])->validated());

// A parsing rule is implicit at runtime, so a blank string cannot bypass it
// into the validated output and the inferred type carries no string.
assertType('array{age?: int}', Validator::make([], [
    'age' => ['sometimes', Parse::integer()],
])->validated());

// Constructing the rule directly resolves the same way as the factory.
assertType('array{age: int}', Validator::make([], [
    'age' => ['required', new IntegerRule()],
])->validated());

// A parser defined outside this package needs no support here.
assertType('array{amount: non-empty-string}', Validator::make([], [
    'amount' => ['required', new MoneyParsingRule()],
])->validated());

// Exclusion still removes the key.
assertType('array{}', Validator::make([], [
    'age' => ['exclude', Parse::integer()],
])->validated());

// Nested and wildcard paths carry the produced type.
assertType('array{profile: array{age: int}}', Validator::make([], [
    'profile.age' => ['required', Parse::integer()],
])->validated());

// A required wildcard descendant constrains the elements that exist, not the
// collection itself.
assertType(
    'array{users?: array<int|string, array{age: int}>}',
    Validator::make([], ['users.*.age' => ['required', Parse::integer()]])->validated()
);

// The parsed value lands in the validator's copy of the data. The caller's
// array keeps the representation it was given, so a successful validation must
// not narrow it by the produced type.
$data = ['age' => '42'];
if (Validator::make($data, ['age' => ['required', Parse::integer()]])->passes()) {
    assertType("array{age: '42'}", $data);
}
