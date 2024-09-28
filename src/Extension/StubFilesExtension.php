<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Extension;

use PHPStan\PhpDoc\StubFilesExtension as BaseStubFilesExtension;

final class StubFilesExtension implements BaseStubFilesExtension
{
    /**
     * @inheritDoc
     */
    public function getFiles(): array
    {
        return [
            __DIR__ . '/../../stubs/Validator.stub.php',
        ];
    }
}
