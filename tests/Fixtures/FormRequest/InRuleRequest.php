<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;

final class InRuleRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['draft', 'published'])],
            'enum_name' => ['required', Rule::in([PureValidationStatus::Draft])],
            'direct_status' => ['required', new In(['draft', 'published'])],
            'direct_enum_name' => ['required', new In([PureValidationStatus::Draft])],
        ];
    }
}
