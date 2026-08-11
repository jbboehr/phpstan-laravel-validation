<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\FormRequest;

use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\ArrayKeysRuleRequest;

use function PHPStan\Testing\assertType;

function inspectArrayKeysRule(ArrayKeysRuleRequest $request): void
{
    assertType(
        'array{payload: array{name?: mixed, email?: mixed}, metadata: array{source?: mixed}}',
        $request->validated()
    );
}
