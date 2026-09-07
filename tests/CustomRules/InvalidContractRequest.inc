<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\CustomRules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

require_once __DIR__ . '/Rules.php';

final class InvalidContractRequest extends FormRequest
{
    /** @return array<string, list<string|ValidationRule>> */
    public function rules(): array
    {
        return ['value' => ['required', new InvalidAttributeRule()]];
    }
}
