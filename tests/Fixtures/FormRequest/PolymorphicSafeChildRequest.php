<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

final class PolymorphicSafeChildRequest extends PolymorphicSafeRequest
{
    /**
     * @param array<mixed>|null $keys
     * @return array{changed: true}
     */
    public function safe(?array $keys = null): array
    {
        return ['changed' => true];
    }
}
