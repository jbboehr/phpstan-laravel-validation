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
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;

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

    public function testRecognizesStateChangingArguments(): void
    {
        $data = new Arg(new Variable('data'));
        $resolver = new CallArgumentResolver();

        self::assertFalse($resolver->otherArgumentMayChangeEvaluationState([
            $data,
            new Arg(new Array_()),
        ], $data));
        self::assertTrue($resolver->otherArgumentMayChangeEvaluationState([
            $data,
            new Arg(new Assign(new Variable('data'), new Array_())),
        ], $data));
    }

    public function testRecognizesIndirectlyExecutableArguments(): void
    {
        $data = new Arg(new Variable('data'));
        $resolver = new CallArgumentResolver();
        $arguments = [
            'unpacked iterable' => new Arg(new Variable('arguments'), false, true),
            'assignment operation' => new Arg(new \PhpParser\Node\Expr\AssignOp\Plus(
                new Variable('state'),
                new \PhpParser\Node\Scalar\Int_(1)
            )),
            'reference assignment' => new Arg(new \PhpParser\Node\Expr\AssignRef(
                new Variable('alias'),
                new Variable('state')
            )),
            'function call' => new Arg(new \PhpParser\Node\Expr\FuncCall(new Name('mutate'))),
            'closure' => new Arg(new \PhpParser\Node\Expr\Closure()),
            'arrow function' => new Arg(new \PhpParser\Node\Expr\ArrowFunction([
                'expr' => new Variable('state'),
            ])),
            'pre-increment' => new Arg(new \PhpParser\Node\Expr\PreInc(new Variable('state'))),
            'pre-decrement' => new Arg(new \PhpParser\Node\Expr\PreDec(new Variable('state'))),
            'post-increment' => new Arg(new \PhpParser\Node\Expr\PostInc(new Variable('state'))),
            'post-decrement' => new Arg(new \PhpParser\Node\Expr\PostDec(new Variable('state'))),
            'array offset fetch' => new Arg(new \PhpParser\Node\Expr\ArrayDimFetch(
                new Variable('state'),
                new \PhpParser\Node\Scalar\String_('key')
            )),
            'property fetch' => new Arg(new \PhpParser\Node\Expr\PropertyFetch(
                new Variable('state'),
                'value'
            )),
            'nullsafe property fetch' => new Arg(new \PhpParser\Node\Expr\NullsafePropertyFetch(
                new Variable('state'),
                'value'
            )),
            'static property fetch' => new Arg(new \PhpParser\Node\Expr\StaticPropertyFetch(
                new Name('State'),
                'value'
            )),
            'string cast' => new Arg(new \PhpParser\Node\Expr\Cast\String_(
                new Variable('mutator')
            )),
            'concatenation' => new Arg(new \PhpParser\Node\Expr\BinaryOp\Concat(
                new Variable('mutator'),
                new \PhpParser\Node\Scalar\String_('')
            )),
            'interpolated string' => new Arg(
                new \PhpParser\Node\Scalar\InterpolatedString([
                    new Variable('mutator'),
                ])
            ),
            'clone' => new Arg(new \PhpParser\Node\Expr\Clone_(
                new Variable('mutator')
            )),
            'print' => new Arg(new \PhpParser\Node\Expr\Print_(
                new Variable('mutator')
            )),
            'shell execution' => new Arg(new \PhpParser\Node\Expr\ShellExec([])),
            'yield' => new Arg(new \PhpParser\Node\Expr\Yield_()),
            'yield from' => new Arg(new \PhpParser\Node\Expr\YieldFrom(
                new Variable('iterator')
            )),
            'include' => new Arg(new \PhpParser\Node\Expr\Include_(
                new \PhpParser\Node\Scalar\String_('rules.php'),
                \PhpParser\Node\Expr\Include_::TYPE_INCLUDE
            )),
            'eval' => new Arg(new \PhpParser\Node\Expr\Eval_(
                new \PhpParser\Node\Scalar\String_('')
            )),
            'dynamic variable' => new Arg(new Variable(new Variable('name'))),
            'class constant fetch' => new Arg(new \PhpParser\Node\Expr\ClassConstFetch(
                new Name('Rules'),
                new Identifier('VALUE')
            )),
        ];

        foreach ($arguments as $description => $argument) {
            self::assertTrue(
                $resolver->otherArgumentMayChangeEvaluationState([
                    $data,
                    $argument,
                ], $data),
                $description
            );
        }
    }
}
