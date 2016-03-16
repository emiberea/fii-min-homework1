<?php

namespace MIN\AppBundle\Model\Operator;

use MIN\AppBundle\Model\Expression\FloatExpressionInterface;
use MIN\AppBundle\Model\Operand\OperandInterface;

class PowOperator implements OperandInterface
{
    /** @var FloatExpressionInterface $left */
    private $left;

    /** @var FloatExpressionInterface $right */
    private $right;

    /**
     * @param FloatExpressionInterface $left
     * @param FloatExpressionInterface $right
     */
    public function __construct(FloatExpressionInterface $left, FloatExpressionInterface $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public function __toString()
    {
        return strval($this->left->getValue()) . ' ^ ' . strval($this->right->getValue());
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return pow($this->left->getValue(), $this->right->getValue());
    }
}
