<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File as FileRule;
use Illuminate\Validation\Rules\ImageFile;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\CustomFileRule;

use function PHPStan\Testing\assertType;

$validated = Validator::make([], [
    'factory_file' => ['required', Rule::file()],
    'factory_image' => ['required', Rule::imageFile()],
    'direct_file' => ['required', (new FileRule())->size(1)->max('2kb')],
    'direct_image' => ['required', (new ImageFile())->dimensions(Rule::dimensions(['width' => 1]))],
    'typed_file' => ['required', FileRule::types(['text/plain'])->between(1, 2)],
    'typed_image' => ['required', ImageFile::types(['image/png'])],
    'image' => ['required', FileRule::image()->dimensions(Rule::dimensions(['height' => 1]))],
    'extensions' => ['required', Rule::file()->extensions(['txt'])],
    'custom_rules' => ['required', Rule::file()->rules('exclude')],
    'optional' => [Rule::file()],
])->validated();

assertType(
    'array{factory_file: Symfony\Component\HttpFoundation\File\File, '
        . 'factory_image: Symfony\Component\HttpFoundation\File\File, '
        . 'direct_file: Symfony\Component\HttpFoundation\File\File, '
        . 'direct_image: Symfony\Component\HttpFoundation\File\File, '
        . 'typed_file: Symfony\Component\HttpFoundation\File\File, '
        . 'typed_image: Symfony\Component\HttpFoundation\File\File, '
        . 'image: Symfony\Component\HttpFoundation\File\File, '
        . 'extensions: Symfony\Component\HttpFoundation\File\File, '
        . 'custom_rules: Symfony\Component\HttpFoundation\File\File, '
        . 'optional?: string|Symfony\Component\HttpFoundation\File\File}',
    $validated
);

$assigned = Rule::file();
$opaque = Validator::make([], [
    'assigned' => ['required', $assigned],
    'conditional' => ['required', Rule::file()->when(true, static fn (FileRule $rule): FileRule => $rule)],
    'default' => ['required', FileRule::default()],
    'subclass' => ['required', new CustomFileRule()],
])->validated();

assertType('array{assigned: mixed, conditional: mixed, default: mixed, subclass: mixed}', $opaque);
