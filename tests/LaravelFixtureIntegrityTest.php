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

use PHPUnit\Framework\TestCase;

class LaravelFixtureIntegrityTest extends TestCase
{
    private const FIXTURES = [
        9 => ['version' => '9.52.21', 'commit' => '6055d9594c9da265ddbf1e27e7dd8f09624568bc'],
        10 => ['version' => '10.50.2', 'commit' => '3ff39b7a9b83e633383ec9b019827ed54b6d38bc'],
        11 => ['version' => '11.55.0', 'commit' => 'dc7ec34ae95bacf4a63b96ec81482b4f3e702289'],
        12 => ['version' => '12.64.0', 'commit' => '727a8ea2949c23ca8b5316b86a00984b6017b7a0'],
        13 => ['version' => '13.23.0', 'commit' => '92a707229148e57f08a249211c8a5a194159c619'],
    ];

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        require_once __DIR__ . '/../scripts/valid-test-extractor-functions.php';
    }

    public function testCommittedFixturesAreInternallyConsistent(): void
    {
        $errors = [];

        foreach (self::FIXTURES as $major => $metadata) {
            $path = __DIR__ . "/fixtures/laravel-export-v{$major}.php";
            $contents = file_get_contents($path);
            if (!is_string($contents)) {
                $errors[] = "Laravel {$major}: fixture is unreadable";
                continue;
            }

            $expectedHeader = sprintf(
                '<?php /* laravel %s commit %s */ return [',
                $metadata['version'],
                $metadata['commit']
            );
            if (!str_starts_with($contents, $expectedHeader)) {
                $errors[] = "Laravel {$major}: source metadata does not match the fixture manifest";
            }

            $entries = require $path;
            if (!is_array($entries) || $entries === []) {
                $errors[] = "Laravel {$major}: fixture has no entries";
                continue;
            }

            foreach ($entries as $hash => $entry) {
                if (!is_string($hash) || !is_array($entry)) {
                    $errors[] = "Laravel {$major}: malformed fixture entry";
                    continue;
                }

                $location = $entry['location'] ?? null;
                $data = $entry['data'] ?? null;
                $validated = $entry['validated'] ?? null;
                $rules = $entry['rules'] ?? null;
                $expandedRules = $entry['expandedRules'] ?? null;

                if (
                    !is_string($location)
                    || !is_array($data)
                    || !is_array($validated)
                    || !is_array($rules)
                    || !is_array($expandedRules)
                ) {
                    $errors[] = "Laravel {$major}: {$hash} has a malformed payload";
                    continue;
                }

                if ($location === 'unknown') {
                    $errors[] = "Laravel {$major}: {$hash} has unknown provenance";
                }
                if ($this->containsPlaceholderKey($entry)) {
                    $errors[] = "Laravel {$major}: {$location} contains an internal placeholder";
                }
                if (!$this->expandedRulesComeFromSource($rules, $expandedRules)) {
                    $errors[] = "Laravel {$major}: {$location} has effective rules absent from source rules";
                }

                $expectedHash = \validation_fixture_hash($location, $data, $validated, $rules, $expandedRules);
                if ($hash !== $expectedHash) {
                    $errors[] = "Laravel {$major}: {$location} has a stale fixture hash";
                }
            }
        }

        self::assertSame([], $errors, implode("\n", $errors));
    }

    private function containsPlaceholderKey(mixed $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $key => $item) {
            if (is_string($key) && preg_match('/__(?:dot|asterisk)__/', $key) === 1) {
                return true;
            }
            if ($this->containsPlaceholderKey($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<mixed, mixed> $sourceRules
     * @param array<mixed, mixed> $expandedRules
     */
    private function expandedRulesComeFromSource(array $sourceRules, array $expandedRules): bool
    {
        foreach ($expandedRules as $expandedAttribute => $effectiveRules) {
            $availableRules = [];

            foreach ($sourceRules as $sourceAttribute => $rules) {
                if ($this->attributeMatches($sourceAttribute, $expandedAttribute)) {
                    array_push($availableRules, ...$this->explicitRules($rules));
                }
            }

            foreach ($this->explicitRules($effectiveRules) as $effectiveRule) {
                if (!in_array($effectiveRule, $availableRules, true)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function attributeMatches(string|int $source, string|int $expanded): bool
    {
        if (is_int($source) || is_int($expanded)) {
            return $source === $expanded;
        }

        $pattern = '';
        $escaped = false;

        foreach (str_split($source) as $character) {
            if ($escaped) {
                $pattern .= preg_quote('\\' . $character, '/');
                $escaped = false;
            } elseif ($character === '\\') {
                $escaped = true;
            } elseif ($character === '*') {
                $pattern .= '[^.]*';
            } else {
                $pattern .= preg_quote($character, '/');
            }
        }
        if ($escaped) {
            $pattern .= preg_quote('\\', '/');
        }

        return preg_match('/^' . $pattern . '$/D', $expanded) === 1;
    }

    /**
     * @return list<mixed>
     */
    private function explicitRules(mixed $rules): array
    {
        if (is_string($rules)) {
            return explode('|', $rules);
        }

        return is_array($rules) ? array_values($rules) : [$rules];
    }
}
