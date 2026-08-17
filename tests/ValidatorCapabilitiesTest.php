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
use jbboehr\Rensei\Internal\ValidatorCapabilities;
use jbboehr\Rensei\UnsupportedLaravelVersion;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('laravel')]
final class ValidatorCapabilitiesTest extends TestCase
{
    public function testAcceptsAValidatorThatCanSetValues(): void
    {
        $factory = new Factory(new Translator(new ArrayLoader(), 'en'));

        $this->expectNotToPerformAssertions();

        ValidatorCapabilities::assertCanSetValue($factory->make([], []));
    }

    public function testRejectsAValidatorWithoutSetValue(): void
    {
        // Laravel 10.0 through 10.6 shipped no Validator::setValue(). No such
        // release is installed here, so the absence is modelled directly.
        $withoutSetValue = new class () {
        };

        $this->expectException(UnsupportedLaravelVersion::class);
        $this->expectExceptionMessage('laravel/framework >= 10.7.0');

        ValidatorCapabilities::assertCanSetValue($withoutSetValue);
    }
}
