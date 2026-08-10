<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\PureValidationStatus;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\StringValidationStatus;

final class EnumRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'pure' => ['required', Rule::enum(PureValidationStatus::class)],
            'one' => ['required', Rule::enum(
                StringValidationStatus::class
            )->only(
                StringValidationStatus::One
            )],
        ];
    }
}
