<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

final class ContainerInjectedRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(RuleService $service): array
    {
        return $service::RULES;
    }
}
