<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

abstract class ThisConstantParentRequest extends FormRequest
{
    /** @var array<string, string> */
    protected const RULES = ['parent' => 'required|string'];

    /** @return array<string, string> */
    public function rules(): array
    {
        return $this::RULES;
    }
}
