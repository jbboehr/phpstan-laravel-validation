<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\NotIn;

final class NotInRuleRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'role' => ['required', 'string', Rule::notIn(['admin'])],
            'value' => ['required', Rule::notIn([1])],
            'direct_role' => ['required', 'string', new NotIn(['admin'])],
        ];
    }
}
