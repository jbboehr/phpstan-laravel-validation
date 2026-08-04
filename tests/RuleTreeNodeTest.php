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

use jbboehr\PhpstanLaravelValidation\Validation\RuleParser;
use PHPUnit\Framework\TestCase;

final class RuleTreeNodeTest extends TestCase
{
    /**
     * @return iterable<string, array{string, bool}>
     */
    public static function blankStringBypassProvider(): iterable
    {
        yield 'optional rule' => ['email', true];
        yield 'sometimes' => ['sometimes|email', true];
        yield 'nullable' => ['nullable|email', true];
        yield 'present' => ['present|email', true];
        yield 'conditional required' => ['required_if:other,value|email', true];
        yield 'conditional accepted' => ['accepted_if:other,value', true];
        yield 'conditional declined' => ['declined_if:other,value', true];
        yield 'optional array in' => ['array|in:foo,bar', true];

        yield 'required' => ['required|email', false];
        yield 'accepted' => ['accepted', false];
        yield 'declined' => ['declined', false];
        yield 'filled' => ['filled|email', false];
        yield 'missing' => ['missing', false];
        yield 'sometimes filled' => ['sometimes|filled|email', false];
        yield 'required array in' => ['required|array|in:foo,bar', false];
    }

    /**
     * @dataProvider blankStringBypassProvider
     */
    public function testAllowsBlankStringBypass(string $rules, bool $expected): void
    {
        $tree = RuleParser::parse(['value' => $rules]);
        $node = $tree->resolvePath('value');

        self::assertSame($expected, $node->allowsBlankStringBypass());
    }
}
