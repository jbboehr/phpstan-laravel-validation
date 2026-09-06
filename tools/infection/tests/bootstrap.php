<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/infection/include-interceptor/src/IncludeInterceptor.php';

use Infection\StreamWrapper\IncludeInterceptor;

$projectRoot = dirname(__DIR__, 3);
$source = $projectRoot . '/src/Validation/FormRequestTypeRegistry.php';
// Unchanged source must pass the same tests when Infection intercepts includes.
IncludeInterceptor::intercept($source, $source);
IncludeInterceptor::enable();
require $projectRoot . '/vendor/autoload.php';
