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

use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ValidTestExtractorFunctionsTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        require_once __DIR__ . '/../scripts/valid-test-extractor-functions.php';
    }

    public function testUnchangedRulesAreNotReportedAsMutated(): void
    {
        $validator = $this->validator(
            ['items' => [['name' => 'valid']]],
            ['items.*.name' => 'required|string']
        );

        self::assertFalse(\validator_rules_were_mutated($validator));
    }

    public function testSupportedObjectSubclassesAreExportable(): void
    {
        self::assertTrue(\is_exportable(CarbonImmutable::parse('2000-01-01', 'UTC')));
        self::assertFalse(\is_exportable($this->createMock(File::class)));
        self::assertFalse(\is_exportable(new \stdClass()));
    }

    public function testLatestStableLaravelVersionSelectsTheNewestReleaseInTheMajor(): void
    {
        self::assertSame(
            '10.50.2',
            \latest_stable_laravel_version(
                ['10.x-dev', 'v10.50.1', '10.50.2', 'v10.51.0-beta.1', 'v11.0.0', null],
                10
            )
        );
        self::assertNull(\latest_stable_laravel_version(['10.x-dev', 'v11.0.0'], 10));
    }

    public function testRulesAddedWithSometimesAreReportedAsMutated(): void
    {
        $validator = $this->validator(['name' => 'valid'], []);
        $validator->sometimes(
            'name',
            'required',
            static fn (): bool => true
        );

        self::assertTrue(\validator_rules_were_mutated($validator));
    }

    public function testRulesAddedWithAddRulesAreReportedAsMutated(): void
    {
        $validator = $this->validator(
            ['name' => 'valid'],
            ['name' => 'string']
        );
        $validator->addRules(['name' => 'required']);

        self::assertTrue(\validator_rules_were_mutated($validator));
    }

    public function testEscapedDotsDoNotLookLikeMutations(): void
    {
        $validator = $this->validator(
            ['literal.name' => 'valid'],
            ['literal\.name' => 'required']
        );

        self::assertFalse(\validator_rules_were_mutated($validator));
    }

    public function testRuntimePlaceholdersAreRevertedInNestedKeys(): void
    {
        $validator = $this->validator(
            ['literal.name' => ['*' => 'valid']],
            ['literal\.name.*' => 'required']
        );
        $placeholders = \get_validator_placeholders($validator);

        self::assertIsString($placeholders['dot']);
        self::assertNotSame('', $placeholders['dot']);
        self::assertStringStartsWith('__asterisk__', $placeholders['asterisk'] ?? '');
        self::assertSame(
            ['literal\.name' => ['*' => 'valid']],
            \revert_validator_placeholders($validator->getData(), $placeholders)
        );
        self::assertSame(
            ['literal\.name.*' => ['required']],
            \revert_validator_placeholders($validator->getRules(), $placeholders)
        );
    }

    public function testLegacyPlaceholdersAreReverted(): void
    {
        $validator = new class (
            new Translator(new ArrayLoader(), 'en'),
            [],
            []
        ) extends Validator {
            /** @var string */
            protected $dotPlaceholder = 'legacy-placeholder';
        };
        $property = (new \ReflectionClass($validator))->getProperty('dotPlaceholder');
        $property->setAccessible(true);
        $property->setValue($validator, 'legacy-placeholder');
        $placeholders = \get_validator_placeholders($validator);

        self::assertSame(
            ['dot' => 'legacy-placeholder', 'asterisk' => '__asterisk__'],
            $placeholders
        );
        self::assertSame(
            ['literal\.name' => ['*' => 'valid']],
            \revert_validator_placeholders(
                ['literallegacy-placeholdername' => ['__asterisk__' => 'valid']],
                $placeholders
            )
        );
    }

    public function testEffectiveRulesArePartOfFixtureHash(): void
    {
        $baseHash = \validation_fixture_hash(
            'ValidationTest::testExample:1',
            ['name' => 'valid'],
            ['name' => 'valid'],
            ['name' => 'string'],
            ['name' => ['string']]
        );
        $mutatedHash = \validation_fixture_hash(
            'ValidationTest::testExample:1',
            ['name' => 'valid'],
            ['name' => 'valid'],
            ['name' => 'string'],
            ['name' => ['string', 'required']]
        );

        self::assertNotSame($baseHash, $mutatedHash);
        self::assertSame(22, strlen($baseHash));
    }

    public function testRuntimeDateObjectStateDoesNotAffectFixtureHash(): void
    {
        $first = Carbon::parse('2000-01-01 12:00:00', 'UTC');
        $second = Carbon::parse('2000-01-01 12:00:00', 'UTC');

        self::assertNotSame($first, $second);
        self::assertSame(
            \validation_fixture_hash('location', ['date' => $first], [], [], []),
            \validation_fixture_hash('location', ['date' => $second], [], [], [])
        );
    }

    public function testRuntimeFileObjectImplementationStateDoesNotAffectFixtureHash(): void
    {
        $first = new class (__FILE__, 'fixture.txt', 'text/plain', UPLOAD_ERR_OK, true) extends UploadedFile {
            public string $runtimeState = 'first';
        };
        $second = clone $first;
        $second->runtimeState = 'second';

        self::assertSame(
            [
                '__validation_fixture_file__' => [
                    'class' => get_class($first),
                    'properties' => [
                        'originalName' => 'fixture.txt',
                        'mimeType' => 'text/plain',
                        'error' => UPLOAD_ERR_OK,
                        'test' => true,
                    ],
                ],
            ],
            \normalize_validation_fixture_hash_value($first)
        );
        self::assertSame(
            \validation_fixture_hash('location', ['file' => $first], [], [], []),
            \validation_fixture_hash('location', ['file' => $second], [], [], [])
        );
    }

    public function testValidationLocationAcceptsTestsOutsideIlluminateNamespace(): void
    {
        self::assertSame(
            self::class . '::testExample:123',
            \validation_test_location([
                ['function' => 'passes'],
                ['class' => Validator::class, 'function' => 'validate', 'line' => 123],
                ['class' => self::class, 'function' => 'testExample', 'line' => 999],
            ])
        );
    }

    public function testValidationLocationSafelyIgnoresIncompleteAndNonTestFrames(): void
    {
        self::assertSame(
            'unknown',
            \validation_test_location([
                [],
                ['function' => 'testHelper'],
                ['class' => Validator::class, 'function' => 'testInternalHelper'],
            ])
        );
    }

    public function testFixtureContentsIncludeValidatedSourceMetadata(): void
    {
        $contents = \validation_fixture_contents(
            ['fixture-hash' => ['location' => 'ValidationTest::testExample:1']],
            '13.23.0',
            '92a707229148e57f08a249211c8a5a194159c619'
        );

        self::assertStringStartsWith(
            '<?php /* laravel 13.23.0 commit 92a707229148e57f08a249211c8a5a194159c619 */ return [',
            $contents
        );
        self::assertStringEndsWith('];', $contents);
    }

    /**
     * @dataProvider invalidFixtureMetadataProvider
     */
    public function testFixtureContentsRejectInvalidSourceMetadata(string $version, string $commit): void
    {
        $this->expectException(\InvalidArgumentException::class);

        \validation_fixture_contents([], $version, $commit);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function invalidFixtureMetadataProvider(): iterable
    {
        yield 'comment injection through version' => [
            '13.23.0 */ return []; /*',
            '92a707229148e57f08a249211c8a5a194159c619',
        ];
        yield 'abbreviated commit' => ['13.23.0', '92a7072291'];
    }

    /**
     * @param array<mixed, mixed> $data
     * @param array<mixed, mixed> $rules
     */
    private function validator(array $data, array $rules): Validator
    {
        return new Validator(
            new Translator(new ArrayLoader(), 'en'),
            $data,
            $rules
        );
    }
}
