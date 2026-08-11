<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

final class KeyedValidatedRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'nickname' => 'nullable|string',
            'age' => 'integer',
            'profile' => 'required|array',
            'profile.email' => 'required|string|email',
            'profile.note' => 'string',
            'items' => 'array',
            'items.*.id' => 'required|integer',
        ];
    }
}
