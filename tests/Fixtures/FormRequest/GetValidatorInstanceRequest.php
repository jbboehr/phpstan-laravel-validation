<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

final class GetValidatorInstanceRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['unsafe' => 'required|string'];
    }

    protected function getValidatorInstance(): Validator
    {
        return parent::getValidatorInstance();
    }
}
