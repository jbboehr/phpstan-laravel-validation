<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\StringRule;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'factory' => ['required', Rule::string()],
    'direct' => ['required', new StringRule()],
    'alpha' => ['required', Rule::string()->alpha()],
    'alpha_ascii' => ['required', Rule::string()->alpha(ascii: true)],
    'alpha_dash' => ['required', Rule::string()->alphaDash()],
    'alpha_numeric' => ['required', Rule::string()->alphaNumeric()],
    'ascii' => ['required', Rule::string()->ascii()],
    'between' => ['required', Rule::string()->between(1, 100)],
    'doesnt_end_with' => ['required', Rule::string()->doesntEndWith('.exe')],
    'doesnt_start_with' => ['required', Rule::string()->doesntStartWith('tmp')],
    'ends_with' => ['required', Rule::string()->endsWith('.php')],
    'exactly' => ['required', Rule::string()->exactly(10)],
    'lowercase' => ['required', Rule::string()->lowercase()],
    'max' => ['required', Rule::string()->max(255)],
    'min' => ['required', Rule::string()->min(1)],
    'starts_with' => ['required', Rule::string()->startsWith('src/')],
    'uppercase' => ['required', Rule::string()->uppercase()],
    'chain' => ['required', Rule::string()->min(1)->max(255)->uppercase()],
    'optional' => [Rule::string()],
])->validated();

assertType(
    'array{factory: string, direct: string, alpha: string, alpha_ascii: string, '
        . 'alpha_dash: string, alpha_numeric: string, ascii: string, between: string, '
        . 'doesnt_end_with: string, doesnt_start_with: string, ends_with: string, '
        . 'exactly: string, lowercase: string, max: string, min: string, '
        . 'starts_with: string, uppercase: string, chain: string, optional?: string}',
    $validated
);

$assigned = Rule::string();
$opaque = Validator::make([], [
    'assigned' => ['required', $assigned],
    'conditional' => ['required', Rule::string()->when(
        true,
        static fn (StringRule $rule): StringRule => $rule->uppercase()
    )],
    'unless' => ['required', Rule::string()->unless(
        false,
        static fn (StringRule $rule): StringRule => $rule->lowercase()
    )],
    'factory_callable' => ['required', Rule::string(...)],
    'method_callable' => ['required', Rule::string()->uppercase(...)],
])->validated();

assertType(
    'array{assigned?: mixed, conditional?: mixed, unless?: mixed, '
        . 'factory_callable: mixed, method_callable: mixed}',
    $opaque
);

$otherBuilders = Validator::make([], [
    'file_factory' => ['required', Rule::file()->max('2kb')],
    'file_types' => ['required', File::types(['text/plain'])->between(1, 2)],
    'numeric' => ['required', Rule::numeric()->min(1)],
])->validated();

assertType(
    'array{file_factory: Symfony\Component\HttpFoundation\File\File, '
        . 'file_types: Symfony\Component\HttpFoundation\File\File, '
        . 'numeric: float|int|numeric-string}',
    $otherBuilders
);
