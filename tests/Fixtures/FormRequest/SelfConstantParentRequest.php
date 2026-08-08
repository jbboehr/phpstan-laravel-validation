<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

abstract class SelfConstantParentRequest extends FormRequest
{
    /** @var array<string, string> */
    protected const RULES = ['parent' => 'required|string'];

    /** @return array<string, string> */
    public function rules(): array
    {
        return self::RULES;
    }
}
