<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

final class ThisConstantChildRequest extends ThisConstantParentRequest
{
    /** @var array<string, string> */
    protected const RULES = ['child' => 'required|array'];
}
