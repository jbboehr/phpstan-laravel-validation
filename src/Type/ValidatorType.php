<?php
declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Type;

use jbboehr\PhpstanLaravelValidation\Validation\RuleTreeNode;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeWithClassName;

final class ValidatorType extends ObjectType implements TypeWithClassName
{
    public function __construct(
        private RuleTreeNode $validatorRules
    ) {
        parent::__construct(\Illuminate\Validation\Validator::class);
    }

    public function getValidatorRules(): RuleTreeNode
    {
        return $this->validatorRules;
    }
}
