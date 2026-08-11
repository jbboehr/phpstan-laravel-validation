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
use Illuminate\Validation\Factory;
use Illuminate\Validation\PresenceVerifierInterface;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\Attributes\Group;

#[Group('laravel')]
final class DatabaseRuleBuilderLaravelRuntimeTest extends \PHPStan\Testing\PHPStanTestCase
{
    public function testDatabaseRulesPreserveValuesAcrossFluentConstraints(): void
    {
        $factory = new Factory(new Translator(new ArrayLoader(), 'en'));
        $factory->setPresenceVerifier(new class () implements PresenceVerifierInterface {
            /** @param array<mixed> $extra */
            public function getCount(
                $collection,
                $column,
                $value,
                $excludeId = null,
                $idColumn = null,
                array $extra = []
            ): int {
                return $collection === 'existing_users' ? 1 : 0;
            }

            /**
             * @param array<mixed> $values
             * @param array<mixed> $extra
             */
            public function getMultiCount($collection, $column, array $values, array $extra = []): int
            {
                return $collection === 'existing_users' ? count($values) : 0;
            }
        });

        $input = ['existing_id' => '42', 'new_email' => 'ada@example.com'];
        $validator = $factory->make($input, [
            'existing_id' => [
                'required',
                'integer',
                Rule::exists('existing_users', 'id')->where('active', true)->withoutTrashed(),
            ],
            'new_email' => [
                'required',
                'string',
                Rule::unique('new_users', 'email')->ignore(7)->whereNotNull('tenant_id'),
            ],
        ]);

        self::assertTrue($validator->passes());
        self::assertSame($input, $validator->validated());
    }
}
