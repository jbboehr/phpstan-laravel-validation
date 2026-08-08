<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

trait ProvidesWithValidator
{
    public function withValidator(Validator $validator): void
    {
        $validator->setRules(['extra' => 'required|string']);
    }
}

final class TraitWithValidatorRequest extends FormRequest
{
    use ProvidesWithValidator;

    /** @return array<string, string> */
    public function rules(): array
    {
        return ['unsafe' => 'required|string'];
    }
}
