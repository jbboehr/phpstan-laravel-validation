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
