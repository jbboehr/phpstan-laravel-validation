<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\FormRequest;

use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\InRuleRequest;

use function PHPStan\Testing\assertType;

function inspectInRule(InRuleRequest $request): void
{
    assertType(
        "array{status: 'draft'|'published'|Stringable, enum_name: 'Draft'|Stringable, "
            . "direct_status: 'draft'|'published'|Stringable, "
            . "direct_enum_name: 'Draft'|Stringable}",
        $request->validated()
    );
}
