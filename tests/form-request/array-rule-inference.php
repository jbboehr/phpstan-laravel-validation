<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\FormRequest;

use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ArrayRuleRequest;

use function PHPStan\Testing\assertType;

function inspectArrayRule(ArrayRuleRequest $request): void
{
    assertType(
        'array{payload: array{name: string}, metadata: array{source?: mixed}}',
        $request->validated()
    );
}
