<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

final class PolymorphicGrandchildRequest extends PolymorphicAbstractRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => 'required|array'];
    }
}
