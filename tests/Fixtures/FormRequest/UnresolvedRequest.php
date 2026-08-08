<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

final class UnresolvedRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        if ($this->isMethod('POST')) {
            return ['partially_resolved' => 'required|string'];
        }

        $rules = ['unresolved' => 'required|string'];

        return $rules;
    }
}
