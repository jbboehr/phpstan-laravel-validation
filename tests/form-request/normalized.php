<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\FormRequestFixtures;

use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\BasicRequest;

use function PHPStan\Testing\assertType;

function inspectNormalized(BasicRequest $request): void
{
    assertType(
        'array{name: string, age?: float|int|numeric-string|Stringable|true}',
        $request->validated()
    );
}
