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

use jbboehr\PhpstanLaravelValidation\Rule\FormRequestResultCacheDependencyRule;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\CollectedDataEmitter;
use PHPStan\Analyser\NodeCallbackInvoker;
use PHPStan\Analyser\Scope;

final class FormRequestResultCacheDependencyDisabledRuleTest extends \PHPStan\Testing\PHPStanTestCase
{
    public function testDisabledIntegrationDoesNotInspectOrRecordCalls(): void
    {
        $scope = $this->createMockForIntersectionOfInterfaces([
            Scope::class,
            CollectedDataEmitter::class,
            NodeCallbackInvoker::class,
        ]);
        $scope->expects(self::never())->method('getType');
        $scope->expects(self::never())->method('emitCollectedData');
        $rule = self::getContainer()->getByType(FormRequestResultCacheDependencyRule::class);

        self::assertSame([], $rule->processNode(
            new MethodCall(new Variable('request'), 'validated'),
            $scope
        ));
    }

    public function testDisabledIntegrationDoesNotResolveDynamicMethodNames(): void
    {
        $scope = $this->createMockForIntersectionOfInterfaces([
            Scope::class,
            CollectedDataEmitter::class,
            NodeCallbackInvoker::class,
        ]);
        $scope->expects(self::never())->method('getType');
        $scope->expects(self::never())->method('emitCollectedData');
        $rule = self::getContainer()->getByType(FormRequestResultCacheDependencyRule::class);

        self::assertSame([], $rule->processNode(
            new MethodCall(new Variable('request'), new Variable('method')),
            $scope
        ));
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../extension.neon'];
    }
}
