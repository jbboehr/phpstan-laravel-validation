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

use jbboehr\PhpstanLaravelValidation\Extension\CallArgumentResolver;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;

final class CallArgumentResolverTest extends \PHPUnit\Framework\TestCase
{
    public function testFindsAnOrdinaryPositionalArgument(): void
    {
        $rules = new Arg(new Variable('rules'));
        $resolver = new CallArgumentResolver();

        self::assertSame(
            $rules,
            $resolver->find([new Arg(new Variable('data')), $rules], 'rules', 1)
        );
    }

    public function testFindsANamedArgumentRegardlessOfSourceOrder(): void
    {
        $rules = new Arg(
            new Variable('rules'),
            false,
            false,
            [],
            new Identifier('rules')
        );
        $resolver = new CallArgumentResolver();

        self::assertSame(
            $rules,
            $resolver->find([
                $rules,
                new Arg(
                    new Variable('data'),
                    false,
                    false,
                    [],
                    new Identifier('data')
                ),
            ], 'rules', 1)
        );
    }

    public function testRejectsAnUnpackedPositionalArgument(): void
    {
        $resolver = new CallArgumentResolver();

        self::assertNull($resolver->find([
            new Arg(new Variable('data')),
            new Arg(new Variable('rest'), false, true),
        ], 'rules', 1));
    }

    public function testRejectsADifferentNamedArgumentAtThePositionalIndex(): void
    {
        $resolver = new CallArgumentResolver();

        self::assertNull($resolver->find([
            new Arg(new Variable('data')),
            new Arg(
                new Variable('messages'),
                false,
                false,
                [],
                new Identifier('messages')
            ),
        ], 'rules', 1));
    }
}
