<?php

declare(strict_types=1);

namespace CustomValidatorHelper {
    use function PHPStan\Testing\assertType;

    $customHelperResult = validator()
        ->setRules(['name' => 'required|string'])
        ->validated();
    assertType('mixed', $customHelperResult);
}

namespace ImportedLaravelValidatorHelper {
    use function PHPStan\Testing\assertType;
    use function validator as laravel_validator;

    $laravelHelperResult = laravel_validator([], ['before' => 'required|string'])
        ->setRules(['after' => 'required|string'])
        ->validated();
    assertType('array{after: string}', $laravelHelperResult);
}
