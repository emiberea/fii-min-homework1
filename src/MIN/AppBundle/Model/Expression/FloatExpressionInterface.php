<?php

namespace MIN\AppBundle\Model\Expression;

interface FloatExpressionInterface extends ExpressionInterface
{
    /**
     * @return float
     */
    public function getValue();
}
