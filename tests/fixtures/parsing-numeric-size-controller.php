<?php

declare(strict_types=1);

namespace ParsingNumericSizeFixture;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use jbboehr\Rensei\Parse;

final class NumericSizeController extends Controller
{
    use ValidatesRequests;

    public function validateInput(Request $request): void
    {
        $this->validate($request, [
            'controller' => [Parse::integer(), 'min:2'],
        ]);

        $this->validateWith([
            'validate_with' => [Parse::integer(), 'min:2'],
        ], $request);

        $this->validateWithBag('bag', $request, [
            'validate_with_bag' => [Parse::integer(), 'min:2'],
        ]);
    }
}

final class TraitOnlyNumericSizeController
{
    use ValidatesRequests;

    public function validateInput(Request $request): void
    {
        $this->validate($request, [
            'trait_only_controller' => [Parse::integer(), 'min:2'],
        ]);
    }
}

final class UnrelatedNumericSizeController extends Controller
{
    public function callUnrelatedMethods(Request $request): void
    {
        $this->validate($request, [
            'unrelated_validate' => [Parse::integer(), 'min:2'],
        ]);
        $this->validateWith([
            'unrelated_validate_with' => [Parse::integer(), 'min:2'],
        ], $request);
        $this->validateWithBag('bag', $request, [
            'unrelated_validate_with_bag' => [Parse::integer(), 'min:2'],
        ]);
    }

    /** @param array<string, mixed> $options */
    public function validate(Request $request, array $options): void
    {
    }

    /** @param array<string, mixed> $options */
    public function validateWith(array $options, Request $request): void
    {
    }

    /** @param array<string, mixed> $options */
    public function validateWithBag(string $bag, Request $request, array $options): void
    {
    }
}
