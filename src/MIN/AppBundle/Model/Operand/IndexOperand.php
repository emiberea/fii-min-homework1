<?php

namespace MIN\AppBundle\Model\Operand;

use MIN\AppBundle\Model\Expression\FloatExpressionInterface;

class IndexOperand implements OperandInterface
{
    /** @var FloatExpressionInterface $min */
    private $min;

    /** @var FloatExpressionInterface $max */
    private $max;

    /** @var int $current */
    private $current;

    /**
     * IndexOperand constructor.
     * @param FloatExpressionInterface $min
     * @param FloatExpressionInterface $max
     */
    public function __construct(FloatExpressionInterface $min, FloatExpressionInterface $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function __toString()
    {
        return 'i';
    }

    /**
     * @return FloatExpressionInterface
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return FloatExpressionInterface
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return floatval($this->current);
    }

    public function next()
    {
        $this->current = $this->current + 1;
    }

    /**
     * @return bool
     */
    public function hasNext()
    {
        return $this->current < intval($this->max->getValue());
    }

    public function reset()
    {
        $this->current = 0;
    }
}
