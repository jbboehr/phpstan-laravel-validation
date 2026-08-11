<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Unique;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\CustomExistsRule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\DatabaseRuleModel;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'exists_only' => ['required', Rule::exists('users', 'id')],
    'integer_exists' => ['required', 'integer', Rule::exists('users', 'id')->where('active', true)],
    'direct_exists' => ['required', 'string', (new Exists('users', 'email'))->whereNotNull('verified_at')],
    'unique' => ['required', 'string', Rule::unique('users', 'email')->ignore(1)->withoutTrashed()],
    'direct_unique' => ['required', 'string', (new Unique('users', 'email'))->ignoreModel(new DatabaseRuleModel())],
    'callback' => ['required', 'string', Rule::exists('users')->using(static function (): void {
    })],
])->validated();

assertType(
    'array{exists_only: mixed, integer_exists: float|int|numeric-string|Stringable|true, '
        . 'direct_exists: string, unique: string, direct_unique: string, callback: string}',
    $validated
);

$assigned = Rule::exists('users');
$opaque = Validator::make([], [
    'assigned' => ['required', 'integer', $assigned],
    'conditional' => ['required', 'integer', Rule::exists('users')->when(true, static fn (Exists $rule): Exists => $rule)],
    'subclass' => ['required', 'integer', new CustomExistsRule('users', 'id')],
])->validated();

assertType('array{assigned?: mixed, conditional?: mixed, subclass?: mixed}', $opaque);
