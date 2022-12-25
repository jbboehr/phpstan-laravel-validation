<?php
declare(strict_types=1);

namespace jbboehr\PhpstanLaravelValidation\Evaluator;

use PhpParser\ConstExprEvaluationException;
use PhpParser\ConstExprEvaluator;
use PhpParser\Node\Expr;

class UnsafeConstExprEvaluator
{
    private ConstExprEvaluator $internalEvalutator;

    public function __construct()
    {
        $this->internalEvalutator = new ConstExprEvaluator();
    }

    /**
     * @return mixed
     * @throws ConstExprEvaluationException
     */
    public function evaluate(Expr $expr): mixed
    {
        return $this->internalEvalutator->evaluateSilently($expr);
    }
}
