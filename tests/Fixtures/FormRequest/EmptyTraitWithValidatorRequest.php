<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

trait ProvidesEmptyWithValidator
{
    public function withValidator(Validator $validator): void
    {
    }
}

final class EmptyTraitWithValidatorRequest extends FormRequest
{
    use ProvidesEmptyWithValidator;

    /** @return array<string, string> */
    public function rules(): array
    {
        return ['trait_empty_hook' => 'required|string'];
    }
}
