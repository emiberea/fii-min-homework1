<?php

namespace MIN\AppBundle\Model\Expression;

interface BooleanExpressionInterface
{
    /**
     * @return boolean
     */
    public function getValue();

    /**
     * @param integer $value
     * @param integer $noOfBits
     * @param integer $precision
     * @return float
     */
    public function wrapValue($value, $noOfBits, $precision);

    /**
     * @return float
     */
    public function getMinValue();

    /**
     * @return float
     */
    public function getMaxValue();
}
