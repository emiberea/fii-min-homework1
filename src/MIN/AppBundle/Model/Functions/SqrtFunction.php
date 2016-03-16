<?php

namespace MIN\AppBundle\Model\Functions;

use MIN\AppBundle\Model\Expression\FloatExpressionInterface;
use MIN\AppBundle\Model\Operand\OperandInterface;

class SqrtFunction implements OperandInterface
{
    /** @var FloatExpressionInterface $expression */
    private $expression;

    /**
     * @param FloatExpressionInterface $expression
     */
    public function __construct(FloatExpressionInterface $expression)
    {
        $this->expression = $expression;
    }

    public function __toString()
    {
        return 'sqrt(' . $this->expression->getValue() . ')';
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return sqrt($this->expression->getValue());
    }
}
