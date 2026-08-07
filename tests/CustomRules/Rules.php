<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\CustomRules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use jbboehr\PhpstanLaravelValidation\Attribute\ValidationRuleType;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class UnrelatedAttribute
{
}

class UnknownRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
    }
}

final class IntegerRule extends UnknownRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_int($value)) {
            $fail('The value must be an integer.');
        }
    }
}

#[UnrelatedAttribute]
#[ValidationRuleType('non-empty-string')]
final class AttributeRule extends UnknownRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || $value === '') {
            $fail('The value must be a non-empty string.');
        }
    }
}

/** @laravel-validation-type positive-int */
final class PhpDocRule extends UnknownRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_int($value) || $value <= 0) {
            $fail('The value must be a positive integer.');
        }
    }
}

/** @laravel-validation-type int */
#[ValidationRuleType('string')]
final class PrecedenceRule extends UnknownRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_bool($value)) {
            $fail('The value must be a boolean.');
        }
    }
}

#[ValidationRuleType('array{')]
final class InvalidAttributeRule extends UnknownRule
{
}

/**
 * @laravel-validation-type int
 * @laravel-validation-type string
 */
final class DuplicatePhpDocRule extends UnknownRule
{
}

final class StringableRuleBuilder implements \Stringable
{
    public function __toString(): string
    {
        return 'exclude';
    }
}

final class LegacyIntegerRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return is_int($value);
    }

    public function message(): string
    {
        return 'The value must be an integer.';
    }
}

final class InvokableIntegerRule implements InvokableRule
{
    public function __invoke($attribute, $value, $fail): void
    {
        if (!is_int($value)) {
            $fail('The value must be an integer.');
        }
    }
}

final class LegacyImplicitIntegerRule implements ImplicitRule
{
    public function passes($attribute, $value): bool
    {
        return is_int($value);
    }

    public function message(): string
    {
        return 'The value must be an integer.';
    }
}

#[ValidationRuleType('non-empty-string')]
final class ImplicitStringRule extends UnknownRule
{
    public bool $implicit = true;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || $value === '') {
            $fail('The value must be a non-empty string.');
        }
    }
}

final class DataAwareIntegerRule extends UnknownRule implements DataAwareRule
{
    /** @var array<mixed> */
    private array $data = [];

    /**
     * @param array<mixed> $data
     */
    public function setData($data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (($this->data['enabled'] ?? null) !== true || !is_int($value)) {
            $fail('The value must be an enabled integer.');
        }
    }
}
