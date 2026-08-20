<?php

declare(strict_types=1);

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\TestController;

use function PHPStan\Testing\assertType;

$rules = [3 => 'required|string'];

assertType('array{3: string}', Illuminate\Support\Facades\Validator::validate([], $rules));

$validator = Illuminate\Support\Facades\Validator::make([], $rules);
assertType('array{3: string}', $validator->validated());

$factory = new Factory(new Translator(new ArrayLoader(), 'en'));
assertType('array{3: string}', $factory->make([], $rules)->validated());
assertType('array{3: string}', validator([], $rules)->validated());

$request = new Illuminate\Http\Request();
assertType('array{3: string}', $request->validate($rules));
assertType('array{3: string}', (new TestController())->validate($request, $rules));
