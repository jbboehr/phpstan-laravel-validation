<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

final class OverriddenValidatedRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['ignored' => 'required|string'];
    }

    /** @param array<array-key, mixed>|int|string|null $key */
    public function validated($key = null, $default = null): string
    {
        return 'custom';
    }
}
