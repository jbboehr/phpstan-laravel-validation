<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

final class CustomValidatorRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['ignored' => 'required|string'];
    }

    public function validator(Factory $factory): Validator
    {
        return $factory->make([], []);
    }
}
