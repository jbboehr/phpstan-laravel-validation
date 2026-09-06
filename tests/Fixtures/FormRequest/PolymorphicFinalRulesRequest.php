<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

class PolymorphicFinalRulesRequest extends FormRequest
{
    /** @return array<string, string> */
    final public function rules(): array
    {
        return ['value' => 'required|string'];
    }
}
