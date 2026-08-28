<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

class ExactTrustedParentRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['trusted_parent' => 'required|string'];
    }

    protected function passedValidation(): void
    {
    }
}
