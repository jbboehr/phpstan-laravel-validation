<?php

declare(strict_types=1);

namespace ParsingNumericSizeFixture;

use Illuminate\Foundation\Http\FormRequest;
use jbboehr\Rensei\Parse;

final class NumericSizeRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        $unrelatedClosure = static function (): array {
            return ['closure_only' => [Parse::integer(), 'min:2']];
        };

        return [
            'form_request' => [Parse::integer(), 'min:2'],
            'form_request_safe' => ['integer', Parse::integer(), 'min:2'],
        ];
    }
}
