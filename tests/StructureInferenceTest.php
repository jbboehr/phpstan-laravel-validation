<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test;

class StructureInferenceTest extends \PHPStan\Testing\TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public function dataFileAsserts(): iterable
    {
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/array.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/factory.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/map.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/readme.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/structure/request.php');
    }

    /**
     * @dataProvider dataFileAsserts
     * @param array<string, mixed[]> $args
     */
    public function testFileAsserts(
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
