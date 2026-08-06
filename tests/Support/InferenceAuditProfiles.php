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
     *     minimumPhp: string,
     *     commit: string
     * }>
     */
    public static function all(): array
    {
        return [
            '10.0.0' => self::exact('10.0.0', '8.1', 'be2ddb5c31b0b9ebc2738d9f37a9d4c960aa3199'),
            '10-latest' => self::latest('10', '^10.0', '8.1', '3ff39b7a9b83e633383ec9b019827ed54b6d38bc'),
            '11.0.0' => self::exact('11.0.0', '8.2', '6089f679d6d29e6071a6448ed5e96de02e57fedb'),
            '11-latest' => self::latest('11', '^11.0', '8.2', 'dc7ec34ae95bacf4a63b96ec81482b4f3e702289'),
            '12.0.0' => self::exact('12.0.0', '8.2', 'bd8aeb64d3f9fa4b11690d702bdf289f5f32ae97'),
            '12.21.0' => self::exact('12.21.0', '8.2', 'ac8c4e73bf1b5387b709f7736d41427e6af1c93b'),
            '12.22.0' => self::exact('12.22.0', '8.2', '6ab00c913ef6ec6fad0bd506f7452c0bb9e792c3'),
            '12-latest' => self::latest('12', '^12.0', '8.2', '727a8ea2949c23ca8b5316b86a00984b6017b7a0'),
            '13.0.0' => self::exact('13.0.0', '8.3', '3e33f431a05365d008742ff8001b92641086d5f8'),
            '13.3.0' => self::exact('13.3.0', '8.3', '118b7063c44a2f3421d1646f5ddf08defcfd1db3'),
            '13.4.0' => self::exact('13.4.0', '8.3', '912de244f88a69742b76e8a2807f6765947776da'),
            '13-latest' => self::latest('13', '^13.0', '8.3', '92a707229148e57f08a249211c8a5a194159c619'),
        ];
    }

    /**
     * @return array{constraint: string, expected: string, exact: bool, minimumPhp: string, commit: string}
     */
    private static function exact(string $version, string $minimumPhp, string $commit): array
    {
        return [
            'constraint' => $version,
            'expected' => $version,
            'exact' => true,
            'minimumPhp' => $minimumPhp,
            'commit' => $commit,
        ];
    }

    /**
     * @return array{constraint: string, expected: string, exact: bool, minimumPhp: string, commit: string}
     */
    private static function latest(string $major, string $constraint, string $minimumPhp, string $commit): array
    {
        return [
            'constraint' => $constraint,
            'expected' => $major,
            'exact' => false,
            'minimumPhp' => $minimumPhp,
            'commit' => $commit,
        ];
    }
}
