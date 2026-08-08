<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

final class RuleConstants
{
    /** @var array<string, string> */
    public const RULES = [
        'constant' => 'required|string',
    ];
}
