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

use Illuminate\Support\Carbon;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;

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

    public function testRulesAddedWithSometimesAreReportedAsMutated(): void
    {
        $validator = $this->validator(['name' => 'valid'], []);
        $validator->sometimes('name', 'required', static fn (): bool => true);

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
