<?php

declare(strict_types=1);

use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Support\Facades\Validator;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\ArrayParsingRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\ExtensibleValueParsingRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\IntegerValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\MoneyParsingRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\NonImplicitIntegerParsingRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus;
use jbboehr\Rensei\Parse;
use jbboehr\Rensei\ParsingRule;
use jbboehr\Rensei\ValueParser;
use jbboehr\Rensei\Rules\AcceptedRule;
use jbboehr\Rensei\Rules\Base64Rule;
use jbboehr\Rensei\Rules\BooleanRule;
use jbboehr\Rensei\Rules\DateTimeRule;
use jbboehr\Rensei\Rules\DeclinedRule;
use jbboehr\Rensei\Rules\EnumRule;
use jbboehr\Rensei\Rules\FloatRule;
use jbboehr\Rensei\Rules\IntegerRule;
use jbboehr\Rensei\Rules\StringRule;
use jbboehr\Rensei\Rules\TimezoneRule;

use function PHPStan\Testing\assertType;
use function PHPStan\Testing\assertSuperType;

$collidingPaths = Validator::make([], [
    'a\\.b' => ['required', Parse::integer()],
    'a.b' => ['required', 'string'],
])->validated();
assertType('int', $collidingPaths['a.b']);
assertType('string', $collidingPaths['a']['b']);

/** @param ParsingRule<int> $parser */
function inspectAbstractIntegerParser(ParsingRule $parser): void
{
    // A caller may expose a parser through its public generic contract
    // without losing the produced type when it is adapted back into Laravel's
    // lifecycle.
    assertType('array{age: int}', Validator::make([], [
        'age' => ['required', Parse::using($parser)],
    ])->validated());
}

/**
 * @template T of object
 *
 * @param ValueParser<T> $parser
 *
 * @return T
 */
function parseAbstractObject(ValueParser $parser): object
{
    $value = Validator::make([], [
        'value' => ['required', Parse::using($parser)],
    ])->validated()['value'];

    assertType('T of object (function parseAbstractObject(), argument)', $value);

    return $value;
}

/**
 * @template T
 *
 * @param ValueParser<T> $parser
 *
 * @return T
 */
function parseAbstractValue(ValueParser $parser): mixed
{
    $value = Validator::make([], [
        'value' => ['required', Parse::using($parser)],
    ])->validated()['value'];

    assertType('T (function parseAbstractValue(), argument)', $value);

    return $value;
}

/** @param ExtensibleValueParsingRule<int> $parser */
function inspectSubclassableParser(ExtensibleValueParsingRule $parser): void
{
    assertType('array{age?: mixed}', Validator::make([], [
        'age' => [$parser],
    ])->validated());
}

assertType('array{age?: mixed}', Validator::make([], [
    'age' => [new NonImplicitIntegerParsingRule()],
])->validated());

// Accepted and declined token parsers retain literal output information.
// Presence remains a separate rule, as it does for every parser.
assertType('array{terms?: true}', Validator::make([], [
    'terms' => [Parse::accepted()],
])->validated());

assertType('array{terms: true}', Validator::make([], [
    'terms' => ['required', Parse::accepted()],
])->validated());

assertType('array{terms?: true|null}', Validator::make([], [
    'terms' => ['nullable', Parse::accepted()],
])->validated());

assertType('array{opt_out?: false}', Validator::make([], [
    'opt_out' => [Parse::declined()],
])->validated());

assertType('array{opt_out: false}', Validator::make([], [
    'opt_out' => ['required', Parse::declined()],
])->validated());

assertType('array{opt_out?: false|null}', Validator::make([], [
    'opt_out' => ['nullable', Parse::declined()],
])->validated());

assertType('array{terms: true, opt_out: false}', Validator::make([], [
    'terms' => ['required', new AcceptedRule()],
    'opt_out' => ['required', new DeclinedRule()],
])->validated());

assertType(
    'array{users?: array<int|string, array{terms: true, opt_out: false}>}',
    Validator::make([], [
        'users.*.terms' => ['required', Parse::accepted()],
        'users.*.opt_out' => ['required', Parse::declined()],
    ])->validated()
);

assertType('array{}', Validator::make([], [
    'terms' => ['exclude', Parse::accepted()],
])->validated());

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
// into the validated output. The predicate below carries that bypass; the
// parser does not, and the contrast is the whole point of suppressing it.
assertType('array{age?: float|int|string|Stringable|true}', Validator::make([], [
    'age' => ['sometimes', 'integer'],
])->validated());

assertType('array{age?: int}', Validator::make([], [
    'age' => ['sometimes', Parse::integer()],
])->validated());

// Constructing the rule directly resolves the same way as the factory.
assertType('array{age: int}', Validator::make([], [
    'age' => ['required', new IntegerRule()],
])->validated());

