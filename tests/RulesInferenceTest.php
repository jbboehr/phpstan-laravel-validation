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

class RulesInferenceTest extends \PHPStan\Testing\TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public function dataFileAsserts(): iterable
    {
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/accepted.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/accepted-if.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/active-url.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/alpha.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/alpha-dash.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/alpha-num.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/array.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/ascii.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/before.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/before-or-equal.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/boolean.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/confirmed.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/current-password.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/date.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/date-equals.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/date-format.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/decimal.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/declined.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/declined-if.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/digits.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/digits_between.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/email.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/exclude-if.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/exclude-unless.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/exclude-with.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/exclude-without.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/file.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/image.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/in.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/integer.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/ip.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/ipv4.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/ipv6.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/json.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/lowercase.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/mac-address.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/max-digits.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/mimes.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/mimetypes.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/min-digits.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/multiple-of.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/not-regex.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/nullable.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/numeric.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/regex.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/string.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/timezone.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/ulid.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/uppercase.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/url.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/rules/uuid.php');
    }

    /**
     * @dataProvider dataFileAsserts
     * @param array<string, mixed[]> $args
     * @group rules
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
