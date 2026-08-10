<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures;

enum IntegerValidationStatus: int
{
    case Zero = 0;
    case One = 1;
    case Two = 2;
}
