<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

use function PHPStan\Testing\assertType;

final class ForwardingFileRule extends File
{
    public static function assertForwardedParentTypesStayOpaque(): void
    {
        $validated = Validator::make([], [
            'value' => ['required', parent::types(['text/plain'])],
        ])->validated();

        assertType('array{value: mixed}', $validated);
    }

    /**
     * @param string|array<int, string> $mimetypes
     */
    public static function forwardedTypes(string|array $mimetypes): static
    {
        return parent::types($mimetypes);
    }

    public function passes(mixed $attribute, mixed $value): bool
    {
        return is_string($value);
    }
}
