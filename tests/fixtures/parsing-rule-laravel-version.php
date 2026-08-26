<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\ParsingRuleLaravelVersion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\MoneyParsingRule;
use jbboehr\Rensei\Parse;
use jbboehr\Rensei\Rules\BaseParsingRule;

function validationEntryPoints(array $input, Factory $factory, Request $request): void
{
    Parse::integer();
    $reusableParser = Parse::integer();

    Validator::make($input, [
        'age' => ['required', Parse::integer()],
        'amount' => ['required', new MoneyParsingRule()],
        'count' => ['required', $reusableParser],
    ]);

    $factory->make($input, [
        'enabled' => ['required', Parse::boolean()],
    ]);

    $factory->make($input, [])->setRules([
        'replacement' => ['required', Parse::integer()],
    ]);

    Validator::make($input, [])->setRules([
        'facade_replacement' => ['required', Parse::integer()],
    ]);

    validator($input, [])->setRules(rules: [
        'helper_replacement' => ['required', Parse::integer()],
    ]);

    $request->validate([
        'identifier' => ['required', Parse::string()],
        'unknown' => ['required', new MixedParsingRule()],
    ]);
}

/** @extends BaseParsingRule<mixed> */
final class MixedParsingRule extends BaseParsingRule
{
    public function parse(mixed $value): mixed
    {
        return $value;
    }

    protected function message(): string
    {
        return 'Invalid value.';
    }
}

final class ParsingFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'timezone' => ['required', Parse::timezone()],
        ];
    }
}
