<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\External;

use Illuminate\Foundation\Http\FormRequest;

final class LinkedFormRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['linked' => 'required|string'];
    }
}
