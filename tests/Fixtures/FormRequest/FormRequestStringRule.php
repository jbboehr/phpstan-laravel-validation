<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use jbboehr\PhpstanLaravelValidation\Attribute\ValidationRuleType;

#[ValidationRuleType('non-empty-string')]
final class FormRequestStringRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
    }
}
