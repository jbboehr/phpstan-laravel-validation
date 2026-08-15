<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\FormRequest;

use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\NotInRuleRequest;

use function PHPStan\Testing\assertType;

function inspectNotInRule(NotInRuleRequest $request): void
{
    assertType('array{role: string, value: mixed, direct_role: string}', $request->validated());
}
