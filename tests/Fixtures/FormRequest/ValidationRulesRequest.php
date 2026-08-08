<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

final class ValidationRulesRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['ordinary' => 'required|string'];
    }

    /** @return array<string, string> */
    protected function validationRules(): array
    {
        return ['unsafe' => 'required|array'];
    }
}