// String parsing deliberately normalizes only its bounded representations:
// strings pass through, integers use decimal syntax, and Stringable objects
// contribute their declared string.
assertType('array{identifier?: string}', Validator::make([], [
    'identifier' => [Parse::string()],
])->validated());

assertType('array{identifier: string}', Validator::make([], [
    'identifier' => ['required', 'integer', Parse::string()],
])->validated());

assertType('array{identifier?: string|null}', Validator::make([], [
    'identifier' => ['nullable', Parse::string()],
])->validated());

assertType('array{identifier: string}', Validator::make([], [
    'identifier' => ['required', new StringRule()],
])->validated());

assertType('array{}', Validator::make([], [
    'identifier' => ['exclude', Parse::string()],
])->validated());

// Canonical Base64 decoding produces at least one byte. PHPStan's
// non-empty-string type applies to binary strings as well as text.
assertType('array{payload?: non-empty-string}', Validator::make([], [
    'payload' => [Parse::base64()],
])->validated());

assertType('array{payload: non-empty-string}', Validator::make([], [
    'payload' => ['required', Parse::base64()],
])->validated());

assertType('array{payload?: non-empty-string|null}', Validator::make([], [
    'payload' => ['nullable', Parse::base64()],
])->validated());

assertType('array{payload: non-empty-string}', Validator::make([], [
    'payload' => ['required', new Base64Rule()],
])->validated());

assertType(
    'array{documents?: array<int|string, array{payload: non-empty-string}>}',
    Validator::make([], [
        'documents.*.payload' => ['required', Parse::base64()],
    ])->validated()
);

assertType('array{}', Validator::make([], [
    'payload' => ['exclude', Parse::base64()],
])->validated());

// Float parsing accepts several representations but always produces a finite
// native float.
assertType('array{ratio?: float}', Validator::make([], [
    'ratio' => [Parse::float()],
])->validated());

assertType('array{ratio: float}', Validator::make([], [
    'ratio' => ['required', Parse::float()],
])->validated());

assertType('array{ratio?: float|null}', Validator::make([], [
    'ratio' => ['nullable', Parse::float()],
])->validated());

assertType('array{ratio: float}', Validator::make([], [
    'ratio' => ['required', new FloatRule()],
])->validated());

// Laravel-compatible and exact date/time parsing both produce one stable
// object type.
assertType('array{starts_at: DateTimeImmutable}', Validator::make([], [
    'starts_at' => ['required', Parse::dateTime()],
])->validated());

assertType('array{starts_at?: DateTimeImmutable}', Validator::make([], [
    'starts_at' => [Parse::dateTime('Y-m-d H:i:s')],
])->validated());

assertType('array{starts_at: DateTimeImmutable}', Validator::make([], [
    'starts_at' => ['required', Parse::dateTime(
        ['Y-m-d H:i:s', DateTimeInterface::ATOM],
        'UTC'
    )],
])->validated());

assertType('array{starts_at?: DateTimeImmutable|null}', Validator::make([], [
    'starts_at' => ['nullable', Parse::dateTime('Y-m-d H:i:s')],
])->validated());

assertType('array{starts_at: DateTimeImmutable}', Validator::make([], [
    'starts_at' => ['required', new DateTimeRule('Y-m-d H:i:s')],
])->validated());

assertType('array{}', Validator::make([], [
    'starts_at' => ['exclude', Parse::dateTime('Y-m-d H:i:s')],
])->validated());

// Timezone parsing follows Laravel's default identifier list for strings and
// always produces a DateTimeZone object.
assertType('array{timezone?: DateTimeZone}', Validator::make([], [
    'timezone' => [Parse::timezone()],
])->validated());

assertType('array{timezone: DateTimeZone}', Validator::make([], [
    'timezone' => ['required', Parse::timezone()],
])->validated());

assertType('array{timezone?: DateTimeZone|null}', Validator::make([], [
    'timezone' => ['nullable', Parse::timezone()],
])->validated());

assertType('array{timezone: DateTimeZone}', Validator::make([], [
    'timezone' => ['required', new TimezoneRule()],
])->validated());

assertType(
    'array{offices?: array<int|string, array{timezone: DateTimeZone}>}',
    Validator::make([], [
        'offices.*.timezone' => ['required', Parse::timezone()],
    ])->validated()
);

assertType('array{}', Validator::make([], [
    'timezone' => ['exclude', Parse::timezone()],
])->validated());

// Boolean parsing accepts Laravel's boolean input set but produces one
// canonical PHP type.
assertType('array{enabled?: bool}', Validator::make([], [
    'enabled' => [Parse::boolean()],
])->validated());

assertType('array{enabled: bool}', Validator::make([], [
    'enabled' => ['required', Parse::boolean()],
])->validated());

assertType('array{enabled?: bool|null}', Validator::make([], [
    'enabled' => ['nullable', Parse::boolean()],
])->validated());

