<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\FormRequest;

use jbboehr\PhpstanLaravelValidation\Test\Fixtures\FormRequest\EnumRequest;

use function PHPStan\Testing\assertType;

function inspectEnumRule(EnumRequest $request): void
{
    assertType(
        "array{pure: jbboehr\\PhpstanLaravelValidation\\Test\\Fixtures\\PureValidationStatus::Draft"
            . '|jbboehr\\PhpstanLaravelValidation\\Test\\Fixtures\\PureValidationStatus::Published, '
            . "one: 1|'1'|float|"
            . 'jbboehr\\PhpstanLaravelValidation\\Test\\Fixtures\\StringValidationStatus::One|Stringable|true}',
        $request->validated()
    );
}
