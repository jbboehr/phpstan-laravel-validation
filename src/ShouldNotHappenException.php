<?php
declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation;

final class ShouldNotHappenException extends \RuntimeException
{
    private const URL = 'https://github.com/jbboehr/phpstan-laravel-validation/issues';

    public function __construct(
        string $message = 'Internal error.',
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            sprintf('%s, please open an issue on GitHub %s', $message, self::URL),
            0,
            $previous
        );
    }
}
