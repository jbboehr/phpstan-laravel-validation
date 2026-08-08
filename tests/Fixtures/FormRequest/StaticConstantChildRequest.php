<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

final class StaticConstantChildRequest extends StaticConstantParentRequest
{
    /** @var array<string, string> */
    protected const RULES = ['child' => 'required|array'];
}
