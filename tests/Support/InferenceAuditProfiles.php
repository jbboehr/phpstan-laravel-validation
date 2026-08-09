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

namespace jbboehr\PhpstanLaravelValidation\Test\Support;

final class InferenceAuditProfiles
{
    /**
     * @return array<string, array{
     *     constraint: string,
     *     expected: string,
     *     exact: bool,
     *     minimumPhp: string
     * }>
     */
    public static function all(): array
    {
        return [
            '10.0.0' => self::exact('10.0.0', '8.1'),
            '10.32.1' => self::exact('10.32.1', '8.1'),
            '10.33.0' => self::exact('10.33.0', '8.1'),
            '10.34.0' => self::exact('10.34.0', '8.1'),
            '10-latest' => self::latest('10', '^10.0', '8.1'),
            '11.0.0' => self::exact('11.0.0', '8.2'),
            '11.22.0' => self::exact('11.22.0', '8.2'),
            '11.23.0' => self::exact('11.23.0', '8.2'),
            '11-latest' => self::latest('11', '^11.0', '8.2'),
            '12.0.0' => self::exact('12.0.0', '8.2'),
            '12.21.0' => self::exact('12.21.0', '8.2'),
            '12.22.0' => self::exact('12.22.0', '8.2'),
            '12.39.0' => self::exact('12.39.0', '8.2'),
            '12.40.0' => self::exact('12.40.0', '8.2'),
            '12-latest' => self::latest('12', '^12.0', '8.2'),
            '13.0.0' => self::exact('13.0.0', '8.3'),
            '13.3.0' => self::exact('13.3.0', '8.3'),
            '13.4.0' => self::exact('13.4.0', '8.3'),
            '13.20.0' => self::exact('13.20.0', '8.3'),
            '13.21.0' => self::exact('13.21.0', '8.3'),
            '13.23.0' => self::exact('13.23.0', '8.3'),
            '13.24.0' => self::exact('13.24.0', '8.3'),
            '13-latest' => self::latest('13', '^13.0', '8.3'),
        ];
    }

    /**
     * @return array{constraint: string, expected: string, exact: bool, minimumPhp: string}
     */
    private static function exact(string $version, string $minimumPhp): array
    {
        return [
            'constraint' => $version,
            'expected' => $version,
            'exact' => true,
            'minimumPhp' => $minimumPhp,
        ];
    }

    /**
     * @return array{constraint: string, expected: string, exact: bool, minimumPhp: string}
     */
    private static function latest(string $major, string $constraint, string $minimumPhp): array
    {
        return [
            'constraint' => $constraint,
            'expected' => $major,
            'exact' => false,
            'minimumPhp' => $minimumPhp,
        ];
    }
}
