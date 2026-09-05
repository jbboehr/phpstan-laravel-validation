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

namespace jbboehr\Rensei\Internal;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\InvokableValidationRule;
use Illuminate\Validation\Validator;
use WeakMap;
use WeakReference;

use function array_key_exists;
use function is_array;
use function spl_object_id;
use function strtr;

/**
 * Index callback names and parser identities once per rule map and run.
 *
 * The validator owns this cache through an after callback. The registry must
 * hold only weak references: rule wrappers in the snapshot retain their
 * validator, so a strongly held snapshot would keep request data alive.
 *
 * @internal
 */
final class ParsingRuleMap
{
    /** @var WeakMap<Validator, WeakReference<self>>|null */
    private static ?WeakMap $maps = null;

    /** @var array<array-key, mixed> */
    private array $rules = [];

    /** @var array<array-key, array<int, string|null>> */
    private array $dataKeys = [];

    private ?MessageBag $runMessages = null;

    public static function resolve(Validator $validator, object $parser, string $attribute): ?string
    {
        $maps = self::$maps;
        if ($maps === null) {
            /** @var WeakMap<Validator, WeakReference<self>> $maps */
            $maps = new WeakMap();
            self::$maps = $maps;
        }

        $map = ($maps[$validator] ?? null)?->get();
        if ($map === null) {
            $map = new self();
            $maps[$validator] = WeakReference::create($map);
            $validator->after(static function () use ($map): void {
                $map->rules = [];
                $map->dataKeys = [];
                $map->runMessages = null;
            });
        }

        $rules = $validator->getRules();
        $messages = $validator->errors();

        // Unchanged PHP arrays share storage, so this comparison is constant
        // time on ordinary callbacks. A changed map or a retry after a thrown
        // rule must rebuild the index before resolving another attribute.
        if ($map->rules !== $rules || $map->runMessages !== $messages) {
            $map->rebuild($validator, $rules);
            $map->runMessages = $messages;
        }

        return $map->dataKeys[$attribute][spl_object_id($parser)] ?? null;
    }

    /** @param array<array-key, mixed> $rules */
    private function rebuild(Validator $validator, array $rules): void
    {
        $this->rules = $rules;
        $this->dataKeys = [];
        // Ask this validator to encode the two literal characters. Decode only
        // those exact tokens: a wildcard row key can contain a real backslash
        // before a path separator, which is not an escaped literal dot.
        /** @var array<string, string> $placeholders */
        $placeholders = $validator->parseData(['.' => '.', '*' => '*']);

        foreach ($rules as $key => $attributeRules) {
            if (!is_array($attributeRules)) {
                continue;
            }

            $dataKey = (string) $key;
            $attribute = strtr($dataKey, $placeholders);
            foreach ($attributeRules as $rule) {
                if (!$rule instanceof InvokableValidationRule) {
                    continue;
                }

                $id = spl_object_id($rule->invokable());
                $matches = $this->dataKeys[$attribute] ?? [];
                $this->dataKeys[$attribute][$id] = array_key_exists($id, $matches)
                    && $matches[$id] !== $dataKey ? null : $dataKey;
            }
        }
    }
}
