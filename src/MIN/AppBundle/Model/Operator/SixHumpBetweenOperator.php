<?php

namespace MIN\AppBundle\Model\Operator;

use MIN\AppBundle\Model\Expression\FloatExpressionInterface;
use MIN\AppBundle\Model\Helper\Variable;

class SixHumpBetweenOperator extends BetweenOperator
{
    /** @var bool $odd */
    private $odd;

    /**
     * @param Variable|null $variable
     * @param FloatExpressionInterface|null $left
     * @param FloatExpressionInterface|null $right
     */
    public function __construct(Variable $variable = null, FloatExpressionInterface $left = null, FloatExpressionInterface $right = null)
    {
        parent::__construct();
    }

    public function __toString()
    {
        return '';
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
        $a = $this->odd ? -2 : -3;
        $b = $this->odd ? 2 : 3;
        $this->odd = !$this->odd;
        $truncated = $scale * ($b - $a) + $a;

        return round($truncated, $precision);
    }
}
