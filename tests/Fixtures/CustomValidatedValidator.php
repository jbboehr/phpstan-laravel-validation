<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures;

use Illuminate\Validation\Validator;

final class CustomValidatedValidator extends Validator
{
    /** @return array<array-key, mixed> */
    public function validated(): array
    {
        return [];
    }
}
