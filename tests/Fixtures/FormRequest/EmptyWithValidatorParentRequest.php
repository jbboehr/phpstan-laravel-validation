<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

abstract class EmptyWithValidatorParentRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['inherited_empty_hook' => 'required|string'];
    }

    public function withValidator(Validator $validator): void
    {
        // Deliberately empty.
    }
}
