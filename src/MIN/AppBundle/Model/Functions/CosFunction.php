<?php

namespace MIN\AppBundle\Model\Functions;

use MIN\AppBundle\Model\Expression\FloatExpressionInterface;
use MIN\AppBundle\Model\Operand\OperandInterface;

class CosFunction implements OperandInterface
{
    /** @var FloatExpressionInterface $operand */
    private $operand;

    /**
     * @param FloatExpressionInterface $operand
     */
    public function __construct(FloatExpressionInterface $operand)
    {
        $this->operand = $operand;
    }

    public function __toString()
    {
        return 'cos(' . $this->operand->getValue() . ')';
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return cos($this->operand->getValue());
    }
}
