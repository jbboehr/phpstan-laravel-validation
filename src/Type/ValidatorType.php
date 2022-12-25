<?php
declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Type;

use jbboehr\PhpstanLaravelValidation\Rule\RuleTreeMapNode;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeWithClassName;

final class ValidatorType extends ObjectType implements TypeWithClassName
{
    public function __construct(
        private RuleTreeMapNode $validatorRules
    ) {
        parent::__construct(\Illuminate\Validation\Validator::class);
    }

    public function getValidatorRules(): RuleTreeMapNode
    {
        return $this->validatorRules;
    }
}
