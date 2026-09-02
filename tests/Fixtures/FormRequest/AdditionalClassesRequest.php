<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

final class AdditionalClassesRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['listed' => 'required|string'];
    }
}

final class UnlistedAdditionalClassesSiblingRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['unlisted' => 'required|string'];
    }
}

final class AdditionalClassesWrongEntry
{
}

abstract class AdditionalClassesAbstractRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['abstract' => 'required|string'];
    }
}

final class TrustedAdditionalClassesRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['trusted' => 'required|string'];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->setRules(['replacement' => 'required|array']);
    }
}
