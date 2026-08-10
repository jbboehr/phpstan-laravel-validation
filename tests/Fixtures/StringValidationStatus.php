<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures;

enum StringValidationStatus: string
{
    case Zero = '0';
    case One = '1';
    case LeadingZero = '01';
    case Draft = 'draft';
    case Published = 'published';
}
