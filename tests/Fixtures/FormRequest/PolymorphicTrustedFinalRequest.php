<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Validation\Validator;

final class PolymorphicTrustedFinalRequest extends PolymorphicRequest
{
    public function withValidator(Validator $validator): void
    {
        $validator->setRules(['value' => 'required|string']);
    }
}
