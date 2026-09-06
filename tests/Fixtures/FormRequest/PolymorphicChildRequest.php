<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

final class PolymorphicChildRequest extends PolymorphicRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => 'required|array'];
    }
}
