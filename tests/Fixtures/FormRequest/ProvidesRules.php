<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

trait ProvidesRules
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return [
            'from_trait' => 'required|string',
        ];
    }
}
