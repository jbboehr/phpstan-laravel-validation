<?php
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

$rules = [
    'avatar' => [
        'required',
        Rule::dimensions()->maxWidth(1000)->maxHeight(500)->ratio(3 / 2),
    ],
];

\PHPStan\dumpType($rules);
$validator = Validator::make([], $rules);
\PHPStan\dumpType($validator);
