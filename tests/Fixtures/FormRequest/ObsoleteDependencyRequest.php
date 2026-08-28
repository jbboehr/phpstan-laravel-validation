<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

final class ObsoleteDependencyRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['obsolete' => 'required|string'];
    }
}
