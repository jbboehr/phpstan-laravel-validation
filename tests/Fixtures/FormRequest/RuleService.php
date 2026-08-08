<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

final class RuleService
{
    /** @var array<string, string> */
    public const RULES = [
        'injected' => 'required|array',
    ];
}
