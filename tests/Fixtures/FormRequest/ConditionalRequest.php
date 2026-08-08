<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

final class ConditionalRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        if ($this->isMethod('POST')) {
            return ['created' => 'required|string'];
        }

        return ['updated' => 'required|integer'];
    }
}
