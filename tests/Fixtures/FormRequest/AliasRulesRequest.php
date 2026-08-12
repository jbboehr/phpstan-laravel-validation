<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

final class AliasRulesRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return [
            'id' => 'required|int',
            'flag' => 'required|bool',
        ];
    }
}
