<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

abstract class InheritedRulesRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return [
            'inherited' => 'required|boolean',
        ];
    }
}