assertType('array{enabled: bool}', Validator::make([], [
    'enabled' => ['required', new BooleanRule()],
])->validated());

// Enum parsing carries the concrete enum class through the generic rule.
assertType('array{status?: jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus}', Validator::make([], [
    'status' => [Parse::enum(StringValidationStatus::class)],
])->validated());

assertType('array{status: jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus}', Validator::make([], [
    'status' => ['required', Parse::enum(StringValidationStatus::class)],
])->validated());

assertType('array{status?: jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus|null}', Validator::make([], [
    'status' => ['nullable', Parse::enum(StringValidationStatus::class)],
])->validated());

assertType('array{level: jbboehr\PhpstanLaravelValidation\Test\Fixtures\IntegerValidationStatus}', Validator::make([], [
    'level' => ['required', new EnumRule(IntegerValidationStatus::class)],
])->validated());

assertType(
    'array{users?: array<int|string, array{status: jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus}>}',
    Validator::make([], [
        'users.*.status' => ['required', Parse::enum(StringValidationStatus::class)],
    ])->validated()
);

assertType(
    'array{age: int, enabled: bool, status: jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus}',
    Validator::make([], [
        'age' => ['required', Parse::integer()],
        'enabled' => ['required', Parse::boolean()],
        'status' => ['required', Parse::enum(StringValidationStatus::class)],
    ])->validated()
);

// Direct Validator safe() inference stays broad because Factory::resolver()
// may substitute a Validator with a different virtual validated() contract.
assertType(
    'array',
    Validator::make([], [
        'age' => ['required', Parse::integer()],
        'enabled' => ['required', Parse::boolean()],
        'status' => ['required', Parse::enum(StringValidationStatus::class)],
    ])->safe()->all()
);

// Laravel 13 later gained a conditional PHPDoc return for safe(), narrowing
// this call from the historical union to array<string, mixed>. Do not pin an
// upstream declaration detail that does not change parser inference.
assertSuperType(
    'array|Illuminate\Support\ValidatedInput',
    Validator::make([], ['age' => ['required', Parse::integer()]])->safe(['age'])
);

assertType(
    'array',
    Validator::make([], ['age' => ['required', Parse::integer()]])->safe()->only(['age'])
);

assertType(
    'array',
    Validator::make([], ['age' => ['required', Parse::integer()]])->safe()->toArray()
);

assertType(
    'array',
    Validator::make([], ['age' => ['required', Parse::integer()]])->validate()
);

// A parser defined outside this package needs no support here.
assertType('array{amount: non-empty-string}', Validator::make([], [
    'amount' => ['required', new MoneyParsingRule()],
])->validated());

// Arbitrary executable rules can mutate the validator after parser write-back.
// Without a static lifecycle contract, the only sound result is mixed.
assertType('mixed', Validator::make([], [
    'age' => [
        Parse::integer(),
        static function (string $attribute, mixed $value, Closure $fail): void {
        },
    ],
])->validated());

// Exclusion still removes the key.
assertType('array{}', Validator::make([], [
    'age' => ['exclude', Parse::integer()],
])->validated());

// Nested and wildcard paths carry the produced type.
assertType('array{profile: array{age: int}}', Validator::make([], [
    'profile.age' => ['required', Parse::integer()],
])->validated());

// A parser on a parent and nested child rules are separate projection paths.
// When the optional child is absent, Laravel preserves the parsed parent.
assertType('array{payload: array{name?: mixed}|array{parsed: true}}', Validator::make([], [
    'payload' => ['present', 'array:name', new ArrayParsingRule()],
    'payload.name' => ['sometimes', 'string'],
])->validated());

// A required wildcard descendant constrains the elements that exist, not the
// collection itself.
assertType(
    'array{users?: array<int|string, array{age: int}>}',
    Validator::make([], ['users.*.age' => ['required', Parse::integer()]])->validated()
);

assertType(
    'array{measurements?: array<int|string, array{ratio: float}>}',
    Validator::make([], ['measurements.*.ratio' => ['required', Parse::float()]])->validated()
);

assertType(
    'array{events?: array<int|string, array{starts_at: DateTimeImmutable}>}',
    Validator::make([], [
        'events.*.starts_at' => ['required', Parse::dateTime('Y-m-d H:i:s')],
    ])->validated()
);

// The parsed value lands in the validator's copy of the data. The caller's
// array keeps the representation it was given, so a successful validation must
// not narrow it by the produced type.
$data = ['age' => '42'];
if (Validator::make($data, ['age' => ['required', Parse::integer()]])->passes()) {
    assertType("array{age: '42'}", $data);
}

$enumData = ['status' => 'draft'];
if (Validator::make($enumData, [
    'status' => ['required', Parse::enum(StringValidationStatus::class)],
])->passes()) {
    assertType("array{status: 'draft'}", $enumData);
}
