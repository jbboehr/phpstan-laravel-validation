<?php

declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Test\Fixtures;

final class UnrelatedDataContainer
{
    /** @param array<mixed, mixed> $data */
    public function setData(array $data): void
    {
    }

    public function setValue(string $key, mixed $value): void
    {
    }

    /** @param array<mixed, mixed> $rules */
    public function setRules(array $rules): void
    {
    }

    /** @param array<mixed, mixed> $rules */
    public function addRules(array $rules): void
    {
    }

    public function sometimes(string $key, mixed $rules, callable $callback): void
    {
    }
}
