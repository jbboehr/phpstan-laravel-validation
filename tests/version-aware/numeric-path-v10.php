<?php

declare(strict_types=1);

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use jbboehr\PhpstanLaravelValidation\Test\Fixtures\TestController;

use function PHPStan\Testing\assertType;

$rules = [3 => 'required|string'];

assertType('array{string}', Illuminate\Support\Facades\Validator::validate([], $rules));

$validator = Illuminate\Support\Facades\Validator::make([], $rules);
assertType('array{string}', $validator->validated());

$factory = new Factory(new Translator(new ArrayLoader(), 'en'));
assertType('array{string}', $factory->make([], $rules)->validated());
assertType('array{string}', validator([], $rules)->validated());

$request = new Illuminate\Http\Request();
assertType('array{string}', $request->validate($rules));
assertType('array{string}', (new TestController())->validate($request, $rules));
