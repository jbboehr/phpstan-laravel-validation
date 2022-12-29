<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test;

use PHPStan\Testing\TypeInferenceTestCase;

class ConstExprInferenceTest extends TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public function dataFileAsserts(): iterable
    {
        yield from $this->gatherAssertTypes(__DIR__ . '/const-expr/constant.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/const-expr/class-constant.php');
    }

    /**
     * @dataProvider dataFileAsserts
     * @param array<string, mixed[]> $args
     * @group const-expr
     */
    public function testConstExprFileAsserts(
        string $assertType,
        string $file,
        mixed ...$args
    ): void {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../extension.neon'];
    }
}
