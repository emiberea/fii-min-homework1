<?php

namespace MIN\AppBundle\Model\Functions;

use MIN\AppBundle\Model\Expression\FloatExpressionInterface;
use MIN\AppBundle\Model\Operand\IndexOperand;
use MIN\AppBundle\Model\Operand\OperandInterface;

class SumFunction implements OperandInterface
{
    /** @var FloatExpressionInterface $expression */
    private $expression;

    /** @var IndexOperand $indexOperand */
    private $indexOperand;

    /**
     * @param FloatExpressionInterface $expression
     * @param IndexOperand $indexOperand
     */
    public function __construct(FloatExpressionInterface $expression, IndexOperand $indexOperand)
    {
        $this->expression = $expression;
        $this->indexOperand = $indexOperand;
    }

    public function __toString()
    {
        return 'sum(' . $this->expression->getValue() . ')';
    }

    /**
     * @return float|int
     */
    public function getValue()
    {
        $result = 1;
        $this->indexOperand->reset();
        while ($this->indexOperand->hasNext()) {
            $this->indexOperand->next();
            $result += $this->expression->getValue();
        }

        return $result;
    }
}
