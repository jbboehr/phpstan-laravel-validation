<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class CustomRuleRequest extends FormRequest
{
    /** @return array<string, list<string|ValidationRule>> */
    public function rules(): array
    {
        return [
            'custom' => ['required', new FormRequestStringRule()],
        ];
    }
}
