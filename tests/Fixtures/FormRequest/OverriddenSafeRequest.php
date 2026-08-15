<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

final class OverriddenSafeRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['ignored' => 'required|string'];
    }

    /**
     * @param array<array-key, mixed>|null $keys
     * @return array{custom: string}
     */
    public function safe(?array $keys = null): array
    {
        return ['custom' => 'value'];
    }
}
