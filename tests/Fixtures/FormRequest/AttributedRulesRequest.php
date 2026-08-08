<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

final class AttributedRulesRequest extends FormRequest
{
    /** @return array<string, string> */
    #[\ReturnTypeWillChange]
    public function rules(): array
    {
        return ['attributed' => 'required|string'];
    }
}
