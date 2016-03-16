<?php

namespace MIN\AppBundle\Model\Operator;

use MIN\AppBundle\Model\Expression\FloatExpressionInterface;
use MIN\AppBundle\Model\Expression\BooleanExpressionInterface;
use MIN\AppBundle\Model\Helper\Variable;

class BetweenOperator implements BooleanExpressionInterface
{
    /** @var Variable $variable */
    private $variable;

    /** @var FloatExpressionInterface $left */
    private $left;

    /** @var FloatExpressionInterface $right */
    private $right;

    /**
     * @param Variable $variable
     * @param FloatExpressionInterface $left
     * @param FloatExpressionInterface $right
     */
    public function __construct(Variable $variable = null, FloatExpressionInterface $left = null, FloatExpressionInterface $right = null)
    {
        $this->variable = $variable;
        $this->left = $left;
        $this->right = $right;
    }

    public function __toString()
    {
        return $this->left->getValue() . ' <= ' . $this->variable . ' <= ' . $this->right->getValue();
    }

    /**
     * @return Variable
     */
    public function getVariable()
    {
        return $this->variable;
    }

    /**
     * @param Variable $variable
     * @return $this
     */
    public function setVariable($variable)
    {
        $this->variable = $variable;

        return $this;
    }

    /**
     * @return FloatExpressionInterface
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param FloatExpressionInterface $left
     * @return $this
     */
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * @return FloatExpressionInterface
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param FloatExpressionInterface $right
     * @return $this
     */
    public function setRight($right)
    {
        $this->right = $right;

        return $this;
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->variable->getValue() >= $this->left->getValue() && $this->variable->getValue() <= $this->right->getValue();
    }

    /**
     * @param int $value
     * @param int $noOfBits
     * @param int $precision
     * @return float
     */
    public function wrapValue($value, $noOfBits, $precision)
    {
        $maxValue = $this->getMaxValueByBits($noOfBits);
        $scale = $value / $maxValue;
        $a = $this->left->getValue();
        $b = $this->right->getValue();
        $truncated = $scale * ($b - $a) + $a;

        return round($truncated, $precision);
    }

    /**
     * @return float
     */
    public function getMinValue()
    {
        return $this->left->getValue();
    }

    /**
     * @return float
     */
    public function getMaxValue()
    {
        return $this->right->getValue();
    }

    /**
     * @param int $noOfBits
     * @return int
     */
    public function getMaxValueByBits($noOfBits)
    {
        $result = 0;
        for ($i = 0; $i < $noOfBits; $i++) {
            $result = ($result << 1) + 1;
        }

        return $result;
    }
}
