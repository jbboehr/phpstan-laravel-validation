<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

class PolymorphicAnonymousRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return ['value' => 'required|string'];
    }
}

function anonymousPolymorphicRequest(): PolymorphicAnonymousRequest
{
    return new class () extends PolymorphicAnonymousRequest {
        /** @return array<string, string> */
        public function rules(): array
        {
            return ['value' => 'required|array'];
        }
    };
}
