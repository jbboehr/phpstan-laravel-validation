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

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use ValueError;

use function array_is_list;
use function checkdate;
use function date_parse;
use function is_int;
use function is_numeric;
use function is_string;
use function in_array;
use function ltrim;
use function sprintf;
use function str_contains;
use function str_starts_with;
use function strlen;
use function strtotime;

/**
 * The date/time grammar used by `Parse::dateTime()`.
 *
 * Without an explicit format, strings and numeric values follow Laravel's
 * ordinary `date` rule: `strtotime()` must recognize the value and
 * `date_parse()` must describe a valid calendar date. A DateTimeImmutable must
 * then be constructible from that same input.
 *
 * Explicit formats are stricter. Strings are parsed with every unspecified
 * field reset from the Unix epoch, then formatted back with the selected
 * format. A value is accepted only when the parse has no warnings or errors
 * and the round trip is byte-for-byte exact. Multiple formats are tried in
 * declaration order.
 *
 * Already-produced immutable dates pass through. Other DateTimeInterface
 * implementations are copied into a DateTimeImmutable while retaining their
 * instant and timezone. The configured formats and timezone describe scalar
 * input only. Unix timestamps retain their instant and are represented in the
 * configured timezone unless the input format carries a timezone itself.
 *
 * @internal
 */
final class DateTimeGrammar
{
    /** @var list<string> */
    private const PARSE_ONLY_FORMAT_CONTROLS = ['!', '|', '+', '*', '?', '#'];

    /** @var list<string> */
    private const TIMEZONE_FORMAT_CHARACTERS = ['e', 'O', 'P', 'p', 'T'];

    /** @var null|non-empty-list<array{format: string, usesUnixTimestamp: bool}> */
    private ?array $formats;

    /**
     * The public rule API accepts only lists. This internal boundary validates
     * a wider array type so malformed runtime calls fail with a configuration
     * exception instead of relying on PHPDoc.
     *
     * @param string|array<mixed>|null $formats
     */
    public function __construct(
        string|array|null $formats,
        private DateTimeZone $timezone
    ) {
        if ($formats === null) {
            $this->formats = null;

            return;
        }

        if (is_string($formats)) {
            if ($formats === '') {
                throw new InvalidArgumentException(
                    'A date/time parser requires a non-empty format.'
                );
            }

            $formats = [$formats];
        }

        if ($formats === [] || !array_is_list($formats)) {
            throw new InvalidArgumentException(
                'A date/time parser requires a non-empty format or format list.'
            );
        }

        $compiled = [];
        foreach ($formats as $format) {
            if (!is_string($format) || $format === '') {
                throw new InvalidArgumentException(
                    'Every date/time parser format must be a non-empty string.'
                );
            }

            $compiled[] = $this->compileFormat($format);
        }

        $this->formats = $compiled;
    }

    /**
     * @return array{format: string, usesUnixTimestamp: bool}
     */
    private function compileFormat(string $format): array
    {
        $usesUnixTimestamp = false;
        $parsesTimezone = false;
        $length = strlen($format);
        for ($index = 0; $index < $length; $index++) {
            $character = $format[$index];

            if ($character === '\\') {
                if ($index === $length - 1) {
                    throw new InvalidArgumentException(
                        'A date/time parser format cannot end with an escape character.'
                    );
                }

                $index++;

                continue;
            }

            if (in_array($character, self::PARSE_ONLY_FORMAT_CONTROLS, true)) {
                throw new InvalidArgumentException(sprintf(
                    'Date/time format control character "%s" must be escaped or removed.',
                    $character
                ));
            }

            $usesUnixTimestamp = $usesUnixTimestamp || $character === 'U';
            $parsesTimezone = $parsesTimezone
                || in_array($character, self::TIMEZONE_FORMAT_CHARACTERS, true);
        }

        if ($usesUnixTimestamp && $parsesTimezone) {
            throw new InvalidArgumentException(
                'A date/time parser format cannot combine a Unix timestamp with an input timezone.'
            );
        }

        return [
            'format' => $format,
            'usesUnixTimestamp' => $usesUnixTimestamp,
        ];
    }

    public function parse(mixed $value): ?DateTimeImmutable
    {
        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        if ($this->formats === null) {
            return $this->parseLikeLaravelDate($value);
        }

        if (!is_string($value)) {
            return null;
        }

        foreach ($this->formats as $format) {
            $parsed = $this->parseExactFormat(
                $value,
                $format['format'],
                $format['usesUnixTimestamp']
            );
            if ($parsed !== null) {
                return $parsed;
            }
        }

        return null;
    }

    private function parseLikeLaravelDate(mixed $value): ?DateTimeImmutable
    {
        if (!is_string($value) && !is_numeric($value)) {
            return null;
        }

        $input = (string) $value;
        if (str_contains($input, "\0")) {
            return null;
        }

        try {
            if (strtotime($input) === false) {
                return null;
            }

            $date = date_parse($input);
            if (
                !is_int($date['month'])
                || !is_int($date['day'])
                || !is_int($date['year'])
                || !checkdate($date['month'], $date['day'], $date['year'])
            ) {
                return null;
            }

            $parsed = new DateTimeImmutable($input, $this->timezone);

            // As with createFromFormat('U'), PHP forces @timestamp input into
            // +00:00 and ignores the supplied timezone. The timestamp fixes
            // the instant, not its display zone, so honor this parser's
            // configured output zone.
            if (str_starts_with(ltrim($input), '@')) {
                $parsed = $parsed->setTimezone($this->timezone);
            }

            return $parsed;
        } catch (Exception | ValueError) {
            return null;
        }
    }

    private function parseExactFormat(
        string $value,
        string $format,
        bool $usesUnixTimestamp
    ): ?DateTimeImmutable {
        try {
            // `!` resets unspecified fields before the user-supplied format
            // is applied. Without it, date-only input inherits the current
            // clock time and makes parsing nondeterministic.
            $parsed = DateTimeImmutable::createFromFormat(
                '!' . $format,
                $value,
                $this->timezone
            );
        } catch (ValueError) {
            return null;
        }

        // PHP 8.1 returns an all-zero array after a clean parse; PHP 8.2 and
        // later return false. Checking the counts supports both contracts.
        $errors = DateTimeImmutable::getLastErrors();
        if (
            $parsed === false
            || (
                $errors !== false
                && ($errors['warning_count'] !== 0 || $errors['error_count'] !== 0)
            )
        ) {
            return null;
        }

        // createFromFormat() forces `U` timestamps into +00:00 and ignores
        // its timezone argument. Preserve the instant while honoring this
        // parser's output-zone contract when the input carries no timezone.
        if ($usesUnixTimestamp) {
            $parsed = $parsed->setTimezone($this->timezone);
        }

        return $parsed->format($format) === $value ? $parsed : null;
    }
}
