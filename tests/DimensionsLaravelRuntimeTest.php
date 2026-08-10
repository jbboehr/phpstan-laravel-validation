<?php

/**
 * Copyright (c) anno Domini nostri Jesu Christi MMXXIV John Boehr & contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test;

use jbboehr\PhpstanLaravelValidation\Test\Support\AssertsLaravelValidation;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\File\File;

#[Group('laravel')]
final class DimensionsLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    use AssertsLaravelValidation;

    private string $imagePath;

    protected function setUp(): void
    {
        parent::setUp();

        $path = tempnam(sys_get_temp_dir(), 'phpstan-laravel-dimensions-');
        $contents = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
            true
        );
        if (!is_string($path) || !is_string($contents) || file_put_contents($path, $contents) === false) {
            throw new \RuntimeException('Could not create the dimensions test image.');
        }

        $this->imagePath = $path;
    }

    protected function tearDown(): void
    {
        if (isset($this->imagePath) && is_file($this->imagePath)) {
            unlink($this->imagePath);
        }

        parent::tearDown();
    }

    public function testImageFileIsAcceptedAndPreserved(): void
    {
        $file = new File($this->imagePath);
        $this->assertLaravelValidationCase(
            'image file is accepted and preserved',
            ['image' => $file],
            ['image' => 'required|dimensions:width=1,height=1,ratio=1'],
            true,
            ['image' => $file]
        );
    }

    public function testDimensionPredicateRejectsTheWrongSize(): void
    {
        $this->assertLaravelValidationCase(
            'dimension predicate rejects the wrong size',
            ['image' => new File($this->imagePath)],
            ['image' => 'required|dimensions:width=2'],
            false,
            null
        );
    }

    public function testNativePathStringIsNotAFileValue(): void
    {
        $this->assertLaravelValidationCase(
            'native path string is not a file value',
            ['image' => $this->imagePath],
            ['image' => 'required|dimensions:width=1'],
            false,
            null
        );
    }

    public function testMissingBlankAndNullableValuesFollowNonImplicitRuleSemantics(): void
    {
        $this->assertLaravelValidationCase(
            'missing value bypasses the non-implicit rule',
            [],
            ['image' => 'dimensions:width=1'],
            true,
            []
        );
        $this->assertLaravelValidationCase(
            'blank value bypasses the non-implicit rule',
            ['image' => ''],
            ['image' => 'dimensions:width=1'],
            true,
            ['image' => '']
        );
        $this->assertLaravelValidationCase(
            'nullable value bypasses the dimensions predicate',
            ['image' => null],
            ['image' => 'nullable|dimensions:width=1'],
            true,
            ['image' => null]
        );
    }
}
