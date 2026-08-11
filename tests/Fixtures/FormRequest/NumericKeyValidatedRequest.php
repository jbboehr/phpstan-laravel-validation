<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

final class NumericKeyValidatedRequest extends FormRequest
{
    /** @return array<array-key, string> */
    public function rules(): array
    {
        return [0 => 'required|string'];
    }
}
