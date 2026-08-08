<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\FormRequestFixtures;

use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\BasicRequest;

use function PHPStan\Testing\assertType;

function inspectDisabled(BasicRequest $request): void
{
    assertType('mixed', $request->validated());
}
