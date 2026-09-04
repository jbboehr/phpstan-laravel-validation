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

use jbboehr\PhpstanLaravelValidation\Internal\FormRequestRulesFingerprint;
use jbboehr\PhpstanLaravelValidation\Parser\FormRequestRulesFingerprintVisitor;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PHPStan\Parser\Parser;

final class FormRequestRulesFingerprintVisitorTest extends \PHPStan\Testing\PHPStanTestCase
{
    public function testAddsIndependentFingerprintsToRelevantMethodsOnly(): void
    {
        $nodes = $this->parseAndTraverse(<<<'PHP'
<?php

final class Example extends ParentExample
{
    public function rules(): array
    {
        return ['value' => 'required|string'];
    }

    public function withValidator(): void
    {
    }

    public function unrelated(): void
    {
    }
}
PHP);

        $methods = [];
        foreach ((new NodeFinder())->findInstanceOf($nodes, ClassMethod::class) as $method) {
            $methods[$method->name->toString()] = $method;
        }

        $rulesFingerprint = $this->fingerprintOf($methods['rules']);
        self::assertNotSame($rulesFingerprint, $this->fingerprintOf($methods['withValidator']));
        self::assertSame([], $methods['unrelated']->attrGroups);
    }

    public function testDoesNotFingerprintAClassWithoutAParent(): void
    {
        $nodes = $this->parseAndTraverse(<<<'PHP'
<?php

final class Example
{
    public function rules(): array
    {
        return ['value' => 'required|string'];
    }
}
PHP);

        $methods = (new NodeFinder())->findInstanceOf($nodes, ClassMethod::class);
        self::assertCount(1, $methods);
        self::assertSame([], $methods[0]->attrGroups);
    }

    public function testFingerprintsATraitThatMaySupplyAFormRequestMethod(): void
    {
        $nodes = $this->parseAndTraverse(<<<'PHP'
<?php

trait ProvidesRules
{
    public function rules(): array
    {
        return ['value' => 'required|string'];
    }
}
PHP);

        $methods = (new NodeFinder())->findInstanceOf($nodes, ClassMethod::class);
        self::assertCount(1, $methods);
        $this->fingerprintOf($methods[0]);
    }

    public function testFingerprintChangesWhenTheRelevantMethodBodyChanges(): void
    {
        $string = $this->fingerprintOf($this->rulesMethod(<<<'PHP'
<?php

final class Example extends ParentExample
{
    public function rules(): array
    {
        return ['value' => 'required|string'];
    }
}
PHP));
        $array = $this->fingerprintOf($this->rulesMethod(<<<'PHP'
<?php

final class Example extends ParentExample
{
    public function rules(): array
    {
        return ['value' => 'required|array'];
    }
}
PHP));

        self::assertNotSame($string, $array);
    }

    public function testFingerprintDoesNotChangeWhenAnUnrelatedMethodBodyChanges(): void
    {
        $first = $this->fingerprintOf($this->rulesMethod(<<<'PHP'
<?php

final class Example extends ParentExample
{
    public function rules(): array
    {
        return ['value' => 'required|string'];
    }

    public function unrelated(): int
    {
        return 1;
    }
}
PHP));
        $second = $this->fingerprintOf($this->rulesMethod(<<<'PHP'
<?php

final class Example extends ParentExample
{
    public function rules(): array
    {
        return ['value' => 'required|string'];
    }

    public function unrelated(): int
    {
        return 2;
    }
}
PHP));

        self::assertSame($first, $second);
    }

    public function testDisabledVisitorDoesNotModifyTheTree(): void
    {
        $nodes = $this->parseAndTraverse(<<<'PHP'
<?php

final class Example extends ParentExample
{
    public function rules(): array
    {
        return [];
    }
}
PHP, false);

        $methods = (new NodeFinder())->findInstanceOf($nodes, ClassMethod::class);
        self::assertCount(1, $methods);
        self::assertSame([], $methods[0]->attrGroups);
    }

    private function rulesMethod(string $code): ClassMethod
    {
        $method = (new NodeFinder())->findFirst(
            $this->parseAndTraverse($code),
            static fn (\PhpParser\Node $node): bool => $node instanceof ClassMethod
                && $node->name->toLowerString() === 'rules'
        );
        self::assertInstanceOf(ClassMethod::class, $method);

        return $method;
    }

    private function fingerprintOf(ClassMethod $method): string
    {
        self::assertCount(1, $method->attrGroups);
        self::assertCount(1, $method->attrGroups[0]->attrs);
        $attribute = $method->attrGroups[0]->attrs[0];
        self::assertSame(FormRequestRulesFingerprint::class, $attribute->name->toString());
        self::assertTrue($attribute->getAttribute(
            FormRequestRulesFingerprintVisitor::SYNTHETIC_ATTRIBUTE
        ));
        self::assertCount(1, $attribute->args);
        self::assertInstanceOf(String_::class, $attribute->args[0]->value);
        self::assertMatchesRegularExpression('/^[a-f0-9]{64}$/D', $attribute->args[0]->value->value);

        return $attribute->args[0]->value->value;
    }

    /** @return array<array-key, \PhpParser\Node> */
    private function parseAndTraverse(string $code, bool $enabled = true): array
    {
        $parser = $this->createParser();
        $nodes = $parser->parseString($code);

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new FormRequestRulesFingerprintVisitor($enabled));

        return $traverser->traverse($nodes);
    }

    private function createParser(): Parser
    {
        $parser = self::getContainer()->getService('currentPhpVersionSimpleDirectParser');
        self::assertInstanceOf(Parser::class, $parser);

        return $parser;
    }
}
