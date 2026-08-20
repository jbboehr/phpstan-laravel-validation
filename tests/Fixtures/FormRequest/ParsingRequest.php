<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use jbboehr\Rensei\Parse;

final class ParsingRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return ['age' => ['required', Parse::integer()]];
    }
}
