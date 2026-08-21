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

namespace jbboehr\Rensei\Rules;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use jbboehr\Rensei\Internal\DateTimeGrammar;
use jbboehr\Rensei\ParseFailure;

use function is_string;
use function sprintf;
use function str_contains;

/**
 * Parses Laravel-compatible or exactly formatted input into a DateTimeImmutable.
 *
 * The configured timezone is explicit and stable. It supplies the timezone
 * when the input format does not carry one; an offset or timezone parsed from
 * the input takes precedence.
 *
 * @extends BaseParsingRule<DateTimeImmutable>
 */
final class DateTimeRule extends BaseParsingRule
{
    private DateTimeGrammar $grammar;

    private bool $usesExplicitFormats;

    /**
     * @param string|list<string>|null $format
     */
    public function __construct(
        string|array|null $format = null,
        DateTimeZone|string $timezone = 'UTC'
    ) {
        if (is_string($timezone)) {
            // DateTimeZone throws ValueError for this case, unlike ordinary
            // invalid identifiers. Check it explicitly so both paths expose
            // the documented configuration exception without catching engine
            // failures unrelated to the identifier.
            if (str_contains($timezone, "\0")) {
                throw new InvalidArgumentException('Invalid date/time timezone identifier.');
            }

            try {
                $timezone = new DateTimeZone($timezone);
            } catch (Exception $exception) {
                throw new InvalidArgumentException(
                    sprintf('Invalid date/time timezone "%s".', $timezone),
                    0,
                    $exception
                );
            }
        }

        $this->usesExplicitFormats = $format !== null;
        $this->grammar = new DateTimeGrammar($format, $timezone);
    }

    public function parse(mixed $value): DateTimeImmutable
    {
        $parsed = $this->grammar->parse($value);

        if ($parsed === null) {
            throw new ParseFailure();
        }

        return $parsed;
    }

    protected function message(): string
    {
        return $this->usesExplicitFormats
            ? 'The :attribute field must match a configured date/time format.'
            : 'The :attribute field must be a valid date.';
    }
}
