<?php

namespace Illuminate\Validation;

use Illuminate\Contracts\Translation\Translator;

/**
 * @template R of array
 */
class Validator
{
    /**
     * @param array<mixed, mixed> $data
     * @param R $rules
     * @param array<mixed, mixed> $messages
     * @param array<mixed, mixed> $attributes
     */
    public function __construct(
        Translator $translator,
        array $data,
        array $rules,
        array $messages = [],
        array $attributes = []
    ) {}
}
