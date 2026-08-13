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

use Composer\InstalledVersions;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File as FileRule;
use Illuminate\Validation\Rules\ImageFile;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\ForwardingFileRule;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\File\File;

#[Group('laravel')]
final class FileRuleBuilderLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    private Factory $factory;
    private string $imagePath;
    private string $textPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory(new Translator(new ArrayLoader(), 'en'));
        $application = new Application();
        $application->instance('validator', $this->factory);
        Facade::setFacadeApplication($application);

        $this->imagePath = $this->createTemporaryFile(
            'phpstan-laravel-image-builder-',
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
            true
        );
        $this->textPath = $this->createTemporaryFile(
            'phpstan-laravel-file-builder-',
            'plain text',
            false
        );
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);

        if (isset($this->imagePath) && is_file($this->imagePath)) {
            unlink($this->imagePath);
        }
        if (isset($this->textPath) && is_file($this->textPath)) {
            unlink($this->textPath);
        }

        parent::tearDown();
    }

    public function testFreshFileBuildersPreserveSuccessfulFileValues(): void
    {
        $textFile = new File($this->textPath);
        $imageFile = new File($this->imagePath);

        $cases = [
            'factory file' => [$textFile, Rule::file()],
            'factory image' => [$imageFile, Rule::imageFile()],
            'direct file' => [$textFile, (new FileRule())->min(0)->max('1mb')],
            'direct image' => [$imageFile, new ImageFile()],
            'typed file' => [$textFile, FileRule::types(['text/plain'])],
            'typed image' => [$imageFile, ImageFile::types(['image/png'])],
            'image dimensions' => [
                $imageFile,
                FileRule::image()->dimensions(Rule::dimensions(['width' => 1, 'height' => 1])),
            ],
            'custom rules' => [$textFile, Rule::file()->rules('exclude')],
        ];

        foreach ($cases as $caseId => [$file, $rule]) {
            $validator = $this->factory->make(['value' => $file], ['value' => ['required', $rule]]);
            self::assertTrue($validator->passes(), $caseId);
            self::assertSame(['value' => $file], $validator->validated(), $caseId);
        }
    }

    public function testFileBuilderRetainsLaravelBlankBypass(): void
    {
        $validator = $this->factory->make(['value' => ''], ['value' => [Rule::file()]]);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => ''], $validator->validated());
    }

    public function testCustomRulesCannotBypassTheUnderlyingFilePredicate(): void
    {
        $validator = $this->factory->make(
            ['value' => 'not-a-file'],
            ['value' => [Rule::file()->rules('exclude')]]
        );

        self::assertFalse($validator->passes());
    }

    public function testParentTypesUsesTheSubclassThroughLateStaticBinding(): void
    {
        $rule = ForwardingFileRule::forwardedTypes(['text/plain']);
        $validator = $this->factory->make(
            ['value' => 'accepted by the subclass'],
            ['value' => ['required', $rule]]
        );

        self::assertTrue($validator->passes());
        self::assertSame(['value' => 'accepted by the subclass'], $validator->validated());
    }

    /**
     * @dataProvider versionedBuilderMethodProvider
     */
    public function testVersionedBuilderMethodsFollowTheirRuntimeBoundaries(
        string $method,
        string $introduced,
        string $argument
    ): void {
        $available = version_compare(self::frameworkVersion(), $introduced, '>=');
        self::assertSame($available, method_exists(FileRule::class, $method));
        if (!$available) {
            return;
        }

        $file = $method === 'extensions'
            ? new \Symfony\Component\HttpFoundation\File\UploadedFile(
                $this->textPath,
                'fixture.txt',
                'text/plain',
                UPLOAD_ERR_OK,
                true
            )
            : new File($this->textPath);
        $rule = (new \ReflectionMethod(FileRule::class, $method))->invoke(new FileRule(), $argument);
        $validator = $this->factory->make(['value' => $file], ['value' => ['required', $rule]]);

        self::assertTrue($validator->passes());
        self::assertSame(['value' => $file], $validator->validated());
    }

    /**
     * @return iterable<string, array{string, string, string}>
     */
    public static function versionedBuilderMethodProvider(): iterable
    {
        yield 'extensions' => ['extensions', '10.34.0', 'txt'];
        yield 'encoding' => ['encoding', '12.40.0', 'UTF-8'];
    }

    private function createTemporaryFile(string $prefix, string $contents, bool $decode): string
    {
        $path = tempnam(sys_get_temp_dir(), $prefix);
        $decoded = $decode ? base64_decode($contents, true) : $contents;
        if (!is_string($path) || !is_string($decoded) || file_put_contents($path, $decoded) === false) {
            throw new \RuntimeException('Could not create a file-rule builder fixture.');
        }

        return $path;
    }

    private static function frameworkVersion(): string
    {
        return ltrim((string) InstalledVersions::getPrettyVersion('laravel/framework'), 'v');
    }
}
