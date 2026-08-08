<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

final class SelfConstantChildRequest extends SelfConstantParentRequest
{
    /** @var array<string, string> */
    protected const RULES = ['child' => 'required|array'];
}
