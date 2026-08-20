<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Validation\Validator;

final class OverriddenEmptyWithValidatorRequest extends EmptyWithValidatorParentRequest
{
    public function withValidator(Validator $validator): void
    {
        $validator->setRules(['replacement' => 'required|array']); // @phpstan-ignore laravelValidation.validatorMutation
    }
}
