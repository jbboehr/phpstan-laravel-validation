<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ArrayRuleRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'payload' => ['required', Rule::array()],
            'payload.name' => 'required|string',
            'metadata' => ['required', Rule::array(['source'])],
        ];
    }
}
