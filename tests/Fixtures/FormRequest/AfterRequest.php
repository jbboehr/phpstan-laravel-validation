<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

final class AfterRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['unsafe' => 'required|string'];
    }

    /** @return list<callable(Validator): void> */
    public function after(): array
    {
        return [static function (Validator $validator): void {
        }];
    }
}
